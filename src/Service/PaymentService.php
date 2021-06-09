<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

const RENT_TRANSACTION_TYPE = 2;
const PURCHASE_TRANSACTION_TYPE = 1;
const FREE_TRANSACTION_TYPE = 0;
const MAX_DEPOSITE_VALUE = 10000;
const MIN_DEPOSITE_VALUE = 1;
const NULL_COST = 0;


class PaymentService extends AbstractController
{

    private $courseRepo;
    private $transRepo;
    private $userRepo;


    public function __construct(
        CourseRepository $courseRepo,
        TransactionRepository $transRepo,
        UserRepository $userRepo
    ) {
        $this->courseRepo = $courseRepo;
        $this->transRepo = $transRepo;
        $this->userRepo = $userRepo;
    }

    public function deposite(float $sum, string $bearerToken)
    {
        $user = $this->getUserByToken($bearerToken);


        $em = $this->getDoctrine()->getManager();


        $em->getConnection()->beginTransaction();

        try {
            if ($sum > MAX_DEPOSITE_VALUE) {
                throw new Exception('Невозможно внести больше 10000 кредитов за одну транзакцию');
            }

            if ($sum < MIN_DEPOSITE_VALUE) {
                throw new Exception('Сумма не может быть отрицательной или равной нулю');
            }

            $user->setBalance($user->getBalance() + $sum);


            $transaction = new Transaction();

            $transaction->setUsername($user);
            $transaction->setOperationType(1);
            $transaction->setCreatedAt(new \DateTime());
            $transaction->setValue($sum);


            $em->persist($user);
            $em->persist($transaction);
            $em->flush();
            $em->getConnection()->commit();

            return ['success' => 'true', 'balance' => $user->getBalance(), 'sum' => $sum];

        } catch (Exception $e) {

            $em->getConnection()->rollBack();
            return ['code' => '406', 'message' => 'Невозможно произвести операцию'];

        }
    }

    public function pay(string $code, string $bearerToken)
    {
        $em = $this->getDoctrine()->getManager();

        $token = explode(' ', $bearerToken)[1];
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload, true);


        $username = $jwtPayload['username'];

        $course = $this->
        courseRepo->
        findOneBy(['symbolCode' => $code]);


        $cost = $course->getCost() == null ? NULL_COST : $course->getCost();

        $user = $this->userRepo->findOneBy(['email' => $username]);


        $em->getConnection()->beginTransaction();

        $validity = 0;

