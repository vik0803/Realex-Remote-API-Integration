<?php
require_once("realexFunctions.php");

if(strlen(session_id()) < 1) {
  session_start();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title>Realex: Express Checkout</title>
  <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
  <link type="text/css" href="../Common/css/bootstrap/cosmo/bootstrap.min.css" rel="stylesheet" />
  <link type="text/css" href="./css/style.css" rel="stylesheet" />
</head>
<body>
<h2>Realex Express Checkout Test Page</h2>

<?php
// Check for token from PayPal
if(!isset($_REQUEST['token'])) {
  echo '<p>Error: Token missing from URL</p>';
  echo '</body></html>';
  exit;
}
?>

<form action="<?php echo COMPLETE_URL ?>" method="post">
  <button class="btn btn-primary" onclick="updateTotal()" type="submit">
    <i class="icon-shopping-cart icon-white"></i>
    Complete Checkout
  </button>
</form>

<?php
$_SESSION["PAYPAL_TOKEN"] = $_REQUEST['token'];
$sCompleteURL = 'complete.php';

$sXmlResponse = realexPaymentGetRequest($_SESSION["PAYMENTREQUEST_0_INVNUM"],
                                        $_SESSION["REALEX_PASREF"],
                                        $_SESSION["PAYPAL_TOKEN"]);

$xmlResponse = new DOMDocument();
$xmlResponse->formatOutput = TRUE;
$xmlResponse->loadXML($sXmlResponse);

$oMessage = $xmlResponse->getElementsByTagName(REALEX_TAG_MESSAGE)->item(0);
$sMessage = $oMessage->nodeValue;

$oPayerId = $xmlResponse->getElementsByTagName(REALEX_TAG_PAYERID)->item(0);
$_SESSION["PAYPAL_PAYERID"] = $oPayerId->nodeValue;

if("SUCCESS" == $sMessage) {
  echo '<h4>Realex response: <span class="btn btn-success btn-small">Success<span></h4><pre class="prettyprint">';
}
else {
  echo '<h4>Realex response: <span class="btn btn-danger btn-small">Failure<span></h4><span class="help-inline">'.$sMessage.'<span><pre>';
}

print htmlentities($xmlResponse->saveXML());
echo "</pre></body></html>";
?>

