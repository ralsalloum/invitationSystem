<?php

namespace App\Controller;

use App\AutoMapping;
use App\Constant\InvitationResultConstant;
use App\Constant\InvitationStatusConstant;
use App\Constant\UserReturnResultConstant;
use App\Request\InvitationCreateRequest;
use App\Request\InvitationStatusUpdateRequest;
use App\Service\InvitationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("v1/invitation/")
 */
class InvitationController extends BaseController
{
    private AutoMapping $autoMapping;
    private ValidatorInterface $validator;
    private InvitationService $invitationService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, InvitationService $invitationService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->invitationService = $invitationService;
    }

    /**
     * Create a new invitation by user.
     * @Route("newinvitation", name="createNewInvitationByUser", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Tag(name="Invitation")
     *
     * @OA\RequestBody(
     *      description="Create a new invitation request",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="subject"),
     *          @OA\Property(type="string", property="text"),
     *          @OA\Property(type="string", property="receiver")
     *      )
     * )
     *
     * @OA\Response(
     *      response=201,
     *      description="Returns the new invitation creation date and status",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="object", property="Data",
     *                  ref=@Model(type="App\Response\UserRegisterResponse")
     *          )
     *      )
     * )
     */
    public function createNewInvitation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, InvitationCreateRequest::class, (object)$data);

        $request->setSender($this->getUserId());

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->invitationService->createNewInvitation($request);

        if ($response === UserReturnResultConstant::EITHER_SEND_OR_RECEIVER_IS_NOT_EXIST_RESULT) {
            return $this->response($response, self::ERROR_SEND_OR_RECEIVER_IS_NOT_EXIST);
        }

        return $this->response($response, self::CREATE);
    }

    /**
     * Update sent invitation status by sender.
     * @Route("sentinvitationstatus", name="UpdateInvitationStatusBySender", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Tag(name="Invitation")
     *
     * @OA\RequestBody(
     *      description="Update invitation status request",
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="id"),
     *          @OA\Property(type="string", property="status")
     *      )
     * )
     *
     * @OA\Response(
     *      response=204,
     *      description="Returns the updated invitation creation date and status",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="object", property="Data",
     *                  ref=@Model(type="App\Response\UserRegisterResponse")
     *          )
     *      )
     * )
     */
    public function updateInvitationStatusBySender(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, InvitationStatusUpdateRequest::class, (object)$data);

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->invitationService->updateInvitationStatusBySender($request);

        if ($response === InvitationResultConstant::CAN_NOT_UPDATE_INVITATION_STATUS) {
            return $this->response($response, self::ERROR_CAN_NOT_UPDATE_INVITATION_STATUS);
        }

        return $this->response($response, self::UPDATE);
    }

    /**
     * Update received invitation status by sender.
     * @Route("receivedinvitationstatus", name="UpdateInvitationStatusByReceiver", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Tag(name="Invitation")
     *
     * @OA\RequestBody(
     *      description="Update invitation status request",
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="id"),
     *          @OA\Property(type="string", property="status")
     *      )
     * )
     *
     * @OA\Response(
     *      response=204,
     *      description="Returns the updated invitation creation date and status",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="object", property="Data",
     *                  ref=@Model(type="App\Response\UserRegisterResponse")
     *          )
     *      )
     * )
     */
    public function updateInvitationStatusByReceiver(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, InvitationStatusUpdateRequest::class, (object)$data);

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->invitationService->updateInvitationStatusByReceiver($request);

        if ($response === InvitationResultConstant::CAN_NOT_UPDATE_INVITATION_STATUS) {
            return $this->response($response, self::ERROR_CAN_NOT_UPDATE_INVITATION_STATUS);
        }

        return $this->response($response, self::UPDATE);
    }
}
