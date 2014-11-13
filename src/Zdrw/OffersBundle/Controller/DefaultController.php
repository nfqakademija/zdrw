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
        $infoProvider = $this->get('user_info_provider');
        return $this->render("ZdrwOffersBundle:Default:index.html.twig", $infoProvider->userInfo($this->getUser()));
    }
    public function daresAction()
    {
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        $infoProvider = $this->get('user_info_provider');
        $pass = $infoProvider->userInfo($this->getUser());
        $pass["dares"] = $dares;
        return $this->render('ZdrwOffersBundle:Default:dares.html.twig', $pass);
    }

    public function staresAction()
    {
        $infoProvider = $this->get('user_info_provider');
        return $this->render('ZdrwOffersBundle:Default:stares.html.twig', $infoProvider->userInfo($this->getUser()));
    }
    public function profileAction()
    {
        $infoProvider = $this->get('user_info_provider');
        $pass = $infoProvider->userInfo($this->getUser());
        $notifications = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')->findBy(array("user" => $pass["id"]));
        $pass["notifications"] = $notifications;
        return $this->render('ZdrwOffersBundle:Default:profile.html.twig', $pass);
    }
}
