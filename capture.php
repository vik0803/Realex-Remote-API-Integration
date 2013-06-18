<?php
require_once("realexFunctions.php");

if(strlen(session_id()) < 1) {
  session_start();
}

$sPasRef = isset($_SESSION["REALEX_PASREF"]) ? $_SESSION["REALEX_PASREF"] : "";
$sInvNum = isset($_SESSION["PAYMENTREQUEST_0_INVNUM"]) ? $_SESSION["PAYMENTREQUEST_0_INVNUM"] : "";
$sCurrency = isset($_SESSION["CURRENCYCODE"]) ? $_SESSION["CURRENCYCODE"] : "";
$sAmt = isset($_SESSION["PAYMENTREQUEST_0_AMT"]) ? ($_SESSION["PAYMENTREQUEST_0_AMT"] / 100) : "";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title>Realex: Express Checkout</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
  <form action="doCapture.php" method="post">
    <div class="paramName">PASREF</div>
    <input type="text" id="PASREF" name="PASREF" value="<?php echo $sPasRef; ?>" /><br />
    <div class="paramName">INVNUM</div>
    <input type="text" id="INVNUM" name="INVNUM" value="<?php echo $sInvNum; ?>" /><br />
    <div class="paramName">CURRENCYCODE</div>
    <input type="text" id="CURRENCYCODE" name="CURRENCYCODE" value="<?php echo $sCurrency; ?>" /><br />
    <div class="paramName">AMT</div>
    <input type="text" id="AMT" name="AMT" value="<?php echo $sAmt; ?>" /><br />

    <div class="paramName">COMPLETETYPE:</div>
    <div class="controls" style="display: inline-block">
      <select name="COMPLETETYPE">
        <option value="complete" selected="selected">Complete</option>
        <option value="partial">Partial</option>
      </select>
    </div><br />

    <div class="paramName">&nbsp;</div>
    <button class="btn btn-primary" type="submit">
      Capture
    </button>
  </form>
</body>
</html>