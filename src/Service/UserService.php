<?php

namespace App\Service;

use App\AutoMapping;
use App\Constant\UserReturnResultConstant;
use App\Entity\UserEntity;
use App\Manager\UserManager;
use App\Request\UserRegisterRequest;
use App\Response\UserRegisterResponse;

class UserService
{
    private AutoMapping $autoMapping;
    private UserManager $userManager;

    public function __construct(AutoMapping $autoMapping, UserManager $userManager)
    {
        $this->autoMapping = $autoMapping;
        $this->userManager = $userManager;
    }

    public function registerNewUser(UserRegisterRequest $request): UserRegisterResponse
    {
        $userRegister = $this->userManager->registerNewUser($request);

        if ($userRegister === UserReturnResultConstant::USER_IS_FOUND_RESULT) {
            $user = [];

            $user['found'] = UserReturnResultConstant::YES_RESULT;

            return $this->autoMapping->map("array", UserRegisterResponse::class, $user);
        }

        return $this->autoMapping->map(UserEntity::class, UserRegisterResponse::class, $userRegister);
    }

    public function getUserEntityByUserId($userId)
    {
        return $this->userManager->getUserEntityByUserId($userId);
    }

    public function getUserEntityByEmail($email)
    {
        return $this->userManager->getUserEntityByEmail($email);
    }
}
