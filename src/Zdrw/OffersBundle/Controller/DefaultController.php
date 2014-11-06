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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function offersAction()
    {
        $offers = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        $name = $this->getUser()->getUsername();
        $email = $this->getUser()->getEmail();
        $pass = array(
            'name' => $name,
            'email' => $email,
            "offers" => $offers
        );
        return $this->render('ZdrwOffersBundle:Default:offers.html.twig', $pass);
    }
}
