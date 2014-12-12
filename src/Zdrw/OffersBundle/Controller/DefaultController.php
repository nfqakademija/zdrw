<?php

namespace Zdrw\OffersBundle\Controller;

use Zdrw\OffersBundle\Entity\Notification;
use Zdrw\OffersBundle\Entity\Reward;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zdrw\OffersBundle\Entity\Offer;
use Symfony\Component\HttpFoundation\Request;
use Zdrw\OffersBundle\Form\Type\OfferType;
use Symfony\Component\HttpFoundation\Response;

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

            $not1->setNotification("Admin declined your video. Now dare is available for everyone.");
            $url = $this->container->get('router')->generate(
                'zdrw_dare',
                array('id' => $dare->getId())
            );
            $not1->setLink($url);


            $not2 = new Notification();
            $not2->setUser($dare->getOwner());
            $not2->setNotification("Admin declined video for your dare. Now dare is available for everyone.");
            $url = $this->container->get('router')->generate(
                'zdrw_dare',
                array('id' => $dare->getId())
            );
            $not2->setLink($url);
            $manager->persist($not2);

        } elseif ($user == $dare->getOwner()) {
            $dare->setStatus(4);
            $not1->setNotification("Dare owner declined your video. Website admins will review your video one more time.");
            $url = $this->container->get('router')->generate(
                'zdrw_dare',
                array('id' => $dare->getId())
            );
            $not1->setLink($url);
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
            $not1->setNotification("Admin accepted your video after the owner declined it. You got " . $reward . " points. Congratulations!");
            $url = $this->container->get('router')->generate(
                'zdrw_dare',
                array('id' => $dare->getId())
            );
            $not1->setLink($url);

            $not2->setUser($dare->getOwner());
            $not2->setNotification("Admin have accepted video of your offer.");
            $url = $this->container->get('router')->generate(
                'zdrw_dare',
                array('id' => $dare->getId())
            );
            $not2->setLink($url);
        } elseif ($user == $dare->getOwner()) {
            $not1->setNotification("Dare owner accepted your video. You got " . $reward . " points. Congratulations!");
            $url = $this->container->get('router')->generate(
                'zdrw_dare',
                array('id' => $dare->getId())
            );
            $not1->setLink($url);

            $not2->setUser($user);
            $not2->setNotification("You have accepted video of your offer");
            $url = $this->container->get('router')->generate(
                'zdrw_dare',
                array('id' => $dare->getId())
            );
            $not2->setLink($url);
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
        $not1->setNotification("You have reserved offer. Do not forget to upload video to prove offer completion!");
        $url = $this->container->get('router')->generate(
            'zdrw_dare',
            array('id' => $dare->getId())
        );
        $not1->setLink($url);
        $manager->persist($not1);

        $not2 = new Notification();
        $not2->setUser($dare->getOwner());
        $not2->setNotification("Your offer reserved user ".$user->getNickname());
        $url = $this->container->get('router')->generate(
            'zdrw_dare',
            array('id' => $dare->getId())
        );
        $not2->setLink($url);
        $manager->persist($not2);

        $manager->flush();
    }

    /**
     * Method to get specific data, like comments data
     *
     * @param $repo
     * @param $findBy
     * @param $id
     * @return array
     */
    private function getData($repo, $findBy, $id)
    {
        $data = $this
            ->getDoctrine()
            ->getRepository($repo)
            ->findBy(array($findBy => $id), array('id' => 'desc'));
        return $data;
    }

    /**
     * Method to get offer comments
     *
     * @param $id
     * @return array
     */
    private function getComments($id)
    {
        $repo = 'ZdrwUserBundle:Comment';
        $findBy = 'offer';
        $comments = $this->getData($repo, $findBy, $id);
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
        $repo = 'ZdrwOffersBundle:Offer';
        $findBy = 'owner';
        $dares = $this->getData($repo, $findBy, $id);
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
        $repo = 'ZdrwOffersBundle:Offer';
        $findBy = 'participant';
        $stares = $this->getData($repo, $findBy, $id);
        return $stares;
    }

    /**
     * Method to get specific data, like comments data, with limit
     *
     * @param $repo
     * @param $findBy
     * @param $id
     * @return array
     */
    private function getDataWithLimit($repo, $findBy, $id, $limit, $offset)
    {
        $data = $this
            ->getDoctrine()
            ->getRepository($repo)
            ->findBy(array($findBy => $id), array('id' => 'desc'), $limit, $offset);
        return $data;
    }

    /**
     * Method to get dares
     *
     * @param $limit
     * @param $offset
     * @return array
     */
    private function getDares($limit = null, $offset = 0)
    {
        $repo = 'ZdrwOffersBundle:Offer';
        $findBy = 'status';
        $id = array(1, 2);
        $dares = $this->getDataWithLimit($repo, $findBy, $id, $limit, $offset);
        return $dares;
    }

    /**
     * Method to get stares
     *
     * @param $limit
     * @param $offset
     * @return array
     */
    private function getStares($limit = null, $offset = 0)
    {
        $repo = 'ZdrwOffersBundle:Offer';
        $findBy = 'status';
        $id = 5;
        $stares = $this->getDataWithLimit($repo, $findBy, $id, $limit, $offset);
        return $stares;
    }

    public function getOfferLikesAction($id)
    {
        return new Response($count = $this->getDoctrine()->getRepository('ZdrwUserBundle:Like')->countByOffer($id));
    }
    public function getOfferCommentsAction($id)
    {
        return new Response($count = $this->getDoctrine()->getRepository('ZdrwUserBundle:Comment')->countByOffer($id));
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
        
        $dares = $this->getDares(4);
        $stares = $this->getStares(6);
        return $this->render(
            "ZdrwOffersBundle:Default:index.html.twig",
            array(
                'nId' => $nId,
                'dares' => $dares,
                'stares' => $stares,
                'user' => $this->getUser()
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
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dareAction(Request $request, $id)
    {
        $nId = $this->unreadNotifications();

        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        $dare = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('id' => $id));
        $pointsMsg = false;
        if (($user != null)) {
            if ($request->request->has('decline')) {
                $this->declineVideo($user, $dare, $manager);
            } elseif ($request->request->has('accept')) {
                $this->acceptVideo($user, $dare, $manager);
            } elseif ($request->request->has('reservation')) {
                $this->makeReservation($user, $dare, $manager);
            } elseif ($request->request->has('add')) {
                $points = $request->request->get('points');
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
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('ROLE_USER')) {
            $stares = $this->getStares(5);
            $user = $this->getUser();
            $errorMsg = 0;
            $form = null;
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
                        (strlen($offer->getTitle()) <= 50) &&
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

            }
            $nId = $this->unreadNotifications();
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
        } else {
            return $this->redirect($this->generateUrl('zdrw_dares'));
        }

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
        $repo = 'ZdrwOffersBundle:Notification';
        $findBy = 'user';
        $notifications = $this->getData($repo, $findBy, $id);
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $dares = array();
        $stares = array();
        if ($request->request->has('search')) {
            $keyword = $request->request->get('keyword');
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
