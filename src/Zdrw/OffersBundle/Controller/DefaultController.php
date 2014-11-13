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

    public function indexAction()
    {
        return $this->render("ZdrwOffersBundle:Default:index.html.twig", array('user' => $this->getUser()));
    }
    public function daresAction()
    {
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render('ZdrwOffersBundle:Default:dares.html.twig', array('dares' => $dares, 'user'=> $this->getUser()));
    }
    public function dareAction($id)
    {
        $dare = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findOneById($id);
        return $this->render("ZdrwOffersBundle:Default:dare.html.twig", array('dare' => $dare, 'user' => $this->getUser()));
    }
    public function staresAction()
    {
        return $this->render('ZdrwOffersBundle:Default:stares.html.twig', array('user' => $this->getUser()));
    }
    public function profileAction()
    {
        $user = $this->getUser();
        $notifications = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')->findBy(array("user" => $user->getId()));
        return $this->render('ZdrwOffersBundle:Default:profile.html.twig', array('notifications' => $notifications, 'user'=> $user));
    }
}
