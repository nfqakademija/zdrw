<?php

namespace Zdrw\OffersBundle\Controller;

use Zdrw\OffersBundle\Entity\Notification;
use Zdrw\OffersBundle\Entity\Reward;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zdrw\OffersBundle\Entity\Offer;
use Symfony\Component\HttpFoundation\Request;
use Zdrw\OffersBundle\Form\Type\OfferType;

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
     * Method to add points to offer

     * @param $user
     * @param $points
     * @param $dare
     * @param $manager
     * @return int
     */
    private function addPoints($user, $points, $dare, $manager)
    {
        if (($user->getPoints() >= $points) && ($points > 0)) {
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
        return $pointsMsg;
    }

    /**
     * Method to decline video for owner
     *
     * @param $user
     * @param $dare
     * @param $manager
     */
    private function declineVideo($user, $dare, $manager)
    {
        if ($user == $dare->getOwner()) {
            $dare->setStatus(4);

            $not1 = new Notification();
            $not1->setUser($dare->getParticipant());
            $not1->setNotification("Dare owner declined your video of confirmation. Website admins will review your video.");
            $manager->persist($not1);

            $manager->flush();
        }
    }

    /**
     * Method to accept video for owner
     *
     * @param $user
     * @param $dare
     * @param $manager
     */
    private function acceptVideo($user, $dare, $manager)
    {
        $participant = $dare->getParticipant();

        if ($user == $dare->getOwner()) {
            $rewards = $dare->getRewards();
            $reward = 0;
            foreach ($rewards as $r) {
                $reward += $r->getPoints();
                $manager->remove($r);
            }
            $partPoints = $participant->getPoints();
            $partPoints += $reward;
            $participant->setPoints($partPoints);

            $dare->setStatus(5);

            $not1 = new Notification();
            $not1->setUser($dare->getParticipant());
            $not1->setNotification("Dare owner accepted your video of confirmation. You got " . $reward . " points. Congratulations!");
            $manager->persist($not1);

            $not2 = new Notification();
            $not2->setUser($user);
            $not2->setNotification("You have accepted video of your offer");
            $manager->persist($not2);

            $manager->flush();
        }
    }

    /**
     * Method to make offer reservation
     *
     * @param $user
     * @param $dare
     * @param $manager
     */
    private function makeReservation($user, $dare, $manager)
    {
        $dare->setStatus(2);
        $dare->setParticipant($user);

        $not1 = new Notification();
        $not1->setUser($user);
        $not1->setNotification("You have reserved offer. Do not forget to upload video to prove offer completion");
        $manager->persist($not1);

        $not2 = new Notification();
        $not2->setUser($dare->getOwner());
        $not2->setNotification("Your offer reserved user ".$user->getNickname());
        $manager->persist($not2);

        $manager->flush();
    }

    /**
     * Method to get all dares
     *
     * @param $id
     * @return array
     */
    private function getUserDares($id)
    {
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array('owner' => $id));
        return $dares;
    }

    /**
     * Method to get all dares
     *
     * @param $id
     * @return array
     */
    private function getUserPerformedDares($id)
    {
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array('participant' => $id));
        return $stares;
    }

    /**
     * Method to get all dares
     *
     * @return array
     */
    private function getDares()
    {
        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array('status' => array(1,2)));
        return $dares;
    }

    /**
     * Method to get all stares
     *
     * @return array
     */
    private function getStares()
    {
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array('status' => 5));
        return $stares;
    }

    /**
     * Method to render the "Dares" page with all dares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function daresAction()
    {
        $dares = $this->getDares();
        $stares = $this->getStares();
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
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        $dare = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('id' => $id));
        $pointsMsg = false;
        if (($user != null)) {
            if ($post->request->has('decline')) {
                $this->declineVideo($user, $dare, $manager);
            } elseif ($post->request->has('accept')) {
                $this->acceptVideo($user, $dare, $manager);
            } elseif ($post->request->has('reservation')) {
                $this->makeReservation($user, $dare, $manager);
            } elseif ($post->request->has('add')) {
                $points = $post->request->get('points');
                $pointsMsg = $this->addPoints($user, $points, $dare, $manager);
            }
        }
        $rewards = $dare->getRewards();
        $reward = 0;
        foreach ($rewards as $r) {
            $reward += $r->getPoints();
        }
        $stares = $manager->getRepository('ZdrwOffersBundle:Offer')->findAll();
        return $this->render("ZdrwOffersBundle:Default:dare.html.twig", array('dare' => $dare, 'stares' => $stares,
            'user' => $user, 'reward' => $reward, 'points' => $pointsMsg));
    }

    /**
     * Method to render page, where user can add a dare
     *
     * @param $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newDareAction(Request $request)
    {
        $stares = $this->getStares();

        $user = $this->getUser();
        if ($user != null) {
            $offer = new Offer();
            $offer->setOwner($user);
            $form = $this->createForm(new OfferType(), $offer);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($offer);
                $em->flush();

                return $this->redirect($this->generateUrl('zdrw_dares'));
            }
        }
        return $this->render("ZdrwOffersBundle:Default:newDare.html.twig", array('form' => $form->createView(), 'stares' => $stares, 'user' => $user));
    }


    /**
     * Method to render the "Stares" page with all stares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staresAction()
    {
        $dares = $this->getDares();
        $stares = $this->getStares();
        return $this->render('ZdrwOffersBundle:Default:stares.html.twig', array('stares' => $stares, 'dares' => $dares, 'user' => $this->getUser()));
    }

    /**
     * Method to get user DARES, STARES and NOTIFICATIONS
     *
     * @param $id
     * @return array
     */
    private function getNotifications($id)
    {
        $notifications = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')->findBy(array("user" => $id));
        return $notifications;
    }

    /**
     * Method to render user profile page with that user data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction()
    {

        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('homepage'));
        } else {
            $user = $this->getUser();
            $notifications = $this->getNotifications($user->getId());
            $dares = $this->getUserDares($user->getId());
            $stares = $this->getUserPerformedDares($user->getId());
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
        $notifications = $this->getNotifications($user->getId());
        $dares = $this->getUserDares($user->getId());
        $stares = $this->getUserPerformedDares($user->getId());
        return $this->render('ZdrwOffersBundle:Default:user.html.twig', array('user' => $user, 'notifications' => $notifications, 'dares' => $dares, 'stares' => $stares));
    }

    /**
     * Method to search dares and stares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction()
    {
        $post = Request::createFromGlobals();

        if ($post->request->has('search')) {
            $keyword = $post->request->get('keyword');
            $manager = $this->getDoctrine()->getManager()->getRepository('ZdrwOffersBundle:Offer');
            $dares = $manager->searchForDares($keyword);
            $stares = $manager->searchForStares($keyword);
        }
        return $this->render('ZdrwOffersBundle:Default:search.html.twig', array('dares' => $dares,'stares' => $stares,
        'user'=> $this->getUser()));
    }
}
