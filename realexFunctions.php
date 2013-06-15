<?php
require_once("config.php");
require_once("common.php");

function realexPaymentSetRequest($sAmount, $sCurrency, $sOrderId,
                                 $sReturnURL, $sCancelURL, $sAutoSettle = '1',
                                 $sOrderTotal = NULL, $sShippingAmount = NULL, $sHandlingAmount = NULL, $sTaxAmount = NULL,
                                 $aShipping = NULL, $aOptions = NULL, $aLineItems = NULL,
                                 $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                                 $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexCreateSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $sPaymentMethod);

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  '<request type="'.$aRealexFunctions["PAYMENT-SET"].'"'." timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<amount currency=\"$sCurrency\">$sAmount</amount>".
    "<autosettle flag=\"$sAutoSettle\" />".
    "<orderid>$sOrderId</orderid>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails>'.
      "<ReturnURL>$sReturnURL</ReturnURL>".
      "<CancelURL>$sCancelURL</CancelURL>";

  // Line Item Details
  if($aLineItems) {
    $sXmlRequest .= "<PaymentDetails>";

    // BUG: This will not be required.
    //$iOrderTotal = $sOrderTotal + $sShippingAmount + $sHandlingAmount + $sTaxAmount;
    $fOrderTotal = $sAmount / 100;
    $sXmlRequest .= "<OrderTotal currencyID=\"$sCurrency\">$fOrderTotal</OrderTotal>";
    ///

    $sXmlRequest .= "<ItemTotal currencyID=\"$sCurrency\">$sOrderTotal</ItemTotal>";
    $sXmlRequest .= "<ShippingTotal currencyID=\"$sCurrency\">$sShippingAmount</ShippingTotal>";
    $sXmlRequest .= "<HandlingTotal currencyID=\"$sCurrency\">$sHandlingAmount</HandlingTotal>";
    $sXmlRequest .= "<TaxTotal currencyID=\"$sCurrency\">$sTaxAmount</TaxTotal>";

    // Shipping Address
    if($aShipping) {
      $sXmlRequest .= "<ShipToAddress>";
      foreach($aShipping as $sParamName => $sParamValue) {
        $sXmlRequest .= "<$sParamName>$sParamValue</$sParamName>";
      }
      $sXmlRequest .= "</ShipToAddress>";
    }

    foreach($aLineItems as $aLineItem) {
      $sXmlRequest .= "<PaymentDetailsItem>";
      foreach($aLineItem as $sParamName => $sParamValue) {
        if("Amount" == $sParamName) {
          $sXmlRequest .= "<$sParamName currencyID=\"$sCurrency\">$sParamValue</$sParamName>";
        }
        else {
          $sXmlRequest .= "<$sParamName>$sParamValue</$sParamName>";
        }
      }
      $sXmlRequest .= "</PaymentDetailsItem>";
    }
    $sXmlRequest .= "</PaymentDetails>";
  }

  // Optional Parameters
  if($aOptions) {
    foreach($aOptions as $sParamName => $sParamValue) {
      if("MaxAmount" == $sParamName) {
        $sXmlRequest .= "<$sParamName currencyID=\"$sCurrency\">$sParamValue</$sParamName>";
      }
      else {
        $sXmlRequest .= "<$sParamName>$sParamValue</$sParamName>";
      }
    }
  }

    $sXmlRequest .= '</paymentmethoddetails>'.
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
  $sSha1Hash = _realexCreateSha1Hash($sTime, $sMerchantId, $sOrderId, '', '', $sPaymentMethod);

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  '<request type="'.$aRealexFunctions["PAYMENT-GET"].'"'." timestamp=\"$sTime\">".
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
  $sSha1Hash = _realexCreateSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $sPaymentMethod);

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  '<request type="'.$aRealexFunctions["PAYMENT-DO"].'"'." timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<amount currency=\"$sCurrency\">$sAmount</amount>".
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails>'.
      "<Token>$sToken</Token>".
      "<PayerID>$sPayerId</PayerID>".
    '</paymentmethoddetails>'.
    '<comments />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

function realexPaymentSettle($sAmount, $sCurrency, $sOrderId, $sPasRef,
                             $sMultiSettle = 'complete', $sNote = '',
                             $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                             $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexCreateSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $sPaymentMethod);

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  '<request type="'.$aRealexFunctions["PAYMENT-SETTLE"].'"'." timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<amount currency=\"$sCurrency\">$sAmount</amount>".
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<multisettle type=\"$sMultiSettle\" />".
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
  $sSha1Hash = _realexCreateSha1Hash($sTime, $sMerchantId, $sOrderId, '', '', $sPaymentMethod);

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  '<request type="'.$aRealexFunctions["PAYMENT-VOID"].'"'." timestamp=\"$sTime\">".
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

function realexPaymentCredit($sAmount, $sCurrency, $sOrderId, $sPasRef,
                             $sPaymentMethod = PAYPAL_PAYMENT_METHOD,
                             $sMerchantId = MERCHANT_ID, $sAccount = ACCOUNT, $sRefundHash = REFUND_HASH) {
  global $aRealexFunctions;
  $sTime = _realexGetTimeStamp();
  $sSha1Hash = _realexCreateSha1Hash($sTime, $sMerchantId, $sOrderId, $sAmount, $sCurrency, $sPaymentMethod);

  $sXmlRequest = '<?xml version="1.0" encoding="UTF-8"?>'.
  '<request type="'.$aRealexFunctions["PAYMENT-CREDIT"].'"'." timestamp=\"$sTime\">".
    "<merchantid>$sMerchantId</merchantid>".
    "<account>$sAccount</account>".
    "<amount currency=\"$sCurrency\">$sAmount</amount>".
    "<orderid>$sOrderId</orderid>".
    "<pasref>$sPasRef</pasref>".
    "<paymentmethod>$sPaymentMethod</paymentmethod>".
    '<paymentmethoddetails />'.
    "<sha1hash>$sSha1Hash</sha1hash>".
    "<refundhash>$sRefundHash</refundhash>".
  '</request>';

  return _callRealex($sXmlRequest);
}

/* Internal */
function _realexCreateSha1Hash($sTimeStamp, $sMerchantID, $sOrderID, $sAmount, $sCurrency, $sPaymentMethod, $sSecret = REALEX_SECRET) {
  $sHash = sha1($sTimeStamp.'.'.$sMerchantID.'.'.$sOrderID.'.'.$sAmount.'.'.$sCurrency.'.'.$sPaymentMethod);
  return sha1($sHash.'.'.$sSecret);
}

function _realexCheckSha1Hash($sTimeStamp, $sMerchantID, $sOrderID, $sAmount, $sCurrency, $sPaymentMethod, $sSecret = REALEX_SECRET) {

  return;
}

function _realexGetTimeStamp() {
  return date('YmdHis');
}

function _callRealex($sXmlRequest) {
  $hCURL = curl_init();
  curl_setopt($hCURL, CURLOPT_URL, API_ENDPOINT);
  //curl_setopt($hCURL, CURLOPT_VERBOSE, 1);
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

  if(LOG_RESPONSES) {
    logNotice("REALEX RESPONSE XML:".$sResponse);
  }
  return $sResponse;
}
?>

