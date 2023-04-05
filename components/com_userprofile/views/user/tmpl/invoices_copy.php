<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access

require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
$document = JFactory::getDocument();
$document->setTitle("Invoices in Boxon Pobox Software");
defined('_JEXEC') or die;
$config = JFactory::getConfig();
$backend_url=$config->get('backend_url');

$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$CompanyId = Controlbox::getCompanyId();
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
// get labels

    $lang=$session->get('lang_sel');
    $res=Controlbox::getlabels($lang);
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    }

    $domainDetails = ModProjectrequestformHelper::getDomainDetails();
   $CompanyId = $domainDetails[0]->CompanyId;
   $companyName = $domainDetails[0]->CompanyName;
   $domainEmail = $domainDetails[0]->PrimaryEmail;
   $domainName =  $domainDetails[0]->Domain;
   
   foreach($domainDetails[0]->PaymentGateways as $PaymentGateways){
       if($PaymentGateways->PaymentGatewayName == "Paypal")
            $PaypalEmail = $PaymentGateways->Email;
            $AccountType = strtolower($PaymentGateways->AccountType);
            $ClientId = $PaymentGateways->UserId;
   }

?>

<?php include 'dasboard_navigation.php' ?>
<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/styles.css">
<link rel="stylesheet" href="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/css/demo.css">
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $ClientId; ?>&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>

<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){
    
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    
    clientId = "<?php echo $ClientId; ?>";
    user='<?php echo $user; ?>';
    
    $joomla('a.btn-primary').click(function(e){
        e.preventDefault();
        var hostname=window.location.hostname;
        hostArr=hostname.split('.');
       
        service_url = "<?php echo $backend_url; ?>";
        
        //$joomla('.form-group').html('<div id="loading-image" ><img src="/components/com_userprofile/images/loader.gif"></div>');
        console.log($joomla(this).data('id'));
        
        var url=service_url+'/ASPX/Tx_Invoice_Receipt.aspx?bid='+$joomla(this).data('id')+'&type=Invoice&companyid=<?php echo $CompanyId; ?>';
        console.log("Url:::"+url);
        
        window.open(url, "_blank");
        
        //$joomla('.form-group').html('<iframe src="'+url+'" width="700px" height="500px"></iframe>');
    });
});


