<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
$document = JFactory::getDocument();
$document->setTitle("Order Process in Boxon Pobox Software");
defined('_JEXEC') or die;
$config = JFactory::getConfig();
$backend_url=$config->get('backend_url');

$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$pass=$session->get('user_casillero_password');
$CompanyId = Controlbox::getCompanyId();
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}

$menuAccessStr=Controlbox::getMenuAccess($user,$pass);
$menuCustData = explode(":",$menuAccessStr);
$maccarr=array();
foreach($menuCustData as $menuaccess){
    
    $macess = explode(",",$menuaccess);
    $maccarr[$macess[0]]=$macess[1];
 
}
$menuCustType=end($menuCustData);

// get domain details start

   $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
   $clientConf = json_decode($clientConfigObj, true);
   $clients = $clientConf['ClientList'];
   
   $domainDetails = ModProjectrequestformHelper::getDomainDetails();
   $CompanyId = $domainDetails[0]->CompanyId;
   $companyName = $domainDetails[0]->CompanyName;
   $domainEmail = $domainDetails[0]->PrimaryEmail;
   $domainName =  $domainDetails[0]->Domain;
   
   // get domain details end
   
   // get labels
    $lang=$session->get('lang_sel');
    $res=Controlbox::getlabels($lang);
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    }

?>

<?php include 'dasboard_navigation.php' ?>
<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>

#viewImage .row:after {
  content: "";
  display: table;
  clear: both;
}



/* Expanding image text */
#imgalttext {
  position: absolute;
  bottom: 15px;
  left: 15px;
  color: white;
  font-size: 20px;
}


</style>
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->


<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

    // $joomla('select[name=txtHistoryStatus]').change(function(){
    //     var resk=$joomla(this).val();
    //     $joomla(".page_loader").slideToggle("slow");
    //     if(resk=='All'){
    //         $joomla('input.input-sm').val('');
    //         $joomla('input.input-sm').trigger('keyup');
    //     }else    
    //     $joomla('input.input-sm').val($joomla(this).val());
    //     $joomla('input.input-sm').trigger('keyup');
    //     $joomla(".page_loader").slideToggle("slow");
    // });     
       
 
    $joomla('.getsstatus').on('click',function(e){
        e.preventDefault();
        var html =$joomla("#shipdetailsModal .modal-body").html();
        var awdid=$joomla(this).data('id');
      $joomla('input[name=txtShippingDetails]').val('');
      $joomla('input[name=txtComments]').val('');
      $joomla('input[name=txtDocumentCharges]').val('');
      $joomla('input[name=txtShippingCost]').val('');
      $joomla('input[name=txtFinalCost]').val('');
      $joomla('input[name=txtAmountPaid]').val('');
      $joomla('input[name=txtAmountPayable]').val('');
      $joomla('input[name=txtPaymentMethod]').val('');
      $joomla('input[name=txtTransactionNumber]').val('');
      $joomla('input[name=txtInvoiceNumber]').val('');
      $joomla('input[name=txtDate]').val('');
      $joomla('input[name=txtAmountPaidPaid]').val('');
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&ordershiptype="+awdid +"&ordershipflag=1&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "ordershiptype": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              $joomla("#shipdetailsModal .modal-body").html('<div id="loading-image3" ><img src="/components/com_userprofile/images/loader.gif"></div>');
           },success: function(data){
              $joomla("#shipdetailsModal .modal-body").html(html);
              var cosawd=data;
		      cosawd=cosawd.split(":");
		      $joomla('input[name=txtShippingDetails]').val(cosawd[3]);
		      $joomla('input[name=txtComments]').val(cosawd[2]);
		      $joomla('input[name=txtDocumentCharges]').val(cosawd[4]);
		      $joomla('input[name=txtShippingCost]').val(cosawd[5]);
		      $joomla('input[name=txtFinalCost]').val(cosawd[6]);
		      $joomla('input[name=txtAmountPaid]').val(cosawd[7]);
		      $joomla('input[name=txtAmountPayable]').val(cosawd[8]);
		      $joomla('input[name=txtPaymentMethod]').val(cosawd[9]);
		      $joomla('input[name=txtTransactionNumber]').val(cosawd[10]);
		      $joomla('input[name=txtInvoiceNumber]').val(cosawd[11]);
		      $joomla('input[name=txtDate]').val(cosawd[12]);
		      $joomla('input[name=txtAmountPaidPaid]').val(cosawd[13]);
            }
		});
    });    
    
        //momentoLogs
    $joomla(document).on('click','.getmlog',function(){
         $joomla("#momentoLogs").html('');
         $joomla(".page_loader").show();
        //$joomla("#momentoLogs").html('<div id="loading-image3" ><img src="/components/com_userprofile/images/loader.gif"></div>');
         var res=$joomla(this).data('id');
     setTimeout(function(){ 
         //$joomla("#momentoLogs").html('');
        $joomla.ajax({
			url: "<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&momentotype="+res+"&momentoflag=1&momentoid="+res+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime(),
			data: { "momentoid": $joomla(this).data('id') },
			dataType:"html",
			type: "get",
			beforeSend: function() {
              //$joomla("#momentoLogs").html('<div id="loading-image3" ><img src="/components/com_userprofile/images/loader.gif"></div>');
            
           },success: function(data){
                $joomla(".page_loader").hide();
              $joomla("#momentoLogs").html('<table class="table table-bordered theme_table" id="O_table"><thead><tr><th><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_STATUS');?></th><th><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_USER');?></th><th><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_DATE');?></th></tr>'+data+'</thead></table>');
        	}
		});
         
     }, 1000);
       
    });


 $joomla(document).ready(function(){
 $joomla(".child_row").hide();
});

