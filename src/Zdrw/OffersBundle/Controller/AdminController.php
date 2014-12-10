<?php

namespace Zdrw\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zdrw\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller managing the offers
 */
class AdminController extends Controller
{
    /**
     * Method rendering admin panel page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $stares = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Offer')->findBy(array('status' => 4));
            $points = 0;
            $userPoints = 0;
            $userNick = 0;

            $user = new User();

            $form = $this->createFormBuilder($user)
                ->add('nickname', 'text')
                ->add('points', 'integer')
                ->add('save', 'submit', array('label' => 'Add points'))
                ->getForm();

            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData();
                $userNick = $data->getNickname();
                $user = $em->getRepository('ZdrwUserBundle:User')
                    ->findOneBy(
                        array(
                            'nickname' => $userNick
                        )
                    );
                if (!$user) {
                    $points = 2;
                } else {
                    $points = $user->getPoints();
                    $userPoints = $points + $data->getPoints();
                    $user->setPoints($userPoints);
                    $em->persist($user);
                    $em->flush();

                    $points = 1;
                }
            }

            $user = $this->getUser();
            $nId = $this->getDoctrine()->getRepository('ZdrwOffersBundle:Notification')
                ->findBy(array('user' => $user, 'seen' => 0));
            $nId = count($nId);

            return $this->render(
                'ZdrwOffersBundle:Admin:index.html.twig',
                array(
                    'nId' => $nId,
                    'user' => $this->getUser(),
                    'stares' => $stares,
                    'form' => $form->createView(),
                    'points' => $points,
                    'userPoints' => $userPoints,
                    'userNick' => $userNick
                )
            );
        } else {
            return $this->redirect($this->generateUrl('zdrw_index'), 301);
        }
    }
}
