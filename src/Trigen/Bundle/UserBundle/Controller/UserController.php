<?php
/*
	Copyright (C) TrigenSoftware, 2014

	This file is part of OpenSayThank.

	OpenSayThank is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	OpenSayThank is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with OpenSayThank.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Trigen\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
	public function loginAction()
	{
		$count = $this->get('trigen.thank_controller')->apiThanksCount();
		return $this->render('TrigenUserBundle:Thank:login.html.twig', array( 
			"requireLogin" => true,
			"thanksCount"  => $count['count']
		));
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
			
			if (!$userexist && preg_match("/^[\.a-zA-Z0-9_-]{3,255}$/", $newUsername) 
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
		$count = $this->get('trigen.thank_controller')->apiThanksCount();

		return $this->render('TrigenUserBundle:Thank:login.html.twig', array(
			"username"     => $newUsername ? $newUsername : $username,
			"userexist"    => $userexist,
			"requireLogin" => true,
			"thanksCount"  => $count['count'],
			"verify"       => $request->get("_route") == 'trigen_user_verify' && !$this->getUser()->getVerified()
		));
	}
}
