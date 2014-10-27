<?php

namespace Zdrw\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the offers
 */
class DefaultController extends Controller
{
    /**
     * Function rendering the main project page
     *
     * @param string
     * @return object
     */
    public function indexAction($name)
    {
        return $this->render('ZdrwOffersBundle:Default:index.html.twig', array('name' => $name));
    }
}
