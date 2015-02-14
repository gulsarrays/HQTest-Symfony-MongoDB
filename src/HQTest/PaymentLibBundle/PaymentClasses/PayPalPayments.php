<?php

namespace HQTest\PaymentLibBundle\PaymentClasses;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\CreditCardToken;

class PayPalPayments extends PaymentLib {

    public $payment_gateway_response;

    public function __construct() {
        parent::__construct();
    }

    private function getApiContext() {

        $apiContext = new ApiContext(new OAuthTokenCredential(
                PAYPAL_API_CLIENTID, PAYPAL_API_SECRET
        ));
        return $apiContext;
    }

    private function pay_direct_with_credit_card($payment_gateway) {

        $credit_card_params = $payment_gateway->credit_card_params;

        $card = new CreditCard();
        $card->setType($credit_card_params['card_type']);
        $card->setNumber($credit_card_params['card_number']);
        $card->setExpireMonth($credit_card_params['card_expiry_month']);
        $card->setExpireYear($credit_card_params['card_expiry_year']);
        $card->setCvv2($credit_card_params['card_cvv']);
        $card->setFirstName($credit_card_params['first_name']);
        $card->setLastName($credit_card_params['last_name']);

        $funding_instrument = new FundingInstrument();
        $funding_instrument->setCreditCard($card);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card");
        $payer->setFundingInstruments(array($funding_instrument));

        $amount = new Amount();
        $amount->setCurrency($credit_card_params['currency']);
        $amount->setTotal($credit_card_params['amount']);

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        $payment->create($this->getApiContext());

        return $payment;
    }

    public function doPaypalPayment($payment_gateway) {   

        try {
            $result = $this->pay_direct_with_credit_card($payment_gateway);

            $payment_response = array(
                'success' => true,
                'raw_response' => $result,
                'transaction_id' => $result->transactions[0]->related_resources[0]->sale->id,
                'transaction_currency' => $result->transactions[0]->related_resources[0]->sale->amount->currency,
                'transaction_amount' => $result->transactions[0]->related_resources[0]->sale->amount->total,
                'transaction_method' => $result->payer->payment_method,
                'transaction_state' => $result->transactions[0]->related_resources[0]->sale->state,
                'transaction_time' => isset($result->transactions[0]->related_resources[0]->sale->create_time) ? $result->transactions[0]->related_resources[0]->sale->create_time : date("y-m-d h:i:s")
            );
        } catch (Exception $ex) {
            $payment_response = array(
                'error' => array(
                    'title' => "PayPal Error !!",
                    'message' => $ex->getMessage()
                )
            );
        }
        return $payment_response;
    }

}

?>