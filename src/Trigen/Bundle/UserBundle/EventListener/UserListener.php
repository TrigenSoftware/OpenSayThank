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

namespace Trigen\Bundle\UserBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class UserListener {

	private $context;

	private $router;

	public function __construct(SecurityContext $context, Router $router)
    {
        $this->context = $context;
        $this->router  = $router;
    }

	public function onRequest( GetResponseEvent $event )
    {
        $currentUser = $this->context->getToken();
        $currentUser = $currentUser ? $currentUser->getUser() : "anon.";
        $isAnon = $currentUser == "anon.";
        $path = preg_replace("/\/$/", "", $event->getRequest()->getPathInfo());
        $redirect = false;
        
        if ($isAnon && !$this->isSafePath($path)) {
	        $redirect = "trigen_user_login";
		} else

		if (!$isAnon && !$currentUser->getVerified() && !$this->isSafePath($path, true)) {
	        $redirect = "trigen_user_verify";
		}

		if ($redirect) {
			$url = $this->router->generate($redirect);
			$response = new RedirectResponse($url);
			$event->setResponse($response);
		}
    }

    private function isSafePath( $path, $logined = false ){
    	return preg_match("/^\/\w+(\/\w+|)$/", $path) && (
    		strpos($path, "/connect") !== false || 
    		strpos($path, "/login")   !== false ||
    		strpos($path, "/preview")   !== false ||
    		( $path == "/verify" && $logined )  ||
    		$path == "/logout" || $path == "/api" 
    	);
    }
}