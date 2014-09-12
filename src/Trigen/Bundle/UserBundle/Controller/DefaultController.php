<?php

namespace Trigen\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
    	var_dump($this->getUser());
        return $this->render('TrigenUserBundle:Default:index.html.twig', array('name' => $this->getUser()->getUsername()));
    }
}
