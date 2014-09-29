<?php

namespace Trigen\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function loginAction()
    {
        return $this->render('TrigenUserBundle:Thank:login.html.twig', array( "requireLogin" => true ));
    }

    public function verifyAction(Request $request)
    {
    	$userexist   = false;
    	$username    = $this->getUser()->getUsername();
    	$newUsername = $request->request->get("username");

    	if ($newUsername) {
    		$email = $request->request->get("email");
	    	if (trim($email) == "") $email = false;

	    	$users = $this->getDoctrine()->getRepository('TrigenUserBundle:User');
	    	$userexist = $users->findOneBy(array('username' => $newUsername));
	    	
	    	if (!$userexist && preg_match("/^[a-zA-Z0-9_-\.]{3,255}$/", $newUsername) 
	    		&& (!$email 
	    			? true 
	    			: preg_match("/^[-a-z0-9!#$%&'*+\/=?^_`{|}~]+(?:\.[-a-z0-9!#$%&'*+\/=?^_`{|}~]+)*@(?:[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])?\.)*(?:aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|[a-z][a-z])$/", $email)
	  			)
	    	) {
	    		$em = $this->getDoctrine()->getManager();

	    		$user = $users->findOneBy(array('username' => $username));
	    		$user->setUsername($newUsername);
	    		$user->setVerified(true);

	    		$em->persist($user);
	    		$em->flush();

	    		$profile = $this->getDoctrine()
	    			->getRepository('TrigenUserBundle:Profile')
	    			->findOneBy(array('username' => $username));
	    		$profile->setUsername($newUsername);
	    		if ($email) $profile->setEmail($email);

	    		$em->persist($profile);
	    		$em->flush();

	    		return new RedirectResponse($this->generateUrl('trigen_thank_homepage', array( "path" => "" )));
	    	}
	    }

    	$username = preg_replace("/[\d\w]+@/", "", $username);

        return $this->render('TrigenUserBundle:Thank:login.html.twig', array(
        	"username"     => $newUsername ? $newUsername : $username,
        	"userexist"    => $userexist,
        	"requireLogin" => true,
        	"verify"       => $request->get("_route") == 'trigen_user_verify' && !$this->getUser()->getVerified()
        ));
    }
}
