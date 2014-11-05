<?php

namespace Trigen\Bundle\UserBundle\Entity;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ManagerRegistry;

class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface {

    /**
     * @var mixed
     */
    protected $em;

    /**
     * @var mixed
     */
    protected $users;


    /**
     * @var mixed
     */
    protected $profiles;

    /**
     * @var mixed
     */
    protected $currentUser;

    public function __construct(ManagerRegistry $registry) 
    {
        $this->em = $registry->getManager();
        $this->users = $this->em->getRepository("TrigenUserBundle:User"); 
        $this->profiles = $this->em->getRepository("TrigenUserBundle:Profile");  
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response) 
    {
        $id       = $response->getUsername()."";
        $username = $response->getNickname();
        $realname = $response->getRealName();
        $photo    = $response->getProfilePicture();

        $resourceOwnerName  = $response->getResourceOwner()->getName();
        $setId       = "set".ucfirst($resourceOwnerName)."Id"; 
        $setPhoto    = "set".ucfirst($resourceOwnerName)."Photo"; 
        $setUsername = "set".ucfirst($resourceOwnerName)."Username"; 
        $setRealname = "set".ucfirst($resourceOwnerName)."Realname"; 

        if ($resourceOwnerName == "vkontakte") {
            $realname = preg_replace("/(.+)\s(.+)/", "$2 $1", $realname);
            if (strlen(trim($username)) == 0) $username = $this->realnameToUsername($realname);
        }

        $user = $this->users->findOneBy(
            array( $resourceOwnerName.'Id' => $id )
        );

        if ($user === NULL && $this->currentUser !== NULL)
            $user = $this->users->findOneBy(
                array( "username" => $this->currentUser->getUsername() )
            );

        if ($user === null) {
            $user = new User();
            $user->setUsername("$id@$username");
            $user->setPhoto($photo);
            $user->setLastLogin(new \DateTime(null, new \DateTimeZone("GMT")));
            $user->$setId($id);

            $this->em->persist($user);
            $this->em->flush();

            $profile = new Profile();
            $profile->setUsername("$id@$username");
            $profile->$setPhoto($photo);
            $profile->$setUsername($username);
            $profile->$setRealname($realname);

            $this->em->persist($profile);
            $this->em->flush();
        } else {
            $user->setLastLogin(new \DateTime(null, new \DateTimeZone("GMT")));
            $user->$setId($id);

            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

    public function loadUserByUsername($username) 
    {

        $user = $this->users->findOneBy(array('username' => $username));
        if (!$user) {
            throw new UsernameNotFoundException(sprintf("User '%s' not found.", $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user) 
    {
        $this->currentUser = $user;
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === $this->class || is_subclass_of($class, $this->class);
    }

    public function realnameToUsername($realname) 
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"Ts","Ч"=>"Ch",
            "Ш"=>"Sh","Щ"=>"Sch","Ъ"=>"","Ы"=>"Yi","Ь"=>"",
            "Э"=>"E","Ю"=>"Yu","Я"=>"Ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"yo","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
            " "=> "", "."=> ""
        );
        
        return strtr($realname, $tr);
    }
}