$joomla(document).on('click','.ship',function(){
       
        $joomla(".dvPaymentInformation").hide();
        $joomla('.paymentopt').css("display","none");
        $joomla('input[name="cc"]').prop("checked",false);
        $joomla('input[name="prepaidMethod"]').prop("checked",false);
        $joomla(".prepaid_method_sec").hide();
        

    var looping=$joomla(this).data('id');
    var loops=looping.split(":");

    $joomla('#ord_ship').show();
    $joomla('#bill_form_no').html(loops[0]);
    $joomla('#bill_form_nostr').val(loops[0]);
    $joomla('#Id_Serv').html(loops[1]);
    $joomla('#Id_Servstr').val(loops[1]);
    var amtPaid = loops[3];
    $joomla('#TotalAmountPaid').html(Number(amtPaid).toFixed(2));
    $joomla('#TotalAmountPaidstr').val(loops[3]);
    $joomla('#InHouseNo').html(loops[4]);
    $joomla('#InHouseNostr').val(loops[4]);
    $joomla('#TotalCost').html(loops[5]);
    $joomla('#TotalCoststr').val(loops[5]);
    $joomla('#AdditionalCost').html(loops[6]);
    $joomla('#AdditionalCoststr').val(loops[6]);
    $joomla('.TotalFinalCost').val(loops[7]);
    var finalCost = loops[7];
    $joomla('#TotalFinalCoststr').val(loops[7]);
    $joomla('#TotalFinalCost').html(Number(finalCost).toFixed(2));
    $joomla('#Discount').html(loops[8]);
    $joomla('#Discountstr').val(loops[8]);
    $joomla('#InhouseIdk').html(loops[9]);
    $joomla('#InhouseIdkstr').val(loops[9]);
    $joomla('#InvoiceNo').val(loops[10]);
    $joomla('#InvoiceType').val(loops[11]);
    
    var totamount = parseFloat(loops[7])-parseFloat(loops[3]);
    
    // due amt and total amt code
    
    $joomla('#amtStr').val(totamount);
    $joomla('input[name="amount"]').val(totamount);
    $joomla('#DueAmount').html(totamount.toFixed(2));
    
  //  end
    
      
      $joomla("input[name='return']").val('<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=response&page=cod&invoice='+loops[10]+'&pay=<?php echo base64_encode(date("m/d/0Y"));?>');
  
    
  });  
  
  
  $joomla(document).on('click','input[name="cc"]',function(){
        
        $joomla("label.error").html("");

       // convenience fees 
       
         $joomla('.paymentopt').css("display","none");
          $joomla.ajax({
              url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="TotalFinalCoststr"]').val()+"&convflag=1&gateway="+$joomla(this).val()+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
        data: { "shippmentid": 1 },
        dataType:"html",
        type: "get",
        beforeSend: function() {
                $joomla(".page_loader").show();
                $joomla('#ord_ship #step2 .btn-primary').attr("disabled", true);
            },success: function(data){
                slot=1
                var sg=data;
                sg=sg.split(":");
                $joomla('input[name="Conveniencefees"]').val(sg[1]);
                var totFinalCost = sg[0];
                $joomla('#TotalFinalCost').text(Number(totFinalCost).toFixed(2));
                $joomla('#amtStr').val(sg[0]);
                $joomla("input[name='amount']").val(sg[0]);
                $joomla(".page_loader").hide();
                $joomla('#ord_ship #step2 .btn-primary').attr("disabled", false);
              
            }
          });
          
          // end
          
          if($joomla(this).val() == "authorize.net"){
              $joomla(".paypalCreditDebit").hide();
              $joomla(".final_btns").show();
              $joomla(".dvPaymentInformation input").val("");
              $joomla(".dvPaymentInformation select").val("");
              $joomla(".paygaterrormsg").html("");
              $joomla('#userprofileFormFive').attr('action','<?php echo JURI::base(); ?>payment.php');
              $joomla(".final_ship").removeAttr("onclick");
              $joomla(".dvPaymentInformation").show();
          }else if($joomla(this).val() == "Stripe"){
              $joomla(".paypalCreditDebit").hide();
              $joomla(".final_btns").show();
              $joomla(".dvPaymentInformation input").val("");
              $joomla(".dvPaymentInformation select").val("");
              $joomla(".paygaterrormsg").html("");
              $joomla(".final_ship").removeAttr("onclick");
              $joomla(".dvPaymentInformation").show();
          }else if($joomla(this).val() == "Paypal"){
            //   $joomla(".final_ship").attr("onclick","onGetCardNonce(event)");
            //   $joomla(".dvPaymentInformation").hide();
                $joomla(".paypalCreditDebit").show();
                $joomla(".final_btns").hide();
                $joomla(".dvPaymentInformation").hide();
          }
          
          
  });

  function onGetCardNonce(event) {
            event.preventDefault();
            var AccountType = "<?php echo $AccountType; ?>";
            var paymentGateway = $joomla('input[name="cc"]:checked').val();
       
            var user='<?php echo $user;?>';
            $joomla('input[name="item_name"]').val($joomla('input[name="bill_form_nostr"]').val()+":"+$joomla('input[name="Id_Servstr"]').val()+":"+$joomla('[name="TotalAmountPaidstr"]').val()+":"+$joomla('input[name="InHouseNostr"]').val()+":"+$joomla('input[name="TotalCoststr"]').val()+":"+$joomla('input[name="AdditionalCoststr"]').val()+":"+$joomla('input[name="TotalFinalCoststr"]').val()+":"+$joomla('input[name="Discountstr"]').val()+":"+$joomla('input[name="InhouseIdkstr"]').val()+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+$joomla('#InvoiceNo').val()+":"+$joomla('#InvoiceType').val()+":"+user);
            $joomla('input[name="item_number"]').val(1);
            $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
           
       if(paymentGateway == 'Paypal'){
          
            $joomla(".dvPaymentInformation").hide();
           
            if(AccountType == "sandbox"){
                $joomla('#userprofileFormFive').attr('action','https://www.sandbox.paypal.com/cgi-bin/webscr'); 
            }else{
                $joomla('#userprofileFormFive').attr('action','https://www.paypal.com/cgi-bin/webscr'); 
            }
            
            document.getElementById('userprofileFormFive').submit();
            
       }
       
    }

     $joomla(function() {
 
        
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $joomla("form[name='userprofileFormFive']").validate({
        
        // Specify validation rules
        rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          
          cardnumberStr: {
            required: true,
            minlength:15
          },
          txtccnumberStr: {
            required: true
          },
          MonthDropDownListStr: {
            required: true,
            //currentdates:true
          },
          YearDropDownListStr: {
            required: true,
           // currentdates:true
          }
        },
        // Specify validation error messages
        messages: {
                            cardnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_NUMBER_ERROR');?>"},
                            MonthDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_MONTH_ERROR');?>"},
                            YearDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_YEAR_ERROR');?>"},
                            txtccnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_CVV_ERROR');?>"}
          
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
       	
    		    $joomla(".page_loader").show();
                var user='<?php echo $user;?>';
                if($joomla('input[name="cc"]:checked').val() == "Stripe"){
                    
                    setTimeout(function () {
                        ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val()+"&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&invidkStr=&qtyStr="+''+"&wherhourecStr="+$joomla('input[name="bill_form_nostr"]').val()+"&user="+user+"&txtspecialinsStr="+''+"&cc=PayForCOD&paymentgateway=Stripe&shipservtStr="+$joomla('input[name="Id_Servstr"]').val()+"&consignidStr="+''+"&invf="+''+"&filenameStr="+''+"&articleStr="+''+"&priceStr="+'';
                        $joomla.ajax({
                       			url: ajaxurl,
                       			data: { "paymentgatewayflag":1,"ratetypeStr": "","Conveniencefees":$joomla('input[name="Conveniencefees"]').val(),"addSerStr":"","addSerCostStr":"","companyId":$joomla('input[name="companyId"]').val(),"insuranceCost":"","extAddSer":"","paypalinvoice":"","page":"cod","inhouseNo":$joomla('input[name="InHouseNostr"]').val(),"invoice":$joomla('input[name="InvoiceNo"]').val(),"InhouseIdkstr":$joomla('input[name="InhouseIdkstr"]').val(),"InvoiceType":$joomla('input[name="InvoiceType"]').val()},
                       			dataType:"text",
                       			type: "get",
                                beforeSend: function() {
                                   
                                },
                                success: function(data){
                                    res = data.split(":");
                                    if(res[0] == 1){
                                    window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&page=cod&invoice="+$joomla('input[name="InvoiceNo"]').val()+"&res="+res[1];
                                    }else{
                                        $joomla(".page_loader").hide();
                                        $joomla(".paygaterrormsg").html(data);
                                    }
                                }
                           });
                           
                   }, 5000); 
                    
                }else if($joomla('input[name="cc"]:checked').val() == "authorize.net"){
                    
                        var paymentGateway = $joomla('input[name="cc"]:checked').val();
                        var user='<?php echo $user;?>';
                        $joomla('input[name="item_name"]').val($joomla('input[name="bill_form_nostr"]').val()+":"+$joomla('input[name="Id_Servstr"]').val()+":"+$joomla('[name="TotalAmountPaidstr"]').val()+":"+$joomla('input[name="InHouseNostr"]').val()+":"+$joomla('input[name="TotalCoststr"]').val()+":"+$joomla('input[name="AdditionalCoststr"]').val()+":"+$joomla('input[name="TotalFinalCoststr"]').val()+":"+$joomla('input[name="Discountstr"]').val()+":"+$joomla('input[name="InhouseIdkstr"]').val()+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+user);
                        $joomla('input[name="item_number"]').val(1);
                        $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
                    
                    document.getElementById('userprofileFormFive').submit();
                    
                }
           
          
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "cc") {
              //error.insertAfter(#cc);
              error.appendTo(element.parent('div').after());            
                
            }
            else if (element.attr("name") == "ccStr") {
              //error.insertAfter(#cc);
              error.appendTo(element.parent('div').after());            
                
            }
            else {
              error.insertAfter(element);
            }
        }

        
        });   
      
