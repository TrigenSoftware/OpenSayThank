<?php

namespace Trigen\Bundle\UserBundle\Entity;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface {

    /**
     * @var mixed
     */
    protected $em;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var mixed
     */
    protected $repository;

    public function __construct(ManagerRegistry $registry, $className) {
        $this->em = $registry->getManager();
        $this->repository = $this->em->getRepository($className);
        $this->className = $className;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $id       = $response->getUsername()."";
        $username = $response->getNickname();
        $realname = $response->getRealName();

        $resourceOwnerName  = $response->getResourceOwner()->getName();
        $cResourceOwnerName = "set".ucfirst($resourceOwnerName)."Id"; 

        $user = $this->repository->findOneBy(
            array($resourceOwnerName.'Id' => $id)
        );

        if ($user === null) {
            $user = new $this->className();
            $user->setUsername($username);
            $user->setLastLogin(new \DateTime());
            $user->$cResourceOwnerName($id);

            $this->em->persist($user);
            $this->em->flush();

        }

        return $user;
    }

    public function loadUserByUsername($username) {

        $user = $this->repository->findOneBy(array('username' => $username));
        if (!$user) {
            throw new UsernameNotFoundException(sprintf("User '%s' not found.", $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === $this->class || is_subclass_of($class, $this->class);
    }
}