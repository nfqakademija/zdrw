<?php

namespace Zdrw\OffersBundle\Controller;

use Zdrw\OffersBundle\Entity\Reward;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zdrw\OffersBundle\Entity\Offer;
use Symfony\Component\HttpFoundation\Request;
use Zdrw\OffersBundle\Form\Type\OfferType;
use Zdrw\OffersBundle\Services\UserInfoProvider;

/**
 * Controller managing the offers
 */
class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('ZdrwOffersBundle:Admin:index.html.twig', array('user' => $this->getUser()));
    }
}