$joomla(function() {
    
$joomla(".expand_all").on('click',function(){
    inhno = $joomla(this).attr("data-id");
    
        if($joomla(this).html()=='-'){
                $joomla("tbody tr .inhsrec").each(function(){
                        inhnoLoop = $joomla(this).attr("data-id");
                    if(inhno == inhnoLoop){
                        $joomla(this).closest("tr").hide();
                    }
                });
            $joomla(this).html('+');
         }else{
             
                $joomla("tbody tr .inhsrec").each(function(){
                        inhnoLoop = $joomla(this).attr("data-id");
                    if(inhno == inhnoLoop){
                        $joomla(this).closest("tr").show();
                    }
                });
             
             $joomla(this).html('-');
         }    
});
 
        
 // Initialize form validation on the registration form.
 // It has the name attribute "registration"
 $joomla("form[name='userprofileFormFive']").validate({
 // Specify validation rules
 rules: {
   // The key name on the left side is the name attribute
   // of an input field. Validation rules are defined
   // on the right side
   
   cardnumberStr: {
     required: true,
     minlength:15
   },
   txtccnumberStr: {
     required: true
   },
   MonthDropDownListStr: {
     required: true,
     //currentdates:true
   },
   YearDropDownListStr: {
     required: true,
    // currentdates:true
   }
 },
 // Specify validation error messages
 messages: {
                     cardnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_NUMBER_ERROR');?>"},
                     MonthDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_MONTH_ERROR');?>"},
                     YearDropDownListStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_EXP_YEAR_ERROR');?>"},
                     txtccnumberStr: {required:"<?php echo Jtext::_('COM_USERPROFILE_SHIP_CARD_CVV_ERROR');?>"}
   
 },
 // Make sure the form is submitted to the destination defined
 // in the "action" attribute of the form when valid
 submitHandler: function(form) {
  
     $joomla(".page_loader").show();
         var user='<?php echo $user;?>';
         if($joomla('input[name="cc"]:checked').val() == "Stripe"){
             
             setTimeout(function () {
                 ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val()+"&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&invidkStr="+''+"&qtyStr="+''+"&wherhourecStr="+$joomla('input[name="bill_form_nostr"]').val()+"&user="+user+"&txtspecialinsStr="+''+"&cc=PayForCOD&paymentgateway=Stripe&shipservtStr="+$joomla('input[name="Id_Servstr"]').val()+"&consignidStr="+''+"&invf="+''+"&filenameStr="+''+"&articleStr="+''+"&priceStr="+'';
                 $joomla.ajax({
                      url: ajaxurl,
                      data: { "paymentgatewayflag":1,"ratetypeStr": "","Conveniencefees":$joomla('input[name="Conveniencefees"]').val(),"addSerStr":"","addSerCostStr":"","companyId":$joomla('input[name="companyId"]').val(),"insuranceCost":"","extAddSer":"","paypalinvoice":"","page":"cod","inhouseNo":$joomla('input[name="InHouseNostr"]').val(),"invoice":$joomla('input[name="InvoiceNo"]').val()},
                      dataType:"text",
                      type: "get",
                         beforeSend: function() {
                            
                         },
                         success: function(data){
                             res = data.split(":");
                             if(res[0] == 1){
                             window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&page=cod&invoice="+$joomla('input[name="InvoiceNo"]').val()+"&res="+res[1];
                             }else{
                                 $joomla(".page_loader").hide();
                                 $joomla(".paygaterrormsg").html(data);
                             }
                         }
                    });
                    
            }, 5000); 
             
         }else if($joomla('input[name="cc"]:checked').val() == "authorize.net"){
             
                 var paymentGateway = $joomla('input[name="cc"]:checked').val();
                 var user='<?php echo $user;?>';
                 $joomla('input[name="item_name"]').val($joomla('input[name="bill_form_nostr"]').val()+":"+$joomla('input[name="Id_Servstr"]').val()+":"+$joomla('[name="TotalAmountPaidstr"]').val()+":"+$joomla('input[name="InHouseNostr"]').val()+":"+$joomla('input[name="TotalCoststr"]').val()+":"+$joomla('input[name="AdditionalCoststr"]').val()+":"+$joomla('input[name="TotalFinalCoststr"]').val()+":"+$joomla('input[name="Discountstr"]').val()+":"+$joomla('input[name="InhouseIdkstr"]').val()+":"+$joomla('input[name="Conveniencefees"]').val()+":"+$joomla('input[name="companyId"]').val()+":"+user);
                 $joomla('input[name="item_number"]').val(1);
                 $joomla('input[name="amount"]').val($joomla('input[name="amtStr"]').val());
             
             document.getElementById('userprofileFormFive').submit();
             
         }
    
   
 },
 errorPlacement: function(error, element) {
     if (element.attr("name") == "cc") {
       //error.insertAfter(#cc);
       error.appendTo(element.parent('div').after());            
         
     }
     else if (element.attr("name") == "ccStr") {
       //error.insertAfter(#cc);
       error.appendTo(element.parent('div').after());            
         
     }
     else {
       error.insertAfter(element);
     }
 }
 });  



});

  

    });

