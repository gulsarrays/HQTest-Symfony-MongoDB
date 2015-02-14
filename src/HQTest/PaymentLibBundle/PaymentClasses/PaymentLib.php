<?php
namespace HQTest\PaymentLibBundle\PaymentClasses;

class PaymentLib {
    /*
     * @ payment gateway method
     * @ type : String
     */

    public $payment_gateway;
    /*
     * Card and Currency Realtion (restriction)
     * @type : Array
     */
    private $card_currency_restriction_array = array();
    /*
     * Card and Payment Gateway Relation (restriction)
     * @type : Array
     */
    private $card_gateway_array = array();
    /*
     * Currency and Gateway Realtion (restriction)
     * @type : Array
     */
    private $currency_gateway_array = array();
    /*
     * Payment Currency 
     * @type : String
     */
    public $currency;
    /*
     * Card Type
     * @type : Sting (Upper Case)
     */
    private $card_type;
    /*
     * Holds the credit card input parameters
     */
    public $credit_card_params = array();
    /*
     * Payment Response
     */
    public $payment_response = array();

    /*
     *  Class constructor
     */

    public function __construct() {
        $this->payment_gateway = defined(DEFAULT_PAYMENT_GATEWAY) ? DEFAULT_PAYMENT_GATEWAY : 'braintree';
        $this->card_currency_restriction_array = array("AMEX" => "USD");
        $this->card_gateway_array = array("AMEX" => 'paypal');
        $this->currency_gateway_array = array("paypal" => 'USD,EUR,AUD');
    }

    /*
     * Set card type as in upper case
     * @retruns card type in UPPER case
     */

    private function setCardType($card_type) {
        $this->card_type = strtoupper($card_type);
    }

    /*
     * Set currency as in upper case
     * @retruns currecny in UPPER case
     */

    private function setCurrency($currency) {
        $this->currency = strtoupper($currency);
    }

    /*
     * Checks for card in card_gateway_array
     * @ return true if Card is present as a key in array
     */

    private function isCardExistsInCardCurrecyRestrictionArray() {
        if (array_key_exists($this->card_type, $this->card_currency_restriction_array)) {
            return true;
        }
        return false;
    }

    /*
     * this function check if for given card teh currency is allowed or not
     * if currency is not allowed the it will return false
     */

    private function getAllowedCurrenciesForThisCard() {
        $only_allowed_currencies = explode(',', $this->card_currency_restriction_array[$this->card_type]);
        if (in_array($this->currency, $only_allowed_currencies)) {
            return true;
        }
        return false;
    }

    /*
     * Checks for card in card_gateway_array
     * @ return true if Card is present as a key in array
     */

    private function isCardExitsInCardGatewayArray() {
        if (array_key_exists($this->card_type, $this->card_gateway_array)) {
            return true;
        }
        return false;
    }

    /*
     * check if currency is exists in currency gateway array
     */

    private function isCurrencyExistsInCurrencyGatewayArray($get_payment_gateway_name = false) {
        foreach ($this->currency_gateway_array as $gateway => $currency_string) {
            $tmp_currency_array = explode(",", $currency_string);

            if (in_array($this->currency, $tmp_currency_array)) {
                if ($get_payment_gateway_name === true) {
                    return $gateway;
                }
                return true;
            }
        }
        return false;
    }

    public function is_valid_amount_for_order() {
        if ((float) $this->credit_card_params['amount'] > 0) {
            return true;
        }
        return false;
    }

    public function isValidCardExpiry() {

        if ($this->credit_card_params['card_expiry_year'] >= date('Y') && $this->credit_card_params['card_expiry_month'] >= date('m')) {
            return true;
        }
        return false;
    }

    /*
     * retruns my payment gateway name
     */

    public function myPaymentGateway($card_type, $currency) {
        $this->setCardType($card_type);
        $this->setCurrency($currency);

        if ($this->is_valid_amount_for_order() === false || $this->isValidCardExpiry() === false) {
            $this->payment_gateway = 'false';
            return $this->payment_gateway;
        }
        // we first check if the card type is present in card_currency_restriction_array 
        if ($this->isCardExistsInCardCurrecyRestrictionArray() === true) {

            if ($this->getAllowedCurrenciesForThisCard() === false) { // if current card type is NOT present in card_gateway_array  then set payment gatewa y as false -  so no payment gateway - so show error
                $this->payment_gateway = 'false';
            } else if ($this->isCardExitsInCardGatewayArray() === true) {  // if current card type is present in card_gateway_array  then set that payment gateway
                $this->payment_gateway = $this->card_gateway_array[$this->card_type];
            }
        } else if ($this->isCardExitsInCardGatewayArray() === true) { // if current card type is present in card_gateway_array  then set that payment gateway
            $this->payment_gateway = $this->card_gateway_array[$this->card_type];
        } else if ($this->isCurrencyExistsInCurrencyGatewayArray() === true) { // if current currency is present in currency_gateway_array then set that payment gateway
            $get_payment_gateway_name = true;
            $this->payment_gateway = $this->isCurrencyExistsInCurrencyGatewayArray($get_payment_gateway_name);
        }
        return $this->payment_gateway;
    }

    public function maskCreditCard($credit_card) {
        //return substr($credit_card, 0, 4) . str_repeat("X", strlen($credit_card) - 8) . substr($credit_card, -4);        
        return str_repeat("X", strlen($credit_card) - 4) . substr($credit_card, -4);
    }

    public function collectCreditCardParemeter($request_arr) {        
        
        // collect all the post data into variables 
        $customer_name = $request_arr['customer_name'];
        $total_amount = $request_arr['item_price'];
        $currency = $request_arr['currency'];

        $card_holder = $request_arr['cc_name'];
        $card_holder_arr = str_word_count($card_holder, 1);
        $first_name = array_shift($card_holder_arr);
        $last_name = implode(" ", $card_holder_arr);

        $card_type = $request_arr['cc_type'];
        $card_number = str_replace(' ', '', $request_arr['cc_number']);

        $card_expiry = $request_arr['cc_expiry'];
        $card_expiry_month = substr($card_expiry, 0, 2);
        $card_expiry_year = substr(stristr($card_expiry, '/'), 1);

        $card_cvv = $request_arr['cc_ccv'];


        $this->credit_card_params = array(
            'customer_name' => $customer_name,
            'amount' => $total_amount,
            'currency' => $currency,
            'card_holder' => $card_holder,
            'card_type' => $card_type,
            'card_number' => $card_number,
            'card_expiry' => $card_expiry,
            'card_cvv' => $card_cvv,
            'card_expiry_month' => $card_expiry_month,
            'card_expiry_year' => $card_expiry_year,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'mask_card_number' => $this->maskCreditCard($card_number)
        );
        return $this->credit_card_params;
    }

}

?>