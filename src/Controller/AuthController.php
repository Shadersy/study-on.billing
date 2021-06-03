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



class AuthController extends AbstractController
{

    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @SWG\Post(
     *
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Returns token and creating user in billing database",
     *         @SWG\Schema(
     *             type="array",
     *             @Model(type=App\Entity\User::class)
     *         )
     *     ),
     *
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="User email used to create account.",
     *     required=true,
     *     @SWG\Schema(
     *     @SWG\Property(property="email", type="string", example="abc@abc.com"),
     *     @SWG\Property(property="password", type="string", example="12345678")
     * ),
     *   ),
     *
     *
     * )
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        ValidatorInterface $validator,
        JWTTokenManagerInterface $JWTManager,
        RefreshTokenManagerInterface $refreshTokenManager
    ) {


        $em = $this->getDoctrine()->getManager();

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);



        $userDto = $serializer->deserialize($request->getContent(), User::class, 'json');



        $errors = $validator->validate($userDto);


        if (count($errors) > 0) {
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


        return new JsonResponse(['token' => $JWTManager->create($user),
            'refreshToken' => $refreshToken->getRefreshToken()]);
    }

    /**
     * @SWG\Get(
     *
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Returns balance of user",
     *
     *     ),
     *
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="User's token.",
     *     required=true,
     *     type="string",
     *    @SWG\Property(property="apiToken", type="string", example="Bearer {token}"),
     *   ),
     *
     *
     * )
     */
    public function api(): ?JsonResponse
    {

        $arr = array('balance' => $this->getUser()->getBalance());
        return new JsonResponse(['balance' => $this->getUser()->getBalance()]);
    }


    public function refresh(Request $request, RefreshToken $refreshService)
    {

        return $refreshService->refresh($request);
    }





}