</script>

<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading"><?php echo $assArr['invoices'];?></div>
		<div class="panel-body">

	        
	    <?php  
            
            Controlbox::getInvoicedetailsListCsv($user);
            
        ?>
	        
	        <div class="row">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        <h3 class=""><strong><?php echo Jtext::_('COM_USERPROFILE_INV_SUB_TITLE');?></strong></h3>
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <!--<a style="color:white;" href="<?#php echo $assArr['eXPORT_CSV']; ?>/csvdata/invoice_list.csv" class="btn btn-primary csvDownload export-csv"><?#php echo $assArr['eXPORT_CSV'];?></a>-->
                    </div>
                </div>
          </div>
	        
	        <div class="row">
	        <div class="col-md-12">
	      <table class="table table-bordered theme_table export_table">
	        	<thead>
							<tr>
								<th><?php echo $assArr['sNo'];?></th>
								<th><?php echo $assArr['invoice#'];?></th>
								<th><?php echo $assArr['inhouse#'];?></th>
								<th><?php echo $assArr['generated_in']; ?></th>
								<th><?php echo $assArr['consignee'];?></th>
								<th><?php echo $assArr['invoice_Type'];?></th>
                                <th>Card Number</th>
                                <th>Transcation ID</th>
                                <th>Payment Gateway</th>
                                <th>Payment Type</th>
								<th><?php echo $assArr['view'];?></th>
                                <th><?php echo 'Due Amount';?></th>
                                <th><?php echo 'Pay';?></th>
               

								
							</tr>
	        </thead>	
            <tbody>
                      <?php

                          $ordersView= UserprofileHelpersUserprofile::getInvoicedetailsList($user);
                          $arrOrders = json_decode($ordersView); 
                          
                          // echo '<pre>';
                          // var_dump($ordersView);exit;
                          
                          $i=1;
                          $formNums = array();
                          foreach($ordersView as $rg){
                            $totDue = ($rg->FinalCost)-($rg->AmountPaid);
                            if($totDue > 0){
                            $paynow = '<td class="inhouse_paynow"><a class="ship label-warning" data-target="#ord_ship" data-toggle="modal" data-id="'.$rg->ItemDetails[0]->BillFormNo.':'.$rg->ItemDetails[0]->ItemIdk.'::'.$rg->AmountPaid.':'.$rg->FormNumber.':::'.$rg->FinalCost.'::'.$rg->InhouseIdk.':'.$rg->InvoiceNumber.':'.$rg->InvoiceType.'" >Pay Now</a></td>';
                            }else{
                              $paynow = '<td class="inhouse_paynow" ><a class="ship label-success">Paid</a></td>';
                            }
                            
                            
                            
                            // if (in_array($rg->FormNumber, $formNums)){
                            //     echo '<tr style="display:none;"><td><span data-id="'.$rg->FormNumber.'" class="inhsrec"></span></td><td>'.$rg->InvoiceNumber.'</td><td>'.$rg->FormNumber.'</td><td>'.$rg->Date.'</td><td>'.$rg->ConsigneeName.'</td><td>'.$rg->InvoiceType.'</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id="'.$rg->InvoiceNumber.'" ><i class="fa fa-eye"></i></a></td><td>'.number_format($totDue,2).'</td>'.$paynow.'</tr>';
                            // }else{
                            //echo '<tr><td class="action_btns"><span data-id="'.$rg->FormNumber.'" class="expand_all btn btn-success">+</span></td><td>'.$rg->InvoiceNumber.'</td><td>'.$rg->FormNumber.'</td><td>'.$rg->Date.'</td><td>'.$rg->ConsigneeName.'</td><td>'.$rg->InvoiceType.'</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id="'.$rg->InvoiceNumber.'" ><i class="fa fa-eye"></i></a></td><td>'.number_format($totDue,2).'</td>'.$paynow.'</tr>';
                            // }
                            
                             echo '<tr><td>'.$i.'</td>
                             <td>'.$rg->InvoiceNumber.'</td>
                             <td>'.$rg->FormNumber.'</td>
                             <td>'.$rg->Date.'</td>
                             <td>'.$rg->ConsigneeName.'</td>
                             <td>'.$rg->InvoiceType.'</td>';
                          
                            if($rg->CardNumber !=""){
                            echo'<td>XXXX-XXXX-XXXX-'.$rg->CardNumber.'</td>';
                            }else{
                            echo'<td><center>-<center></td>';
                            }
                             echo'<td>'.$rg->TransactionNumber.'</td>
                             <td>'.$rg->PaymentGateway.'</td>
                             <td>'.$rg->PaymentMethod.'</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id="'.$rg->InvoiceNumber.'" ><i class="fa fa-eye"></i></a></td><td>'.number_format($totDue,2).'</td>'.$paynow.'</tr>';

                             //$formNums[$i] = $rg->FormNumber;
                          
                          $i++;
                              
                          }
                          
                          
                      ?>						
				    </tbody>
	             </table>
	            </div>
	        </div>
          </div>
       </div>
    </div>

