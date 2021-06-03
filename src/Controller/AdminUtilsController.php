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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Nelmio\ApiDocBundle\Annotation;



class AdminUtilsController extends AbstractController
{

    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function createCourseByAdmin(Request $request)
    {

        $bearerToken = $request->headers->get('Authorization');
        $params = json_decode($request->getContent());

        $response = $this->paymentService->createCourse($bearerToken, (array) $params);

        return new JsonResponse($response);
    }


    public function editCourse(Request $request)
    {
        $bearerToken = $request->headers->get('Authorization');
        $params = json_decode($request->getContent());

        $currentCode = $request->attributes->get(['_route_params'][0])['code'];


        $response = $this->paymentService->editCourse($bearerToken, (array) $params, $currentCode);


        return new JsonResponse($response);

    }
}