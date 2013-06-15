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
$_SESSION["PAYMENTREQUEST_0_AMT"] = str_replace(".", "", $_POST["PAYMENTREQUEST_0_AMT"]);
$_SESSION["CURRENCYCODE"] = $_POST["PAYMENTREQUEST_0_CURRENCYCODE"];
$_SESSION["PAYMENTREQUEST_0_INVNUM"] = $_POST["PAYMENTREQUEST_0_INVNUM"];
$PAYMENTREQUEST_0_ITEMAMT = $_POST["PAYMENTREQUEST_0_AMT"]
                            - $_POST["PAYMENTREQUEST_0_SHIPPINGAMT"]
                            - $_POST["PAYMENTREQUEST_0_HANDLINGAMT"]
                            - $_POST["PAYMENTREQUEST_0_TAXAMT"];
$PAYMENTREQUEST_0_SHIPPINGAMT = $_POST["PAYMENTREQUEST_0_SHIPPINGAMT"];
$PAYMENTREQUEST_0_HANDLINGAMT = $_POST["PAYMENTREQUEST_0_HANDLINGAMT"];

$_SESSION["COMMIT"] = isset($_POST["COMMIT"]) ? TRUE : FALSE;
$ALLOWNOTE = isset($_POST["ALLOWNOTE"]) ? '1' : '0';
$NOSHIPPING = isset($_POST["NOSHIPPING"]) ? '1' : '0';
$REQBILLINGADDRESS = isset($_POST["REQBILLINGADDRESS"]) ? '1' : '0';
$ADDROVERRIDE = isset($_POST["ADDROVERRIDE"]) ? '1' : '0';

// Sale or AC2?
$sAutoSettle = $_POST["SETTLEMENT"];

// Line Items
$i = 0;
$aLineItems = array();
while(isset($_POST["L_PAYMENTREQUEST_0_NAME".$i]) &&
      isset($_POST["L_PAYMENTREQUEST_0_AMT".$i]) &&
      isset($_POST["L_PAYMENTREQUEST_0_QTY".$i]) &&
      isset($_POST["L_PAYMENTREQUEST_0_DESC".$i])) {
  $aLineItems[$i]['Name'] = $_POST["L_PAYMENTREQUEST_0_NAME".$i];
  $aLineItems[$i]['Quantity'] = $_POST["L_PAYMENTREQUEST_0_QTY".$i];
  $aLineItems[$i]['Amount'] = $_POST["L_PAYMENTREQUEST_0_AMT".$i];
  $aLineItems[$i]['Description'] = $_POST["L_PAYMENTREQUEST_0_DESC".$i];

  $i++;
}

$aOptions = array();
$aOptions["TaxTotal"] = $_POST["PAYMENTREQUEST_0_TAXAMT"];
$aOptions["cpp-logo-image"] = $_POST["LOGOIMG"];
$aOptions["cpp-cart-border-color"] = $_POST["CARTBORDERCOLOR"];
$aOptions["AllowNote"] = $ALLOWNOTE;
$aOptions["ReqBillingAddress"] = $REQBILLINGADDRESS;
$aOptions["BrandName"] = $_POST["BRANDNAME"];
$aOptions["LocaleCode"] = $_POST["PAYMENTREQUEST_0_LOCALECODE"];
$aOptions["LandingPage"] = $_POST["LANDINGPAGE"];
$aOptions["AddressOverride"] = $ADDROVERRIDE;
$aOptions["NoShipping"] = $NOSHIPPING;
if("" != $_POST["CUSTOM"]) {
  $aOptions["Custom"] = $_POST["CUSTOM"];
}
if("" != $_POST["MAXAMT"]) {
  $aOptions["MaxAmount"] = $_POST["MAXAMT"];
}

// Shipping
$aShipping = NULL;
if($NOSHIPPING) {
  $_SESSION["PAYMENTREQUEST_0_AMT"] = str_replace(".", "", ($_POST["PAYMENTREQUEST_0_AMT"]
                                                            - $PAYMENTREQUEST_0_SHIPPINGAMT
                                                            - $PAYMENTREQUEST_0_HANDLINGAMT));
  $PAYMENTREQUEST_0_SHIPPINGAMT = 0;
  $PAYMENTREQUEST_0_HANDLINGAMT = 0;
}
elseif($ADDROVERRIDE) {
  $aShipping = array();
	if(isset($_POST["PAYMENTREQUEST_0_SHIPTONAME"])) {
    $aShipping['Name'] = $_POST["PAYMENTREQUEST_0_SHIPTONAME"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOSTREET"])) {
    $aShipping['Street1'] = $_POST["PAYMENTREQUEST_0_SHIPTOSTREET"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOSTREET2"])) {
    $aShipping['Street2'] = $_POST["PAYMENTREQUEST_0_SHIPTOSTREET2"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOCITY"])) {
    $aShipping['CityName'] = $_POST["PAYMENTREQUEST_0_SHIPTOCITY"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOSTATE"])) {
    $aShipping['StateOrProvince'] = $_POST["PAYMENTREQUEST_0_SHIPTOSTATE"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"])) {
    $aShipping['Country'] = $_POST["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOPHONENUM"])) {
    $aShipping['Phone'] = $_POST["PAYMENTREQUEST_0_SHIPTOPHONENUM"];
  }

  if(isset($_POST["PAYMENTREQUEST_0_SHIPTOZIP"])) {
    $aShipping['PostalCode'] = $_POST["PAYMENTREQUEST_0_SHIPTOZIP"];
  }
}

$sXmlResponse = realexPaymentSetRequest($_SESSION["PAYMENTREQUEST_0_AMT"],
                                        $_SESSION["CURRENCYCODE"],
                                        $_SESSION["PAYMENTREQUEST_0_INVNUM"],
                                        $_POST["RETURNURL"],
                                        $_POST["CANCELURL"],
                                        $sAutoSettle,
                                        $PAYMENTREQUEST_0_ITEMAMT,
                                        $PAYMENTREQUEST_0_SHIPPINGAMT,
                                        $PAYMENTREQUEST_0_HANDLINGAMT,
                                        $_POST["PAYMENTREQUEST_0_TAXAMT"],
                                        $aShipping,
                                        $aOptions,
                                        $aLineItems);

$xmlResponse = getXmlDoc($sXmlResponse);

$oMessage = $xmlResponse->getElementsByTagName(REALEX_TAG_MESSAGE)->item(0);
$sMessage = $oMessage->nodeValue;

if("SUCCESS" == $sMessage) {
  // Get Token
  $oToken = $xmlResponse->getElementsByTagName(REALEX_TAG_TOKEN)->item(0);
  $_SESSION["TOKEN"] = $oToken->nodeValue;

  // Get Realex pasref
  $oPasref = $xmlResponse->getElementsByTagName(REALEX_TAG_PASREF)->item(0);
  $_SESSION["REALEX_PASREF"] = $oPasref->nodeValue;

  header("Location: ".getRedirectUrl($_SESSION["COMMIT"], $_SESSION["TOKEN"]));
}
else {
  echo '<h4>Realex request: <span class="btn btn-info btn-small">'.$aRealexFunctions["PAYMENT-SET"].'</span></h4>';
  echo '<h4>Realex response: <span class="btn btn-danger btn-small">Failed<span></h4><pre class="prettyprint">';
  print htmlentities($xmlResponse->saveXML());
  echo "</pre></div></body></html>";
}
?>