<!-- Modal -->
<div id="ord_ship" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">      
        <input type="button" data-dismiss="modal" value="x" aria-label="Close" class="btn-close1">
         <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_SHIPPING_INFORMATION_LABEL');?>#</strong></h4>       
      </div>
      <div class="modal-body">
          <form name="userprofileFormFive" id="userprofileFormFive" method="post" action="" enctype="multipart/form-data">
             <?php $CompanyId = Controlbox::getCompanyId(); ?>
            <input type="hidden" id="companyId" name="companyId" value="<?php echo $CompanyId; ?>">
            <input type="hidden" id="bill_form_nostr" name="bill_form_nostr">
            <input type="hidden" id="Id_Servstr" name="Id_Servstr">
            <input type="hidden" id="TotalAmountPaidstr" name="TotalAmountPaidstr">
            <input type="hidden" id="InHouseNostr" name="InHouseNostr">
            <input type="hidden" id="TotalCoststr" name="TotalCoststr">
            <input type="hidden" id="AdditionalCoststr" name="AdditionalCoststr">
            <input type="hidden" id="TotalFinalCoststr" name="TotalFinalCoststr">
            <input type="hidden" id="Discountstr" name="Discountstr">
            <input type="hidden" id="InhouseIdkstr" name="InhouseIdkstr">
            <input type="hidden" id="InvoiceNo" name="InvoiceNo">
            <input type="hidden" id="InvoiceType" name="InvoiceType">
             <input type="hidden"  name="page" value="cod">
            
            <input type="hidden" id="amtStr" name="amtStr">
           
             <input type='hidden' name='business' value='<?php echo $PaypalEmail; ?>'> 
             <input type='hidden'   name='item_name'>
                <input type='hidden' name='item_number' value=1> 
                <input type='hidden' name='amount'>
                <input type='hidden' name='paypalinvoice' id="paypalinvoice">
                <input type='hidden' name='Conveniencefees' id="Conveniencefees" value="0">
                
                
                <input type='hidden' name='no_shipping' value='1'> 
                <input type='hidden' name='currency_code' value='USD'>
                <input type='hidden' name='notify_url' value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=notify-cod'>
            <input type='hidden' name='cancel_return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=cod'>
            <input type='hidden' name='return'
                value='<?php echo JURI::base(); ?>index.php?option=com_userprofile&&view=user&layout=response&page=cod&invoice=&pay=<?php echo base64_encode(date("m/d/0Y"));?>'>
            <input type="hidden" name="cmd" value="_xclick">  
              

            <div class="row">
              <div class="col-md-12">
                <div class="finish-shipping">
                  <label><?php echo Jtext::_('COM_USERPROFILE_FINAL_SHIPPING_LABEL');?># : </label><div id="shipmethodStrValuetwo" style="float:right"></div>
                  <p><?php echo Jtext::_('COM_USERPROFILE_MY_SUMMERY_SHIPPING');?># </p>
                  <p> Included (s):</p>
                  <table width="100%" class="table table-bordered theme_table shipping-costtbl">
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_WEREHOUSE_RECEIPT');?>#</label></td>
                      <td class="txt-right"><div id="bill_form_no"></div></td>
                    </tr>
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_SHIPPING_LABEL');?>#</label></td>
                      <td class="txt-right"><div id="InHouseNo"></div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><label><?php echo Jtext::_('COM_USERPROFILE_SHIPPING_OPTIONS');?></label></td>
                    </tr>

                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_TOTAL_COST');?></label></td>
                      <td class="txt-right"><div id="TotalCost"></div></td>
                    </tr>
 
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_ADDITIONAL_SERVICES');?></label></td>
                      <td class="txt-right"><div id="AdditionalCost">0.00</div></td>
                    </tr>
                    
                   
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_DISCOUNT');?></label></td>
                      <td class="txt-right"><div id="Discount">0.00</div></td>
                    </tr>
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_CUSTOM_CHARGES');?></label></td>
                      <td class="txt-right">0.00</td>
                    </tr>
                    <tr>
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_COSTS');?></label></td>
                      <td class="txt-right">0.00</td>
                    </tr>
                    <tr class="total_cst">
                      <td><label><?php echo Jtext::_('COM_USERPROFILE_TOTAL_BUY_FOR_TODAY');?></label></td>
                      <td class="txt-right"><div id="TotalFinalCost"></div></td>
                    </tr>
                    <tr class="">
                      <td><label><?php echo Jtext::_('Total Amount Paid');?></label></td>
                      <td class="txt-right"><div id="TotalAmountPaid"></div></td>
                    </tr>
                    <tr class="">
                      <td><label><?php echo Jtext::_('Due Amount');?></label></td>
                      <td class="txt-right"><div id="DueAmount"></div></td>
                    </tr>

                  </table>
                  <div class="clearfix"></div>
                  <div class="rdo_cust">
                    <!--<div class="rdo_rd1">-->
                    <!--  <input type="radio" name="cc" value="paypal">-->
                    <!--  <label><?php //echo Jtext::_('COM_USERPROFILE_SHIP_PREPAID');?></label>-->
                    <!--</div>-->
                    
                    <div class="rdo_rd1">
                      <div class="paymentmethodsDiv">
                                
                                <?php 
                                    $paymentmethodsStr = ''; 
                                    $paymentmethodsStr.=Controlbox::getpaymentgateways('PPD');
                                    echo $paymentmethodsStr;
                                ?>
                               
                    </div>
                    </div>
                    
                  </div>
                  
                  <div class="clearfix"></div>
                  
                    <!--paypal credit card payment-->
                      <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 paypalCreditDebit" style="display:none;" >                        
                        <div id="smart-button-container">
                          <div style="text-align: center;">
                            <div id="paypal-button-container"></div>
                          </div>
                        </div>
                      </div> 
                    <!--end -->                  
                 
        <div class="modal-body pagshipup" style="display:none"><img src='/components/com_userprofile/images/loader.gif' height="400"></div>
                  
        <div class="dvPaymentInformation col-md-6 col-sm-12 col-xs-12" style="display:none">
            <div class="heading">
                <h3 class="text-center">Confirm Purchase</h3>
            </div>
            <div class="payment">
                 <div class="form-group col-md-12 col-sm-12 col-xs-12" id="card-number-field">
                         <label for="cardNumber"><?php echo Jtext::_('COM_USERPROFILE_CARD_NUMBER');?></label>
                        <input type="text" class="form-control" id="cardNumber" name="cardnumberStr" minlength="15" maxlength="16" placeholder="4111111111111111">
                    </div>
                 <div class="clearfix"></div>
                   <div class="form-group" id="credit_cards">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/visa.jpg" id="visa">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/mastercard.jpg" id="mastercard">
                        <img src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/images/amex.jpg" id="amex">
                    </div>
                  
                    <div class="form-group col-md-12 col-sm-12" id="expiration-date">
                        <div class="row">
                        <label class="col-md-12 col-sm-12 col-xs-12">Expiration Date</label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <select class="form-control" name="MonthDropDownListStr">
                            <option value="">Select Month</option>
                            <option value="01">January</option>
                            <option value="02">February </option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <select class="form-control" name="YearDropDownListStr">
                            <option value="">Select Year</option>
                           <option value="2022" selected> 2022</option>
                            <option value="2023"> 2023</option>
                            <option value="2024"> 2024</option>
                            <option value="2025"> 2025</option>
                            <option value="2026"> 2026</option>
                            <option value="2027"> 2027</option>
                            <option value="2028"> 2028</option>
                            <option value="2029"> 2029</option>
                            <option value="2030"> 2030</option>
                            
                        </select>
                        </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                       <div class="form-group CVV col-md-12 col-sm-12 col-xs-12">
                        <label for="cvv">CVV</label>
                        <input type="password" class="form-control" id="cvv" name="txtccnumberStr" style="width:20%">
                    </div>
                    <div class="clearfix"></div>
                   
                    <div class="col-md-12 col-sm-12 col-xs-12 error paygaterrormsg"></div>
            </div>
        </div>

                  
                  <div class="clearfix"></div>
                  <div class="row">
                    <div class="col-md-12 text-center final_btns" >
                      <input type="hidden" name="task" value="user.dirpayshippment">
                      <input type="hidden" name="id" />
                      <input type="hidden" name="user" value="<?php echo $user;?>" />
                      <input type="hidden" id="amount" name="amount">
                      <input type="hidden" id="card-nonce" name="nonce">
                      
                      <input type="submit" onclick="onGetCardNonce(event)" value="Ship"  class="btn btn-primary final_ship">
                      <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
                    </div>
                  </div>
                  <div class="form-group"></div>
                </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
    

