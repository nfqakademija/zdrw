<?php

namespace Zdrw\UserBundle\Controller;

use Proxies\__CG__\Zdrw\OffersBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Zdrw\OffersBundle\Controller\DefaultController;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller managing the user profile page
 */
class ProfileController extends DefaultController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $notifications = $this->getNotifications($this->getUser());
        $dares = $this->getUserDares($this->getUser());
        $stares = $this->getUserPerformedDares($this->getUser());

        $nId = $this->unreadNotifications();

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'nId' => $nId, 'notifications' => $notifications, 'dares' => $dares, 'user' => $user, 'stares' => $stares
        ));
    }

    /**
     * Method updating notification to seen
     *
     * @param Request $request
     * @return Response
     */
    public function seenAction(Request $request)
    {
        $id = $request->request->get('id');
        $notification = $this
            ->getDoctrine()
            ->getRepository('ZdrwOffersBundle:Notification')
            ->find(array('id' => $id));
        $notification->setSeen(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();
        return new Response();
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(
                FOSUserEvents::PROFILE_EDIT_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }

        $nId = $this->unreadNotifications();

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'nId' => $nId
        ));
    }
}
