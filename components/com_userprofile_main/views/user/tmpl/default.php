<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     Boxon <Boxon@iblesoft.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access

defined('_JEXEC') or die;
require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
$document = JFactory::getDocument();
$document->setTitle("Dashboard in Boxon Pobox Software");
$session = JFactory::getSession();
$langSel=$session->get('lang_sel');
$domainList=Controlbox::getDomainList();

//  Default Page

$hostnameStr = $_SERVER['HTTP_HOST'];
$hostnameArr = explode(".",$hostnameStr);
$count = count($hostnameArr);

$domainName=$hostnameArr[0];


$domainListArr=array();

foreach($domainList as $domain){
    $domainListArr[]=strtolower($domain->Domain);
}

if(!in_array($domainName,$domainListArr)){
    include 'notfound.php';
    exit;
}
  
if($count == '2'){
     include 'welcome.php';
     exit;
}else{
    $user=$session->get('user_casillero_id');
    $pass=$session->get('user_casillero_password');
    $session->clear( 'userData');
    
    if(!$user){
        $app =& JFactory::getApplication();
        $app->redirect('index.php?option=com_register&view=login');
    }
}

$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$UserView= UserprofileHelpersUserprofile::getUserprofileDetails($user);
$UserViews=UserprofileHelpersUserprofile::getUserpersonalDetails($user);
//$ordersViewCount= UserprofileHelpersUserprofile::getUsersorderscount($user);
$dynamicpages= UserprofileHelpersUserprofile::dynamicpages();
$menuAccessStr=Controlbox::getMenuAccess($user,$pass);
$getBranches=Controlbox::getAllBranches();


// get domain details start

   $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
   $clientConf = json_decode($clientConfigObj, true);
   $clients = $clientConf['ClientList'];
   
   
   $CompanyId = $domainDetails[0]->CompanyId;
   $companyName = $domainDetails[0]->CompanyName;
   $domainEmail = $domainDetails[0]->PrimaryEmail;
   $domainNameDb =  $domainDetails[0]->Domain;
   
   // get domain details end

// echo '<pre>';
// var_dump($dynpage);
// exit;

// get cust type and menu access


$menuCustData = explode(":",$menuAccessStr);


$maccarr=array();
foreach($menuCustData as $menuaccess){
    
    $macess = explode(",",$menuaccess);
    $maccarr[$macess[0]]=$macess[1];
 
}

$menuCustType=end($menuCustData);

// end



// get labels
    
if(strpos($_SERVER['REQUEST_URI'], '/index.php/') !== false){
    $strplace = strpos($_SERVER['REQUEST_URI'], '/index.php/');
    $langplace = $strplace + 11;
    $language = substr($_SERVER['REQUEST_URI'],$langplace,2);
}
    
?>
<?php include 'dasboard_navigation.php'; ?>


