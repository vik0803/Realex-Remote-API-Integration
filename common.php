<?php
function logNotice($sMessage) {
  $caller = array_shift(debug_backtrace());
  file_put_contents('php://stderr', print_r(PHP_EOL.date("Y-m-d H:i:s").PHP_EOL.'File: '.$caller['file'].PHP_EOL.'Line: '.$caller['line'].PHP_EOL.$sMessage.PHP_EOL, TRUE));
}

function getXmlDoc($sXml) {
  $xmlDoc = new DOMDocument();
  $xmlDoc->formatOutput = TRUE;
  $xmlDoc->preserveWhiteSpace = FALSE;
  $xmlDoc->loadXML($sXml);
  return $xmlDoc;
}

function getRedirectUrl($bCommit, $sToken) {
  $sSandbox = SANDBOX ? "sandbox." : "";
  $sUserActionCommit = $bCommit ? "&useraction=commit" : "";
  return "https://www.".$sSandbox."paypal.com/webscr?cmd=_express-checkout".$sUserActionCommit."&token=".$sToken;
}

?>
