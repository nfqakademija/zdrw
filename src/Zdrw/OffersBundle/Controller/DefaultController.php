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
class DefaultController extends Controller
{
    /**
     * Method to render the main project page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render("ZdrwOffersBundle:Default:index.html.twig", array('user' => $this->getUser()));
    }

    /**
     * Method to get all stares and dares;
     *
     * @return array
     */
    private function daresAndStares()
    {
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findAll();
        $result = array('dares' => $dares, 'stares' => $stares);
        return $result;
    }

    /**
     * Method to render the "Dares" page with all dares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function daresAction()
    {
        $daresAndStares = $this->daresAndStares();
        $dares = $daresAndStares['dares'];
        $stares = $daresAndStares['stares'];
        return $this->render('ZdrwOffersBundle:Default:dares.html.twig', array('dares' => $dares,'stares' => $stares,
            'user'=> $this->getUser()));
    }

    /**
     * Method to render certain dare page
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dareAction($id)
    {
        $post = Request::createFromGlobals();
        $manager = $this->getDoctrine()->getManager();
        $dare = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('id' => $id));
        $user = $this->getUser();
        $pointsMsg = false;
        if ($post->request->has('add')) {
            $points = $post->request->get('points');
            if ($user->getPoints() >= $points) {
                $reward = new Reward();
                $reward->setUser($user);
                $reward->setOffer($dare);
                $reward->setPoints($points);
                $manager->persist($reward);

                $userPoints = $user->getPoints();
                $userPoints -= $points;
                $user->setPoints($userPoints);
                $manager->persist($user);
                $manager->flush();
                $pointsMsg = 1;
            } else {
                $pointsMsg = 2;
            }
        }

        $rewards = $dare->getRewards();
        $reward = 0;
        foreach($rewards as $r)
            $reward += $r->getPoints();
        $stares = $manager->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render("ZdrwOffersBundle:Default:dare.html.twig", array('dare' => $dare, 'stares' => $stares,
            'user' => $user, 'reward' => $reward, 'points' => $pointsMsg));
    }

    /**
     * Method to render page, where user can add a dare
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
     * Method to render the "Stares" page with all stares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staresAction()
    {
        $daresAndStares = $this->daresAndStares();
        $dares = $daresAndStares['dares'];
        $stares = $daresAndStares['stares'];
        return $this->render('ZdrwOffersBundle:Default:stares.html.twig', array('stares' => $stares, 'dares' => $dares, 'user' => $this->getUser()));
    }

    /**
     * Method to get user DARES, STARES and NOTIFICATIONS
     *
     * @param $id
     * @return array
     */
    private function DrStNt($id)
    {
        $notifications = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')->findBy(array("user" => $id));
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array("owner" => $id));
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array("participant" => $id));
        $result = array('notifications' => $notifications, 'dares' => $dares, 'stares' => $stares);
        return $result;
    }

    /**
     * Method to render user profile page with that user data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction()
    {

        if (!$this->get('security.context')->isGranted('ROLE_USER'))
        {
            return $this->redirect($this->generateUrl('homepage'));
        }
        else
        {
            $user = $this->getUser();
            $DrStNt = $this->DrStNt($user->getId());
            $notifications = $DrStNt['notifications'];
            $dares = $DrStNt['dares'];
            $stares = $DrStNt['stares'];
            return $this->render('ZdrwOffersBundle:Default:profile.html.twig', array('notifications' => $notifications, 'dares' => $dares, 'user' => $user, 'stares' => $stares));
        }
    }

    /**
     * Method to render certain user profile page
     *
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function userAction($name)
    {
        $user = $this->getDoctrine()->getRepository('ZdrwUserBundle:User')->findOneBy(array("username" => $name));
        $DrStNt = $this->DrStNt($user->getId());
        $notifications = $DrStNt['notifications'];
        $dares = $DrStNt['dares'];
        $stares = $DrStNt['stares'];
        return $this->render('ZdrwOffersBundle:Default:user.html.twig', array('user' => $user, 'notifications' => $notifications, 'dares' => $dares, 'stares' => $stares));
    }
}
