var hShippingAddress = null
var hTotalAmtText = null
var hItem0AmtText = null
var hItem0QtyText = null
var hItem1AmtText = null
var hItem1QtyText = null
var hItem1QtyText = null
var hShippingText = null
var hNoShippingOption = null

$(document).ready(function() {

  hTotalAmtText = $('#PAYMENTREQUEST_0_AMT')[0]
  hMaxAmtText = $('#MAXAMT')[0]
  hInvNumText = $('#PAYMENTREQUEST_0_INVNUM')[0]
  hItem0AmtText = $('#L_PAYMENTREQUEST_0_AMT0')[0]
  hItem0QtyText = $('#L_PAYMENTREQUEST_0_QTY0')[0]
  hItem1AmtText = $('#L_PAYMENTREQUEST_0_AMT1')[0]
  hItem1QtyText = $('#L_PAYMENTREQUEST_0_QTY1')[0]
  hItem1QtyText = $('#L_PAYMENTREQUEST_0_QTY1')[0]
  hShippingText = $('#PAYMENTREQUEST_0_SHIPPINGAMT')[0]
  hHandlingText = $('#PAYMENTREQUEST_0_HANDLINGAMT')[0]
  hTaxText = $('#PAYMENTREQUEST_0_TAXAMT')[0]
  hShippingAddress = $('#shippingAddressContainer')[0]

  var sInvNum = '00000' + Math.floor((Math.random() * 9999999) + 1);
  hInvNumText.value = 'O-' + sInvNum.substr(sInvNum.length-7);

  updateTotal()
});

function showHideShippingAddress(show) {
  hShippingAddress.style.display = show ? "block" : 'none'
}

function updateTotal() {
  fTotalAmtText = Number(hItem0AmtText.value) * Number(hItem0QtyText.value)
                  + Number(hItem1AmtText.value) * Number(hItem1QtyText.value)
                  + Number(hTaxText.value) + Number(hShippingText.value) + Number(hHandlingText.value)

  hTotalAmtText.value = hMaxAmtText.value = fTotalAmtText.toFixed(2);
}
