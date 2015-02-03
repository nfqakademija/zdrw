<?php

namespace Zdrw\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends Controller
{
    public function prepareAction()
    {
        $paymentName = 'stripe';

        $storage = $this->get('payum')->getStorage('Zdrw\PaymentBundle\Entity\PaymentOrder');

        $order = $storage->create();
        $order->setNumber(uniqid());
        $order->setCurrencyCode('EUR');
        $order->setTotalAmount(123); // 1.23 EUR
        $order->setDescription('A description');
        $order->setClientId('anId');
        $order->setClientEmail('foo@example.com');

        $storage->update($order);

        $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
            $paymentName,
            $order,
            'zdrw_payment_done' // the route to redirect after capture
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function doneAction(Request $request)
    {
        $token = $this->get('payum.security.http_request_verifier')->verify($request);

        $payment = $this->get('payum')->getPayment($token->getPaymentName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum.security.http_request_verifier')->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$order = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $payment->execute($status = new GetHumanStatus($token));
        $order = $status->getFirstModel();

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'order' => array(
                'total_amount' => $order->getTotalAmount(),
                'currency_code' => $order->getCurrencyCode(),
                'details' => $order->getDetails(),
            ),
        ));
    }


    public function preparePaypalExpressCheckoutPaymentAction()
    {
        $paymentName = 'paypal';

        $storage = $this->get('payum')->getStorage('Zdrw\PaymentBundle\Entity\PaymentOrder');
        /** @var \Zdrw\PaymentBundle\Entity\PaymentOrder $PaymentOrder */
        $payment = $storage->create();

        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount(123);
        $payment->setDescription("test");
        $payment->setClientId(1);
        $payment->setClientEmail("test@test.com");

        $storage->update($payment);

        $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
            $paymentName,
            $payment,
            'zdrw_payment_done_2' // the route to redirect after capture
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function done2Action(Request $request)
    {
        $token = $this->get('payum.security.http_request_verifier')->verify($request);

        $payment = $this->get('payum')->getPayment($token->getPaymentName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum.security.http_request_verifier')->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$order = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $payment->execute($status = new GetHumanStatus($token));
        $order = $status->getFirstModel();

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'order' => array(
                'total_amount' => $order->getTotalAmount(),
                'currency_code' => $order->getCurrencyCode(),
                'details' => $order->getDetails(),
            ),
        ));
    }

}

