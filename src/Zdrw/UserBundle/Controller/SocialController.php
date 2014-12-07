<?php

namespace Zdrw\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Zdrw\UserBundle\Entity\Like;

class SocialController extends Controller
{
    public function likeAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $exist = $manager->getRepository('ZdrwUserBundle:Like')->findOneBy(array("offer" => $id, "user" => $user->getId()));
        if ($exist == null)
        {
            $offer = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('id' => $id));
            if ($offer)
            {
                $like = new Like();
                $like->setOffer($offer);
                $like->setUser($user);
                $manager->persist($like);
            }
            else
            {
                return new Response("error");
            }
        }
        else
        {
            $manager->remove($exist);
        }
        $manager->flush();
        return new Response("success");
    }
    public function checkLikeAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $exist = $manager->getRepository('ZdrwUserBundle:Like')->findOneBy(array("offer" => $id, "user" => $user->getId()));
        if ($exist == null)
        {
            return new Response("Like");
        }
        else
        {
            return new Response("Unlike");
        }
    }
}
