<?php

namespace Zdrw\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PayseraPaymentsController extends Controller
{
    /**
     * @Route("/payments/paysera/pay", name="paysera.pay")
     */
    public function redirectToPaymentAction()
    {
        $acceptUrl = $this->generateUrl('paysera_accept');
        $cancelUrl = $this->generateUrl('paysera_cancel');
        $callbackUrl = $this->generateUrl('paysera_callback');

        $url = $this->container->get('evp_web_to_pay.request_builder')->buildRequestUrlFromData(array(
            'orderid' => 0,
            'amount' => 1000,
            'currency' => 'EUR',
            'country' => 'LT',
            'accepturl' => $acceptUrl,
            'cancelurl' => $cancelUrl,
            'callbackurl' => $callbackUrl,
            'test' => 0,
        ));

        return new RedirectResponse($url);
    }

    /**
     * @Route("/payments/paysera/accept", name="paysera.accept")
     */
    public function acceptAction()
    {
        // payment was successful
    }

    /**
     * @Route("/payments/paysera/cancel", name="paysera.cancel")
     */
    public function cancelAction()
    {
        // payment was unsuccessful
    }

    /**
     * @Route("/payments/paysera/callback", name="paysera.callback")
     */
    public function callbackAction()
    {
        try {
            $callbackValidator = $this->get('evp_web_to_pay.callback_validator');
            $data = $callbackValidator->validateAndParseData($this->container->get('request_stack')->getCurrentRequest());
            if ($data['status'] == 1) {
                // Provide your customer with the service

                return new Response('OK');
            }
        } catch (\Exception $e) {
            //handle the callback validation error here

            return new Response($e->getTraceAsString(), 500);
        }
    }
}