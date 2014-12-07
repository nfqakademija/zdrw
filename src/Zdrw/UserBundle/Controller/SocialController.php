<?php

namespace Zdrw\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Zdrw\UserBundle\Entity\Comment;
use Zdrw\UserBundle\Entity\Like;
use Symfony\Component\HttpFoundation\Request;

class SocialController extends Controller
{
    /**
     * Method to like or unlike offer
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function likeAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $exist = $manager->getRepository('ZdrwUserBundle:Like')->findOneBy(array("offer" => $id, "user" => $user->getId()));
        if ($exist == null) {
            $offer = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('id' => $id));
            if ($offer) {
                $like = new Like();
                $like->setOffer($offer);
                $like->setUser($user);
                $manager->persist($like);
            } else {
                return new Response("error");
            }
        } else {
            $manager->remove($exist);
        }
        $manager->flush();
        return new Response("success");
    }

    /**
     * Method to check if user already liked offer
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkLikeAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $exist = $manager->getRepository('ZdrwUserBundle:Like')->findOneBy(array("offer" => $id, "user" => $user->getId()));
        if ($exist == null) {
            return new Response("Like");
        } else {
            return new Response("Unlike");
        }
    }

    /**
     * Method to create comment
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentAction()
    {
        $post = Request::createFromGlobals();
        if ($post->request->has('id') && $post->request->has('text')) {
            $manager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $offer = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('id' => $post->request->get('id')));
            if ($offer && $user) {
                $comment = new Comment();
                $comment->setUser($user);
                $comment->setComment($post->request->get('text'));
                $comment->setOffer($offer);
                $manager->persist($comment);
                $manager->flush();
                return new Response("success");
            } else {
                return new Response("error");
            }
        } else {
            return new Response("error");
        }
    }
}
