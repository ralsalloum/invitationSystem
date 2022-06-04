<?php

namespace App\Tests\Service;

use App\AutoMapping;
use App\Entity\InvitationEntity;
use App\Entity\UserEntity;
use App\Manager\InvitationManager;
use App\Manager\UserManager;
use App\Request\InvitationCreateRequest;
use App\Request\InvitationStatusUpdateRequest;
use App\Response\InvitationCreateResponse;
use App\Service\InvitationService;
use App\Service\UserService;
use App\Tests\Fixtures\InvitationProvider;
use PHPUnit\Framework\TestCase;

class InvitationTest extends TestCase
{
    private $autoMapping;
    private $userEntityOne;
    private $userEntityTwo;
    private $userManagerMock;
    private $invitationManagerMock;

    public function setUp(): void
    {
        $this->autoMapping = new AutoMapping();

        $this->userManagerMock = $this->createMock(UserManager::class);
        $this->invitationManagerMock = $this->createMock(InvitationManager::class);

        // prepare required entities
        $this->userEntityOne = new UserEntity('user0@example.com');
        $this->userEntityOne->setCreatedAt(new \DateTime('now'));
        $this->userEntityOne->setPassword("123456");
        $this->userEntityOne->setRoles(['ROLE_USER']);

        $this->userEntityTwo = new UserEntity('user1@example.com');
        $this->userEntityTwo->setCreatedAt(new \DateTime('now'));
        $this->userEntityTwo->setPassword("123456");
        $this->userEntityTwo->setRoles(['ROLE_USER']);
    }

    /**
     * @dataProvider provideInvitationData
     */
    public function testCreateNewInvitation(string $expectedStatus, string $actualStatus)
    {
        // prepare expected response
        $invitationCreateResponse = new InvitationCreateResponse();

        $invitationCreateResponse->status = $expectedStatus;
        $invitationCreateResponse->createdAt = new \DateTime("2022-06-03");

        $invitation = new InvitationEntity();
        $invitation->setStatus($actualStatus);
        $invitation->setSender($this->userEntityOne);
        $invitation->setReceiver($this->userEntityTwo);
        $invitation->setCreatedAt(new \DateTime('Now'));
        $invitation->setUpdatedAt(new \DateTime('Now'));

        // prepare required request
        $invitationCreateRequest = new InvitationCreateRequest();
        $invitationCreateRequest->setSender(1);
        $invitationCreateRequest->setReceiver('user1@example.com');

        // mock required functions
        $this->userManagerMock->method('getUserEntityByUserId')->willReturn($this->userEntityOne);
        $this->userManagerMock->method('getUserEntityByEmail')->willReturn($this->userEntityTwo);

        $userService = new UserService($this->autoMapping, $this->userManagerMock);

        $this->invitationManagerMock->method('createNewInvitation')->willReturn($invitation);

        $invitationService = new InvitationService($this->autoMapping, $this->invitationManagerMock, $userService);

        //check if specific filed of the expected response is equal to the field of the actual one
        $this->assertEquals($invitationCreateResponse->status, $invitationService->createNewInvitation($invitationCreateRequest)->status);
    }

    /**
     * @dataProvider provideInvitationStatusForSender
     */
    public function testUpdateInvitationStatusBySender(string $expectedStatus, string $actualStatus)
    {
        $invitationCreateResponse = new InvitationCreateResponse();

        $invitationCreateResponse->status = $expectedStatus;

        $invitation = new InvitationEntity();

        $invitation->setStatus($actualStatus);
        $invitation->setSender($this->userEntityOne);
        $invitation->setReceiver($this->userEntityTwo);
        $invitation->setUpdatedAt(new \DateTime('Now'));

        $invitationStatusUpdateRequest = new InvitationStatusUpdateRequest();

        $invitationStatusUpdateRequest->setId(1);
        $invitationStatusUpdateRequest->setStatus('cancelled');

        $userService = new UserService($this->autoMapping, $this->userManagerMock);

        $this->invitationManagerMock->method('getInvitationStatusByInvitationId')->willReturn($actualStatus);

        $this->invitationManagerMock->method('updateInvitationStatus')->willReturn($invitation);

        $invitationService = new InvitationService($this->autoMapping, $this->invitationManagerMock, $userService);

        $this->assertEquals($invitationCreateResponse->status, $invitationService->updateInvitationStatusBySender($invitationStatusUpdateRequest)->status);
    }

    /**
     * @dataProvider provideInvitationStatusForReceiver
     */
    public function testUpdateInvitationStatusByReceiver(string $expectedStatus, string $actualStatus)
    {
        $invitationCreateResponse = new InvitationCreateResponse();

        $invitationCreateResponse->status = $expectedStatus;

        $invitation = new InvitationEntity();

        $invitation->setStatus($actualStatus);
        $invitation->setSender($this->userEntityOne);
        $invitation->setReceiver($this->userEntityTwo);
        $invitation->setUpdatedAt(new \DateTime('Now'));

        $invitationStatusUpdateRequest = new InvitationStatusUpdateRequest();

        $invitationStatusUpdateRequest->setId(1);
        $invitationStatusUpdateRequest->setStatus($actualStatus);

        $userService = new UserService($this->autoMapping, $this->userManagerMock);

        $this->invitationManagerMock->method('getInvitationStatusByInvitationId')->willReturn('sent');

        $this->invitationManagerMock->method('updateInvitationStatus')->willReturn($invitation);

        $invitationService = new InvitationService($this->autoMapping, $this->invitationManagerMock, $userService);

        $this->assertEquals($invitationCreateResponse->status, $invitationService->updateInvitationStatusByReceiver($invitationStatusUpdateRequest)->status);
    }

    /**
     * @return string[][]
     */
    public function provideInvitationData(): array
    {
        $result = new InvitationProvider();

        return $result->provideInvitationData();
    }

    /**
     * @return string[][]
     */
    public function provideInvitationStatusForSender(): array
    {
        $result = new InvitationProvider();

        return $result->provideInvitationStatusForSender();
    }

    /**
     * @return string[][]
     */
    public function provideInvitationStatusForReceiver(): array
    {
        $result = new InvitationProvider();

        return $result->provideInvitationStatusForReceiver();
    }
}
