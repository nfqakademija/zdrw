<?php

namespace Main\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MainOffersBundle:Default:index.html.twig', array('name' => $name));
    }
}
