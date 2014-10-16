<?php
namespace Acme\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller{
    
    public function listAction(){
        $posts = $this->get('doctrine')->getManager()->getRepository('AcmeBlogBundle:Post')->getPosts();
        return $this->render('AcmeBlogBundle:Blog:list.html.twig', array('posts' => $posts));
    }
    public function showAction($id){
        $post = $this->getDoctrine()->getManager()->getRepository('AcmeBlogBundle:Post')->find($id);
        if(!$post){
            throw $this->createNotFoundException();
        }
        
        return $this->render('AcmeBlogBundle:Blog:show.html.twig', array('post' => $post));
    }
}