<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(function() {
    
     <!-- getall the existing branches on change  -->
    
    $joomla(document).on('change','#exBranch',function(){
        var branchCode = $joomla(this).val();
        
        if(branchCode !=''){
            
            branchDet = branchCode.split(":");
           // console.log(branchDet);
                            /*
    			                0->BranchCode;
    			                1->Address1
    			                2->Address2
    			                3->City
    			                4->State
    			                5->Country
    			                6->PostalCode
    			                7->PhoneCell
    			             */
            $joomla(".userAdd1").html(branchDet[1].toUpperCase());
            $joomla(".userAdd2").html(branchDet[2].toUpperCase());
            $joomla(".userCity").html(branchDet[3]);
            $joomla(".userState").html(branchDet[4]);
            $joomla(".userCountry").html(branchDet[5]);
            $joomla(".userPostalCode").html(branchDet[6]);
            
            if(branchDet[7] !=''){
                $joomla(".userPhone").html('PHONE : ' + branchDet[7] + '<br>');
            }
            
        }
        
    });
    
    // Change Default Address
    
    $joomla(document).on('click','#defaultBranch',function(){
        
         var defaultCheck = $joomla("#defaultBranch").is(":checked");
         
         if(defaultCheck){
             $joomla('#branchConfirm').modal('show');
         }
        
     });
     
     $joomla(document).on('click','button[data-dismiss="modal"]',function(){
        
        $joomla("#defaultBranch").prop("checked",false);
        
     });
    
     $joomla(document).on('click','#branchConfirm .btn-primary',function(){
         
        
         var custId=$joomla(".user_num").html();
         var branch = $joomla("#exBranch").val();
         var defaultCheck = $joomla("#defaultBranch").is(":checked");
         
         if(defaultCheck){
             
            branchDet = branch.split(":");
            branchCode = branchDet[0];
            
            if(branchCode == 0){
                alert("Please Select Branch");
                return false;
            }else{
                
                var ulks="<?php echo JURI::base(); ?>index.php?option=com_userprofile&task=user.get_ajax_data&custid="+custId+"&branch="+branchCode+"&jpath=<?php echo urlencode  (JPATH_SITE); ?>&pseudoParam="+new Date().getTime();
           
            $joomla.ajax({
    			url: ulks,
    			data: { "updatebranchflag": 1 },
    			dataType:"html",
    			type: "get",
                beforeSend: function() {
                    $joomla(".page_loader").show();
                    $joomla('#branchConfirm').modal('hide');
                },
                success: function(data){
                    
                   if(data == 1){
                       $joomla(".page_loader").hide();
                       $joomla("#defaultBranch").prop("checked",false);
                       alert("<?php echo Jtext::_('COM_USERPROFILE_CHANGE_DEFAULT_ADDRESS_SUCCESS')  ?>");
                       
                   }
               }
            });   
                
            }
            
            
         }else{
             alert("Please Select Checkbox");
             return false;
         }
         
            
     });
     
     // end
     
    
    
});

</script>


