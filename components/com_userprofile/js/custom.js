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
            
            var feitem=[];
               $joomla("input[name='invFile[]']").each( function () {
                   var tem=$joomla(this).attr('id');
                   if($joomla(this).val()){
                       var file_data = $joomla(this).prop('files')[0];   
                       var form_data = new FormData();                  
                       form_data.append('file', file_data);
                       $joomla.ajax({
                       	url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&uploadflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
                   	    dataType: 'text',  // what to expect back from the PHP script, if anything
                           cache: false,
                           contentType: false,
                           processData: false,
                           data: form_data,                         
                           type: 'post',
                       	beforeSend: function() {
                             $joomla(".pagshipup").show();
                             $joomla('.pagshipdown').hide();
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "button");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", true);
                           },success: function(data){
                             $joomla('#ord_ship #step3 .btn-primary').attr("type", "submit");
                             $joomla('#ord_ship #step3 .btn-primary').attr("disabled", false);
                             $joomla(".pagshipup").hide();
                             $joomla('.pagshipdown').show();
                             feitem.push(tem+"-"+data);
                             
                             /** Debug **/
                             console.log(feitem);
                             
                             $joomla('input[name=paypalinvoice]').val(feitem); 
                           }
                       });
                   }
               }); 
                   var articlestrs=[];
               $joomla.each($joomla("input[name='articleStr[]']"), function(){
                   articlestrs.push($joomla(this).val());
               });    
               var pricestrs=[];
               $joomla.each($joomla("input[name='priceStr[]']"), function(){
                   pricestrs.push($joomla(this).val());
               }); 
               
               if($joomla('input[name="cc"]:checked').val() == "Paypal"){
               
                   setTimeout(function () {
                        ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val()+"&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&invidkStr="+$joomla('input[name="invidkStr"]').val()+"&qtyStr="+$joomla('input[name="qtyStr"]').val()+"&wherhourecStr="+$joomla('input[name="wherhourecStr"]').val()+"&user="+$joomla('input[name="user"]').val()+"&txtspecialinsStr="+$joomla('input[name="txtspecialinsStr"]').val()+"&cc=PPD&paymentgateway=Paypal&shipservtStr="+$joomla('input[name="shipservtStr"]').val()+"&consignidStr="+$joomla('input[name="consignidStr"]').val()+"&invf=&filenameStr=&articleStr="+articlestrs+"&priceStr="+pricestrs;
                        $joomla.ajax({
                       			url: ajaxurl,
                       			data: { "paymentgatewayflag":1,"ratetypeStr": $joomla('input[name="ratetypeStr"]').val(),"Conveniencefees":$joomla('input[name="Conveniencefees"]').val(),"addSerStr":$joomla('input[name="addSerStr"]').val(),"addSerCostStr":$joomla('input[name="addSerCostStr"]').val(),"companyId":$joomla('input[name="companyId"]').val(),"insuranceCost":$joomla('input[name="insuranceCost"]').val(),"extAddSer":$joomla('input[name="extAddSer"]').val(),"paypalinvoice":$joomla('input[name="paypalinvoice"]').val(),"length":$joomla("#lengthStr").val(),"width":$joomla("#widthStr").val(),"height":$joomla("#heightStr").val(),"grosswt":$joomla("#weightStr").val(),"volume":$joomla("#volStr").val(),"volumetwt":$joomla("#volmetStr").val(),"totalDecVal":$joomla('#totalDecVal').val(),"shipmentCost":$joomla('input[name=shipmentCost]').val(),"couponCodeStr":$joomla('#couponCodeStr').val(),"couponDiscAmt":$joomla('input[name=couponDiscAmt]').val(),"TxnId":txnId,"repackLblStr":$joomla('#repackLblStr').val()},
                       			dataType:"text",
                       			type: "get",
                                beforeSend: function() {
                                   $joomla(".page_loader").show();
                                },
                                success: function(data){
                                    //console.log(data);
                                    res = data.split(":");
                                    if(res[0] == 1){
                                    window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&res="+res[1];
                                    }else{
                                        $joomla(".page_loader").hide();
                                        $joomla(".paygaterrormsg").html(data);
                                    }
                                }
                           });
                           
                   }, 5000); 
               
               }
            

            // Or go to another URL:  actions.redirect('thank_you.html');
            
          });
        },

        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');
    }
    
    //initPayPalButton();
    
    // end
    
    
    // Apply couponcodes
    
    $joomla(document).on('click','.applyCode',function() {
        var disAmt = parseFloat($joomla(this).attr("data-val"));
        $joomla(".applyCode").hide();
        $joomla(".cupn-serch").hide();
        $joomla(this).parent().show();
        $joomla("#couponCodeStr").val($joomla(this).attr("data-code"));
        $joomla("#couponDiscAmt").val($joomla(this).attr("data-val"));
        $joomla("#promoDiscountStr").html(disAmt.toFixed(2));
        AfterDiscTotAmt = parseFloat($joomla('input[name="shipcostStr"]').val())-parseFloat($joomla(this).attr("data-val"));
        $joomla('input[name="shipcostStr"]').val(AfterDiscTotAmt.toFixed(2));
        $joomla('input[name=amtStr]').val(AfterDiscTotAmt.toFixed(2));
        $joomla('#shipmethodtotalStr').text(AfterDiscTotAmt.toFixed(2));
        $joomla('#shipmethodStrValuetwo').text(AfterDiscTotAmt.toFixed(2));
        $joomla('input[name=amount]').val(AfterDiscTotAmt.toFixed(2));
        $joomla('input[name=cc]').prop("checked",false);
        $joomla(this).parent().next().show();
        $joomla(this).parent().hide();
        $joomla(".paypalCreditDebit").hide(); 
        $joomla(".dvPaymentInformation").hide();
        
    });
    
    // End
    
    // Remove couponcodes
    
    $joomla(document).on('click','.removeCode',function() {
        $joomla(".applyCode").parent().show();
        $joomla("#couponCodeStr").val("");
        $joomla("#couponDiscAmt").val("");
        $joomla("#promoDiscountStr").html('0.00');
        BeforeDiscAmt = parseFloat($joomla('input[name="shipcostStr"]').val())+parseFloat($joomla(this).attr("data-val"));
        $joomla('input[name="shipcostStr"]').val(BeforeDiscAmt.toFixed(2));
        $joomla('input[name=amtStr]').val(BeforeDiscAmt.toFixed(2));
        $joomla('#shipmethodtotalStr').text(BeforeDiscAmt.toFixed(2));
        $joomla('#shipmethodStrValuetwo').text(BeforeDiscAmt.toFixed(2));
        $joomla('input[name=amount]').val(BeforeDiscAmt.toFixed(2));
        $joomla('input[name=cc]').prop("checked",false);
        $joomla(".removeCode").parent().prev().show();
        $joomla(".removeCode").parent().prev().find("a").show();
        $joomla(".removeCode").parent().hide();
        $joomla(".paypalCreditDebit").hide(); 
        $joomla(".dvPaymentInformation").hide();
    });
    
    // End
    
    // item details 
    
    
    $joomla(document).on('click','.wrhsItemDetails',function() {
        $joomla(".itemDetBody").html("");
        $joomla("#itemDetailsModal .modal-title strong").html("");
        $joomla("#itemDetailsModal").modal("show");
        var itemDetails = $joomla(this).attr("data-val");
        var itemDetailsArr = itemDetails.split(",");
        $joomla("#itemDetailsModal .modal-title strong").html("Item List");
        var itemsHtmlContent = "<table class='table table-bordered theme_table' ><tr><th>S.No</th><th>Item Name</th></tr>";
        
        for(i=0 ; i<itemDetailsArr.length ;i++ ){
            itemsHtmlContent += '<tr><td>'+(i+1)+'</td>';
            itemsHtmlContent += '<td>'+itemDetailsArr[i]+'</td></tr>';
            
        }
        
        itemsHtmlContent += "</table>";
        $joomla(".itemDetBody").html(itemsHtmlContent);
        
    });
    
    // tracking details 
    
    
    $joomla(document).on('click','.trackIdDetails',function() {
        $joomla("#itemDetailsModal .modal-title strong").html("");
        $joomla(".itemDetBody").html("");
        $joomla("#itemDetailsModal").modal("show");
        var itemDetails = $joomla(this).attr("data-val");
        var itemDetailsArr = itemDetails.split(",");
        $joomla("#itemDetailsModal .modal-title strong").html("Tracking ID List");
        var itemsHtmlContent = "<table class='table table-bordered theme_table' ><tr><th>S.No</th><th>Tracking Id</th></tr>";
        
        for(i=0 ; i<itemDetailsArr.length ;i++ ){
            itemsHtmlContent += '<tr><td>'+(i+1)+'</td>';
            itemsHtmlContent += '<td>'+itemDetailsArr[i]+'</td></tr>';
            
        }
        
        itemsHtmlContent += "</table>";
        $joomla(".itemDetBody").html(itemsHtmlContent);
        
    });
    
    // merchant name details 
    
    $joomla(document).on('click','.merchNameDetails',function() {
        $joomla("#itemDetailsModal .modal-title strong").html("");
        $joomla(".itemDetBody").html("");
        $joomla("#itemDetailsModal").modal("show");
        var itemDetails = $joomla(this).attr("data-val");
        var itemDetailsArr = itemDetails.split(",");
        $joomla("#itemDetailsModal .modal-title strong").html("Merchant Name List");
        var itemsHtmlContent = "<table class='table table-bordered theme_table' ><tr><th>S.No</th><th>Merchant Name</th></tr>";
        
        for(i=0 ; i<itemDetailsArr.length ;i++ ){
            itemsHtmlContent += '<tr><td>'+(i+1)+'</td>';
            itemsHtmlContent += '<td>'+itemDetailsArr[i]+'</td></tr>';
            
        }
        
        itemsHtmlContent += "</table>";
        $joomla(".itemDetBody").html(itemsHtmlContent);
        
    });
    
    if(typeof localStorage.getItem("repackConsolidStatus") != 'undefined'){
        
        if(localStorage.getItem("repackConsolidStatus") != null){
             $joomla(".alertSection").show();
             $joomla(".alertSection strong").html(localStorage.getItem("repackConsolidStatus"));
             setTimeout(function(){
                 $joomla("#success-alert").slideUp(500);
                 localStorage.removeItem("repackConsolidStatus");
                  $joomla(".alertSection").hide();
             }, 10000);
             
        }
    }
    if(typeof localStorage.getItem("repackConsolidFail") != 'undefined'){
        
        if(localStorage.getItem("repackConsolidFail") != null){
             $joomla(".alertfailSection").show();
             $joomla(".alertfailSection strong").html(localStorage.getItem("repackConsolidFail"));
             setTimeout(function(){
                 localStorage.removeItem("repackConsolidFail");
                 $joomla(".alertfailSection").slideUp(500);
             }, 10000);
             
        }
    }
    
    
    if(typeof localStorage.getItem("unpackSuccess") != 'undefined'){
        if(localStorage.getItem("unpackSuccess") != null){
             $joomla(".alertSection").show();
             $joomla(".alertSection strong").html(localStorage.getItem("unpackSuccess"));
             setTimeout(function(){
                 $joomla("#success-alert").slideUp(500);
                 localStorage.removeItem("unpackSuccess");
                  $joomla(".alertSection").hide();
             }, 10000);
        }  
    }

    if(typeof localStorage.getItem("updateInv") != 'undefined'){
        if(localStorage.getItem("updateInv") != null){
            $joomla(".alertSection").show();
            $joomla(".alertSection strong").html(localStorage.getItem("updateInv"));
            setTimeout(function(){
                $joomla("#success-alert").slideUp(500);
                localStorage.removeItem("updateInv");
                 $joomla(".alertSection").hide();
            }, 10000);
       }  
    }
    
    function displayWarningAlert(alertMsg){
        $joomla(".alertfailSection strong").html(alertMsg);
        $joomla(".alertfailSection").show();
        setTimeout(function(){
                 $joomla(".alertfailSection").slideUp(500);
             }, 10000);
        
    }
    
    // search coupon and apply
    
    
    
     // merchant name details 
    
    $joomla(document).on('click','.couponSubmit',function(e) {
        var couponCode = $joomla("#custCouponCode").val();
        $joomla.ajax({
   	        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&jpath=<?php echo urlencode(JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
   			data: { "getcouponflag":1,"user":$joomla("input[name='user']").val(),"amount":$joomla("input[name='amount']").val(),"volmetStr":$joomla("input[name='volmetStr']").val(),"volStr":$joomla("input[name='volStr']").val(),"qtyStr":$joomla("input[name='qtyStr']").val(),"wtStr":$joomla("input[name='weightStr']").val(),"shippingCost":$joomla("input[name='shipmentCost']").val(),"couponCode":couponCode},
   			dataType:"html",
   			type: "get",
   			beforeSend: function() {
                       $joomla(".page_loader").show();
                   },    
   			success: function(data){
   			    $joomla(".page_loader").hide();
   			    res=data.split(":");
   			    if(parseFloat(res[1])){
       			    disAmt = parseFloat(res[1]);
                    $joomla("#couponCodeStr").val(couponCode);
                    $joomla("#couponDiscAmt").val(disAmt);
                    $joomla("#promoDiscountStr").html(disAmt.toFixed(2));
                    AfterDiscTotAmt = parseFloat($joomla('input[name="shipcostStr"]').val())-parseFloat(res[1]);
                    $joomla('input[name="shipcostStr"]').val(AfterDiscTotAmt.toFixed(2));
                    $joomla('input[name=amtStr]').val(AfterDiscTotAmt.toFixed(2));
                    $joomla('#shipmethodtotalStr').text(AfterDiscTotAmt.toFixed(2));
                    $joomla('#shipmethodStrValuetwo').text(AfterDiscTotAmt.toFixed(2));
                    $joomla('input[name=amount]').val(AfterDiscTotAmt.toFixed(2));
                    $joomla('input[name=cc]').prop("checked",false);
                    couponStr = '<div class="cupn-cde-lst-bg-gray"><h4><strong>'+couponCode+'</strong></h4><p>You can save $'+disAmt+'<a class="removeCouponCode" style="cursor: pointer;" data-code="'+couponCode+'" data-val="'+disAmt+'">Applied <i class="fa fa-times-circle" aria-hidden="true"></i> </a></p></div>';
                     $joomla('.cupn-cdes').show();
                     $joomla('.cupn-cde').hide();
                     $joomla('.cupn-cdes').html(couponStr);
                     $joomla("#custCouponCode").val("");
                     $joomla(".couponErrorMsg").html("");
                     $joomla(".paypalCreditDebit").hide(); 
                     $joomla(".dvPaymentInformation").hide();
   			    }else{
   			        $joomla(".page_loader").hide();
   			        $joomla(".couponErrorMsg").html("Invalid Coupon");
   			         
   			    }
                 
                  
   			}
   		}); 
    });
    
    $joomla(document).on('click','.removeCouponCode',function() {
        $joomla('.cupn-cde').show();
        $joomla('.cupn-cde a').show();
         $joomla('.cupn-cde p').hide();
        $joomla("#couponCodeStr").val("");
        $joomla("#couponDiscAmt").val("");
        $joomla("#promoDiscountStr").html('0.00');
        BeforeDiscAmt = parseFloat($joomla('input[name="shipcostStr"]').val())+parseFloat($joomla(this).attr("data-val"));
        $joomla('input[name="shipcostStr"]').val(BeforeDiscAmt.toFixed(2));
        $joomla('input[name=amtStr]').val(BeforeDiscAmt.toFixed(2));
        $joomla('#shipmethodtotalStr').text(BeforeDiscAmt.toFixed(2));
        $joomla('#shipmethodStrValuetwo').text(BeforeDiscAmt.toFixed(2));
        $joomla('input[name=amount]').val(BeforeDiscAmt.toFixed(2));
        $joomla('input[name=cc]').prop("checked",false);
        $joomla('.cupn-cdes').html("");
    });
    
    //warehoue collapse data
$joomla(document).on('click','.panel-title',function() {
    $joomla('.panel-title span').attr('class','expandPlus');
    // $joomla(this).find(".expand").html();
    var expn =   $joomla(this).find("#expand").attr("class");
    // alert(expn);
    if(expn =='expandPlus'){
        $joomla(this).find("#expand").attr('class','expandMinus');
    }
   else{
    $joomla(this).find("#expand").attr('class','expandPlus');
   }
    $joomla(".panel-collapse").eq(0).addClass("collapse");
    var numItems = $joomla(".panel-title").length;
    if(numItems > 0){
        $joomla(".collapse").hide();
        $joomla(this).parent().next().toggle();
    }else{
       $joomla(this).parent().next().toggle();
    }
});


// Update Invoice 

$joomla(document).on('click','#update_invoice_submit,.update_invoice_submit_ktable',function() {
    $joomla(".page_loader").show();
    feitem=[];
    file_data_len=0;
    trggerClass = $joomla(this).attr("class");
    trggerId = $joomla(this).attr("id");
    console.log(trggerClass +"##" +trggerId);
    if(trggerClass == "update_invoice_submit_ktable"){
        file_data_len = $joomla(this).prev().prev().prop('files').length;
    }else if(trggerId == "update_invoice_submit" ){
        file_data_len = $joomla("input[name='update_invoice[]'").prop('files').length;
    }
   
 if(file_data_len > 0){
     
     for(i=0;i<file_data_len;i++){
        var file_data = "";
        if(trggerClass == "update_invoice_submit_ktable"){
            file_data = $joomla(this).prev().prev().prop('files')[i];
            $joomla(this).prev().html("");
        }else if(trggerId == "update_invoice_submit" ){
            file_data = $joomla("input[name='update_invoice[]").prop('files')[i];  
        }
        
      
        
        var form_data = new FormData(); 
        form_data.append('file', file_data);
        $joomla.ajax({
            url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&uploadflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data, 
            async: false,                        
            type: 'post',
            beforeSend: function() {
            },
            success: function(data){
               feitem.push(data);
            }
        });
    }

    // update invoice

    $joomla.ajax({
        url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&updateinvoiceflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        dataType: 'html', 
        data: {"invData":feitem,"itemIdk":$joomla(this).attr("data-idk")},                         
        type: 'get',
        context: this,
        beforeSend: function() {
            $joomla(".page_loader").show();
        },
        success: function(data){
            var resArr = data.split(":");
            if(resArr[0] == 1){
                
                if(trggerClass == "update_invoice_submit_ktable"){
                    $joomla(".page_loader").hide();
                    var itemsList = '';
                    for(i=0;i<feitem.length;i++){
                        itemsList += '<a href="'+baseurl+'/media/com_userprofile/'+feitem[i]+'" target="_blank">('+viewInvoice+')</a>';
                    }
                    $joomla(this).parents().eq(2).find(".sfile").html(itemsList);
                    $joomla(this).parents().eq(2).find("input").val("");
                    alert(resArr[1]);
                }else if(trggerId == "update_invoice_submit" ){
                    localStorage.setItem("updateInv",resArr[1]);
                    window.location.reload();
                }
            }
        }
    });
     
 }else{
    $joomla(".page_loader").hide();
     alert("Please choose a file");
 }   
    

});

    
    
    

    
