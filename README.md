This is HQTest Assignment in Symfony FrameWork With MongoDB
===========================================================

## Requirements

1) Create a payment gateway library, that could handle payments with:

* [Paypal REST API](https://developer.paypal.com/docs/api/)
* [Braintree payments](https://www.braintreepayments.com/docs/php/guide/overview)

Library should be designed to easily add another additional payment gateways.

2) Create a simple form for making payment. Form should have this fields:

* In order section:
  * Price (amount)
  * Currency (USD, EUR, THB, HKD, SGD, AUD)
  * Customer Full name
* In payment section:
  * Credit card holder name
  * Credit card number
  * Credit card expiration
  * Credit card CCV
* Submit button

Show success or error message after payment. 

Use appropriate form validations.

3) Save order data + response from payment gateway to database table.

4) Create a public repository on Github and push the solution there. Send us the link to the repository.

## Specification

* Create your own sandbox accounts for Paypal and Braintree
* To make it easier, implement only **single payment** with credit card. No need to implement saving credit card and authorization of payments (unless you really want to try it out).
* After submitting the form, use a different gateway based on these rules:
  * if credit card type is AMEX, then use Paypal.
  * if currency is USD, EUR, or AUD, then use Paypal. Otherwise use Braintree.
  * if currency is **not** USD and credit card **is** AMEX, return error message, that AMEX is possible to use only for USD
* Use any PHP framework you want or no framework at all, it's up to you.
* Don't bother with any graphics, just simple HTML, simple form, no CSS needed. Or just use [Twitter Bootstrap](http://getbootstrap.com).
* Use only Paypal, Braintree PHP libraries. You can use jQuery for form validations. Do not use any other 3rd party libraries.
* Cover code with unit tests.
* The code needs to work after we clone it and try it (no bugs) and should process the payments.

## Quality requirements

Similarly as during any other code review in our team, we'll be checking the following:

* code quality
* usage of the configuration files
* usage of the unit tests
* naming convention

## Bonus question

* How would you handle security for saving credit cards?

==================================================================================

## Installation :

  + Download the Zip from the GITHUB
  + Unzip the folder in web root directory 
  + By command line go to the project folder
  + run the composer update command and provide the DB credentials
  + run php app/console server:run command
  + go to the below url http://127.0.0.1:8000     
  + Here you can test the assignment on brower
  + you might need to give write permission to app/cache and app/logs folder

      for that navigate to project folder by command promt and run the following commands as give in symfony website
      -----------------------------------------------------------------------------------------------------------------

      HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`

      sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

      sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

      -----------------------------------------------------------------------------------------------------------------
      
  + To run the phpunit tests
    phpunit -c app/ or sudo phpunit -c app/