<!-- Modal -->
<form name="userprofileFormOne" id="userprofileFormOne" method="post" action="" enctype="multipart/form-data">
  <div id="inv_view" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <input type="button" data-dismiss="modal" value="X" class="btn-close1">
          <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_INV_MODAL_TITLE');?></strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group"></div>
            </div>
      </div>
    </div>
  </div>
</form>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/js/jquery.payform.min.js" charset="utf-8"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_userprofile/assets/js/script.js"></script>
<script>
$joomla(document).keyup('#cvv,#cardNumber',function() {
        $joomla("#cvv-error").html("");
        cardNum = $joomla('#cardNumber').val();
        cardNumLen = cardNum.length;
        if(cardNumLen == 16){
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }else if(cardNumLen == 15){
            $joomla("#cvv").attr("maxlength",4);
            $joomla("#cvv").attr("minlength",4);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_FOUR_DIGITS_ERROR'); ?>");
        }else{
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }
    });
    $joomla("#cvv,#cardNumber").on('blur',function() {
       
        $joomla("#cvv-error").html("");
        cardNum = $joomla('#cardNumber').val();
        cardNumLen = cardNum.length;
        if(cardNumLen == 16){
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }else if(cardNumLen == 15){
            $joomla("#cvv").attr("maxlength",4);
            $joomla("#cvv").attr("minlength",4);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_FOUR_DIGITS_ERROR'); ?>");
        }else{
            $joomla("#cvv").attr("maxlength",3);
            $joomla("#cvv").attr("minlength",3);
            $joomla("#cvv-error").html("<?php echo Jtext::_('COM_USERPROFILE_ENTER_THREE_DIGITS_ERROR'); ?>");
        }
    });
    
    
