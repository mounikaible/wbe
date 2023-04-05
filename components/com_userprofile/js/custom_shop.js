// paypal credit/debit

function initPayPalButton() {
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'gold',
          layout: 'vertical',
          label: 'paypal',
        },

        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"amount":{"currency_code":"USD","value":$joomla('input[name="amount"]').val()}}]
          });
        },

        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
            $joomla(".page_loader").show();
            // Full available details
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            txnId = orderData.purchase_units[0].payments.captures[0].id;
            // Show a success message within this page, e.g.
            const element = document.getElementById('paypal-button-container');
            element.innerHTML = '';
            
             ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&user="+$joomla('input[name="user"]').val()+"&txtSpecialIn="+$joomla('textarea[name="txtSpecialIn"]').val();
                  
                    $joomla.ajax({
                   			url: ajaxurl,
                   			data: { "paymentgatewayshopflag":1,"amount":$joomla('input[name="amountStr"]').val(),"hiddentxtTaxes":$joomla('input[name="hiddentxtTaxes"]').val(),"hiddentxtShippCharges":$joomla('input[name="hiddentxtShippCharges"]').val(),"hiddenItemIds":$joomla('input[name="hiddenItemIds"]').val(),"hiddenItemQuantity":$joomla('input[name="hiddenItemQuantity"]').val(),"hiddenItemSupplierId":$joomla('input[name="hiddenItemSupplierId"]').val(),"paymentmethod":"PPD","paymentgateway":"Paypal","TxnId":txnId,"invoiceStr":$joomla('input[name="paypalinvoice"]').val()},
                   			dataType:"text",
                   			type: "get",
                            beforeSend: function() {
                               
                            },
                            success: function(res){
                                response = res.split(":");
                                if(response[0] == 1){
                                        
                                        window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&res="+response[1];
                                }else{
                                    $joomla(".page_loader").hide();
                                    $joomla(".paygaterrormsg").html(res);
                                }
                            }
                       });
            
           

            // Or go to another URL:  actions.redirect('thank_you.html');
            
          });
        },

        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');
    }