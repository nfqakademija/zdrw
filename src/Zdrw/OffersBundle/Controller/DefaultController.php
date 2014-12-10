<?php

namespace Zdrw\OffersBundle\Controller;

use Zdrw\OffersBundle\Entity\Notification;
use Zdrw\OffersBundle\Entity\Reward;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zdrw\OffersBundle\Entity\Offer;
use Zdrw\OffersBundle\Entity\OfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Zdrw\OffersBundle\Form\Type\OfferType;
use Zdrw\OffersBundle\Services\UserInfoProvider;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Controller managing the offers
 */
class DefaultController extends Controller
{
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
        if (($user->getPoints() >= $points) && ($points >= 0)) {
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
        $securityContext = $this->container->get('security.context');
        $not1 = new Notification();
        $not1->setUser($dare->getParticipant());

        if ($securityContext->isGranted('ROLE_ADMIN')) {
            $dare->setStatus(1);

            $not1->setNotification("Admin declined your video of confirmation. Now dare is available for everyone");

            $not2 = new Notification();
            $not2->setUser($dare->getOwner());
            $not2->setNotification("Admin declined video of confirmation for your dare. Now dare is available for everyone");
            $manager->persist($not2);

        } elseif ($user == $dare->getOwner()) {
            $dare->setStatus(4);
            $not1->setNotification("Dare owner declined your video of confirmation. Website admins will review your video.");
        }
        $manager->persist($not1);
        $manager->flush();
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
        $securityContext = $this->container->get('security.context');

        $participant = $dare->getParticipant();
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
        $dare->setStartDate(new \DateTime('now'));

        $not1 = new Notification();
        $not1->setUser($participant);
        $not2 = new Notification();

        if ($securityContext->isGranted('ROLE_ADMIN')) {
            $not1->setNotification("Admin accepted your video of confirmation. You got " . $reward . " points. Congratulations!");

            $not2->setUser($dare->getOwner());
            $not2->setNotification("Admin have accepted video of your offer");
        } elseif ($user == $dare->getOwner()) {
            $not1->setNotification("Dare owner accepted your video of confirmation. You got " . $reward . " points. Congratulations!");

            $not2->setUser($user);
            $not2->setNotification("You have accepted video of your offer");
        }
        $manager->persist($not1);
        $manager->persist($not2);
        $manager->flush();
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
     * Method to get offer comments
     *
     * @param $id
     * @return array
     */
    private function getComments($id)
    {
        $comments = $this
            ->getDoctrine()
            ->getRepository('ZdrwUserBundle:Comment')
            ->findBy(array('offer' => $id), array('id' => 'desc'));
        return $comments;
    }

    /**
     * Method to get all dares
     *
     * @param $id
     * @return array
     */
    public function getUserDares($id)
    {
        $dares = $this
            ->getDoctrine()
            ->getRepository('ZdrwOffersBundle:Offer')
            ->findBy(array('owner' => $id), array('id' => 'desc'));
        return $dares;
    }

    /**
     * Method to get all dares
     *
     * @param $id
     * @return array
     */
    public function getUserPerformedDares($id)
    {
        $stares = $this
            ->getDoctrine()
            ->getRepository('ZdrwOffersBundle:Offer')
            ->findBy(array('participant' => $id), array('id' => 'desc'));
        return $stares;
    }

    /**
     * Method to get dares
     *
     * @return array
     */
    private function getDares($limit = null, $offset = 0)
    {
        $dares = $this
            ->getDoctrine()
            ->getRepository('ZdrwOffersBundle:Offer')
            ->findBy(array('status' => array(1, 2)), array('id' => 'desc'), $limit, $offset);
        return $dares;
    }

    /**
     * Method to get stares
     *
     * @return array
     */
    private function getStares($limit = null, $offset = 0)
    {
        $stares = $this
            ->getDoctrine()
            ->getRepository('ZdrwOffersBundle:Offer')
            ->findBy(array('status' => 5), array('id' => 'desc'), $limit, $offset);
        return $stares;
    }

    /**
     * Method counting unread notifications
     *
     * @return int|void
     */
    public function unreadNotifications()
    {
        $user = $this->getUser();
        $nId = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')->
        findBy(array('user' => $user, 'seen' => 0));

        return count($nId);
    }

    /**
     * Method to render the main project page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $nId = $this->unreadNotifications();

        $dares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->
            findBy(array('status' => array(1, 2)), array('id' => 'desc'), 4);
        $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->
            findBy(array('status' => 5), array('startDate' => 'desc'), 6);
        return $this->render(
            "ZdrwOffersBundle:Default:index.html.twig",
            array('nId' => $nId, 'dares' => $dares, 'stares' => $stares, 'user' => $this->getUser()
            )
        );
    }

    /**
     * Method to render the "Dares" page with all dares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function daresAction($page)
    {
        $nId = $this->unreadNotifications();
        $limit = 6;
        $page--;
        $dares = $this->getDares($limit, $page*$limit);
        $stares = $this->getStares(5);
        $count = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->countDares();

        return $this->render(
            'ZdrwOffersBundle:Default:dares.html.twig',
            array(
                'nId' => $nId,
                'dares' => $dares,
                'stares' => $stares,
                'user'=> $this->getUser(),
                'page'=> ++$page,
                'total' => ceil($count/$limit)
            )
        );
    }

    /**
     * Method to render certain dare page
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dareAction($id)
    {
        $nId = $this->unreadNotifications();

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
        $stares = $manager->getRepository('ZdrwOffersBundle:Offer')->
            findBy(array('status' => 5), array('startDate' => 'desc'), 3);
        $comments = $this->getComments($dare->getId());
        return $this->render(
            "ZdrwOffersBundle:Default:dare.html.twig",
            array(
                'nId' => $nId,
                'dare' => $dare,
                'stares' => $stares,
                'user' => $user,
                'reward' => $reward,
                'comments' => $comments,
                'points' => $pointsMsg
            )
        );
    }

    /**
     * Method to render page, where user can add a dare
     *
     * @param $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newDareAction(Request $request)
    {
        $stares = $this->getStares(5);

        $user = $this->getUser();
        $errorMsg = 0;
        if ($user != null) {
            $offer = new Offer();
            $reward = new Reward();
            $offer->setOwner($user);
            $form = $this->createForm(new OfferType(), $offer);
            $form->handleRequest($request);

            $thisOffer = $form->getData();
            $reward->setOffer($thisOffer);
            $reward->setUser($user);
            $givenReward = $form['rewards']->getData();

            $userPoints = $user->getPoints();

            if ($userPoints >= $givenReward) {
                $reward->setPoints($givenReward);
                $reward->setOffer($thisOffer);
                $user->setPoints($userPoints - $givenReward);

                if ($form->isValid() &&
                    (strlen($offer->getDescription()) <= 500) &&
                    (strlen($offer->getLongDesc()) <= 1500)
                ) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($offer);
                    $em->persist($reward);
                    $em->persist($user);
                    $em->flush();

                    return $this->redirect($this->generateUrl('zdrw_dares'));
                }
            } elseif ($userPoints < $givenReward) {
                $errorMsg = 1;
            }

            $nId = $this->unreadNotifications();
        }
        return $this->render(
            "ZdrwOffersBundle:Default:newDare.html.twig",
            array(
                'nId' => $nId,
                'form' => $form->createView(),
                'stares' => $stares,
                'user' => $user,
                'errorMsg' => $errorMsg
            )
        );
    }


    /**
     * Method to render the "Stares" page with all stares
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staresAction($page)
    {
        $nId = $this->unreadNotifications();

        $limit = 12;
        $page--;
        $dares = $this->getDares(5);
        $stares = $this->getStares($limit, $page*$limit);
        $count = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->countStares();
        return $this->render(
            'ZdrwOffersBundle:Default:stares.html.twig',
            array(
                'nId' => $nId,
                'stares' => $stares,
                'dares' => $dares,
                'user' => $this->getUser(),
                'page'=> ++$page,
                'total' => ceil($count/$limit)
            )
        );
    }
    /**
     * Method to get user notifications
     *
     * @param $id
     * @return array
     */
    public function getNotifications($id)
    {
        $notifications = $this
            ->getDoctrine()
            ->getRepository('ZdrwOffersBundle:Notification')
            ->findBy(array("user" => $id), array('id' => 'desc'));
        return $notifications;
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
        $user= $this->getUser();
        $currentUser = $this->getDoctrine()
            ->getRepository('ZdrwUserBundle:User')
            ->findOneBy(array("username" => $name));
        $dares = $this->getUserDares($currentUser->getId());
        $stares = $this->getUserPerformedDares($currentUser->getId());

        $nId = $this->unreadNotifications();

        return $this->render(
            'ZdrwUserBundle:Profile:user.html.twig',
            array(
                'nId' => $nId,
                'user' => $user, 'currentUser' => $currentUser, 'dares' => $dares, 'stares' => $stares
            )
        );
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

        $nId = $this->unreadNotifications();

        return $this->render(
            'ZdrwOffersBundle:Default:search.html.twig',
            array(
                'nId' => $nId, 'dares' => $dares,'stares' => $stares, 'user'=> $this->getUser()
            )
        );
    }
}
