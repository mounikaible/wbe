<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->setTitle("Ticket in Boxon Pobox Software");
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');




?>
<?php include 'dasboard_navigation.php' ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_register/css/style_global.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->  
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script>
	 
	 var $joomla = jQuery.noConflict(); 
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


</script>
<div class="container">
	<div class="main_panel persnl_panel">
	    
		<div class="main_heading">Notifications</div>
		<div class="panel-body">
		    <div class="col-sm-12">
		       <div class="panel notifction_panel">
		           <div class="panel-body">
		               <!--<div class="ntifiction-info" id="notification">-->
		               <!--<h4>Lorem ipsum is dummy text <span>14 Apr ,2021 at 3.10</span></h4>-->
		               <!--<p  class="collapse" id="collapseExample" aria-expanded="false">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s-->
		               <!--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s-->
		               <!--Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>-->
		               <!-- <a role="button" class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></a>-->
		               <!--</div>-->
		               <!--<div class="ntifiction-info">-->
		               <!--<h4>Lorem ipsum is dummy text <span>14 Apr ,2021 at 3.10</span></h4>-->
		               <!--<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>-->
		               <!--<a href="#">Read More</a>-->
		              
		               <!--</div>-->
		               <!--<div class="ntifiction-info">-->
		               <!--<h4>Lorem ipsum is dummy text <span>14 Apr ,2021 at 3.10</span></h4>-->
		               <!--<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>-->
		               <!--<a href="#">Read More</a>-->
		               <!--</div>-->
		               
		               <?php
               $mainPageDetails = RegisterHelpersRegister::getmainpagedetails();
               
            //   echo '<pre>';
            //   var_dump($mainPageDetails);
            //   exit;
               
               if(!isset($mainPageDetails)){
                  echo '<img src="'.JURI::base().'/images/cmg-soon-image.png" >';
               }
               
               $config = JFactory::getConfig();
			   

			   $ntfTitle="";
               
               foreach($mainPageDetails as $data){

				$doc = new DOMDocument();
				$doc->loadHTML($data->Content);
				$tags = $doc->getElementsByTagName('img');
				
				foreach ($tags as $tag) {
					$oldSrc = $tag->getAttribute('src');
					$newScrURL = $config->get('backend_url').$oldSrc;
					$tag->setAttribute('src', $newScrURL);
					$tag->setAttribute('data-src', $oldSrc);
				} 
				
				$htmlString = $doc->saveHTML();
				// echo '<p>'.$htmlString.'</p></div>';

                 $str = '$id';
                 //echo '<div id="'.$data->$str.'" class="ntifiction-info" id="notification"><h4>'.$data->Heading.'</h4>';
				  $ntfTitle.='<div class="panel-group notifications" id="'.$data->$str.'" >
      
				  <div class="panel panel-primary">
					<div class="panel-heading">
					  <h4 class="panel-title '.$data->$str.'">
						<a>'.$data->Heading.'</a><span id="expand" class="expandPlus"></span>
					  </h4>
					</div>
					<div id="test" class="panel-collapse collapse '.$data->$str.'">
					  <div class="panel-body"> 
					  <p>'.$htmlString.'</p>
					</div>
					</div>
				  </div>
				
				</div>';
				// echo  $ntfTitle;
			   }
			   echo  $ntfTitle;
				   //  <div class="panel-collapse collapse">
			
                   
                        // $doc = new DOMDocument();
                        // $doc->loadHTML($data->Content);
                        // $tags = $doc->getElementsByTagName('img');
                        
                        // foreach ($tags as $tag) {
                        //     $oldSrc = $tag->getAttribute('src');
                        //     $newScrURL = $config->get('backend_url').$oldSrc;
                        //     $tag->setAttribute('src', $newScrURL);
                        //     $tag->setAttribute('data-src', $oldSrc);
                        // } 
                        
                        // $htmlString = $doc->saveHTML();
                        // echo '<p>'.$htmlString.'</p></div>';
                    
               
			   
               ?>
		               
		               
		           </div>
            </div>
		    </div>
		    
		</div>
	</div>
</div>


<script>
	$joomla(document).ready(function() {
	    
	    var headerHeight = $joomla('header').outerHeight();
	    
	    
		urlString = window.location.href;
		let paramString = urlString.split('?')[1];
		let queryString = new URLSearchParams(paramString);

		for(let pair of queryString.entries()) {
		idNum = pair[1];
		}
		var idNumArr = idNum.split("#");
		$joomla('.collapse.'+idNumArr[1]).toggle();
		$joomla('.panel-title.'+idNumArr[1]).find("span").attr("class","expandMinus");
	
	});
</script>