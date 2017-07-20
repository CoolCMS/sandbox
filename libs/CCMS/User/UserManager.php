<?php

namespace CCMS\User;

use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityManager;

class UserManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isValidLogin($email, $password){
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if(!$user){
            return false;
        }
        if(!$user->verifyPassword($password)){
            return false;
        }
        return $user;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isEmailUsed($email){
        return (bool)$this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function createIdentityArray(User $user)
    {
        return $user->toArray();
    }

    public function activateUserEmail($hash)
    {
        try{
            /** @var User $user */
            $user = $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.emailVerifyHash = :hash')
                ->setParameter('hash', $hash)
                ->getQuery()
                ->getSingleResult();
        }catch (NoResultException $e){
            return false;
        }
        $user->setEmailVerified();
        $this->entityManager->flush();
        return true;
    }

    /**
     * @param $id
     * @return null|User
     */
    public function getUser($id)
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }
}