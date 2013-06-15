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
          <li><a href="index.html">Checkout</a></li>
          <li class="active"><a href="capture.php">Capture</a></li>
          <li><a href="void.php">Void</a></li>
          <li><a href="refund.php">Refund</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="result">

<?php
$sXmlResponse = realexPaymentSettle(str_replace(".", "", $_POST["AMT"]),
                                    $_POST["CURRENCYCODE"],
                                    $_POST["INVNUM"],
                                    $_POST["PASREF"],
                                    $_POST["COMPLETETYPE"]);

$xmlResponse = getXmlDoc($sXmlResponse);

$oMessage = $xmlResponse->getElementsByTagName(REALEX_TAG_MESSAGE)->item(0);
$sMessage = $oMessage->nodeValue;

echo '<h4>Realex request: <span class="btn btn-info btn-small">'.$aRealexFunctions["PAYMENT-SETTLE"].'</span></h4>';

if("SUCCESS" == $sMessage) {
  echo '<h4>Realex response: <span class="btn btn-success btn-small">Success<span></h4>';
  $oPasref = $xmlResponse->getElementsByTagName(REALEX_TAG_PASREF)->item(0);
  $_SESSION["REALEX_REFUND_PASREF"] = $oPasref->nodeValue;
}
else {
  echo '<h4>Realex response: <span class="btn btn-danger btn-small">Failure<span></h4>';
}

echo '<pre class="prettyprint">';
print htmlentities($xmlResponse->saveXML());
echo "</pre></div></body></html>";
?>