$joomla(document).on('click','.exp_item',function(e){
 $joomla(this).parent().parent().next().toggle();
 

 if($joomla(this).html() == '+'){
             $joomla(this).html('-');
             $joomla(this).parent().parent().next().show();
         }else{
             $joomla(this).html('+');
             $joomla(this).parent().parent().next().hide();
         }


});
    
    // $joomla(".wrchild").click(function(){
        
    //     //$joomla('#M_table tbody tr#dfg').remove(); 
    //     var rs=$joomla(this).closest('tr').find('td:eq(2)').text();
    //     var htms='';
    //     $joomla('#M_table tbody tr').each( function () {
    //         if($joomla(this).attr('id')==rs){ 
    //           htms+='<tr id="dfg">'+$joomla(this).html()+'</tr>';
          
    //         }
    //     })
    //     if($joomla(this).val()=='-'){
    //       $joomla.each($joomla('#M_table tbody tr#dfg'), function(){
    //           if($joomla(this).closest('tr').find('td:eq(2)').text()==rs){ 
    //               $joomla(this).closest('tr').remove();
    //             }
    //         })

    //         $joomla(this).val('+');
    //     }else{
    //         $joomla(this).closest('tr').after(htms); 
    //         $joomla(this).val('-');
    //     }     
    // });

     // expand all
    
     $joomla('.expand_all_btn').on('click',function(e){
         var rowCount = $joomla("table tbody .exp_item:visible ").length;
       if(rowCount < 1){
            alert("no records to found expand");
            e.preventDefault();
                return false;
             }
        $joomla('#expandAll').trigger("click");
        if($joomla('#expandAll').prop("checked") == true){
             $joomla(this).addClass("collapse_icon");
             $joomla(this).attr("title","Collapse All");
            $joomla(".expand_all_btn").removeClass("expand_icon");
            // $joomla(this).html("Collapse All");
        }else{
            // $joomla(this).html("Expand All");
             $joomla(this).addClass("expand_icon");
              $joomla(this).attr("title","Expand All");
            $joomla(".expand_all_btn").removeClass("collapse_icon");
            
            
            
        }
    });
    
    $joomla('#expandAll').on('click',function(e){
        if($joomla(this).prop("checked") == true){
            $joomla(".exp_item").each(function(){
                var expCont = $joomla(this).val();
                if(expCont == "+"){
                    $joomla(this).trigger("click");
                }
            });
        }else{
            $joomla(".exp_item").each(function(){
                var expCont = $joomla(this).val();
                if(expCont == "-"){
                $joomla(this).trigger("click");
                    
                }
            });
        } 
    });
       
    //////////////////////////////////////
    
    $joomla(document).on('change','select[name=txtHistoryStatus]',function(){
          var status=$joomla(this).val();
            window.location.href="/wbe/index.php/en/user?layout=shiphistory&status="+status;
      });
      ///////////////fhgjhk

     $joomla(document).on('click','.exp_item',function(){

      var btnCount=$joomla(".exp_item").length;

     if($joomla(this).val() == "-"){
       $joomla("#expandAll").prop("checked",false);
        $joomla(".expand_all_btn").addClass("expand_icon");
        $joomla(".expand_all_btn").attr("title","Expand All");
       $joomla(".expand_all_btn").removeClass("collapse_icon");
    //   $joomla(".expand_all_btn").html("Expand All");
      }
       var htmse='';
       var rs=$joomla(this).attr("data-id");
       rs = rs.replace(/\s/g,'');
       htmse+='<tr class="child_row"><td colspan="10"><table class="table table-bordered"><tr class="'+rs+' wrhuse-grid"><th style="display:none"></th><th>Item Name</th><th>Item Quantity</th><th>Item Status</th><th>Expected Delivery Date</th><th>Update Invoice</th><th style="display:none"></th><th style="display:none"></th><th style="display:none"></th><th style="display:none"></th><th style="display:none"></th></tr>';
       $joomla('#M_table tbody tr').each( function () {
           
         if($joomla(this).attr('id') == rs){
           htmse+='<tr id="dfg" class="'+rs+'">'+$joomla(this).html()+'</tr>';
           
         }
       })
       if($joomla(this).val()=='-'){
            $joomla('.'+rs).remove();
            $joomla(this).val('+');
         }else{
             $joomla(this).closest('tr').after(htmse); 
             $joomla(this).val('-');
         } 

         var count=0;
         $joomla(".exp_item").each(function(){
         if($joomla(this).val() == "-"){
         count++;
         }
      });

      
      if(count == btnCount){
         $joomla('.expand_all_btn').trigger("click");
         }

     });

   ///update invoice submit
    //  $joomla(document).on('click','.ship_img',function(){
    //      // alert($joomla(this).attr("data-idk"));
    //       $joomla('#update_invoice_submit').attr('data-idk',$joomla(this).attr("data-idk"));
    //     $joomla('#viewImage').html('');
         
    //  });
     
