<?php

namespace Zdrw\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zdrw\OffersBundle\Entity\Offer;
use Symfony\Component\HttpFoundation\Request;
use Zdrw\OffersBundle\Form\Type\OfferType;
use Zdrw\OffersBundle\Services\UserInfoProvider;
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
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render('ZdrwOffersBundle:Default:dares.html.twig', array('dares' => $dares,'stares' => $stares, 'user'=> $this->getUser()));
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
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render("ZdrwOffersBundle:Default:dare.html.twig", array('dare' => $dare, 'stares' => $stares, 'user' => $this->getUser()));
    }

    /**
     * Method rendering page, where user can add a dare
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newDareAction(Request $request)
    {
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();

        $offer = new Offer();
        $offer->setOwner($this->getUser());
        $form = $this->createForm(new OfferType(), $offer);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();

            return $this->redirect($this->generateUrl('zdrw_dares'));
        }

        return $this->render("ZdrwOffersBundle:Default:newDare.html.twig", array('form' => $form->createView(), 'stares' => $stares, 'user' => $this->getUser()));
    }


    /**
     * Method rendering the "Stares" page with all stares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staresAction()
    {
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render('ZdrwOffersBundle:Default:stares.html.twig', array('stares' => $stares, 'dares' => $dares, 'user' => $this->getUser()));
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

    /**
     * Method rendering certain user profile page
     *
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function userAction($name)
    {
        $user = $this->getDoctrine()->getRepository('ZdrwUserBundle:User')->findOneBy(array("username" => $name));
        return $this->render('ZdrwOffersBundle:Default:user.html.twig', array('user' => $user));
    }
}
