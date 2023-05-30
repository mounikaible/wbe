<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Register
 * @author     Srikanth <srikanth.aare@iblesoft.com>
 * @copyright  2021 Srikanth
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
require_once JPATH_ROOT.'/components/com_register/helpers/register.php';

$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$res=JRequest::getVar('res');

$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$mainPageDetails = RegisterHelpersRegister::getmainpagedetails();
$domainName =  $domainDetails[0]->Domain;

if($user){
$app =& JFactory::getApplication();
$app->redirect('index.php?option=com_userprofile&view=user');
//$this->setRedirect(JRoute::_('', false));
}


$canEdit = JFactory::getUser()->authorise('core.edit', 'com_register');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_register'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}

//get labels
   
   if(strpos($_SERVER['REQUEST_URI'], '/index.php/') !== false){
    $strplace = strpos($_SERVER['REQUEST_URI'], '/index.php/');
    $langplace = $strplace + 11;
    $language = substr($_SERVER['REQUEST_URI'],$langplace,2);
}
    $res1=Controlbox::getlabels($language);
    $assArr = [];
    
    foreach($res1 as $response){
        $assArr[$response['Id']]  = $response['Text'];
     }


?>

<style>

.clearable__clear{
  font-style: normal;
    font-size: 2em;
    user-select: none;
    cursor: pointer;
    position: absolute;
    top: 24px;
    right: 10px;
    opacity:0.2;
}
.lognew_sec{
    position: relative;
}

</style>

