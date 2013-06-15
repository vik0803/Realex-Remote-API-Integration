<?php
require_once("realexFunctions.php");

if(strlen(session_id()) < 1) {
  session_start();
}

$sPasRef = isset($_SESSION["REALEX_REFUND_PASREF"]) ? $_SESSION["REALEX_REFUND_PASREF"] : "";
$sInvNum = isset($_SESSION["PAYMENTREQUEST_0_INVNUM"]) ? $_SESSION["PAYMENTREQUEST_0_INVNUM"] : "";
$sAmt = isset($_SESSION["PAYMENTREQUEST_0_AMT"]) ? ($_SESSION["PAYMENTREQUEST_0_AMT"] / 100) : "";
$sCurrency = isset($_SESSION["CURRENCYCODE"]) ? $_SESSION["CURRENCYCODE"] : "";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title>Realex: Express Checkout</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link type="text/css" href="../Common/css/bootstrap/cosmo/bootstrap.min.css" rel="stylesheet" />
  <link type="text/css" href="./css/style.css" rel="stylesheet" />
  <script type="text/javascript" src="../Common/javascript/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="./js/basket.js"></script>
</head>
<body>
  <div class="navbar">
    <div class="navbar-inner">
      <div class="container">
        <a class="brand" href="#">Realex Express Checkout Test Page</a>
        <div class="nav-collapse">
          <ul class="nav">
            <li><a href="index.html">Checkout</a></li>
            <li><a href="capture.php">Capture</a></li>
            <li><a href="void.php">Void</a></li>
            <li class="active"><a href="refund.php">Refund</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <form action="doRefund.php" method="post">
    <div class="paramName">PASREF</div>
    <input type="text" id="PASREF" name="PASREF" value="<?php echo $sPasRef; ?>" /><br />
    <div class="paramName">INVNUM</div>
    <input type="text" id="INVNUM" name="INVNUM" value="<?php echo $sInvNum; ?>" /><br />
    <div class="paramName">AMT</div>
    <input type="text" id="AMT" name="AMT" value="<?php echo $sAmt; ?>" /><br />

<?php
if("" != $sCurrency) {
?>
    <div class="paramName">CURRENCY</div>
    <input type="text" id="CURRENCY" name="CURRENCY" value="<?php echo $sCurrency; ?>" /><br />
<?php
}
else {
?>
    <div class="paramName">CURRENCY:</div>
    <div class="controls" style="display: inline-block">
      <select name="CURRENCY">
        <option value="GBP" selected="selected">GBP</option>
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="AUD">AUD</option>
        <option value="BRL">BRL</option>
        <option value="CAD">CAD</option>
        <option value="CZK">CZK</option>
        <option value="DKK">DKK</option>
        <option value="HKD">HKD</option>
        <option value="HUF">HUF</option>
        <option value="ILS">ILS</option>
        <option value="JPY">JPY</option>
        <option value="MYR">MYR</option>
        <option value="MXN">MXN</option>
        <option value="NOK">NOK</option>
        <option value="NZD">NZD</option>
        <option value="PHP">PHP</option>
        <option value="PLN">PLN</option>
        <option value="SGD">SGD</option>
        <option value="SEK">SEK</option>
        <option value="CHF">CHF</option>
        <option value="TWD">TWD</option>
        <option value="THB">THB</option>
        <option value="TRY">TRY</option>
      </select>
    </div><br />
<?php
}
?>

    <div class="paramName">&nbsp;</div>
    <button class="btn btn-primary" type="submit">
      Refund
    </button>
  </form>
</body>
</html>