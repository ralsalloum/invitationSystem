<?php

namespace App\Controller;

use App\AutoMapping;
use App\Request\UserRegisterRequest;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("v1/user/")
 */
class UserController extends BaseController
{
    private AutoMapping $autoMapping;
    private ValidatorInterface $validator;
    private UserService $userService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, UserService $userService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * Register a new user.
     * @Route("registernewuser", name="registerNewUser", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Tag(name="User")
     *
     * @OA\RequestBody(
     *      description="Register a new user request",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="email"),
     *          @OA\Property(type="string", property="password")
     *      )
     * )
     *
     * @OA\Response(
     *      response=201,
     *      description="Returns the new user' roles and the creation date",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="object", property="Data",
     *                  ref=@Model(type="App\Response\InvitationCreateResponse")
     *          )
     *      )
     * )
     */
    public function registerNewUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, UserRegisterRequest::class, (object)$data);

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->userService->registerNewUser($request);

        if (isset($response->found)) {
            return $this->response($response, self::ERROR_USER_FOUND);
        }

        return $this->response($response, self::CREATE);
    }
}