//update invoice

$joomla(document).on('click','.ship_img',function(){
         $joomla(".invic-suces-msg").hide();
         $joomla(".invoice_pic_error").hide(); 
          $joomla(".invoice_pic_name").html("");
          $joomla("#update_invoice").val("");
         $joomla('#update_invoice_submit').attr('data-idk',$joomla(this).attr("data-idk"));
        $joomla('#viewImage #multimages').html('');
        var expandImg = document.getElementById("expandedImg");
        expandImg.parentElement.style.display = "none";
        var res=$joomla(this).data('id');
        
        // var imgarr = res.split("##");
        // var imgstr = '';
        
        // for(var i=0;i<imgarr.length;i++){
        //     if (imgarr[i] !=''){ 
        //         imgext = imgarr[i].split(".");
        //         if (imgext[imgext.length - 1] != 'pdf') {
        //             imgstr += "<div class='col-img-all images-view'><img src='"+imgarr[i]+"' width='100%'></div>";
        //         }else{
        //             imgstr += "<div class='col-img-all pdf-view'><a href='"+imgarr[i]+"' target='_blank' class='pdf-img'><img src='<?php echo JURI::base(); ?>components/com_userprofile/images/pdf-icon.png' width='100%'><span class='btn btn-primary'>View PDF</span></a></div>";
        //          }
        //     }
        // }
        // if(imgstr !=''){
        //     $joomla('#viewImage #multimages').html(imgstr);
        //     var firstImgSrc = $joomla('#multimages .images-view:first img').attr("src");
        //     if(typeof firstImgSrc !== 'undefined'){
        //      expandImg.src = $joomla('#multimages .images-view:first img').attr("src");
        //      expandImg.parentElement.style.display = "block";
        //     }else{
        //         expandImg.parentElement.style.display = "none";
        //     }
        //     $joomla('#multimages .images-view:first').addClass("img-active");
        // }else{
        //     $joomla('#viewImage #multimages').html("There is no image for this order");
        // }
    });
       
       
        // $joomla(document).on('click','.col-img-all img',function(){
        //     $joomla('.col-img-all').removeClass("img-active");
        //     $joomla(this).parent().addClass("img-active");
        //     var imgsrc = $joomla(this).attr("src");
        //     var alttext = $joomla(this).attr("alt");
        //     var expandImg = document.getElementById("expandedImg");
        //     var imgText = document.getElementById("imgalttext");
        //     expandImg.src = imgsrc;
        //     //imgText.innerHTML = alttext;
        //     expandImg.parentElement.style.display = "block";
    // });
    
   //update invoice
   
   $joomla(document).on('click','#update_invoice_submit',function() {
    feitem=[];
    var file_data_len = $joomla("input[name='update_invoice[]'").prop('files').length;
   
    if(file_data_len > 0){
        $joomla(".invoice_pic_error").hide();
        for(i=0;i<file_data_len;i++){
        var file_data = $joomla("input[name='update_invoice[]").prop('files')[i];   
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
        beforeSend: function() {
            $joomla(".page_loader").show();
        },
        success: function(data){
            var resArr = data.split(":");
            if(resArr[0] == 1){
                localStorage.setItem("updateInv",resArr[1]);
                
                $joomla(".invic-suces-msg").html(resArr[1]);
                $joomla(".invic-suces-msg").show();
                    var itemsList = '';
                    for(i=0;i<feitem.length;i++){
                        itemsList += '<a href="<?php echo JURI::base(); ?>/media/com_userprofile/'+feitem[i]+'" target="_blank" class="view-invioce"> ( View Invoice '+(i+1)+' ) </a>';
                    }
                    $joomla(".page_loader").hide();
                    $joomla(".invoice_details .invoice_pic_name").html(itemsList);
                    $joomla("#update_invoice").val("");
                    
            setTimeout(function(){
                 $joomla(".invic-suces-msg").hide();
            }, 3000);
            }
        }
    });
    }else{
        $joomla(".invoice_pic_error").show();
    }

});
    

