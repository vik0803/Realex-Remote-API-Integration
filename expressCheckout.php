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
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
  <link type="text/css" href="../Common/css/bootstrap/cosmo/bootstrap.min.css" rel="stylesheet" />
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
$_SESSION["PAYMENTREQUEST_0_AMT"] = str_replace(".", "", $_POST["PAYMENTREQUEST_0_AMT"]);
$_SESSION["CURRENCYCODE"] = $_POST["PAYMENTREQUEST_0_CURRENCYCODE"];
$_SESSION["PAYMENTREQUEST_0_INVNUM"] = $_POST["PAYMENTREQUEST_0_INVNUM"];
$PAYMENTREQUEST_0_SHIPPINGAMT = $_POST["PAYMENTREQUEST_0_SHIPPINGAMT"];
$PAYMENTREQUEST_0_PAYMENTACTION = $_POST["PAYMENTREQUEST_0_PAYMENTACTION"];
$COMMIT = isset($_POST["COMMIT"]) ? false : true;
$ALLOWNOTE = isset($_POST["ALLOWNOTE"]) ? true : false;
$NOSHIPPING = isset($_POST["NOSHIPPING"]) ? true : false;
$REQBILLINGADDRESS = isset($_POST["REQBILLINGADDRESS"]) ? true : false;
$ADDROVERRIDE = isset($_POST["ADDROVERRIDE"]) ? true : false;
$LOGOIMG = $_POST["LOGOIMG"];
$CARTBORDERCOLOR = $_POST["CARTBORDERCOLOR"];
$BRANDNAME = $_POST["BRANDNAME"];
$RETURNURL = $_POST["RETURNURL"];
$CANCELURL = $_POST["CANCELURL"];

// Line Items
$i = 0;
$aLineItems = array();
while(isset($_POST["L_PAYMENTREQUEST_0_NAME".$i]) &&
      isset($_POST["L_PAYMENTREQUEST_0_AMT".$i]) &&
      isset($_POST["L_PAYMENTREQUEST_0_QTY".$i]) &&
      isset($_POST["L_PAYMENTREQUEST_0_DESC".$i])) {
  $aLineItems[$i]['NAME'] = $_POST["L_PAYMENTREQUEST_0_NAME".$i];
  $aLineItems[$i]['AMT'] = $_POST["L_PAYMENTREQUEST_0_AMT".$i];
  $aLineItems[$i]['QTY'] = $_POST["L_PAYMENTREQUEST_0_QTY".$i];
  $aLineItems[$i]['QTY'] = $_POST["L_PAYMENTREQUEST_0_DESC".$i];

  $i++;
}

// Shipping Address
$aShipping = array();
if($ADDROVERRIDE) {
  if(isset($_POST["PAYMENTREQUEST_0_SHIPTONAME"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTONAME'] = $_POST["PAYMENTREQUEST_0_SHIPTONAME"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOSTREET"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTOSTREET'] = $_POST["PAYMENTREQUEST_0_SHIPTOSTREET"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOSTREET2"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTOSTREET2'] = $_POST["PAYMENTREQUEST_0_SHIPTOSTREET2"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOCITY"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTOCITY'] = $_POST["PAYMENTREQUEST_0_SHIPTOCITY"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOSTATE"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTOSTATE'] = $_POST["PAYMENTREQUEST_0_SHIPTOSTATE"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOZIP"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTOZIP'] = $_POST["PAYMENTREQUEST_0_SHIPTOZIP"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $_POST["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOPHONENUM"])) {
    $aShipping['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $_POST["PAYMENTREQUEST_0_SHIPTOPHONENUM"];
  }
}

// Shipping
if($NOSHIPPING) {
  $PAYMENTREQUEST_0_SHIPPINGAMT = 0;
}

$sXmlResponse = realexPaymentSetRequest($_SESSION["PAYMENTREQUEST_0_AMT"],
                                        $_SESSION["CURRENCYCODE"],
                                        $_SESSION["PAYMENTREQUEST_0_INVNUM"],
                                        $RETURNURL,
                                        $CANCELURL);

$xmlResponse = new DOMDocument();
$xmlResponse->formatOutput = TRUE;
$xmlResponse->loadXML($sXmlResponse);

$oMessage = $xmlResponse->getElementsByTagName(REALEX_TAG_MESSAGE)->item(0);
$sMessage = $oMessage->nodeValue;

if("SUCCESS" == $sMessage) {
  // Get Token
  $oToken = $xmlResponse->getElementsByTagName(REALEX_TAG_TOKEN)->item(0);
  $sToken = $oToken->nodeValue;

  // Get Realex pasref
  $oPasref = $xmlResponse->getElementsByTagName(REALEX_TAG_PASREF)->item(0);
  $_SESSION["REALEX_PASREF"] = $oPasref->nodeValue;

  // Redirect
  $sSandbox = SANDBOX ? "sandbox." : "";
  $sUserActionCommit = $COMMIT ? "&useraction=commit" : "";
  $sRedirectURL = "https://www.".$SANDBOX."paypal.com/webscr?cmd=_express-checkout".$payNow."&token=".$sToken;

  header("Location: ".$sRedirectURL);
}
else {
  echo '<h4>Realex response: <span class="btn btn-danger btn-small">Failed<span></h4><pre class="prettyprint">';
  print htmlentities($xmlResponse->saveXML());
  echo "</pre></div></body></html>";
}
?>