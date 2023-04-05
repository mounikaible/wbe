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
             
          
                        ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val()+"&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&invidkStr="+''+"&qtyStr="+''+"&wherhourecStr="+$joomla('input[name="bill_form_nostr"]').val()+"&user="+user+"&txtspecialinsStr="+''+"&cc=PayForCOD&paymentgateway=Paypal&shipservtStr="+$joomla('input[name="Id_Servstr"]').val()+"&consignidStr="+''+"&invf="+''+"&filenameStr="+''+"&articleStr="+''+"&priceStr="+'';
                        $joomla.ajax({
                       			url: ajaxurl,
                       			data: { "paymentgatewayflag":1,"ratetypeStr": "","Conveniencefees":$joomla('input[name="Conveniencefees"]').val(),"addSerStr":"","addSerCostStr":"","companyId":$joomla('input[name="companyId"]').val(),"insuranceCost":"","extAddSer":"","paypalinvoice":"","page":"cod","inhouseNo":$joomla('input[name="InHouseNostr"]').val(),"invoice":$joomla('input[name="InvoiceNo"]').val(),"InhouseIdkstr":$joomla('input[name="InhouseIdkstr"]').val(),"TxnId":txnId},
                       			dataType:"text",
                       			type: "get",
                                beforeSend: function() {
                                   
                                },
                                success: function(data){
                                    console.log(data);
                                    $joomla(".page_loader").hide();
                                    res = data.split(":");
                                    if(res[0] == 1){
                                    window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&page=cod&invoice="+$joomla('input[name="InvoiceNo"]').val()+"&res="+res[1];
                                    }else{
                                        $joomla(".page_loader").hide();
                                        $joomla(".paygaterrormsg").html(data);
                                        
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