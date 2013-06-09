<?php
require_once("config.php");
require_once("common.php");

function realexPaymentSetRequest($sAmount, $sCurrency, $sOrderId,
                                 $sReturnURL, $sCancelURL, $bAutoSettle = TRUE,
                                 $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                                 $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $aRealexFunctions["PAYMENT-SET"]);

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  "<request type=\"payment-set\" timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<amount currency=\"$sCurrency\">$sAmount</amount>".
    '<autosettle flag="'.($bAutoSettle ? '1' : '0').'" />'.
    "<orderid>$sOrderId</orderid>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails>'.
      '<ReturnURL>'.urlencode($sReturnURL).'</ReturnURL>'.
      '<CancelURL>'.urlencode($sCancelURL).'</CancelURL>'.
    '</paymentmethoddetails>'.
    '<comments />'.
    '<custnum />'.
    '<prodid />'.
    '<varref />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

function realexPaymentGetRequest($sOrderId, $sPasRef, $sToken,
                                 $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                                 $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $aRealexFunctions("PAYMENT-GET"));

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  "<request type=\"payment-get\" timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails>'.
      "<Token>$sToken</Token>".
    '</paymentmethoddetails>'.
    '<comments />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

function realexPaymentDoRequest($sAmount, $sCurrency, $sOrderId, $sPasRef, $sToken, $sPayerId,
                                $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                                $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $aRealexFunctions("PAYMENT-DO"));

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  "<request type=\"payment-do\" timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<amount currency=\"$sCurrency\">$sAmount</amount>".
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails>'.
      "<Token>$sToken</Token>".
      "<PayerId>$sPayerId</PayerId>".
    '</paymentmethoddetails>'.
    '<comments />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

function realexPaymentSettle($sAmount, $sCurrency, $sOrderId, $sPasRef, $sNote,
                             $sMultiSettle = 'none', // ('none', 'partial', 'complete')
                             $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                             $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $aRealexFunctions("PAYMENT-SETTLE"));

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  "<request type=\"payment-settle\" timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<amount currency=\"$sCurrency\">$sAmount</amount>".
    ($bMultiSettle != 'none' ? "<multisettle type=\"$sMultiSettle\" />" : "").
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails>'.
      "<Note>$sNote</Note>".
    '</paymentmethoddetails>'.
    '<comments />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

function realexPaymentVoid($sOrderId, $sPasRef,
                           $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                           $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $aRealexFunctions("PAYMENT-VOID"));

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  "<request type=\"payment-void\" timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

function realexPaymentCredit($sOrderId, $sPasRef,
                             $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                             $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $aRealexFunctions("PAYMENT-CREDIT"));

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  "<request type=\"payment-credit\" timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

/* Internal */
function _realexSha1Hash($sTimeStamp, $sMerchantID, $sOrderID, $sAmount, $sCurrency, $sPaymentMethod, $sSecret = REALEX_SECRET) {
  $sHash = sha1($sTimeStamp.$sMerchantID.$sOrderID.$sAmount.$sCurrency.$sPaymentMethod);
  return sha1($sHash.$sSecret);
}

function _realexGetTimeStamp() {
  return date('YmdHis');
}

function _callRealex($sXmlRequest) {
  $hCURL = curl_init();
  curl_setopt($hCURL, CURLOPT_URL, API_ENDPOINT);
  curl_setopt($hCURL, CURLOPT_VERBOSE, 1);
  curl_setopt($hCURL, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($hCURL, CURLOPT_POST, 1);
  curl_setopt($hCURL, CURLOPT_POSTFIELDS, $sXmlRequest);

  if(LOG_REQUESTS) {
    logNotice("REALEX REQUEST XML:".$sXmlRequest);
  }

  $sResponse = curl_exec($hCURL);

  if(curl_errno($hCURL)) {
    logNotice("CURL Error: Error #: ".curl_errno($hCURL));
    logNotice("CURL Error: Error Msg: ".curl_error($hCURL));
  }
  else {
    curl_close($hCURL);
  }

  if(LOG_REQUESTS) {
    logNotice("REALEX RESPONSE XML:".$sResponse);
  }
  return $sResponse;
}
?>

