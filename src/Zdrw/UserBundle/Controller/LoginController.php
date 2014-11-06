<?php

namespace Zdrw\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController;

/**
 * Controller managing login
 */
class LoginController extends SecurityController
{
    protected function renderLogin(array $data)
    {
        return $this->render('ZdrwUserBundle::login.html.twig', $data);
    }
}
