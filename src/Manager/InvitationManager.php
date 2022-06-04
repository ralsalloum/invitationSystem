<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\InvitationEntity;
use App\Repository\InvitationEntityRepository;
use App\Request\InvitationCreateRequest;
use App\Request\InvitationStatusUpdateRequest;
use Doctrine\ORM\EntityManagerInterface;

class InvitationManager
{
    private AutoMapping $autoMapping;
    private EntityManagerInterface $entityManager;
    private InvitationEntityRepository $invitationEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, InvitationEntityRepository $invitationEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->invitationEntityRepository = $invitationEntityRepository;
    }

    public function createNewInvitation(InvitationCreateRequest $request): InvitationEntity
    {
        $invitationEntity = $this->autoMapping->map(InvitationCreateRequest::class, InvitationEntity::class, $request);

        $this->entityManager->persist($invitationEntity);
        $this->entityManager->flush();

        return $invitationEntity;
    }

    public function getInvitationStatusByInvitationId(int $invitationId): string|null
    {
        $invitationEntity = $this->invitationEntityRepository->find($invitationId);

        if ($invitationEntity !== null) {
            return $invitationEntity->getStatus();
        }

        return $invitationEntity;
    }

    public function updateInvitationStatus(InvitationStatusUpdateRequest $request): ?InvitationEntity
    {
        $invitationEntity = $this->invitationEntityRepository->find($request->getId());

        if ($invitationEntity) {
            $invitationEntity->setStatus($request->getStatus());

            $this->entityManager->flush();
        }

        return $invitationEntity;
    }
}
