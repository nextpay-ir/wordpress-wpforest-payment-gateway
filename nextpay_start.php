<?php

// Include the Nextpay library
include_once ('Nextpay.php');
require_once ("nextpay_payment.php");

// Create an instance of the Nextpay library
$myNextpay = new Nextpay();

$domain = "http://YOUR_HOST";
$api_key = "xxxx-xxx-xxx-xxxx-xxxx";
$amount = 100;
$order_id = time();
$callback_uri = $domain.'/payment/nextpay_ipn.php';

// Specify the url where Nextpay will send the IPN
$myNextpay->addField('callback_uri', $callback_uri);

// Specify the product information
//$myNextpay->addField('Description', 'T-Shirt');
//$myNextpay->addField('Amount', $amount);
//$myNextpay->addField('Invoice_num', $order_id);
//$myNextpay->addField('Cust_ID', 'nextpay-'.$order_id);


$parameters = array
(
    'api_key'		=> $api_key,
    'order_id'		=> $order_id,
    'amount' 		=> $amount,
    'callback_uri'	=> $callback_uri,
);
try {
    $nextpay = new Nextpay_Payment();
    //$nextpay->setDefaultVerify(0);
    $result = $nextpay->token($parameters);
    $result->trans_id;
    $result->code;
    if (intval($result->code) == -1){
      // Let's start the train!
      $myNextpay->submitPayment();
    }else{
      echo $nextpay->code_error(intval($result->code));
      exit();
    }
}catch (Exception $e) { echo 'Error'. $e->getMessage();  exit();}

