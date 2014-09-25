<?php

namespace Trigen\Bundle\ThankBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ThankController extends Controller
{
    public function indexAction()
    {
        return $this->render('TrigenThankBundle:Thank:index.html.twig', array('name' => "index"));
    }

    public function apiAction(Request $request)
    {
    	if ($this->getUser() === null) {
    		$response = array('error' => 'access denied');
    	} else 

    	if ($request->request->get("action") === "currentUserData") {
    		$response = $this->apiCurrentUserData();
    	} else 

    	$response = array('error' => 'invalid request');

    	return new JsonResponse($response);
    }

    private function apiCurrentUserData()
    {
    	$user = $this->getUser();

    	return array(
    		"username" => $user->getUsername(),
    		"photo"    => $user->getPhoto()
    	);
    }
}
