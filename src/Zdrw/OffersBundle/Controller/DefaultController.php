<?php

namespace Zdrw\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the offers
 */
class DefaultController extends Controller
{
    /**
     * Method rendering the main project page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render("ZdrwOffersBundle:Default:index.html.twig", array('user' => $this->getUser()));
    }

    /**
     * Method rendering the "Dares" page with all dares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function daresAction()
    {
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render('ZdrwOffersBundle:Default:dares.html.twig', array('dares' => $dares, 'user'=> $this->getUser()));
    }

    /**
     * Method rendering certain dare page
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dareAction($id)
    {
        $dare = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findOneById($id);
        return $this->render("ZdrwOffersBundle:Default:dare.html.twig", array('dare' => $dare, 'user' => $this->getUser()));
    }

    /**
     * Method rendering the "Stares" page with all stares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staresAction()
    {
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render('ZdrwOffersBundle:Default:stares.html.twig', array('stares' => $stares, 'user' => $this->getUser()));
    }

    /**
     * Method rendering user profile page with that user data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction()
    {
        $user = $this->getUser();
        $notifications = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')->findBy(array("user" => $user->getId()));
        return $this->render('ZdrwOffersBundle:Default:profile.html.twig', array('notifications' => $notifications, 'user'=> $user));
    }
}
