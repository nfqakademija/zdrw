<?php

namespace Zdrw\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    public function loginPageAction()
    {
        return $this->render('ZdrwUserBundle:Security:loginPage.html.twig');
    }
}