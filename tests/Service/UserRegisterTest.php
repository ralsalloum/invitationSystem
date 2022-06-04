<?php

namespace App\Tests\Service;

use App\AutoMapping;
use App\Entity\UserEntity;
use App\Manager\UserManager;
use App\Request\UserRegisterRequest;
use App\Response\UserRegisterResponse;
use App\Service\UserService;
use App\Tests\Fixtures\UserProvider;
use PHPUnit\Framework\TestCase;

class UserRegisterTest extends TestCase
{
    private $autoMapping;
    private $userManagerMock;

    public function setUp(): void
    {
        $this->autoMapping = new AutoMapping();
        $this->userManagerMock = $this->createMock(UserManager::class);
    }

    /**
     * @dataProvider provideUserCreateRole
     */
    public function testRegisterNewUser(array $expectedRole, array $actualRole)
    {
        $userRegisterResponse = new UserRegisterResponse();

        $userRegisterResponse->roles = $expectedRole;

        $user = new UserEntity("useremail@example.com");
        $user->setRoles($actualRole);
        $user->setPassword("123456");
        $user->setCreatedAt(new \DateTime('now'));

        $userRegisterRequest = new UserRegisterRequest();
        $userRegisterRequest->setEmail("useremail@example.com");
        $userRegisterRequest->setPassword("123456");
        $user->setRoles($expectedRole);

        $this->userManagerMock->method('registerNewUser')->willReturn($user);

        $userService = new UserService($this->autoMapping, $this->userManagerMock);

        $this->assertEquals($userRegisterResponse, $userService->registerNewUser($userRegisterRequest));
    }

    /**
     * @dataProvider provideUserFoundResult
     */
    public function testRegisterExistingUser(string $expectedFound, string $actualFound)
    {
        $userRegisterResponse = new UserRegisterResponse();
        $userRegisterResponse->found = $expectedFound;

        $user = new UserEntity("useremail@example.com");
        $user->setRoles(['ROLE_USER']);
        $user->setPassword("123456");
        $user->setCreatedAt(new \DateTime('now'));

        $userRegisterRequest = new UserRegisterRequest();
        $userRegisterRequest->setEmail("useremail@example.com");
        $userRegisterRequest->setPassword("123456");
        $user->setRoles(['ROLE_USER']);

        $this->userManagerMock->method('registerNewUser')->willReturn($actualFound);

        $userService = new UserService($this->autoMapping, $this->userManagerMock);

        $this->assertEquals($userRegisterResponse, $userService->registerNewUser($userRegisterRequest));
    }

    /**
     * return string[][]
     */
    public function provideUserCreateRole(): array
    {
        $result = new UserProvider();

        return $result->provideUserCreateRole();
    }

    /**
     * return string[][]
     */
    public function provideUserFoundResult(): array
    {
        $result = new UserProvider();

        return $result->provideUserFoundResult();
    }
}
