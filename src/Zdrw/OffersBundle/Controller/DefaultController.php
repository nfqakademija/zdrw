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
    private function userInfo(){
        $id = $this->getUser()->getId();
        $name = $this->getUser()->getUsername();
        $email = $this->getUser()->getEmail();
        $pass = array(
            'id' => $id,
            'name' => $name,
            'email' => $email
        );
        return $pass;
    }
    public function daresAction()
    {
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        $pass = $this->userInfo();
        $pass["dares"] = $dares;
        return $this->render('ZdrwOffersBundle:Default:dares.html.twig', $pass);
    }

    public function staresAction()
    {
        $pass = $this->userInfo();
        return $this->render('ZdrwOffersBundle:Default:stares.html.twig', $pass);
    }
    public function profileAction()
    {
        $pass = $this->userInfo();
        $notifications = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')->findBy(array("user" => $pass["id"]));
        $pass["notifications"] = $notifications;
        return $this->render('ZdrwOffersBundle:Default:profile.html.twig', $pass);
    }
}