<style>
.branch_section {margin-top: 20px;}
/*ui enhancement 2.6 css start*/
	.prof-pic-blk1{text-align: center; padding:0;}
  .profile_sectn{padding: 0;}
	.prof-pic-blk1 img {width: 180px;height: 180px;border-radius: 50%;border: 1px solid #f2f2f2;background: #f2f2f2;}
.profilepic_address{min-height: 800px;}
.dash_panel .user_info .panel h4 {margin: 5px 0 10px;}
.dash_panel .user_info .panel address {line-height: 34px;}
.dash_option_blck h5 {font-size: 16px;}
.dash_option_blck img {width: 50px;}
.prof-pic-blk1 {text-align: left;}
.prof-pic-blk1 img {margin: 10px auto;display: block;}
.edit-ico {display: none;}
.prof-pic-blk1 .labelFile {border-radius: 4px;text-align: center;padding: 2px 46px;margin: 2px;background: transparent;display: inline-block;}
.labelFile input[type=file] {display: none;}
.labelFile .btn {border:0;color: #fff;}
.prof-pic-blk1:hover .edit-ico i {text-align: center;font-size: 30px;padding-top: 100px;color: #fff;display: block;cursor: pointer;padding-left:0px;}
.prof-pic-blk1:hover .edit-ico {width: 180px;height: 180px;border-radius: 50%;border: 1px solid #f2f2f2;margin: 10px auto;display: block;display: block;background: rgba(0,0,0,0.5) !important;z-index: 999999;position: absolute;top: 0;left: 70px;z-index: 0;}
.address_section{padding: 28px 0px;}
.dash_panel h3.dash_head {font-size: 20px !important;}
.dash_panel h3.dash_head {margin: 18px 0 0;padding: 12px;}
..page_content .main_panel .main_heading{padding: 15px 0 15px !important;}
.prof-pic-blk1 h3{font-size:20px;text-align:center;margin-bottom: 12px;}
/* .labelFile input[type=file]:hover {display: block;} */
/*ui enhancement 2.6 css end*/
</style>

<div class="container">
  <div class="main_panel dash_panel">
    <div class="main_heading"><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_TITLE');?></div>
<div class="panel-body">
      <!-- <h3 class="dash_head"><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_WELCOME');?> <?php echo $UserView->UserName;?>!</h3> -->
	  <div class="row">
      <div class="user_info">
	          <div class="col-md-4 col-sm-12 profilepic_address">
			   
		   <!--Dashboard leftside section start--> 
  <div class="panel panel-default col-md-12 col-sm-12">           
          <!--Confirm alert box-->           
          <!-- Modal popup start -->
          <div class="modal fade" id="branchConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body"> <?php echo Jtext::_('COM_USERPROFILE_CHANGE_DEFAULT_ADDRESS_ALERT');?> </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo Jtext::_('COM_USERPROFILE_CHANGE_DEFAULT_ADDRESS_NO');?></button>
                  <button type="button" class="btn btn-primary"><?php echo Jtext::_('COM_USERPROFILE_CHANGE_DEFAULT_ADDRESS_YES');?></button>
                </div>
              </div>
            </div>
          </div>
          
          <!--Modal popup end-->
		   <!--Profilepic start-->
		  <div class="col-md-12 col-sm-5 profile_sectn">
          <div class="panel-body prof-pic-blk1">
              <?php 
					if($UserViews->imagePath){
				?>
              <img src="<?php echo str_replace('https:','http:',$UserViews->imagePath);?>">
              <?php 
                            }else{
                        ?>
              <img src="<?php echo JURI::base().'/images/default_profile_pic.png'; ?>">
              <?php 
                           }
                        ?>
                        <h3><?php echo $UserView->UserName;?>!</h3>
						<!-- <div class="edit-ico">
                    <label class="labelFile">
                      <input type="file" required="">
                      <span class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></span> </label>
                  </div> -->
            </div>
			</div>
			<!--Profilepic end-->
			
			<!--Address section start--> 
          <div class="col-md-12 col-sm-7 address_section">     
			<div class="row">
            <!-- get all the existing branches  -->            
            <div class="branch_section">
              <div class="col-md-12 col-sm-12 col-xs-12" style="">
                <label><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_BRANCH_TEXT');?>  </label>
                <select id="exBranch" class="form-control" name="exBranch">
                  <option selected value="<?php echo  '0:'.$UserView->Address1.':'.$UserView->Address2,':'.$UserView->City.':'.$UserView->State.':'.$UserView->Country.':'.$UserView->PostalCode.':'.$UserView->PhoneCell; ?>">Select Branch</option>
                  <?php  
			          
    			            foreach($getBranches as $branch){
    			                /*
    			                0->BranchCode;
    			                1->Address1
    			                2->Address2
    			                3->City
    			                4->State
    			                5->Country
    			                6->PostalCode
    			                7->PhoneCell
    			                */
    			                $branchVal = $branch->BranchCode.":".$branch->Address1.":".$branch->Address2.":".$branch->City.":".$branch->State.":".$branch->Country.":".$branch->PostalCode.":".$branch->PhoneCell;
    			        ?>
                  <option value="<?php echo $branchVal; ?>" <?php if($UserView->BranchCode == $branch->BranchCode ){ echo 'selected';} ?> ><?php echo $branch->BranchName; ?></option>
                  <?php
                        
                            }
                        ?>
                </select>
              </div>
			   <div class="col-md-12 col-sm-12 col-xs-12 dfult-chck">
				  
                <input type="checkbox" name="defaultBranch" id="defaultBranch">
               <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_DEFAULT_ADDRESS');?>  
			  </div>
              <div class="clearfix"></div>
            </div>
            <div class="panel-body">
              <h4><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_SHIPMENT_ADDRESS');?></h4>
              <address>
              <span class="name user_name1"><?php echo $UserView->UserName;?></span>  <span class="user_num"> <?php echo strtoupper($user);?></span><br>
              <span class="userAdd1"><?php echo str_replace(",","",strtoupper($UserView->Address1)).'</span>';?><br> <?php if(!empty($UserView->Address2)){ echo '<span class="userAdd2">'.str_replace(",","",strtoupper($UserView->Address2)).'</span> ,<br>'; } ?>
              <span class="userCity"><?php echo $UserView->City;?></span>,&nbsp; <span class="userState"><?php echo $UserView->State;?></span>,&nbsp;
              <span class="userCountry"><?php echo $UserView->Country;?></span>&nbsp;- &nbsp;<span class="userPostalCode"><?php echo $UserView->PostalCode.'</span><br>';?> 
			  <span class="userPhone">
              <?php if($UserView->PhoneCell) echo 'PHONE : '.$UserView->PhoneCell.'<br>';?>
              </span>
              <?php //if($UserView->PhoneFax) echo 'Fax : '.$UserView->PhoneFax.'<br>';?>
              </address>
             
              <div class="row col-md-12" style="display:none;">
                <input type="button" class="btn btn-primary" name="updateBranch" id="updateBranch" value="Update">
              </div>
            </div>
          </div>
		  </div>
        </div>
        </div>
      </div>
        <!-- <div class="col-md-4 col-sm-4">           
          <!--Confirm alert box-->                    
          <!-- Modal -->
          <!--<div class="modal fade" id="branchConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body"> <?php echo Jtext::_('COM_USERPROFILE_CHANGE_DEFAULT_ADDRESS_ALERT');?> </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo Jtext::_('COM_USERPROFILE_CHANGE_DEFAULT_ADDRESS_NO');?></button>
                  <button type="button" class="btn btn-primary"><?php echo Jtext::_('COM_USERPROFILE_CHANGE_DEFAULT_ADDRESS_YES');?></button>
                </div>
              </div>
            </div>
          </div>
          
          <!--End-->
          
          <!--<div class="panel panel-default">             
            <!-- get all the existing branches  -->            
            <!--<div class="branch_section">
              <div class="col-md-6 col-sm-6 col-xs-12" style="">
                <label><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_BRANCH_TEXT');?>  </label>
                <select id="exBranch" class="form-control" name="exBranch">
                  <option selected value="<?php echo  '0:'.$UserView->Address1.':'.$UserView->Address2,':'.$UserView->City.':'.$UserView->State.':'.$UserView->Country.':'.$UserView->PostalCode.':'.$UserView->PhoneCell; ?>">Select Branch</option>
                  <?php  
			          
    			            foreach($getBranches as $branch){
    			                /*
    			                0->BranchCode;
    			                1->Address1
    			                2->Address2
    			                3->City
    			                4->State
    			                5->Country
    			                6->PostalCode
    			                7->PhoneCell
    			                */
    			                $branchVal = $branch->BranchCode.":".$branch->Address1.":".$branch->Address2.":".$branch->City.":".$branch->State.":".$branch->Country.":".$branch->PostalCode.":".$branch->PhoneCell;
    			        ?>
                  <option value="<?php echo $branchVal; ?>" <?php if($UserView->BranchCode == $branch->BranchCode ){ echo 'selected';} ?> ><?php echo $branch->BranchName; ?></option>
                  <?php
                        
                            }
                        ?>
                </select>
              </div>
			   <div class="col-md-6 col-sm-6 col-xs-12 dfult-chck">
				   <label>&nbsp;</label>
                <input type="checkbox" name="defaultBranch" id="defaultBranch">
               <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_DEFAULT_ADDRESS');?>  
			  </div>
              <div class="clearfix"></div>
            </div>
            <div class="panel-body">
              <h4><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_SHIPMENT_ADDRESS');?></h4>
              <address>
              <span class="name user_name1"><?php echo $UserView->UserName;?></span>  <span class="user_num"> <?php echo strtoupper($user);?></span><br>
              <span class="userAdd1"><?php echo str_replace(",","",strtoupper($UserView->Address1)).'</span>';?><br> <?php if(!empty($UserView->Address2)){ echo '<span class="userAdd2">'.str_replace(",","",strtoupper($UserView->Address2)).'</span> ,<br>'; } ?>
              <span class="userCity"><?php echo $UserView->City;?></span>,&nbsp; <span class="userState"><?php echo $UserView->State;?></span>,&nbsp;
              <span class="userCountry"><?php echo $UserView->Country;?></span>&nbsp;- &nbsp;<span class="userPostalCode"><?php echo $UserView->PostalCode.'</span><br>';?> 
			  <span class="userPhone">
              <?php if($UserView->PhoneCell) echo 'PHONE : '.$UserView->PhoneCell.'<br>';?>
              </span>
              <?php //if($UserView->PhoneFax) echo 'Fax : '.$UserView->PhoneFax.'<br>';?>
              </address>
             
              <div class="row col-md-12" style="display:none;">
                <input type="button" class="btn btn-primary" name="updateBranch" id="updateBranch" value="Update">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-4 prof-pic-blk1">
          <div class="panel panel-default">
            <div class="panel-body">
              <?php 
					if($UserViews->imagePath){
				?>
              <img src="<?php echo str_replace('https:','http:',$UserViews->imagePath);?>">
              <?php 
                            }else{
                        ?>
              <img src="<?php echo JURI::base().'/images/default_profile_pic.png'; ?>">
              <?php 
                           }
                        ?>
            </div>
          </div>
        </div>-->
    <!--Address section end--> 
	 <!--Dashboard leftside section end--> 
	 
	 <!--Dashboard rightside section start-->
	<div class="col-md-8 col-sm-12">
      <div class="usr_shipping">
        <div class="">
          <div class="panel panel-default">
            <div class="panel-heading"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_SHIPMENT');?> </div>
            <div class="panel-body">
              <div class="row">
                
                  <?php 
                  
                foreach($clients as $client){ 
                    if(strtolower($client['Domain']) == strtolower($domainNameDb) ){   
                         $prealert_text=$client['Myprealerts_text_dashboard'];
                     }
                }
                
                        if($dynpage["PreAlerts"][2]=="PreAlerts" && $dynpage["PreAlerts"][1]=="ACT" && $menuCustType=="CUST"){  
                  ?>
                  
                    <div class="col-sm-4">
                        <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=orderprocessalerts"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/my_purchase.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/my_purchase_hover.png" class="img-responsive img_hvr">
                        <!--<h5><?php //echo Jtext::_($dynpage["PreAlerts"][0]);?></h5>-->
                        <h5><?php echo $assArr['my_Pre_Alerts']; ?></h5>
                        
                        </a> 
                        </div>
                    </div>
                    
                    <?php }if($dynpage["InventoryAlerts"][2] =="InventoryAlerts" && $dynpage["InventoryAlerts"][1]=="ACT" && $menuCustType == "COMP"){ ?>
               
                    <div class="col-sm-4">
                        <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=inventoryalerts"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/my_purchase.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/my_purchase_hover.png" class="img-responsive img_hvr">
                        <h5><?php echo $assArr['inventory_Pre-Alerts'];?></h5>
                        </a> 
                        </div>
                    </div>
                  <?php  }if($dynpage["PendingShipments"][2] =="PendingShipments" && $dynpage["PendingShipments"][1]=="ACT"){ ?>
                
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=orderprocess"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/pending_ship.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/pending_ship_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['ready_to_ship'];?></h5>
                    <span class="num_count"><?php echo $UserView->ArticleInvCount;?></span> </a> </div>
                </div>
                 <?php  }if($dynpage["ShipmentHistory"][2] =="ShipmentHistory" && $dynpage["ShipmentHistory"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=shiphistory"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/ship_history.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/ship_history_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['shipment_History'];?></h5>
                    </a> </div>
                </div>
                
                <?php }  ?>
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="usr_account">
        <div class="">
          <div class="panel panel-default">
            <div class="panel-heading"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_ACCOUNT_SUMMARY');?> </div>
            <div class="panel-body">
              <div class="row">
                  <?php 
                      
                    if($dynpage["OrdersInProgress"][2] =="OrdersInProgress" && $dynpage["OrdersInProgress"][1]=="ACT" && $menuCustType=="CUST"){ ?>
                  
                    <div class="col-sm-4">
                    <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=pendingorderprocess"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/orders.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/orders_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['order_in_progress'];?></h5>
                    <span class="num_count"><?php echo $UserView->InProgressCount;?></span> </a> 
                    </div>
                    </div>
                  <?php }if($dynpage["FulFillment"][2] =="FulFillment" && $dynpage["FulFillment"][1]=="ACT" && $menuCustType=="COMP"){  ?>
                    <div class="col-sm-4">
                    <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=projectrequest"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/orders.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/orders_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_PROJECT_REQUEST_FORM'); ?></h5>
                    </a> 
                    </div>
                    </div>
                  <?php }if($dynpage["ItemsInStock"][2] =="ItemsInStock" && $dynpage["ItemsInStock"][1]=="ACT"){  ?>
                
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=orderprocessnew&c=2"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/inventory.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/inventory_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['items_in_Stock'];?></h5>
                    <span class="num_count"><?php echo $UserView->ArticleInvCount;?></span> </a> </div>
                </div>
                
                 <?php }if($dynpage["Invoices"][2] =="Invoices" && $dynpage["Invoices"][1]=="ACT"){  ?>
                 
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=invoices"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/invoice.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/invoice_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['invoices'];?></h5>
                    </a> </div>
                </div>
                
                <?php }  ?>
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  <div class="usr_profile">
        <div class="">
          <div class="panel panel-default">
            <div class="panel-heading"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_MY_PROFILE');?> </div>
            <div class="panel-body">
              <div class="row">
                  <?php  if($dynpage["PersonalInformation"][2] =="PersonalInformation" && $dynpage["PersonalInformation"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=personalinformation"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/user_info.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/user_info_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['personal_information'];?></h5>
                    </a> </div>
                </div>
                <?php }if($dynpage["Documents"][2] =="Documents" && $dynpage["Documents"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=personalinformation&c=2"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/documents.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/documents_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['documents'];?></h5>
                    </a></div>
                </div>
                  <?php }if($dynpage["ChangePassword"][2] =="ChangePassword" && $dynpage["ChangePassword"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=changepassword"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/change_pass.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/change_pass_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['change_password'];?></h5>
                    </a> </div>
                </div>
                <?php }  ?>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      
       <?php   if($dynpage["PickUpOrder"][1]=="ACT" && $dynpage["Quotation"][1]=="ACT"){ ?>
     
      <div class="usr_orders">
        <div class="">
          <div class="panel panel-default">
            <div class="panel-heading"> <?php echo Jtext::_('COM_USERPROFILE_DASHBOARD_ORDERS');?> </div>
            <div class="panel-body">
              <div class="row">
                  <?php   if($dynpage["Quotation"][2] =="Quotation" && $dynpage["Quotation"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=quotations" > <!-- style="pointer-events: none" --> 
                    <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/quotation.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/quotation_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['qUOTATION'];?></h5>
                    </a> </div>
                </div>
                <?php  }if($dynpage["PickUpOrder"][2] =="PickUpOrder" && $dynpage["PickUpOrder"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=pickuporders" > <!-- style="pointer-events: none" --> 
                    <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/pickup.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/pickup_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['pickup_order'];?></h5>
                    </a> </div>
                </div>
                 <?php  }if($dynpage["ViewShipments"][2]=="ViewShipments" && $dynpage["ViewShipments"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=viewshipments" > <!-- style="pointer-events: none" --> 
                    <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/view_ship.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/view_ship_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['view_Shipments'];?></h5>
                    </a> </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <?php } ?>
    
     <div class="usr_settings">
        <div class="">
          <div class="panel panel-default">
            <div class="panel-heading"> <?php echo Jtext::_('COM_USERPROFILE_SETTINGS');?> </div>
            <div class="panel-body">
              <div class="row">
                   <?php  if($dynpage["Calculator"][2] =="Calculator" && $dynpage["Calculator"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=calculator"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/calculator.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/calculator_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['calculator'];?></h5>
                    </a> </div>
                </div>
                 <?php  }if($dynpage["ShopperAssist"][2] =="ShopperAssist" && $dynpage["ShopperAssist"][1]=="ACT"){ ?>
                <div class="col-sm-4">
                  <div class="dash_option_blck"> <a href="index.php?option=com_userprofile&view=user&layout=shopperassist"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/shopper_assist.png" class="img-responsive img_view"> <img src="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/images/shopper_assist_hover.png" class="img-responsive img_hvr">
                    <h5><?php echo $assArr['shopper_Assist'];?></h5>
                    <span class="num_count"><?php echo $UserView->ShopperAsstCount;?></span></a> </div>
                </div>
                 <?php  }if($dynpage["COD"][2] =="COD" && $dynpage["COD"][1]=="ACT"){ ?>
                <div class="col-sm-4"> 
                  <div class="dash_option_blck">
                  	<a href="index.php?option=com_userprofile&view=user&layout=cod">
                  	<img src="<?php echo JUri::base(); ?>/components/com_userprofile/images/pickup.png" class="img-responsive img_view">
                  	<img src="<?php echo JUri::base(); ?>/components/com_userprofile/images/pickup_hover.png" class="img-responsive img_hvr">
                  	<h5><?php echo $assArr['cOD'];?></h5>
                  	</a>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!--Dashboard rightside section end-->
	</div>
  </div>
  </div>
  </div>
  <!--</div>-->
