<?php

namespace App\Controller;

use App\Repository\UserRepository;
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



class AuthController extends AbstractController
{

    public function register(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator, JWTTokenManagerInterface $JWTManager)
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
            $em->persist($user);

            $em->flush();


        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

    public function api()
    {



        $response = array(
            'status' => true,
            'message' => 'Success',
            'data' => $this->getUser()->getBalance()
        );

        return new JsonResponse(json_encode($response));

    }
}

