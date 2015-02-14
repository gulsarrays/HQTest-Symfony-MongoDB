<?php

namespace HQTest\PaymentLibBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use HQTest\PaymentLibBundle\PaymentClasses\PaymentLib;
use HQTest\PaymentLibBundle\PaymentClasses\BraintreePayments;
use HQTest\PaymentLibBundle\PaymentClasses\PayPalPayments;
use HQTest\PaymentLibBundle\Document\Orders;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';

class PaymentController extends Controller {

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction() {
        return $this->render('HQTestPaymentLibBundle:Payment:orderinput.html.twig');
    }

    /**
     * @Route("/orders/{ID}")
     * @Template()
     */
    public function ordersAction($ID = 0) {
        if ((int) $ID > 0) {

            $order = $this->get('doctrine_mongodb')
                    ->getRepository('HQTestPaymentLibBundle:Orders')
                    ->findOneBy(array('orderID' => (int) $ID));

            $order_list = array(
                'orderID' => $order->getOrderID(),
                'customerName' => $order->getCustomerName(),
                'cardHolderName' => $order->getCardHolderName(),
                'cardNumber' => $order->getCardNumber(),
                'cardType' => $order->getCardType(),
                'cardExpiry' => $order->getCardExpiry(),
                'cardCvv' => $order->getCardCvv(),
                'paymentCurrency' => $order->getPaymentCurrency(),
                'paymentGateway' => $order->getPaymentGateway(),
                'transactionId' => $order->getTransactionId(),
                'orderStatus' => $order->getOrderStatus(),
                'orderAmount' => $order->getOrderAmount(),
                'orderCreatedTime' => $order->getOrderCreatedTime(),
                'transactionResponse' => $order->getTransactionResponse()
            );

            return $this->render('HQTestPaymentLibBundle:Payment:orderdetails.html.twig', array('order' => $order_list));
        } else {

            $orders = $this->get('doctrine_mongodb')
                    ->getRepository('HQTestPaymentLibBundle:Orders')
                    ->findAll();

            if (!$orders) {
                throw $this->createNotFoundException('No orders exists!! ');
            } else {
                foreach ($orders as $order) {
                    $order_list[] = array(
                        'orderID' => $order->getOrderID(),
                        'customerName' => $order->getCustomerName(),
                        'cardHolderName' => $order->getCardHolderName(),
                        'cardNumber' => $order->getCardNumber(),
                        'cardType' => $order->getCardType(),
                        'cardExpiry' => $order->getCardExpiry(),
                        'cardCvv' => $order->getCardCvv(),
                        'paymentCurrency' => $order->getPaymentCurrency(),
                        'paymentGateway' => $order->getPaymentGateway(),
                        'transactionId' => $order->getTransactionId(),
                        'orderStatus' => $order->getOrderStatus(),
                        'orderAmount' => $order->getOrderAmount(),
                        'orderCreatedTime' => $order->getOrderCreatedTime(),
                        'transactionResponse' => $order->getTransactionResponse()
                    );
                }
            }


            return $this->render('HQTestPaymentLibBundle:Payment:orders.html.twig', array('orders' => $order_list));
        }
    }

    /**
     * @Route("/payment")
     * @Template()
     */
    public function paymentAction(Request $request) {
        $success = false;

        if ($request->getMethod() == 'POST') {

            $request_arr = array(
                'customer_name' => $request->get('customer_name'),
                'item_price' => $request->get('item_price'),
                'currency' => $request->get('currency'),
                'cc_name' => $request->get('cc_name'),
                'cc_type' => $request->get('cc_type'),
                'cc_number' => $request->get('cc_number'),
                'cc_expiry' => $request->get('cc_expiry'),
                'cc_ccv' => $request->get('cc_ccv')
            );

            $payment_gateway = new PaymentLib();
            $credit_card_params = $payment_gateway->collectCreditCardParemeter($request_arr);

            $payment_method = $payment_gateway->myPaymentGateway($credit_card_params['card_type'], $credit_card_params['currency']);
            if ($payment_method === 'paypal') {
                $payment_gateway_paypal = new PayPalPayments();
                $payment_response = $payment_gateway_paypal->doPaypalPayment($payment_gateway);
            } else if ($payment_method === 'braintree') {
                $payment_gateway_braintree = new BraintreePayments();
                $payment_response = $payment_gateway_braintree->doBraintreePayment($payment_gateway);
            } else {
                $payment_response = array(
                    'error' => array(
                        'title' => "Please check for one of teh following Wrong Currency/Card Selection",
                        'message' => " -Wrong Currency/Card Selection" .
                        "AMEX is possible to use only for USD" . " \n " .
                        "-Please check for order amount" . " \n " .
                        "-Please check for Credit Card Expiry"
                    )
                );
            }
        }


        if (isset($payment_response['success']) && $payment_response['success'] === true) {

            $last_orders = $this->get('doctrine_mongodb')
                    ->getRepository('HQTestPaymentLibBundle:Orders')
                    ->findBy(array(), array('orderID' => 'DESC'), 1);


            if (!$last_orders) {
                $OrderID = 1;
            } else {
                $OrderID = $last_orders[0]->getOrderID() + 1;
            }

            $newOrder = new Orders();
            $newOrder->setOrderID($OrderID);
            $newOrder->setCustomerName($credit_card_params['customer_name']);
            $newOrder->setCardHolderName($credit_card_params['card_holder']);
            $newOrder->setCardNumber($credit_card_params['mask_card_number']);
            $newOrder->setCardType($credit_card_params['card_type']);
            $newOrder->setCardExpiry($credit_card_params['card_expiry']);
            $newOrder->setCardCvv($credit_card_params['card_cvv']);
            $newOrder->setPaymentCurrency($credit_card_params['currency']);
            $newOrder->setPaymentGateway($payment_method);
            $newOrder->setTransactionId($payment_response['transaction_id']);
            $newOrder->setOrderStatus($payment_response['transaction_state']);
            $newOrder->setOrderAmount($payment_response['transaction_amount']);
            //$newOrder->setOrderCreatedTime($payment_response['transaction_time']);
            $newOrder->setTransactionResponse($payment_response['raw_response']);

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($newOrder);
            $dm->flush();

            return $this->redirect($this->generateUrl('hqtest_paymentlib_payment_orders'));
        } else {

            return $this->render('HQTestPaymentLibBundle:Payment:error.html.twig', array('payment_response' => $payment_response));
        }
    }

}
