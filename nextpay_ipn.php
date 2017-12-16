<?php

// Include the Nextpay library
include_once ('Nextpay.php');

// Create an instance of the Nextpay library
$myNextpay = new Nextpay();

// Log the IPN results
$myNextpay->ipnLog = TRUE;

// Check validity and write down it
if ($myNextpay->validateIpn())
{
//     file_put_contents('nextpay.txt', 'SUCCESS');
    file_put_contents('nextpay_success.php', 'SUCCESS');
}
else
{
    file_put_contents('nextpay_failed.php', "FAILURE\n\n" . $myAuthorize->ipnData);
}