$joomla(document).ready(function() {
    if(clientId !=""){
        
          initPayPalButton();
    }
    
});


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
         
            // Full available details
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

            // Show a success message within this page, e.g.
            const element = document.getElementById('paypal-button-container');
            element.innerHTML = '';
             
                $joomla(".page_loader").show();
                var user='<?php echo $user;?>';
                if($joomla('input[name="cc"]:checked').val() == "Paypal"){
                    
                    setTimeout(function () {
                        ajaxurl = "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&amount="+$joomla('input[name="amount"]').val()+"&cardnumberStr="+$joomla('input[name="cardnumberStr"]').val()+"&txtccnumberStr="+$joomla('input[name="txtccnumberStr"]').val()+"&MonthDropDownListStr="+$joomla('select[name="MonthDropDownListStr"]').val()+"&YearDropDownListStr="+$joomla('select[name="YearDropDownListStr"]').val()+"&invidkStr=&qtyStr="+''+"&wherhourecStr="+$joomla('input[name="bill_form_nostr"]').val()+"&user="+user+"&txtspecialinsStr="+''+"&cc=PayForCOD&paymentgateway=Paypal&shipservtStr="+$joomla('input[name="Id_Servstr"]').val()+"&consignidStr="+''+"&invf="+''+"&filenameStr="+''+"&articleStr="+''+"&priceStr="+'';
                        $joomla.ajax({
                       			url: ajaxurl,
                       			data: { "paymentgatewayflag":1,"ratetypeStr": "","Conveniencefees":$joomla('input[name="Conveniencefees"]').val(),"addSerStr":"","addSerCostStr":"","companyId":$joomla('input[name="companyId"]').val(),"insuranceCost":"","extAddSer":"","paypalinvoice":"","page":"cod","inhouseNo":$joomla('input[name="InHouseNostr"]').val(),"invoice":$joomla('input[name="InvoiceNo"]').val(),"InhouseIdkstr":$joomla('input[name="InhouseIdkstr"]').val(),"InvoiceType":$joomla('input[name="InvoiceType"]').val(),"TxnId":orderData.id},
                       			dataType:"text",
                       			type: "get",
                                beforeSend: function() {
                                   
                                },
                                success: function(data){
                                    res = data.split(":");
                                    if(res[0] == 1){
                                    window.location.href="<?php echo JURI::base(); ?>index.php?option=com_userprofile&view=user&layout=response&page=cod&invoice="+$joomla('input[name="InvoiceNo"]').val()+"&res="+res[1];
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

    
</script>   

