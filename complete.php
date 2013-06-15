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
  <link type="text/css" href="./css/bootstrap/cosmo/bootstrap.min.css" rel="stylesheet" />
  <link type="text/css" href="./css/style.css" rel="stylesheet" />
</head>
<body>
<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="#">Realex Express Checkout Test Page</a>
      <div class="nav-collapse">
        <ul class="nav">
          <li class="active"><a href="index.html">Checkout</a></li>
          <li><a href="capture.php">Capture</a></li>
          <li><a href="void.php">Void</a></li>
          <li><a href="refund.php">Refund</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="result">

<?php
$sXmlResponse = realexPaymentDoRequest($_SESSION["PAYMENTREQUEST_0_AMT"],
                                       $_SESSION["CURRENCYCODE"],
                                       $_SESSION["PAYMENTREQUEST_0_INVNUM"],
                                       $_SESSION["REALEX_PASREF"],
                                       $_SESSION["PAYPAL_TOKEN"],
                                       $_SESSION["PAYPAL_PAYERID"]);

$xmlResponse = getXmlDoc($sXmlResponse);

$oMessage = $xmlResponse->getElementsByTagName(REALEX_TAG_MESSAGE)->item(0);
$sMessage = $oMessage->nodeValue;

if("SUCCESS" == $sMessage) {
  $oAuthResponse = $xmlResponse->getElementsByTagName(REALEX_TAG_DOAUTHRESPONSE)->item(0);
  if($oAuthResponse) {
    $oAck = $oAuthResponse->getElementsByTagName(REALEX_TAG_ACK)->item(0);
    $sAck = $oAck->nodeValue;
    $oAmount = $oAuthResponse->getElementsByTagName(REALEX_TAG_AMOUNT)->item(0);
    $sAmount = $oAmount->nodeValue;

    if("Success" == $sAck) {
      $oTransactionId = $oAuthResponse->getElementsByTagName(REALEX_TAG_TRANSACTIONID)->item(0);
      $sTransactionId = $oTransactionId->nodeValue;
      $sCaptureUrl = "capture.php?Amount=$sAmount";
  ?>

    <form action="<?php echo $sCaptureUrl; ?>" method="post">
      <button class="btn btn-primary" type="submit">
        Capture
      </button>
    </form>

<?php
    }
  }
  echo '<h4>Realex request: <span class="btn btn-info btn-small">'.$aRealexFunctions["PAYMENT-DO"].'</span></h4>';
  echo '<h4>Realex response: <span class="btn btn-success btn-small">Success<span></h4>';
  $oPasref = $xmlResponse->getElementsByTagName(REALEX_TAG_PASREF)->item(0);
  $_SESSION["REALEX_REFUND_PASREF"] = $oPasref->nodeValue;
}
else {
  // Check for 10486 and redirect if necessary
  $oDoECPResponse = $xmlResponse->getElementsByTagName(REALEX_TAG_DOECPRESPONSE)->item(0);
  $oErrorCode = $oDoECPResponse->getElementsByTagName(REALEX_TAG_ERRORCODE)->item(0);
  if("10486" == $oErrorCode->nodeValue) {
    header("Location: ".getRedirectUrl($_SESSION["COMMIT"], $_SESSION["TOKEN"]));
  }
  else {
    echo '<h4>Realex request: <span class="btn btn-info btn-small">'.$aRealexFunctions["PAYMENT-DO"].'</span></h4>';
    echo '<h4>Realex response: <span class="btn btn-danger btn-small">Failure<span></h4>';
  }
}
echo '<pre class="prettyprint">';
print htmlentities($xmlResponse->saveXML());
echo "</pre></div></body></html>";
?>
