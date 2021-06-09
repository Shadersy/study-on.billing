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



class TransactionController extends AbstractController
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
     *         description="Buing the course"
     *
     *     ),
     *
     *      @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="User's token.",
     *     required=true,
     *     type="string",
     *    @SWG\Property(property="apiToken", type="string", example="Bearer {token}"),
     *   ),
     *
     *)
     */
    public function doPayment(Request $request)
    {

        $code = $request->attributes->get(['_route_params'][0])['code'];

        $bearerToken = $request->headers->get('Authorization');


        $response = $this->paymentService->pay($code, $bearerToken);



        return new JsonResponse($response);
    }



    /**
     * @SWG\Post(
     *
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Do deposite via sum"
     *
     *     ),
     *
     *      @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="User's token.",
     *     required=true,
     *     type="string",
     *    @SWG\Property(property="apiToken", type="string", example="Bearer {token}"),
     *   ),
     *
     *)
     */
    public function doDeposite(Request $request)
    {
        $bearerToken = $request->headers->get('Authorization');

        $sum = $request->attributes->get(['_route_params'][0])['sum'];
        $response = $this->paymentService->deposite($sum, $bearerToken);

        return new JsonResponse($response);
    }

    /**
     * @SWG\Get(
     *
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Show the course from all courses "
     *
     *     ),
     *
     *      @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="User's token.",
     *     required=true,
     *     type="string",
     *    @SWG\Property(property="apiToken", type="string", example="Bearer {token}"),
     *   ),
     *
     *)
     */
    public function showCourse(Request $request)
    {

        $code = $request->attributes->get(['_route_params'][0])['code'];


        $course = $this->
        getDoctrine()->
        getRepository(Course::class)->
        findBy(['symbolCode' => $code]);

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

    /**
     * @SWG\Get(
     *
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Show the course from all courses "
     *
     *     ),
     *
     *      @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="User's token.",
     *     required=true,
     *     type="string",
     *    @SWG\Property(property="apiToken", type="string", example="Bearer {token}"),
     *   ),
     *
     *      @SWG\Parameter(
     *       name="filter[course]",
     *       description="filter the courses by symbol code what user has",
     *       required=false,
     *       type="string",
     *       in="query"
     *     ),
     *
     *      @SWG\Parameter(
     *       name="filter[type]",
     *       description="filter the courses by deposite/payment operation type what user has",
     *       required=false,
     *       type="string",
     *       in="query"
     *     ),
     *
     *
     *       @SWG\Parameter(
     *       name="filter[skipexpired]",
     *       description="skip the expired rent-courses if flag equal true",
     *       required=false,
     *       type="string",
     *       in="query"
     *     ),
     *
     *)
     */
    public function showTransactions(Request $request)
    {

        $bearerToken = $request->headers->get('Authorization');


        $filters = $request->query->all();


        $response =  $this->paymentService->getTransactions($bearerToken, $filters);

        return new JsonResponse($response);
    }

    /**
     * @SWG\Get(
     *
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Returns list of courses",
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
    public function courseList(Request $request)
    {
        $bearerToken = $request->headers->get('Authorization');

        $user = $this->paymentService->getUserByToken($bearerToken);
        $username = $user->getUsername();


        $userCourses = $this->paymentService->getCousesByUser($username);



        $arr = $this->getDoctrine()->getRepository(Course::class)->findAll();

        $allCourses = [];

        foreach ($arr as $item) {
            $allCourses[$item->getSymbolCode()] = [
                'code' => $item->getSymbolCode(),
                'id' => $item->getId(),
                'type' => $item->getCourseType(),
                'cost' => $item->getCost(),
                'email' => null,
                'validity' => null,
            ];
        }


        $nonMergedUserCourses = [];
        foreach ($userCourses as $item) {
            $nonMergedUserCourses[$item['symbol_code']] = [
                'code' => $item['symbol_code'],
                'email' => $item['email'],
                'cost' => $item['cost'],
                'type' => $item['course_type'],
                'validity' => $item['end_of_rent']
            ];
        }


        $result = $nonMergedUserCourses + $allCourses;


        return new JsonResponse($result);
    }
}