        try {
            $em = $this->getDoctrine()->getManager();
            if ($user->getBalance() < $cost) {
                throw new Exception('Недостаточно средств');
            }

            $alreadyExists = $this->transRepo->findOneBy(['course' => $course->getId(), 'username' => $user->getId()]);

            if($alreadyExists) {
                if ($alreadyExists->getCourse()->getCourseType() != RENT_TRANSACTION_TYPE) {
                    throw new Exception("Курс уже куплен и не может быть куплен повторно.");
                }
                else if($alreadyExists->getEndOfRent() > new \DateTime())
                {
                    throw new Exception("Аренда ещё не истекла, невозможно оплатить повторно");
                }
            }

            $user->setBalance($user->getBalance() - $cost);

            $transaction = new Transaction();
            $transaction->setCourse($course);
            $transaction->setUsername($user);
            $transaction->setOperationType(0);
            $transaction->setCreatedAt(new \DateTime());
            $transaction->setValue($cost);


            if ($course->getCourseType() == RENT_TRANSACTION_TYPE) {
                $validity = (new \DateTime())->modify('+1 week');
                $transaction->setEndOfRent($validity);
            }


            $em->persist($user);
            $em->persist($transaction);
            $em->flush();
            $em->getConnection()->commit();

            if ($course->getCourseType() == RENT_TRANSACTION_TYPE) {
                return ['success' => 'true', 'course_type' => 'rent', 'expires_at' => $validity];
            }

            if ($course->getCourseType() == PURCHASE_TRANSACTION_TYPE) {
                return ['success' => 'true', 'course_type' => 'purchase'];
            }

            return ['success' => 'true', 'course_type' => 'free'];


        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            return ['code' => '406', 'message' => 'На вашем счету недостаточно средств'];
        }
    }


    public function getTransactions(string $apiToken, ?array $filters)
    {

        $result = [];
        $user = $this->getUserByToken($apiToken);


        if ($filters) {
            if (array_key_exists('course', $filters['filter'])) {
                $courseCode = $filters['filter']['course'];
                $course = $this->courseRepo->findOneBy(['symbolCode' => $courseCode]);
                $data = $this->transRepo->filterTransaction($user, $filters, $course);

                foreach ($data as $key) {
                    $result[] = [
                        'id' => $key->getId(),
                        'type' => $key->getOperationType() == 0 ? 'payment' : 'deposite',
                        'course_code' => $key->getCourse()->getSymbolCode(),
                        'created_at' => $key->getCreatedAt(),
                        'amount' => ($key->getUsername()->getBalance())
                    ];
                }


                return $result;

            } else {
                return $this->findTransactionWithoutCourse($user, $filters);
            }
        }
        return $this->findTransactionWithoutCourse($user, $filters);
    }


    public function findTransactionWithoutCourse(User $user, array $filters): array
    {

        $data = $this->transRepo->filterTransaction($user, $filters, null);


        foreach ($data as $key) {
            if ($key->getCourse() != null) {
                $result[] = [
                    'id' => $key->getId(),
                    'type' => $key->getOperationType() == 0 ? 'payment' : 'deposite',
                    'course_code' => $key->getCourse()->getSymbolCode(),
                    'created_at' => $key->getCreatedAt(),
                    'amount' => -$key->getCourse()->getCost()
                ];
            } else
                //deposite
                $result[] = [
                    'id' => $key->getId(),
                    'type' => $key->getOperationType() == 0 ? 'payment' : 'deposite',
                    'course_code' => ' ',
                    'created_at' => $key->getCreatedAt(),
                    'amount' => '+' . $key->getValue()
                ];
        }
        return $result;
    }


    public function createCourse(string $bearerToken, array $params)
    {

        $user = $this->getUserByToken($bearerToken);

        if ($user->getRoles()[0] != 'ROLE_SUPER_ADMIN') {
            return new JsonResponse(['message' => "Недостаточно прав"]);
        }


        $em = $this->getDoctrine()->getManager();

        $em->getConnection()->beginTransaction();


        try {
            $em = $this->getDoctrine()->getManager();
            if ($this->courseRepo->findOneBy(['symbolCode' => $params['code']])) {
                throw new Exception('Курс с таким кодом уже существует');
            }

            if ($params['type'] == FREE_TRANSACTION_TYPE && $params['price'] != NULL_COST) {

                throw new Exception('Нельзя установить стоимость бесплатному курса  больше 0');
            }

            if ($params['price'] < NULL_COST) {
                throw new Exception('Цена не может быть меньше 0');
            }

            if ($params['type'] != FREE_TRANSACTION_TYPE && $params['price'] < MIN_DEPOSITE_VALUE) {
                throw new Exception('У платных курсов должна быть указана цена');
            }

            $course = new Course();
            $course->setTitle($params['title']);
            $course->setCourseType($params['type']);
            $course->setCost($params['price']);
            $course->setSymbolCode($params['code']);


            $em->persist($course);
            $em->flush();
            $em->getConnection()->commit();

            return ['success' => 'true'];


        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            return ['code' => '406', 'message' => $e->getMessage()];
        }
    }

    public function editCourse(string $bearerToken, $params, string $currentCode)
    {


        $user = $this->getUserByToken($bearerToken);

        if ($user->getRoles()[0] != 'ROLE_SUPER_ADMIN') {
            return new JsonResponse(['message' => "Недостаточно прав"]);
        }


        $em = $this->getDoctrine()->getManager();

        $em->getConnection()->beginTransaction();


        try {
            $em = $this->getDoctrine()->getManager();
            $courseBefore = $this->courseRepo->findOneBy(['symbolCode' => $currentCode]);
            $isReservedCourse = $this->courseRepo->findOneBy(['symbolCode' => $params['code']]);


            if (!$courseBefore) {
                throw new Exception('Валится на отладке');
            }

            if ($isReservedCourse) {
                if ($isReservedCourse->getSymbolCode() != $courseBefore->getSymbolCode()) {
                    throw new Exception('Курс с таким кодом уже сущесвует');
                }
            }
            if ($params['type'] == FREE_TRANSACTION_TYPE && $params['price'] != NULL_COST) {
                throw new Exception('Нельзя установить стоимость бесплатного курса  больше 0');
            }

            if ($params['price'] < NULL_COST) {
                throw new Exception('Цена не может быть меньше 0');
            }

            if ($params['type'] != FREE_TRANSACTION_TYPE && $params['price'] < MIN_DEPOSITE_VALUE) {
                throw new Exception('У платных курсов должна быть указана цена');
            }


            $courseBefore->setTitle($params['title']);
            $courseBefore->setCourseType($params['type']);
            $courseBefore->setCost($params['price']);
            $courseBefore->setSymbolCode($params['code']);


            $em->persist($courseBefore);
            $em->flush();
            $em->getConnection()->commit();

            return ['success' => 'true'];


        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            return ['code' => '406', 'message' => $e->getMessage()];
        }
    }


    public function getUserByToken(string $bearerToken): User
    {
        $token = explode(' ', $bearerToken)[1];

        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload, true);


        $username = $jwtPayload['username'];


        $user = $this->userRepo->findOneBy(['email' => $username]);

        return $user;
    }

    public function getCousesByUser(string $username)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->courseRepo->getCoursesByUser($username, $em);
    }


}