<?php

namespace Zdrw\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ZdrwPaymentBundle:Default:index.html.twig');
    }
}
