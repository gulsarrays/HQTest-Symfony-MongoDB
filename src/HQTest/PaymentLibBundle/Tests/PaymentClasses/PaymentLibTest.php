<?php
use HQTest\PaymentLibBundle\PaymentClasses\PaymentLib;

define('DEFAULT_PAYMENT_GATEWAY', 'braintree');

Class PaymentLibTest extends \PHPUnit_Framework_TestCase {

    protected $paymentlib;

    public function setup() {
        $this->paymentlib = new PaymentLib();
    }

    public function teardown() {
        unset($this->paymentlib);
    }

    public function testVerifyCreditCardMasking() {
        $card_number = '4123478925893697';
        $this->assertEquals('XXXXXXXXXXXX3697', $this->paymentlib->maskCreditCard($card_number));
    }

    public function testIfpostparemetersCollectedProperly() {

        $request_arr = array(
            'customer_name' => 'My Customer Name',
            'item_price' => '153.69',
            'currency' => 'USD',
            'cc_name' => 'Card Holder Name',
            'cc_type' => 'Visa',
            'cc_number' => '4123 2361 2365 8945',
            'cc_expiry' => '12/2021',
            'cc_ccv' => '678'
        );

        $credit_card_params = array(
            'customer_name' => 'My Customer Name',
            'amount' => '153.69',
            'currency' => 'USD',
            'card_holder' => 'Card Holder Name',
            'card_type' => 'Visa',
            'card_number' => '4123236123658945',
            'card_expiry' => '12/2021',
            'card_cvv' => '678',
            'card_expiry_month' => '12',
            'card_expiry_year' => '2021',
            'first_name' => 'Card',
            'last_name' => 'Holder Name',
            'mask_card_number' => 'XXXXXXXXXXXX8945'
        );

        $this->assertEquals($credit_card_params, $this->paymentlib->collectCreditCardParemeter($request_arr));
    }
    
    public function testIsValidAmount(){
        $credit_card_params = array(
            'amount' => '10.00' // or jhjkhkjhkj or -67.98
                    );
        $this->paymentlib->credit_card_params = $credit_card_params;
        $this->assertNotFalse($this->paymentlib->is_valid_amount_for_order());
    }
    
    public function testIsValidCardExpiry(){
        $credit_card_params = array(  
            'card_expiry_month' => '02',
            'card_expiry_year' => '2019'
        );
        $this->paymentlib->credit_card_params = $credit_card_params;
        $this->assertNotFalse($this->paymentlib->isValidCardExpiry());
    }

    /**
     * @dataProvider gatewayRulesProvider
     */
    public function testVerifyPaymentGatewayAsPerRules($card_type, $currency, $expected_gateway) {
        
        $credit_card_params = array(
            'customer_name' => 'My Customer Name',
            'amount' => '153.69',
            'currency' => $currency,
            'card_holder' => 'Card Holder Name',
            'card_type' => $card_type,
            'card_number' => '4123236123658945',
            'card_expiry' => '12/2021',
            'card_cvv' => '678',
            'card_expiry_month' => '12',
            'card_expiry_year' => '2021',
            'first_name' => 'Card',
            'last_name' => 'Holder Name',
            'mask_card_number' => 'XXXXXXXXXXXX8945'
        );
        $this->paymentlib->credit_card_params = $credit_card_params;
        
        $this->assertEquals($expected_gateway, $this->paymentlib->myPaymentGateway($card_type, $currency));
    }

    public function gatewayRulesProvider() {
        /*
          1) Card Type = AMEX/ EUR, THB, HKD, SGD, AUD ==> Error
          2) Card Type = AMEX/USD ==> paypal
          3) Card Type = !AMEX/EUR, or AUD ==> paypal
          4) Card Type = !AMEX / THB, HKD, SGD ==> Braintree
         */

        return array(
            array('AMEX', 'EUR', 'false'), // condition 1  - starts
            array('AMEX', 'THB', 'false'),
            array('AMEX', 'HKD', 'false'),
            array('AMEX', 'SGD', 'false'),
            array('AMEX', 'AUD', 'false'), // condition 1  - ends
            array('AMEX', 'USD', 'paypal'), // condition 2
            array('visa', 'EUR', 'paypal'), // condition 3 -  starts
            array('mastercard', 'EUR', 'paypal'),
            array('discover', 'EUR', 'paypal'),
            array('visa', 'AUD', 'paypal'),
            array('mastercard', 'AUD', 'paypal'),
            array('discover', 'AUD', 'paypal'), // condition 3 -  Ends
            array('visa', 'THB', 'braintree'), // condition 4 -  starts
            array('mastercard', 'THB', 'braintree'),
            array('discover', 'THB', 'braintree'),
            array('visa', 'HKD', 'braintree'),
            array('mastercard', 'HKD', 'braintree'),
            array('discover', 'HKD', 'braintree'),
            array('visa', 'SGD', 'braintree'),
            array('mastercard', 'SGD', 'braintree'),
            array('discover', 'SGD', 'braintree')  // condition 4 -  Ends
        );
    }

}
?>