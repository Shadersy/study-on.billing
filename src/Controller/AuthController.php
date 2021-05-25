<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\UserRepository;
use App\Service\PaymentService;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;


class AuthController extends AbstractController
{

    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function register(Request $request, UserPasswordEncoderInterface $encoder,
                             ValidatorInterface $validator, JWTTokenManagerInterface $JWTManager,
                             RefreshTokenManagerInterface $refreshTokenManager)
    {


        $em = $this->getDoctrine()->getManager();

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);



        $userDto = $serializer->deserialize($request->getContent(), User::class, 'json');



        $errors = $validator->validate($userDto);


            if(count($errors) > 0){
                $errorsString = (string) $errors;

                return new JsonResponse(['error' => $errorsString]);
            }


            $user = User::fromDto($userDto);


          $refreshToken = $refreshTokenManager->create();
          $refreshToken->setUsername($user->getEmail());
          $refreshToken->setRefreshToken();
          $refreshToken->setValid((new \DateTime())->modify('+1 month'));
          $refreshTokenManager->save($refreshToken);

            $em->persist($user);
            $em->flush();


        return new JsonResponse(['token' => $JWTManager->create($user), 'refreshToken' => $refreshToken->getRefreshToken()]);
    }

    public function api() : ?JsonResponse
    {

        $arr = array('balance' => $this->getUser()->getBalance());

        return new JsonResponse($this->getUser()->getBalance());

    }

    public function courseList()
    {
       $arr = $this->getDoctrine()->getRepository(Course::class)->findAll();

       $result = [];

        foreach ($arr as $key) {
            $result[] = [
                'symbol_code' => $key->getSymbolCode(),
                'course_type' => $key->getCourseType(),
                'cost' => $key->getCost()
            ];
        }


       return new JsonResponse($result);

    }


    public function refresh(Request $request, RefreshToken $refreshService)
    {

        return $refreshService->refresh($request);
    }


    public function doPayment(Request $request)
    {

        $code = explode("/", $request->getPathInfo())[4];

        $bearerToken = $request->headers->get('Authorization');


       $response = $this->paymentService->pay($code, $bearerToken);


        return new JsonResponse($response);
    }


     public function showCourse(Request $request)
     {

         $code = explode("/", $request->getPathInfo())[4];

         $course = $this->
         getDoctrine()->
         getRepository(Course::class)->
         findBy(['symbol_code' => $code]);

         $result = [];

         foreach ($course as $key) {
             $result = [
                 'symbol_code' => $key->getSymbolCode(),
                 'course_type' => $key->getCourseType(),
                 'cost' => $key->getCost()
             ];
         }


         return new JsonResponse($result);
     }

     public function showTransactions(Request $request)
     {

         $bearerToken = $request->headers->get('Authorization');

         $response =  $this->paymentService->getTransactions($bearerToken);

         return new JsonResponse($response);

     }


}