////****

$joomla(document).on('change','select[name=M_table_length]',function(){
  
  
  
   $joomla('.exp_item').each( function () {
    //   $joomla('.wrchild').trigger('click');
        if($joomla(this).val()=='-'){
            $joomla(this).trigger('click');
           
         }
       
       
   });
    
 });

});



</script>
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading"><?php echo $assArr['MY_PACKAGES'];?></div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 tab_view">
          <ul class="nav nav-tabs">
              
            <?php 
            
                    if(!isset($maccarr['FulFillment'])){
                      $maccarr['FulFillment'] = "False";
                    }
            
                    foreach($clients as $client){ 
                        if(strtolower($client['Domain']) == strtolower($domainName) ){   
                            $prealert_text=$client['Myprealerts_text_dashboard'];
                        }
                    }
            
            
            if($menuCustType == "CUST" || ($menuCustType == "COMP" && $maccarr['FulFillment'] == "False") ){  ?>
                <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocessalerts"><?php echo $assArr['my_Pre_Alerts'];?></a> </li>
            <?php }else if($menuCustType == "COMP" && $maccarr['FulFillment'] == "True"){  ?>
                <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=inventoryalerts"><?php echo $assArr['inventory_Pre-Alerts'];?></a> </li>
            <?php } ?>
            
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocess"><?php echo $assArr['ready_to_ship'];?></a> </li>
             <?php if($dynpage["COD"][1]=="ACT" && $session->get('payment_status')=="ACT"){ ?>
            <li> <a class=""  href="index.php?option=com_userprofile&view=user&layout=cod"> <?php echo $assArr['cOD'];?> </a> </li>
            <?php } ?>
            <!--<li> <a class="" href="index.php?option=com_userprofile&view=user&layout=cod">COD</a> </li>-->
            <li> <a class="active"><?php echo $assArr['shipment_History'];?></a> </li>
          </ul>
        </div>
      </div>
      
      <!--get status list-->
      
      <?#php
           // $statusList = Controlbox :: getStatusList('');
      ?>
      
      <div id"tabs3">
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-9 col-sm-9">&nbsp;</div>
            <div class="col-sm-3 p-0">
              <!--<div class="form-group text-right">-->
              <!--  <select class="form-control" name="txtHistoryStatus">-->
                 
              <!--    <option value="All" <?#php  if($_GET['status']=="All"){ echo "Selected"; } ?> >All</option>-->
              <!--    <option value="Ship" <?#php  if($_GET['status']=="Ship"){ echo "Selected"; } ?> >Ship</option>-->
              <!--    <option value="Return" <?#php  if($_GET['status']=="Return"){ echo "Selected"; } ?>>Return</option>-->
              <!--    <option value="Discard" <?#php  if($_GET['status']=="Discard"){ echo "Selected"; } ?>>Discard</option>-->
              <!--    <option value="In Progress" <?#php  if($_GET['status']=="In Progress"){ echo "Selected"; } ?>>In Progress</option>-->
              <!--    <option value="Finished" <?#php  if($_GET['status']=="Finished"){ echo "Selected"; } ?>>Finished</option>-->
              <!--    <option value="Received" <?#php  if($_GET['status']=="Received"){ echo "Selected"; } ?>>Received</option>-->
              <!--    <option value="Hold" <?#php  if($_GET['status']=="Hold"){ echo "Selected"; } ?>>Hold</option>-->
              
              <!--  </select>-->
              <!--</div>-->
            </div>
          </div>
        </div>
        <?php  
            
            Controlbox::getOrdersHistoryListCsv($user);
            
        ?>
        
