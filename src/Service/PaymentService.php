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

            if($user->getBalance()<$cost){
                throw new Exception('Недостаточно средств');
            }

            $user->setBalance($user->getBalance() - $cost);

            $transaction = new Transaction();
            $transaction->setCourse($course);
            $transaction->setUsername($user);
            $transaction->setOperationType(0);
            $transaction->setCreatedAt(new \DateTime());
            $transaction->setValue($cost);

            if($course->getCourseType()==2) {

                $validity = (new \DateTime())->modify('+1 week');
                $transaction->setValidity($validity);

            }

            $em->persist($user);
            $em->persist($transaction);
            $em->flush();
            $em->getConnection()->commit();

            if($course->getCourseType() == 2){
                return ['success' => 'true', 'course_type' => 'rent', 'expires_at' => $validity];
            }

            if($course->getCourseType() == 1){
                return ['success' => 'true', 'course_type' => 'purchase'];
            }

            return ['success' => 'true', 'course_type' => 'free'];


        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            return ['code' => '406', 'message' => 'На вашем счету недостаточно средств'];

        }
    }


    public function getTransactions(string $apiToken){


        $user = $this->getUserByToken($apiToken);

        $userTransactions = $this->transRepo->findBy(['username' => $user]);

        


        $result = [];

        foreach ($userTransactions as $key) {
            $result[] = [
                'id' => $key->getId(),
                'type' => $key->getOperationType()==0?'payment':'deposite',
                'course_code' => $key->getCourse()->getSymbolCode(),
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