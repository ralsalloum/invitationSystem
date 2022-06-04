<?php

namespace App\Service;

use App\AutoMapping;
use App\Constant\InvitationStatusConstant;
use App\Constant\InvitationResultConstant;
use App\Constant\UserReturnResultConstant;
use App\Entity\InvitationEntity;
use App\Manager\InvitationManager;
use App\Request\InvitationCreateRequest;
use App\Request\InvitationStatusUpdateRequest;
use App\Response\InvitationCreateResponse;

class InvitationService
{
    private AutoMapping $autoMapping;
    private InvitationManager $invitationManager;
    private UserService $userService;

    public function __construct(AutoMapping $autoMapping, InvitationManager $invitationManager, UserService $userService)
    {
        $this->autoMapping = $autoMapping;
        $this->invitationManager = $invitationManager;
        $this->userService = $userService;
    }

    public function createNewInvitation(InvitationCreateRequest $request): InvitationCreateResponse|string
    {
        // first, get sender and receiver users entities
        $senderEntity = $this->userService->getUserEntityByUserId($request->getSender());

        $receiverEntity = $this->userService->getUserEntityByEmail($request->getReceiver());

        if ($senderEntity !== null && $receiverEntity !== null) {
            // now we can create the invitation
            $request->setSender($senderEntity);
            $request->setReceiver($receiverEntity);

            $invitationResult = $this->invitationManager->createNewInvitation($request);

            return $this->autoMapping->map(InvitationEntity::class, InvitationCreateResponse::class, $invitationResult);
        }

        return UserReturnResultConstant::EITHER_SEND_OR_RECEIVER_IS_NOT_EXIST_RESULT;
    }

    public function updateInvitationStatusBySender(InvitationStatusUpdateRequest $request): null|string|InvitationCreateResponse
    {
        $currentInvitationStatus = $this->invitationManager->getInvitationStatusByInvitationId($request->getId());

        if ($currentInvitationStatus !== null && $currentInvitationStatus !== InvitationStatusConstant::INVITATION_ACCEPTED_STATUS &&
            $currentInvitationStatus !== InvitationStatusConstant::INVITATION_DECLINED_STATUS) {

            if ($request->getStatus() === InvitationStatusConstant::INVITATION_CANCELLED_STATUS || $request->getStatus() === InvitationStatusConstant::INVITATION_SENT_STATUS) {
                $invitationResult = $this->invitationManager->updateInvitationStatus($request);

                if ($invitationResult !== null) {
                    return $this->autoMapping->map(InvitationEntity::class, InvitationCreateResponse::class, $invitationResult);
                }

                return $invitationResult;
            }

            return InvitationStatusConstant::WRONG_INVITATION_STATUS;

        } elseif ($currentInvitationStatus !== null && ($currentInvitationStatus === InvitationStatusConstant::INVITATION_ACCEPTED_STATUS ||
            $currentInvitationStatus !== InvitationStatusConstant::INVITATION_DECLINED_STATUS)) {
            return InvitationResultConstant::CAN_NOT_UPDATE_INVITATION_STATUS;
        }

        return $currentInvitationStatus;
    }

    public function updateInvitationStatusByReceiver(InvitationStatusUpdateRequest $request): null|string|InvitationCreateResponse
    {
        $currentInvitationStatus = $this->invitationManager->getInvitationStatusByInvitationId($request->getId());

        if ($currentInvitationStatus !== null && $currentInvitationStatus === InvitationStatusConstant::INVITATION_SENT_STATUS) {

            if ($request->getStatus() === InvitationStatusConstant::INVITATION_ACCEPTED_STATUS || $request->getStatus() === InvitationStatusConstant::INVITATION_DECLINED_STATUS) {
                $invitationResult = $this->invitationManager->updateInvitationStatus($request);

                if ($invitationResult !== null) {
                    return $this->autoMapping->map(InvitationEntity::class, InvitationCreateResponse::class, $invitationResult);
                }

                return $invitationResult;
            }

            return InvitationStatusConstant::WRONG_INVITATION_STATUS;

        } elseif ($currentInvitationStatus !== null && $currentInvitationStatus === InvitationStatusConstant::INVITATION_CANCELLED_STATUS) {
            return InvitationResultConstant::CAN_NOT_UPDATE_INVITATION_STATUS;
        }

        return $currentInvitationStatus;
    }
}
