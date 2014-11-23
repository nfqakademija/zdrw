<?php

namespace Zdrw\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    public function loginPageAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_USER'))
            return $this->redirect($this->generateUrl('homepage'));
        else
            return $this->render('ZdrwUserBundle:Security:loginPage.html.twig');
    }
}