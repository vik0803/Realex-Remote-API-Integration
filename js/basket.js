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
  hShippingAddress = $('#shippingAddressContainer')[0]
  hTotalAmtText = $('#PAYMENTREQUEST_0_AMT')[0]
  hItem0AmtText = $('#L_PAYMENTREQUEST_0_AMT0')[0]
  hItem0QtyText = $('#L_PAYMENTREQUEST_0_QTY0')[0]
  hItem1AmtText = $('#L_PAYMENTREQUEST_0_AMT1')[0]
  hItem1QtyText = $('#L_PAYMENTREQUEST_0_QTY1')[0]
  hItem1QtyText = $('#L_PAYMENTREQUEST_0_QTY1')[0]
  hShippingText = $('#PAYMENTREQUEST_0_SHIPPINGAMT')[0]
  hNoShippingOption = $('#NOSHIPPING')[0]

  updateTotal()
});

function showHideShippingAddress(show) {
  hShippingAddress.style.display = show ? "block" : 'none'
}

function updateTotal() {
  fTotalAmtText = Number(hItem0AmtText.value) * Number(hItem0QtyText.value)
                  + Number(hItem1AmtText.value) * Number(hItem1QtyText.value)

  hTotalAmtText.value = hNoShippingOption.checked ? fTotalAmtText.toFixed(2) : (fTotalAmtText + Number(hShippingText.value)).toFixed(2)
}
