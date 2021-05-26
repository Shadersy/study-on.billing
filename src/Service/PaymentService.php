<?php
// $em instanceof EntityManager

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

class PaymentService extends AbstractController
{

    private $courseRepo;
    private $transRepo;
    private $userRepo;


    public function __construct(CourseRepository $courseRepo, TransactionRepository $transRepo,
                                UserRepository $userRepo)
    {
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

            if ($sum > 10000 ) {


                throw new Exception('Невозможно внести больше 10000 кредитов за одну транзакцию');
            }

            $user->setBalance($user->getBalance()  + $sum);

            $transaction = new Transaction();

            $transaction->setUsername($user);
            $transaction->setOperationType(1);
            $transaction->setCreatedAt(new \DateTime());



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
        findOneBy(['symbol_code' => $code]);

        $cost = $course->getCost() == null ? 0 : $course->getCost();

        $user = $this->userRepo->findOneBy(['email' => $username]);

        $em->getConnection()->beginTransaction();

        $validity = 0;

        try {


            $em = $this->getDoctrine()->getManager();

            if ($user->getBalance() < $cost) {


                throw new Exception('Недостаточно средств');
            }

            $user->setBalance($user->getBalance() - $cost);

            $transaction = new Transaction();
            $transaction->setCourse($course);
            $transaction->setUsername($user);
            $transaction->setOperationType(0);
            $transaction->setCreatedAt(new \DateTime());
            $transaction->setValue($cost);


            if ($course->getCourseType() == 2) {

                $validity = (new \DateTime())->modify('+1 week');
                $transaction->setValidity($validity);

            }

            $em->persist($user);
            $em->persist($transaction);
            $em->flush();
            $em->getConnection()->commit();

            if ($course->getCourseType() == 2) {
                return ['success' => 'true', 'course_type' => 'rent', 'expires_at' => $validity];
            }

            if ($course->getCourseType() == 1) {
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
                $course = $this->courseRepo->findOneBy(['symbol_code' => $courseCode]);
                $data = $this->transRepo->filterTransaction($user, $filters, $course);

                foreach ($data as $key) {
                    $result[] = [
                        'id' => $key->getId(),
                        'type' => $key->getOperationType() == 0 ? 'payment' : 'deposite',
                        'course_code' => $key->getCourse()->getSymbolCode(),
                        'created_at' => $key->getCreatedAt(),
                        'amount' => $key->getUsername()->getBalance()
                    ];
                }
                return $result;

            } else {


                return $this->findTransactionWithoutCourse($user, $filters);
            }
        }


        return $this->findTransactionWithoutCourse($user, $filters);

    }



    public function findTransactionWithoutCourse(User $user, array $filters) : array
    {

        $data = $this->transRepo->filterTransaction( $user, $filters, null);




        foreach ($data as $key) {
            if ($key->getCourse() != null) {
                $result[] = [
                    'id' => $key->getId(),
                    'type' => $key->getOperationType() == 0 ? 'payment' : 'deposite',
                    'course_code' => $key->getCourse()->getSymbolCode(),
                    'created_at' => $key->getCreatedAt(),
                    'amount' => $key->getUsername()->getBalance()
                ];
            }
            else
                $result[] = [
                    'id' => $key->getId(),
                    'type' => $key->getOperationType() == 0 ? 'payment' : 'deposite',
                    'created_at' => $key->getCreatedAt(),
                    'amount' => $key->getUsername()->getBalance()
                ];
        }
        return $result;
    }



    public function getUserByToken(string $bearerToken) : User
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
}