<div class="row">
    <?php
      $statusList = Controlbox :: getStatusList('');
     ?>
    <div class="col-sm-12 inventry-item">
        <div class="col-sm-3">
            <div class="form-group text-right">
                <!--<select class="form-control" name="txtHistoryStatus">-->
                 
                <!--  <option value="All" <?#php  if($_GET['status']=="All"){ echo "Selected"; } ?> >All</option>-->
                <!--  <option value="Ship" <?#php  if($_GET['status']=="Ship"){ echo "Selected"; } ?> >Ship</option>-->
                <!--  <option value="Return" <?#php  if($_GET['status']=="Return"){ echo "Selected"; } ?>>Return</option>-->
                <!--  <option value="Discard" <?#php  if($_GET['status']=="Discard"){ echo "Selected"; } ?>>Discard</option>-->
                <!--  <option value="In Progress" <?#php  if($_GET['status']=="In Progress"){ echo "Selected"; } ?>>In Progress</option>-->
                <!--  <option value="Finished" <?#php  if($_GET['status']=="Finished"){ echo "Selected"; } ?>>Finished</option>-->
                <!--  <option value="Received" <?#php  if($_GET['status']=="Received"){ echo "Selected"; } ?>>Received</option>-->
                <!--  <option value="Hold" <?#php  if($_GET['status']=="Hold"){ echo "Selected"; } ?>>Hold</option>-->
                <!--</select>-->
             <select class="form-control" name="txtHistoryStatus">
                 <option value="All">All</option>
                   <?php 
                      foreach($statusList as $status){ ?>
                       <option value="<?php echo $status->StatusId;?>" <?php if(strtolower($_GET['status'])== strtolower($status->StatusId)){ echo "Selected"; } ?> ><?php echo $status->StatusDescription ?></option>
                  <?php } ?>
               
            </select>
             </div>
        </div>
        <div class="col-sm-9 form-group text-right inventry-item-right">
         <div class="action-icons pull-right btn-action-groupnew">
         <input type="checkbox" name="expandAll" id="expandAll"><button class="btnallaction expand_all_btn expand_icon" data-toggle="tooltip" title="Expand All"></button>
                <!-- <a style="color:white;" href="<?php //echo JURI::base(); ?>/csvdata/history_list.csv" class="btn btn-primary csvDownload export-csv"><?php echo $assArr['eXPORT_CSV'];?></a> -->
        </div> 
        </div>
    </div>
</div>
            
        <div class="row">
          <div class="col-md-12">
              <div class="table-responsive">
            <table class="table table-bordered theme_table" id="M_table"  data-page-length='10'>
              <thead>
                <tr>
                  <th><?php echo 'Actions'; ?></th>
                  <th><?php echo $assArr['warehouse_Receipt'];?></th>
                  <th><?php echo $assArr['creation_date'];?></th>
                  <th><?php echo $assArr['carrier'];?></th>
                  <th><?php echo $assArr['the_tracking_number'];?></th>
                  <th><?php echo $assArr['reports'];?></th>
                  <th><?php echo $assArr['Card Number'];?></th>
                  <th><?php echo $assArr['Transaction Number'];?></th>
                  <th><?php echo $assArr['Payment Gateway'];?></th>
                  <th><?php echo $assArr['Payment Method'];?></th>
                </tr>
              </thead>
              <tbody>
                  
                  <!--<th><?php //echo $assArr['item_name'];?></th>-->
                  <!--<th><?php //echo $assArr['quantity'];?></th>-->
                  <!--<th><?php //echo $assArr['status'];?></th>-->
                  
