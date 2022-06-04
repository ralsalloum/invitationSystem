<?php

namespace App\Manager;

use App\AutoMapping;
use App\Constant\UserReturnResultConstant;
use App\Entity\UserEntity;
use App\Repository\UserEntityRepository;
use App\Request\UserRegisterRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    private AutoMapping $autoMapping;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $hasher;
    private UserEntityRepository $userEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, UserEntityRepository $userEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->userEntityRepository = $userEntityRepository;
    }

    public function registerNewUser(UserRegisterRequest $request): string|UserEntity
    {
        $user = $this->userEntityRepository->findOneBy(['email'=>$request->getEmail()]);

        if (! $user) {
            $request->setRoles(["ROLE_USER"]);

            $userRegister = $this->autoMapping->map(UserRegisterRequest::class, UserEntity::class, $request);

            $user = new UserEntity($request->getEmail());

            if ($request->getPassword()) {
                $userRegister->setPassword($this->hasher->hashPassword($user, $request->getPassword()));
            }

            $this->entityManager->persist($userRegister);
            $this->entityManager->flush();

            return $userRegister;

//            if ($userRegister) {
//                return $userRegister;
//
//            } else {
//                return UserReturnResultConstant::USER_IS_NOT_CREATED_RESULT;
//            }

        } else {
            return UserReturnResultConstant::USER_IS_FOUND_RESULT;
        }
    }

    public function getUserEntityByUserId($userId)
    {
        return $this->userEntityRepository->findOneBy(['id'=>$userId]);
    }

    public function getUserEntityByEmail($email)
    {
        return $this->userEntityRepository->findOneBy(['email'=>$email]);
    }
}