<div class="item_fields">


  <form name="registerFormOne" id="registerFormOne" method="post" action="">
    <!-- LogIn Page -->
      <div class="container">
          
         <div class="col-md-4 col-sm-12">
            <div class="loggin_view">
          <div class="main_panel">
            <div class="main_heading"> <?php echo JText::_('COM_REGISTER_LOGIN_LABEL'); ?> </div>
            
            <div class="panel-body">
                
              
                
                 <!--Error Msg-->
            
            <?php
            
             
            
            if($res == '0' ){
                $errorMsg = JText::_('COM_REGISTER_LOGIN_ERROR');
           ?>
             
             <div class="alertmsgsec" >   
                <div class="alert alert-danger alert-dismissible"  role="alert">
                    <span class="login-errormsg"><?php echo $errorMsg;  ?></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div> 
            
            <?php
            
            }
            
            ?>
                
              <div class="form-group lognew_sec">
                <label><?php echo JText::_('COM_REGISTER_USERNAME_LABEL'); ?> <span class="error">*</span></label>
                <input type="text" class="form-control" name="unameTxt" id="unameTxt">
                <i class="clearable__clear">&times;</i>
              </div>
              <div class="form-group lognew_sec">
                <label><?php echo JText::_('COM_REGISTER_PASSWORD_LABEL'); ?> <span class="error">*</span></label>
                <input type="password" class="form-control" name="passwordTxt" id="passwordTxt">
                <i class="clearable__clear">&times;</i>
              </div>
              <div class="form-group">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"><?php echo JText::_('COM_REGISTER_LOGIN_LABELS'); ?></button>
                <a class="btn btn-link btn-block pageloader_link" href="<?php echo JRoute::_('index.php?option=com_register&view=forgotpassword'); ?>"><?php echo JText::_("COM_REGISTER_FORGOT_PASSWORD"); ?></a>
                </div>
            </div>
          </div>
        </div>
        </div>
         <div class="col-md-8 col-sm-12 ntfctin-blk">
               <div class="">
               <div class="main_panel login-frm notification_panel">
            <div class="main_heading">
               <?php echo $assArr['notifications']; ?>
            </div>
            <div class="panel-body" >
               <?php
               
               
               
               if(!isset($mainPageDetails)){
                  echo '<img src="'.JURI::base().'/images/cmg-soon-image.png" >';
               }
               
               $config = JFactory::getConfig();
               
                if(strtolower($domainName) != "kupiglobal"){
                  
                $nb_elem_per_page = 5;
                $page = isset($_GET['page'])?intval($_GET['page']):1;
                $number_of_pages = ceil(count($mainPageDetails)/$nb_elem_per_page);
                
                        foreach(array_slice($mainPageDetails, ($page-1)*$nb_elem_per_page, $nb_elem_per_page) as $data){
                            $str = '$id';
                            if(strlen($data->Content) > 100){
                                $content = substr(strip_tags($data->Content),0,100);
                                $content .= '...<a href="index.php/en/component/register/notifications?Itemid=131#'.$data->$str.'" class="ntifiction-link">Read more</a>';
                            }else{
                                $content = strip_tags($data->Content);
                            }
                           echo '<div class="row ntifiction-info"><a href="index.php/en/component/register/notifications?Itemid=131#'.$data->$str.'" >'.$data->Heading.'</a><p>'.$content.'</p></div>';
                        
                       }
                       
                    ?>
                    
               
 <?php             }else{ 
                            // foreach($mainPageDetails as $data){
                            // $str = '$id';
                            // echo '<div id="'.$data->$str.'" class="" id="notification"><h4>'.$data->Heading.'</h4>';

                            // $doc = new DOMDocument();
                            // $doc->loadHTML($data->Content);

                            // $htmlString = $doc->saveHTML();
                            // echo '<p>'.$htmlString.'</p></div>';

                            // }

                            ?>

                            <div class="row">
                            <div class="col-sm-12 tab_view">
                              <ul class="nav nav-tabs">
                               
                                      <li> <a class="active" href="">Tab1</a> </li>
                                      <li> <a class="active" href="">Tab2</a> </li>
                                      <li> <a class="active" href="">Tab3</a> </li>
                                      <li> <a class="active" href="">Tab4</a> </li>
                                      <li> <a class="active" href="">Tab5</a> </li>
 
                              </ul>
                            </div>
                          </div>


           <?php   }
              
             
               ?>
            </div>
            <div class="panel-footer">
                <ul id='paginator' class="pagination">
                    <?php
                    
                    if($page>1){
                        $pre_page = intval($page)-1;
                        $pagination_bef=JRoute::_('index.php?option=com_register&view=login&page='.$pre_page);
                        echo '<li><a href ="'.$pagination_bef.'">&laquo;</a></li>';
                    }
                      
                      for($i=1;$i<=$number_of_pages;$i++){ 
                      
                                    if($i==$page){
                                    
                                    $active="active";
                                    
                                    }else{
                                        $active="";
                                    } ?>
                                    
                                   <li><a href="<?php echo JRoute::_('index.php?option=com_register&view=login&page='.$i); ?>" <?php if($_GET['page']==$i){ echo 'class="active_col"'; }else if($i == 1 && !isset($_GET['page'])){ echo 'class="active_col"'; } ?> ><?=$i?></a></li>
                                    
                       <?php  } 
                         
                         if($number_of_pages>$page){
                            $next_page = intval($page)+1;
                            $pagination_after=JRoute::_('index.php?option=com_register&view=login&page='.$next_page);
                            echo '<li><a href = "'.$pagination_after.'">&raquo;</a></li>';
                         }
                         
                         ?>
                        
                    </ul>
            </div>
          </div>
          </div>
          </div>
      </div>
    <!-- LogIn Page -->
    <input type="hidden" name="task" value="register.login">
    <input type="hidden" name="id" value="0" />
    <input type="hidden" name="itemid" value="<?php echo $_GET['Itemid'];?>" />
  </form>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {

	$joomla(":reset").on('click',function(){
        $joomla('label.error').hide();
	});

	
		$joomla("form[name='registerFormOne']").validate({
			
		
			rules: {
			 
			  unameTxt: "required",
			  passwordTxt: {
				required: true,
				minlength: 4
              }
			},
		
			messages: {
			  unameTxt: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_USERNAME'); ?>",
			  passwordTxt: {
                  required: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_PASSWORD'); ?>",
                  minlength: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_MUST_5_CHARACTERS'); ?>"
              },
              emailTxt: "<?php echo JText::_('COM_REGISTER_PLEASE_ENTER_YOUR_VALID_EMAIL'); ?>"
      
			},
		
			submitHandler: function(form) {
			     $joomla('.page_loader').show();
			     form.submit();
			}
		});


	// clear text start
	
	$joomla(".clearable__clear").hide();
	
	$joomla("input").on('keyup',function(){
	    
	    in_length = $joomla(this).val().length;
	    
	    if(in_length > 0){
	    
	    $joomla(this).parent().find(".clearable__clear").show();
	    
	    }else{
	        
	        $joomla(this).parent().find(".clearable__clear").hide();
	    }
	    
	});
	
	$joomla(".clearable__clear").on('click',function(){
        $joomla(this).parent().find("input").val("");
        $joomla(this).hide();
	});
	
	$joomla(".btn-danger").on('click',function(){
	    $joomla(".clearable__clear").hide();
	});

	
	// clear text end


});
</script>