<?php
                
    $purchasetype = "History";
    $status = "All";
    
    if(isset($_GET['status'])){
        if($_GET['status']!="All"){
            
             $purchasetype = "Search";
             $status = $_GET['status'];
        }
       
    }
    //  var_dump($_GET['status']);exit;
    
    $ordersHistoryView= UserprofileHelpersUserprofile::getOrdersHistoryList($user,$status,$purchasetype);
    
    // var_dump($ordersHistoryView);
    // exit;
    
       $env = explode(".",$_SERVER['SERVER_NAME']);
        
       $service_url = $backend_url;
       
    
        foreach($ordersHistoryView as $repack){
                               
                                    foreach($repack->WarehouseDetails as $res){
                                        
         $rep='<a href="'.$service_url.'/ASPX/Tx_Wh_Receipt.aspx?bid='.$res->BillFormNo.'&companyid='.$CompanyId.'" target="_blank">'.Jtext::_('COM_USERPROFILE_HISTORY_TABLE_WAREHOUSE_RECEIPT').'<a>';
        
         echo '<tr><td ><input type="button" class="btn btn-success exp_item" data-id="'.$res->BillFormNo.'" value="+"></td>
         <td><a data-toggle="modal" data-backdrop="static" data-keyboard="false" class="getmlog whrse-link label-success" data-id="'.$res->BillFormNo.'" data-target="#logModal" title="momento">'.$res->BillFormNo.'</a></td>
         <td>'.$res->CreatedDate.'</td>
         <td>'.$res->CarrierName.'</td>
         <td>'.$res->TrackingId.'</td>
         <td>'.$rep.'</td>';
       
        if($res->CardNumber !=""){
            echo'<td>XXXX-XXXX-XXXX-'.$res->CardNumber.'</td>';
        }else{
            echo'<td><center>-<center></td>';
        }
        
         echo'<td>'.$res->TransactionId.'</td>
         <td>'.$res->PaymentGateway.'</td>
         <td>'.$res->PaymentType.'</td>
         </tr>';
        
       
        
       foreach($res->ItemDetails as $rg){
           
          
            if($rg->ItemStatus=="Ship"){
            $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP');
         }else if($rg->ItemStatus=="Received"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RECEIVED');
         }else if($rg->ItemStatus=="In Progress"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_IN_PROGRESS');
         }else if($rg->ItemStatus=="Discard"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_DISCARD');
         }else if($rg->ItemStatus=="Hold"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD');
         }else if($rg->ItemStatus=="Return"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RETURN');
         }else if($rg->ItemStatus=="Finished"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_FINISHED');
         }
          
          //echo '<tr id="'.str_replace(" ","",$rg->BillFormNo).'" style="display:block;" ><td></td><td></td><td>'.$rg->ItemDetails[$i]->ItemName.'</td><td>'.$rg->ItemDetails[$i]->ItemQuantity.'</td><td>'.$rg->ItemDetails[$i]->ItemStatus.'</td><td></td></tr>';
                  echo '<tr  class="child_row"  id="'.$res->BillFormNo.'" style="display:none"><td style="display:none"></td>
                  <td>'.$rg->ItemName.'</td>
                  <td>'.$rg->ItemQuantity.'</td>
                  <td>'.$rg->ItemStatus.'</td>
                  <td> - </td>';
                  
                  //var_dump($rg->Status);
                  
                  if($rg->ItemStatus == "Received" || $rg->ItemStatus == "In Progress" || $rg->ItemStatus == "Hold"){
                    echo '<td class="updateInvoice"><input type="hidden" name="ItemIdkTxt" value="'.$rg->ItemIdk.'"><a class="ship_img" data-target="#view_image" data-idk="'.$rg->ItemIdk.'"  data-toggle="modal" data-backdrop="static" data-keyboard="false" href="#" >Update Invoice</a></td>';
                  }else{
                      echo '<td class="updateInvoice">-</td>';
                  }
                  echo '<td style="display:none"></td>
                  <td style="display:none"></td>
                  <td style="display:none"></td>
                  <td style="display:none"></td>
                  <td style="display:none"></td>
                  </tr>';

        }  
        
    }

        
}                         
                  echo' </table></td><td></td><td></td>
                  <td ></td>
                  <td ></td>
                  <td ></td>
                  <td ></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  </tr>';
                ?>
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--- update invoice model--->
<div id="view_image" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <input type="button" data-dismiss="modal" value="x" class="btn-close1 updat_txt">
        <h4 class="modal-title"><strong>Update Invoice</strong></h4>
      </div>
      <div class="modal-body">
        <div class="">
            <span  class="col-md-12 alert alert-success invic-suces-msg"></span>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 file-upload">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 image-upload-wrap">
            <input class="file-upload-input" type='file' name="update_invoice[]" multiple id="update_invoice">
            <div class="drag-text">
            <h3> Choose a file </h3>
            </div>
           
            </div> 
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding">
            <button id="update_invoice_submit" data-idk="" class="btn btn-primary submitBtn">Upload</button>
            </div>
            </div>
            <div><span class="invoice_pic_error error">Please choose a file</span></div>
            <div class="invoice_details"><p class="invoice_pic_name"></p></div>
              
          <div class="col-md-12">
            <div id="viewImage">
                <div class="" id="multimages">
                    
                </div>
                <div class="mult-img-container">
                <img id="expandedImg" src="" style="width:100%">
                <div id="imgalttext"></div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="shipdetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">       
         <input type="button" data-dismiss="modal" value="x" class="btn-close1">       
        <h4 class="modal-title"><strong>Shipping Details</strong></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Shipping details for Inhouse</label>
                    <input type="text" name="txtShippingDetails" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Comments<span class="error">*</span></label>
                    <input type="text" name="txtComments" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Documentation Charges</label>
                    <input type="text" name="txtDocumentCharges" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Shipping cost</label>
                    <input type="text" name="txtShippingCost" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Final cost</label>
                    <input type="text" name="txtFinalCost" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Amount paid</label>
                    <input type="text" name="txtAmountPaid" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Amount payable</label>
                    <input type="text" name="txtAmountPayable" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Payment method</label>
                    <input type="text" name="txtPaymentMethod" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Transaction number</label>
                    <input type="text" name="txtTransactionNumber" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Invoice number</label>
                    <input type="text" name="txtInvoiceNumber" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="text" name="txtDate" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Amount Paid</label>
                    <input type="text" name="txtAmountPaidPaid" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-center">
                  <input type="button" value="Close" data-dismiss="modal" class="btn btn-danger">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="logModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">       
          <input type="button" data-dismiss="modal" value="x" class="btn-close1">       
        <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_HISTORY_MODAL_TRACKING');?></strong></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="momentoLogs"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
      
      $joomla(document).on('change','#update_invoice', function() {
       
           if(this.files.length > 4){
                 alert('<?php echo Jtext::_('Should not exceed more than 4 file.') ?>');
                 $joomla(this).val('');
            }
            if(this.files.length > 0){
                $joomla(".invoice_pic_error").hide(); 
            }
             
            for(i=0;i<this.files.length;i++){
            
            if(this.files[i].size > 2000000){
                 alert('please upload below 2MB file');
                  $joomla(this).val('');
                  return false;
             }
             
            var filename=this.files[i].name;
            var ext = filename.split('.').pop().toLowerCase(); 
           
            if($joomla.inArray(ext, ['gif','png','jpg','jpeg','pdf']) == -1) {
               
                 alert('Sorry, only JPG, JPEG, GIF & PNG PDF files are allowed to upload.');
                 $joomla(this).val('');
                 return false;
            }
            
        }
             
    
  });
  //update invoice name show and hide
  
    $joomla('.updat_txt').click(function(){
          $joomla('.invoice_pic_name').html("").hide();
        $joomla("#update_invoice").click(function(e){
          $joomla('.invoice_pic_name').html("").show();  
      
      });
  });
    
    // profile pic update name display
     
      $joomla(document).on('change','#update_invoice',function(e){
          $joomla(".invoice_pic_name").html("");
          var files = $joomla(this).prop("files");
          var fileStr = [];
          
          for(var i=0;i<files.length;i++){
              fileStr[i]=files[i].name;
          }
          $joomla(".invoice_pic_name").html(fileStr.toString());
      });
     
      
   
     
</script>
