<?php

/**
 * Nextpay Class
 *
 * Integrate the nextpay payment gateway in your site using this
 * easy to use library. Just see the example code to know how you should
 * proceed. Also, remember to read the readme file for this class.
 *
 * @package     Nextpay Payment Gateway
 * @category	Library
 * @author      nextpay co <info@nextpay.ir>
 * @link        https://nextpay.ir
 */

require_once ("nextpay_payment.php");

class Nextpay extends PaymentGateway
{
    public $apikey;

    /**
    * Initialize the Authorize.net gateway
    *
    * @param none
    * @return void
    */
    public function __construct()
    {
	parent::__construct();

	// Some default values of the class
	$this->gatewayUrl = "";
	$this->ipnLogFile = 'nextpay.ipn_results.log';
	
    }

    /**
    * Validate the IPN notification
    *
    * @param none
    * @return boolean
    */
  public function validateIpn()
  {
      foreach ($_POST as $field=>$value)
      {
	      $this->ipnData["$field"] = $value;
      }
      $trans_id = isset($this->ipnData['trans_id']) ? $this->ipnData['trans_id'] : false ;
      $order_id = isset($this->ipnData['order_id']) ? $this->ipnData['order_id'] : false ;

      if (!$trans_id || !$order_id) {
	$this->lastError = "شماره تراکنش  یا شماره سفارش ارسال نشده است";
	$this->logResults(false);
	return false;
	  
      }

      if (!is_string($trans_id) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $trans_id) !== 1)) {
	$this->lastError = "شماره تراکنش ارسالی اشتباه است";
	$this->logResults(false);
	return false;	  
      }
      
      $parameters = array
      (
	  'api_key'	=> $api_key,
	  'order_id'	=> $order_id,
	  'trans_id' 	=> $trans_id,
	  'amount'	=> $price,
      );
      try {
	  $nextpay = new Nextpay_Payment();
	  $nextpay->setDefaultVerify(0);
	  $result = $nextpay->verify_request($parameters);
	  if( $result < 0 ) {
	      $this->lastError = 'خطا در عملیات بانکی پرداخت تائید نگردید' . "تراکنش ناموفق با شماره :" . $trans_id;
	      $this->logResults(false);
	      return false;
	  } elseif ($result==0) {
	      $this->logResults(true);
	      return true;
	  }else{
	      $this->lastError = "تراکنش ناموفق با شماره :" . $trans_id;
	      $this->logResults(false);
	      return false;
	  }
      }catch (Exception $e) { echo 'Error'. $e->getMessage();  return false;}
  }
}
