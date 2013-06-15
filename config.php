<?php
define("SANDBOX", TRUE);
define("API_ENDPOINT", "https://epage.payandshop.com/epage-remote.cgi");
define("REALEX_SECRET", "");
define("MERCHANT_ID", "");
define("ACCOUNT", "");
define("REFUND_HASH", "");

define ("COMPLETE_URL", "complete.php");
define ("CAPTURE_URL", "capture.php");

define("LOG_REQUESTS", FALSE);
define("LOG_RESPONSES", FALSE);

define("REALEX_TAG_MESSAGE", "message");
define("REALEX_TAG_ACK", "Ack");
define("REALEX_TAG_TRANSACTIONID", "TransactionID");
define("REALEX_TAG_AMOUNT", "Amount");
define("REALEX_TAG_TOKEN", "Token");
define("REALEX_TAG_ORDERID", "orderid");
define("REALEX_TAG_PASREF", "pasref");
define("REALEX_TAG_PAYERID", "PayerID");
define("REALEX_TAG_DOAUTHRESPONSE", "DoAuthorizationResponse");
define("REALEX_TAG_DOECPRESPONSE", "DoExpressCheckoutPaymentResponse");
define("REALEX_TAG_PAYMENTSTATUS", "PaymentStatus");
define("REALEX_TAG_PENDINGREASON", "PendingReason");
define("REALEX_TAG_ERRORCODE", "ErrorCode");

define("PAYPAL_PAYMENT_METHOD", "paypal");

$aRealexFunctions = array(
  "PAYMENT-SET"    => "payment-set",
  "PAYMENT-GET"    => "payment-get",
  "PAYMENT-DO"     => "payment-do",
  "PAYMENT-SETTLE" => "payment-settle",
  "PAYMENT-CREDIT" => "payment-credit",
  "PAYMENT-VOID"   => "payment-void"
);
?>
