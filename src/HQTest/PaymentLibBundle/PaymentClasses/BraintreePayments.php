<?php

namespace HQTest\PaymentLibBundle\PaymentClasses;

use Braintree_Configuration;
use Braintree_Transaction;

class BraintreePayments extends PaymentLib {
    /*
     * payment gateyway response
     */

    public $payment_gateway_response;

    public function __construct() {
        parent::__construct();
        Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
        Braintree_Configuration::merchantId(BRAINTREE_MERCHANTID);
        Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
        Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);
    }

    public function doBraintreePayment($payment_gateway) {

        $credit_card_params = $payment_gateway->credit_card_params;

        $result = Braintree_Transaction::sale(array(
                    "amount" => $payment_gateway->credit_card_params['amount'],
                    "creditCard" => array(
                        "number" => $payment_gateway->credit_card_params['card_number'],
                        "cvv" => $payment_gateway->credit_card_params['card_cvv'],
                        "expirationMonth" => $payment_gateway->credit_card_params['card_expiry_month'],
                        "expirationYear" => $payment_gateway->credit_card_params['card_expiry_year']
                    ),
                    "options" => array(
                        "submitForSettlement" => true
                    )
        ));

        
        if ($result->success) {

            $payment_response = array(
                'success' => true,
                'raw_response' => $result,
                'transaction_id' => $result->transaction->id,
                'transaction_currency' => $result->transaction->currencyIsoCode,
                'transaction_amount' => $result->transaction->amount,
                'transaction_method' => $result->transaction->type,
                'transaction_state' => $result->transaction->status,
                'transaction_time' => isset($result->transaction->createdAt->date) ? $result->transaction->createdAt->date : date("y-m-d h:i:s")
            );
        } else if ($result->transaction) {
            $payment_response = array(
                'error' => array(
                    'title' => "Braintree Error !! " . $result->transaction->processorResponseCode,
                    'message' => $result->message
                )
            );
        } else {
            $str = '';
            foreach (($result->errors->deepAll()) as $error) {
                $str .= "- " . $error->message . "\n";
            }
            $payment_response = array(
                'error' => array(
                    'title' => "Braintree Validation errors:",
                    'message' => $str
                )
            );
        }
        return $payment_response;
    }

}

?>