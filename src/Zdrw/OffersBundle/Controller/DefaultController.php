<?php

namespace Zdrw\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zdrw\OffersBundle\Entity\Offer;
use Zdrw\OffersBundle\Entity\OfferRepository;
use Symfony\Component\HttpFoundation\Response;

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
    public function indexAction()
    {
        $offers = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        $name = $this->getUser()->getUsername();
        $email = $this->getUser()->getEmail();
        return $this->render('ZdrwOffersBundle:Default:index.html.twig', array('name' => $name,'email' => $email, "offers" => $offers));
    }
}
