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
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array('status' => 4));
            return $this->render(
                'ZdrwOffersBundle:Admin:index.html.twig',
                array(
                    'user' => $this->getUser(), 'stares' => $stares
                )
            );
        }
        else{
            return $this->redirect($this->generateUrl('zdrw_index'),301);
        }
    }
}
