<?php
function logNotice($sMessage) {
  $caller = array_shift(debug_backtrace());
  file_put_contents('php://stderr', print_r(PHP_EOL.'File: '.$caller['file'].PHP_EOL.'Line: '.$caller['line'].PHP_EOL.$sMessage, TRUE));
}
?>
