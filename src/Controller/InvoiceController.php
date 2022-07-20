<?php

namespace Kiakaha\ChargilyPlugin\Controller;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\OrderPaymentStates;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Chargily\ePay\Chargily;

class InvoiceController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @throws \Exception
     */
    public function renderPdfAction(Request $request): Response
    {
        $number = $request->attributes->get('OrderNumber');

        /** @var PaymentMethod $payfort */
        $chargily_config = $this->get('sylius.repository.gateway_config')->findOneBy(['factoryName' => 'chargily']);
        $repo = $this->get('sylius.repository.order');
        $em = $this->get('doctrine.orm.entity_manager');
        //if($chargily_config){
        /** @var \App\Entity\Order\Order $order */
        $order = $this->get('sylius.repository.order')->findOneBy(['number' => $number]);
        //$order = $this->get('sylius.repository.order')->findOneBy(['number' => intval($number)]);

        $data = json_decode($request->getContent(), true);

        $order_id = isset($data['invoice']['invoice_number']) ? $data['invoice']['invoice_number'] : null;

        if($data['invoice']['status'] == 'paid'){
            /** @var PaymentInterface $payment */
            $payment = $order->getLastPayment();
            $order->setPaymentState(OrderPaymentStates::STATE_PAID);
            $payment->setState(OrderPaymentStates::STATE_PAID);
            $em->persist($order);
            $em->flush();
            return new JsonResponse([
                'code' => 200,
                'message' => 'Update state with success'
            ]);
        }

        elseif($data['invoice']['status'] == 'failed'){
            /** @var PaymentInterface $payment */
            $payment = $order->getLastPayment();
            $order->setPaymentState('failed');
            $payment->setState('failed');
            $em->persist($order);
            $em->flush();
            return new JsonResponse([
                'code' => 200,
                'message' => 'Update state with success'
            ]);
        }elseif( $data['invoice']['status'] == 'canceled'){
            /** @var PaymentInterface $payment */
            $payment = $order->getLastPayment();
            $order->setPaymentState(OrderPaymentStates::STATE_CANCELLED);
            $payment->setState(OrderPaymentStates::STATE_CANCELLED);
            $em->persist($order);
            $em->flush();
            return new JsonResponse([
                'code' => 200,
                'message' => 'Update state with success'
            ]);
        }

        return new JsonResponse([
            'code' => 400,
            'message' => 'Update state Failed'
        ]);
        //return new Response();
    }
}
