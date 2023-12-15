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
ini_set('memory_limit', '44M');

class Controlbox{
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
     
     public static function getCompanyId()
    {
        require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
        $domainDetails = ModProjectrequestformHelper::getDomainDetails();
        $CompanyId = $domainDetails[0]->CompanyId;
        
        return $CompanyId;
       
    }
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
     public static function getPaypalEmail()
    {
        require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
        $domainDetails = ModProjectrequestformHelper::getDomainDetails();
        $PaypalEmail = $domainDetails[0]->PaypalEmail;
        return $PaypalEmail;
    }
    
    
   
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function setRegister($fname,$lname,$addressone,$addresstwo,$pin,$phone,$acctype,$email,$country,$state,$city,$password,$agency)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Register';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	    curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","FirstName":"'.$fname.'","LastName":"'.$lname.'","Address1":"'.$addressone.'","Address2":"'.$addresstwo.'","PostalCode":"'.$pin.'","PhoneNumber":"'.$phone.'","AccountType":"'.$acctype.'","Email":"'.$email.'","Country":"'.$country.'","State":"'.$state.'","City":"'.$city.'","Password":"'.$password.'","ConfirmPassword":"'.$password.'","UpdatedOn":"","ActivationKey":"123456789","AgencyId":"'.$agency.'"}');
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","FirstName":"'.$fname.'","LastName":"'.$lname.'","Address1":"'.$addressone.'","Address2":"'.$addresstwo.'","PostalCode":"'.$pin.'","PhoneNumber":"'.$phone.'","AccountType":"'.$acctype.'","Email":"'.$email.'","Country":"'.$country.'","State":"'.$state.'","City":"'.$city.'","Password":"'.$password.'","ConfirmPassword":"'.$password.'","UpdatedOn":"","ActivationKey":"123456789","AgencyId":"'.$agency.'"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg;
    }
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getLogin($user,$pass)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/AccountApi/Login';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Password":"'.$pass.'","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Password":"'.$pass.'","ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg;
    }
    
    
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getMenuAccess($user,$pass)
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/AccountApi/Login';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Password":"'.$pass.'","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","UserName":"'.$user.'","Password":"'.$pass.'","ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);

        $resStr = '';
        if($msg->ResCode==1){
            
            foreach($msg->menuAccesses as $access){
                $resStr .= $access->MenuItem.",".$access->Access.":";
            }
            
            $resStr .=$msg->CustCompanyType;
            
            return $resStr;
        }
        else
        return 0;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getExistTracking($track)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsAPi/isExistTrackingId?TrackingId='.$track.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        return $result;
    }
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getExistTrackingTicket($track,$user)
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsAPi/isExistTrackIdWithCustId?TrackingId='.$track.'&CompanyID='.$CompanyId.'&CustomerId='.$user.'';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        return $result;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getDocumentList($id)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DocumentsApi/getDocuments?custId='.$id.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }       

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getDocumentListAjax($id)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DocumentsApi/getDocuments?custId='.$id.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $UserDocumentView=$msg->Data;
        $html='';
        if($UserDocumentView->identity_form_doc){ $fileExt=$UserDocumentView->identity_form_Name.'.'.end((explode('/', $UserDocumentView->identity_form_content_type)));
        $html.='<div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label>'.Jtext::_("COM_USERPROFILE_MYDOC_PHOTO_ID").'</label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="1:'.$UserDocumentView->identity_form_doc.'" >
                '.end((explode('/', $UserDocumentView->identity_form_Name))).'</label>
                <input type="hidden" name="downfilenameone" value="'.$fileExt.'" >
              </div>
            </div>
          </div>';
        } 
        if($UserDocumentView->form_doc){$fileExt2=$UserDocumentView->form_name.'.'.end((explode('/', $UserDocumentView->form_content_type)));
        $html.='<div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label>'.Jtext::_("COM_USERPROFILE_FORM_1583").'</label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="2:'.$UserDocumentView->form_doc.'">
                '.end((explode('/', $UserDocumentView->form_name))).'</label>
                <input type="hidden" name="downfilenametwo" value="'.$fileExt2.'" >
              </div>
            </div>
          </div>';
        }
        if($UserDocumentView->utility_doc){$fileExt3=$UserDocumentView->utility_name.'.'.end((explode('/', $UserDocumentView->utility_content_type)));
        $html.=' <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label>'.Jtext::_("COM_USERPROFILE_UTILITY_BILLS").'</label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="3:'.$UserDocumentView->utility_doc.'">
                '.end((explode('/', $UserDocumentView->utility_name))).'</label>
                <input type="hidden" name="downfilenamethree" value="'.$fileExt3.'" >
              </div>
            </div>
          </div>';
        }
        if($UserDocumentView->other_doc){$fileExt4=$UserDocumentView->other_name.'.'.end((explode('/', $UserDocumentView->other_content_type)));
        $html.='  <div class="form-group">
            <div class="row">
              <div class="col-sm-2">
                <label>'.Jtext::_("COM_USERPROFILE_MYDOC_OTHERS").'</label>
              </div>
              <div class="col-sm-10">
                <label class="radio-inline">
                <input type="radio" name="downfile" value="4:'.$UserDocumentView->other_doc.'">
                '.end((explode('/', $UserDocumentView->other_name))).'</label>
                <input type="hidden" name="downfilenamefour" value="'.$fileExt4.'" >
              </div>
            </div>
          </div>';
        }         
        return $html.'<input type="hidden" name="taskmethod" value="'.$UserDocumentView->sendCommandType.'" />';
    }       

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getCountriesList()
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Countries?ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        // echo $url;
        // var_dump($result);exit;
        return $result;
    }       


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getStatesList($countryid,$stateid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/States?countryId='.$countryid.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $states='';
        $states='<option value="" selected >'.Jtext::_('COM_USERPROFILE_SELECT').'</option>';
        $result = json_decode($result); 
        foreach($result->Data as $rg){
          if($stateid==$rg->StatesId){echo $sel="selected";}else{ $sel="";}
          $states.= '<option value="'.$rg->StatesId.'" '.$sel.'>'.$rg->StateDesc.'</option>';
        }        
        return $states;
    } 
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getStatesListPickup($countryid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/States?countryId='.$countryid.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $states='';
         $states.= '<option value="">Select State</option>';
        $result = json_decode($result); 
        foreach($result->Data as $rg){
          $states.= '<option value="'.$rg->StatesId.'">'.$rg->StateDesc.'</option>';
        }        
        return $states;
    }       


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesList($countryid,$stateid,$cityid)
    {
        
        
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Cities?stateId='.urlencode($stateid).'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        $rescities = json_decode($result); 
        $cities='';
        foreach($rescities->Data as $rg){
           if($cityid==$rg->CityCode){ $sel=" selected";}else{ $sel="";}
           //$cities.= '<option value="'.$rg->CityDesc.'" data-xyz="'.$rg->CityCode.'" '.$sel.'>'.$rg->CityDesc.'</option>';
           $cities.= '<option value="'.$rg->CityCode.'"'.$sel.'>'.$rg->CityDesc.'</option>';
           
        }        
        return $cities;
    }  
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesListPickup($stateid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Cities?stateId='.urlencode($stateid).'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        $rescities = json_decode($result); 
        $cities='';
        $cities.= '<option value="">Select City</option>';
        foreach($rescities->Data as $rg){
           //$cities.= '<option value="'.$rg->CityDesc.'" data-xyz="'.$rg->CityCode.'" '.$sel.'>'.$rg->CityDesc.'</option>';
           $cities.= '<option value="'.$rg->CityCode.'" '.$sel.'>'.$rg->CityDesc.'</option>';
        }        
        return $cities;
    }  
    
    
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesListCon($cid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Cities?stateId='.urlencode($cid).'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $rescities = json_decode($result); 
        $cities='';
        foreach($rescities->Data as $rg){
          //echo '<br>'.$rescities[0]->CityCode;
          $cities.= '<option value="'.$rg->CityDesc.'" data-xyz="'.$rg->CityCode.'" >'.$rg->CityDesc.'</option>';
        }        
        return $cities;
    }  



     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getPickupOderInfo($user,$quid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/getAdditionalPickUpOrderInfo?custId='.$user.'&QuoteNumber='.$quid.'&CompanyID='.$CompanyId;;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //$result = json_decode($result); 
        //var_dump($result);exit;
        return $result;
    }   


/**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getQuotationShipmentsListFilter($user,$pickup,$quotation)
    {
        
        mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/GetShipments?custId='.$user.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        $rs='';
        $i=1;
        foreach($msg as $rg){
            $cv='';
            if($rg->number_pickup_order == '' && $rg->status == 'Approved'){
              $cv='<td class="convertToPickup" style="text-align:center;"><a data-id="'.$rg->number_quotation.':'.$rg->id_cust.'">Click Here</a></td>'; 
            }else{
              $cv='<td style="text-align:center;">-</td>';
            }
              if($pickup == "True" && $quotation == "True"){
                $rs.= '<tr><td>'.$i.'</td><td>'.$rg->number_quotation.'</td>'.$cv.'<td>'.$rg->number_pickup_order.'</td><td>'.$rg->bill_form_no.'</td><td>'.$rg->status.'</td><td>'.$rg->totalQty.'</td><td>'.$rg->dti_created.'</td></tr>';
              }else if($pickup == "True"){
                  if($rg->number_pickup_order != '')
                  $rs.= '<tr><td>'.$i.'</td><td>'.$rg->number_pickup_order.'</td><td>'.$rg->bill_form_no.'</td><td>'.$rg->status.'</td><td>'.$rg->totalQty.'</td><td>'.$rg->dti_created.'</td></tr>';
                  
              }elseif($quotation == "True"){    
                  if($rg->number_quotation != '')
                   $rs.= '<tr><td>'.$i.'</td><td>'.$rg->number_quotation.'</td>'.$cv.'<td>'.$rg->bill_form_no.'</td><td>'.$rg->status.'</td><td>'.$rg->totalQty.'</td><td>'.$rg->dti_created.'</td></tr>';
              }
          
          $i++;
        }        
        return $rs;
   }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function addconvertpickup($CustId,$txtShipperName,$txtShipperAddress,$txtConsigneeName,$txtConsigneeAddress,$txtThirdPartyName,$txtThirdPartyAddress,$txtChargableWeight,$txtName,$txtPickupDate,$txtPickupAddress,$QuoteNumberTxt,$hiddenConsigneeAsCustomer,$hiddenThirdpartyAsCustomer,$hiddenShipperAsCustomer)
    {
        $txtShipperNames=explode(":",$txtShipperName);
        $txtConsigneeNames=explode(":",$txtConsigneeName);
        $txtThirdPartyNames=explode(":",$txtThirdPartyName);
        $QuoteNumberTxts=explode(":",$QuoteNumberTxt);
        
        mb_internal_encoding('UTF-8');
        
      
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/ConvertToPickup';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","QuoteNumber":"'.$QuoteNumberTxts[0].'","IdCust":"'.$QuoteNumberTxts[1].'","IdServ":"'.$QuoteNumberTxts[1].'","Shipment_Id":"","ShipperId":"'.$QuoteNumberTxts[1].'","ShipperName":"'.$txtShipperNames[0].'","ShipperAddress":"'.$txtShipperAddress.'","ConsigneeId":"'.$txtConsigneeNames[0].'","ConsigneeName":"'.$txtConsigneeNames[1].'","ConsigneeAddress":"'.$txtConsigneeAddress.'","BitThirdPartySameAsCust":"'.$hiddenThirdpartyAsCustomer.'","ThirdPartyId":"'.$QuoteNumberTxts[1].'","ThirdPartyName":"'.$txtThirdPartyNames[0].'","ThirdPartyAddress":"'.$txtThirdPartyAddress.'","BitConSameAsCust":"'.$hiddenConsigneeAsCustomer.'","BitShipperSameAsCust":"'.$hiddenShipperAsCustomer.'","PickUpInfo":{"Name":"'.$txtName.'","PickupDate":"'.$txtPickupDate.'","PickupAddr":"'.$txtPickupAddress.'"}}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);

        // echo $url;
        // echo '{"CompanyID":"'.$CompanyId.'","QuoteNumber":"'.$QuoteNumberTxts[0].'","IdCust":"'.$QuoteNumberTxts[1].'","IdServ":"'.$QuoteNumberTxts[1].'","Shipment_Id":"","ShipperId":"'.$QuoteNumberTxts[1].'","ShipperName":"'.$txtShipperNames[0].'","ShipperAddress":"'.$txtShipperAddress.'","ConsigneeId":"'.$txtConsigneeNames[0].'","ConsigneeName":"'.$txtConsigneeNames[1].'","ConsigneeAddress":"'.$txtConsigneeAddress.'","BitThirdPartySameAsCust":"'.$hiddenThirdpartyAsCustomer.'","ThirdPartyId":"'.$QuoteNumberTxts[1].'","ThirdPartyName":"'.$txtThirdPartyNames[0].'","ThirdPartyAddress":"'.$txtThirdPartyAddress.'","BitConSameAsCust":"'.$hiddenConsigneeAsCustomer.'","BitShipperSameAsCust":"'.$hiddenShipperAsCustomer.'","PickUpInfo":{"Name":"'.$txtName.'","PickupDate":"'.$txtPickupDate.'","PickupAddr":"'.$txtPickupAddress.'"}}';
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Msg;
    }
   
    public function updateInvoiceDetails($invData,$itemIdk) {

        $imageByteStream = array();
        $fileName = array();
        $fileExt = array();
        $mulfilepath = array();
        $i=0;

        foreach($invData as $data){
                    $photodest = JPATH_SITE. "/media/com_userprofile/".$invData[$i];
                    $image = file_get_contents($photodest);
                    $imageByteStream[$i] = base64_encode($image);
                    $fileData=explode("/",$invData[$i]);
                    $itemImage[$i]=$fileData[1];
                    $fileName[$i]=pathinfo($fileData[1], PATHINFO_FILENAME );
                    $fileExt[$i] ='.'.pathinfo($fileData[1], PATHINFO_EXTENSION );
                    $mulfilepath[$i] = $invData[$i];
            $i++;
        }

        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $CompanyId = Controlbox::getCompanyId();

        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/UploadFile';
        $req = '{
            "idk": "'.$itemIdk.'",    
            "listVM":[
             {
            "ItemImage": "'.$mulfilepath[0].'",
            "ItemImage1": "'.$mulfilepath[1].'",
            "ItemImage2": "'.$mulfilepath[2].'",
            "ItemImage3": "'.$mulfilepath[3].'",
            "ItemImage4": "",
            "ImageByteStream": "" ,
            "ImageByteStream1": "" ,
            "ImageByteStream2": "" ,
            "ImageByteStream3": "" ,
            "ImageByteStream4": "",
            "fileName": "'.$fileName[0].'",
            "fileName1": "'.$fileName[1].'",
            "fileName2": "'.$fileName[2].'",
            "fileName3": "'.$fileName[3].'",
            "fileName4": "",
            "fileExtension": "'.$fileExt[0].'",
            "fileExtension1": "'.$fileExt[1].'",
            "fileExtension2": "'.$fileExt[2].'",
            "fileExtension3": "'.$fileExt[3].'",
            "fileExtension4": ""
                  }
              ]       
          }';


        /** Debug **/

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$req);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		$msg=json_decode($result);
		
	    // echo $url;
		// echo $req;
		// var_dump($msg);
		// exit;

        // var_dump($msg->Response.":".$msg->Description);
        // exit;

        return $msg->Response.":".$msg->Description;
    }

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getPickupOderShippernameId($id)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/getAdditionalUser?UserType=ShipperUser&User=Shipper&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $result = json_decode($result); 
        
        // echo $url;
        // var_dump($result);exit;
        
        $cts='';
        if($id==1)
        return $result->IdAdduser;
        else
        foreach($result->Cntry_List as $rg){
          $cts.= '<option value="'.$rg->id_values.'" '.$sel.'>'.$rg->desc_vals.'</option>';
        }        
        return $cts;
    }   

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getPickupOderConsigneeId($id)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/getAdditionalUser?UserType=ConsigneeUser&User=Consignee&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $result = json_decode($result); 
        //var_dump($result);exit;
        $cts='';
        if($id==1)
        return $result->IdAdduser;
        else
        foreach($result->Cntry_List as $rg){
          $cts.= '<option value="'.$rg->id_values.'" '.$sel.'>'.$rg->desc_vals.'</option>';
        }        
        return $cts;
    }   
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getCodorders($id)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsApi/liOfCodOrders?custId='.$id.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $result = json_decode($result); 
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        return $result->Data;
    }   
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getPickupOderThirdpartyId($id)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/getAdditionalUser?UserType=DeliveryUser&User=Third%20Party&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $result = json_decode($result); 
        
        // echo $url;
        // var_dump($result);exit;
        
        $cts='';
        if($id==1)
        return $result->IdAdduser;
        else
        foreach($result->Cntry_List as $rg){
          $cts.= '<option value="'.$rg->id_values.'" '.$sel.'>'.$rg->desc_vals.'</option>';
        }        
        return $cts;
    }   

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getPickupFieldviewsList($cid)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/getPickupOrder?custId='.$cid.'&Activationkey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }       

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getQuotationFieldviewsList($cid)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/getQuoteId?custId='.$cid.'&Activationkey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }       

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getCalculatorFieldviewsList($cid)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/getPickupOrder?custId='.$cid.'&Activationkey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }       


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getEmailExist($email)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetEmail?Email='.$email.'&ActivationKey=123456789&AdditionalUser=TRUE&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;exit;
        
        $msg=json_decode($result);
        return $msg->Response.":".$msg->Description;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDeleteOrder($id)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/DeleteMypurchase?Id='.$id.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Response;
    }



    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function deleteDownloadfile($custid,$id)
    {
        if($id==1){
        $id="Identification";
        }elseif($id==2){
        $id="Form";
        }elseif($id==3){
        $id="Utility";
        }elseif($id==4){
            $id="Other";
        }
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DocumentsApi/DeleteFile?CustId='.$custid.'&DocumentName='.$id.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Response;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDeleteShopper($id)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShopperAssistAPI/deleteItem?itemid='.$id.'&status=delete&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Response;
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getTicketnumber()
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/SupportTicketAPI/AutoGenerateTicketNumber?ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }


   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUpdatePurchaseDetails($itd)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/GetPurchaseOrderById';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","ItemId":"'.$itd.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","ItemId":"'.$itd.'"}';
// 	    var_dump($result);exit;
		
		$msg=json_decode($result);
        $msg2=$msg->Data;
        return $msg2->ItemId.':'.$msg2->SupplierId.':'.$msg2->CarrierId.':'.$msg2->OrderDate.':'.$msg2->TrackingId.':'.$msg2->ItemName.':'.$msg2->ItemQuantity.':'.$msg2->ItemPrice.':'.$msg2->cost.':'.$msg2->itemstatus.':'.$msg2->fnsku.':'.$msg2->sku.':'.str_replace(":","##",$msg2->ItemImage).':'.$msg2->Dest_Cntry.':'.$msg2->Dest_Hub.':'.str_replace(":","##",$msg2->ItemImage1).':'.str_replace(":","##",$msg2->ItemImage2).':'.str_replace(":","##",$msg2->ItemImage3).':'.str_replace(":","##",$msg2->ItemImage4).':'.$msg2->OrderIdNew.':'.$msg2->RMAValue.':'.$msg2->length.':'.$msg2->height.':'.$msg2->width.':'.$msg2->type_busines.':'.$msg2->PackageType;        
    }



   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function insertDocument($CustId,$photoname, $photosrc,$phototype, $formname,$formsrc,$formtype,$utilityname,$utilitysrc,$utilitytype,$othername,$othersrc,$othertype,$photodest1,$formdest1,$utilitydest1,$otherdest1)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DocumentsApi/insertOrUpdateDocument';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","ActivationKey":"123456789","commandType":"Insert","identity_form_Name":"'.$photoname.'","identity_form_doc":"'.$photosrc.'","identity_form_content_type":"'.$phototype.'","form_name":"'.$formname.'","form_doc":"'.$formsrc.'","form_content_type":"'.$formtype.'","utility_name":"'.$utilityname.'","utility_doc":"'.$utilitysrc.'","utility_content_type":"'.$utilitytype.'","other_name":"'.$othername.'","other_doc":"'.$othersrc.'","other_content_type":"'.$othertype.'","identity_form_doc_ftppath":"'.$photodest1.'","form_doc_ftppath":"'.$formdest1.'","utility_doc_ftppath":"'.$utilitydest1.'","other_doc_ftppath":"'.$otherdest1.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // echo '{"CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","ActivationKey":"123456789","commandType":"Insert","identity_form_Name":"'.$photoname.'","identity_form_doc":"'.$photosrc.'","identity_form_content_type":"'.$phototype.'","form_name":"'.$formname.'","form_doc":"'.$formsrc.'","form_content_type":"'.$formtype.'","utility_name":"'.$utilityname.'","utility_doc":"'.$utilitysrc.'","utility_content_type":"'.$utilitytype.'","other_name":"'.$othername.'","other_doc":"'.$othersrc.'","other_content_type":"'.$othertype.'","identity_form_doc_ftppath":"'.$photodest1.'","form_doc_ftppath":"'.$formdest1.'","utility_doc_ftppath":"'.$utilitydest1.'","other_doc_ftppath":"'.$otherdest1.'"}';
       	// var_dump($result);exit;
       	
		$msg=json_decode($result);
		return $msg->Description;
    }



   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */                                        
    public static function updateDocument($CustId,$photoname, $photosrc,$phototype, $formname,$formsrc,$formtype,$utilityname,$utilitysrc,$utilitytype,$othername,$othersrc,$othertype,$photodest1,$formdest1,$utilitydest1,$otherdest1)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DocumentsApi/insertOrUpdateDocument';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","ActivationKey":"123456789","commandType":"Update","identity_form_Name":"'.$photoname.'","identity_form_doc":"'.$photosrc.'","identity_form_content_type":"'.$phototype.'","form_name":"'.$formname.'","form_doc":"'.$formsrc.'","form_content_type":"'.$formtype.'","utility_name":"'.$utilityname.'","utility_doc":"'.$utilitysrc.'","utility_content_type":"'.$utilitytype.'","other_name":"'.$othername.'","other_doc":"'.$othersrc.'","other_content_type":"'.$othertype.'","identity_form_doc_ftppath":"'.$photodest1.'","form_doc_ftppath":"'.$formdest1.'","utility_doc_ftppath":"'.$utilitydest1.'","other_doc_ftppath":"'.$otherdest1.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","ActivationKey":"123456789","commandType":"Update","identity_form_Name":"'.$photoname.'","identity_form_doc":"'.$photosrc.'","identity_form_content_type":"'.$phototype.'","form_name":"'.$formname.'","form_doc":"'.$formsrc.'","form_content_type":"'.$formtype.'","utility_name":"'.$utilityname.'","utility_doc":"'.$utilitysrc.'","utility_content_type":"'.$utilitytype.'","other_name":"'.$othername.'","other_doc":"'.$othersrc.'","other_content_type":"'.$othertype.'","identity_form_doc_ftppath":"'.$photodest1.'","form_doc_ftppath":"'.$formdest1.'","utility_doc_ftppath":"'.$utilitydest1.'","other_doc_ftppath":"'.$otherdest1.'"}';
// 		var_dump($result);exit;
		
		$msg=json_decode($result);
		return $msg->Description;
    }


   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPackageDetails($itd)
    {
        
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/GetPackageDetailsById?PkgId='.$itd.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		//var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Length.':'.$msg->Width.':'.$msg->Height;
        
    }


   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShipDetails($itd)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/GetHistroyIdkDetails';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CmdType":"History","Idk":"'.$itd.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		//var_dump($result);
		$msg=json_decode($result);
		return $msg->Data->idk.':'.$msg->Data->cmdType.':'.$msg->Data->comments.':'.$msg->Data->ShippingDetails.':'.$msg->Data->DocumentationCharges.':'.$msg->Data->ShippingCost.':'.$msg->Data->FinalCost.':'.$msg->Data->AmountPaid.':'.$msg->Data->AmountPayable.':'.$msg->Data->PaymentMethod.':'.$msg->Data->TransactionNumber.':'.$msg->Data->InvoiceNumber.':'.$msg->Data->Date.':'.$msg->Data->AmountPaidPaid;
    
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getMomentoLog($rec)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/getmomentumlog?billformno='.$rec.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $mlg='';
       foreach($msg->Data as $rg){
            if($rg->Status=="Ship"){
            $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_SHIP');
         }else if($rg->Status=="Received"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RECEIVED');
         }else if($rg->Status=="In Progress"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_IN_PROGRESS');
         }else if($rg->Status=="Discard"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_DISCARD');
         }else if($rg->Status=="Hold"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_HOLD');
         }else if($rg->Status=="Return"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_RETURN');
         }else if($rg->Status=="Finished"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_FINISHED');
         }else if($rg->Status=="De Consolidation"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_DE_CONSOLIDATION');
         }else if($rg->Status=="Arrived Destination"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_ARRIVED_DESTINATION');
         }else if($rg->Status=="Transit"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_TRANSIT');
         }else if($rg->Status=="Ready for Shipment"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_READY_FOR_SHIPMENT');
         }else if($rg->Status=="Active"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_ACTIVE');
         }else if($rg->Status=="Completed"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_COMPLETED');
         }else if($rg->Status=="Ready for Deliver"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_READY_FOR_DELIVERY');
         }else if($rg->Status=="Delivered"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_DELIVERED');
         }else if($rg->Status=="Closed"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_CLOSED');
         }else if($rg->Status=="Out For Delivery"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_OUT_FOR_DELIVERY');
         }else if($rg->Status=="Rejected"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_REJECTED');
         }else if($rg->Status=="Not Picked"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_NOT_PICKED');
         }else if($rg->Status=="Picked"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_PICKED');
         }else if($rg->Status=="Not Packed"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_NOT_PACKED');
         }else if($rg->Status=="Packed"){
           $status=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_STATUS_PACKED');
         }else{
            $status=$rg->Status;
         }
         
         if($rg->CreatedBy=="CUSTOMER"){
            $createdBy=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_USER_CUSTOMER');
         }else if($rg->CreatedBy=="PORTAL"){
           $createdBy=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_USER_PORTAL');
         }else if($rg->CreatedBy=="guest"){
           $createdBy=Jtext::_('COM_USERPROFILE_SHIP_HISTORY_USER_GUEST');
         }else{
             $createdBy=$rg->CreatedBy;
         }
         
          $mlg.= '<tr><td>'.$status.'</td>'.'<td>'.$createdBy.'</td>'.'<td>'.$rg->CreatedDate.'</td></tr>';
        } 
        return $mlg;
    }

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function submitTicket($user, $txtticketDescStr,$txtTicketNumberStr)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/SupportTicketAPI/InsertTicket_ByCustomer';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","number_ticket":"'.$txtTicketNumberStr.'","status_ticket":"Open","id_cust":"'.$user.'","reason_ticket":"'.$txtticketDescStr.'","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","number_ticket":"'.$txtTicketNumberStr.'","status_ticket":"Open","id_cust":"'.$user.'","reason_ticket":"'.$txtticketDescStr.'","ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function postQotationRequest($CustId, $txtMeasurementUnits,$txtTypeOfShipperName,$txtServiceType,$txtIdServ,$txtSourceCntry,$txtDestinationCntry,$txtLength,$txtWidth,$txtHeight,$txtQuantity,$txtWeight,$txtWeightUnits,$txtquotationCost,$discount,$txtfinalCost,$addservcost,$addservid,$txtqty,$quotid,$txtNotes,$txtCommodity,$txtRatetypeIds,$txtItemName,$txtPackageList,$txtVolumeMultiple,$txtVolWtMultiple,$txtIdrate,$txtIns,$txtGrossWeight,$bustype)
    {
        $rows='';
        $CompanyId = Controlbox::getCompanyId();
        
        for($i=0;$i<count($txtPackageList);$i++){
            $txtPackage=explode(":",$txtPackageList[$i]);
            $packageid[]=$txtPackage[0];
            $packagename[]=$txtPackage[1];
        }
        $txtRatetype=explode(",",$txtRatetypeIds);
        $txtVolumeMultiple=explode(",",$txtVolumeMultiple);
        $txtVolWtMultiple=explode(",",$txtVolWtMultiple);
        $txtGrossWeights=explode(",",$txtGrossWeight);
        for($i=0;$i<count($txtVolumeMultiple);$i++){
            if($txtWeight[$i])
            $rows.='{"CompanyID":"'.$CompanyId.'","CommandType":"InsertUpdate","CommodityId":"'.$txtCommodity[$i].'","Gross_Wt":"'.$txtGrossWeights[$i].'","Gross_Wt_PerItem":"'.$txtWeight[$i].'","Height":"'.$txtHeight[$i].'","IdPkg":"'.$packageid[$i].'","IdRateType":"'.$txtRatetype[$i].'","ItemName":"'.$txtItemName[$i].'","ItemQty":"'.$txtQuantity[$i].'","Length":"'.$txtLength[$i].'","Pkg_Name":"'.$packagename[$i].'","QuotationNumber":"'.$quotid.'","Volume":"'.$txtVolumeMultiple[$i].'","Volumetric_Wt":"'.$txtVolWtMultiple[$i].'","Width":"'.$txtWidth[$i].'"},';
        }
        if($txtIns=="true"){
            $txtIns="PEN";
        }else{
            $txtIns="ACT";
        }    
        mb_internal_encoding('UTF-8');
        
        
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/InsertOrUpdateQuotation';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","MUnits":"'.$txtMeasurementUnits.'","Shipment_Id":"'.$txtTypeOfShipperName.'","ServiceType_Id":"'.$txtServiceType.'","RateId":"'.$txtIdrate.'","Source":"'.$txtSourceCntry.'","Destination":"'.$txtDestinationCntry.'","WUnits":"'.$txtWeightUnits.'","Total_Cost":"'.$txtquotationCost.'","discount":"'.$discount.'","Final_Cost":"'.$txtfinalCost.'","addtServiceCost":"'.$addservcost.'","addtServiceId":"'.$addservid.'","itemQty": "'.$txtqty.'","isShowInh": "0,0,","isShowOtherChrg": "0,0,","IdCust":"'.$CustId.'","Quote_Id":"'.$quotid.'","Note":"'.$txtNotes.'","IdServ":"'.$txtIdServ.'","Status":"'.$txtIns.'","TypeBusiness":"'.$bustype.'","ActivationKey":"123456789","Items_List":['.substr($rows,0,-1).']}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","MUnits":"'.$txtMeasurementUnits.'","Shipment_Id":"'.$txtTypeOfShipperName.'","ServiceType_Id":"'.$txtServiceType.'","RateId":"'.$txtIdrate.'","Source":"'.$txtSourceCntry.'","Destination":"'.$txtDestinationCntry.'","WUnits":"'.$txtWeightUnits.'","Total_Cost":"'.$txtquotationCost.'","discount":"'.$discount.'","Final_Cost":"'.$txtfinalCost.'","addtServiceCost":"'.$addservcost.'","addtServiceId":"'.$addservid.'","itemQty": "'.$txtqty.'","isShowInh": "0,0,","isShowOtherChrg": "0,0,","IdCust":"'.$CustId.'","Quote_Id":"'.$quotid.'","Note":"'.$txtNotes.'","IdServ":"'.$txtIdServ.'","Status":"'.$txtIns.'","TypeBusiness":"'.$bustype.'","ActivationKey":"123456789","Items_List":['.substr($rows,0,-1).']}';
// 		var_dump($result);exit;
		
        $msg=json_decode($result);
        return $msg->Description;
    }


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function postPickupRequest($CustId, $txtMeasurementUnits,$txtTypeOfShipperName,$txtServiceType,$txtIdServ,$txtSourceCntry,$txtDestinationCntry,$txtLength,$txtWidth,$txtHeight,$txtQuantity,$txtWeight,$txtWeightUnits,$txtquotationCost,$txtRateId,$quotid,$txtNotes,$txtCommodity,$txtRatetypeIds,$txtItemName,$txtPackageList,$txtVolumeMultiple,$txtVolWtMultiple,$txtShipperName,$txtConsigneeName,$txtThirdPartyName,$txtName,$txtChargableWeight,$txtPickupDate,$txtPickupAddress,$txtConsigneeId,$txtThirdPartyId,$txtShipperNameId,$txtShipperAddress,$txtConsigneeAddress,$txtThirdPartyAddress,$txtIdrate,$txtfinalCost,$txtDiscount,$txtGrossWeight,$addservid,$addservcost,$txtqty,$additemsCost,$bustype)
    {
        $rows='';
        $CompanyId = Controlbox::getCompanyId();
        
        for($i=0;$i<count($txtPackageList);$i++){
            $txtPackage=explode(":",$txtPackageList[$i]);
            $packageid[]=$txtPackage[0];
            $packagename[]=$txtPackage[1];
        }
        //,"QuotationNumber":"'.$quotid.'"
        $txtRatetype=explode(",",$txtRatetypeIds);
        $txtVolumeMultiple=explode(",",$txtVolumeMultiple);
        $txtVolWtMultiple=explode(",",$txtVolWtMultiple);
        $txtGrossWeights=explode(",",$txtGrossWeight);
        
        $txtqtynewstr = "";
        $isShowInh = "";
        $isShowOtherChrg = "";
        $addservidtrim = rtrim($addservid,",");
        $addserArr = explode(",",$addservidtrim);
        foreach($addserArr as $id){
            $txtqtynewstr .= $txtqty; 
            $isShowInh .= "0,";
            $isShowOtherChrg .="0,";
        }
       
        for($i=0;$i<count($txtVolumeMultiple);$i++){
            if($txtWeight[$i])
            $rows.='{"CompanyID":"'.$CompanyId.'","CommandType":"InsertUpdate","CommodityId":"'.$txtCommodity[$i].'","Gross_Wt":"'.$txtGrossWeights[$i].'","Gross_Wt_PerItem":"'.$txtWeight[$i].'","Height":"'.$txtHeight[$i].'","IdPkg":"'.$packageid[$i].'","IdRateType":"'.$txtRatetype[$i].'","ItemName":"'.$txtItemName[$i].'","ItemQty":"'.$txtQuantity[$i].'","Length":"'.$txtLength[$i].'","Pkg_Name":"'.$packagename[$i].'","Status":"PEN","PickUpOrderNumber":"'.$quotid.'","Volume":"'.$txtVolumeMultiple[$i].'","Volumetric_Wt":"'.$txtVolWtMultiple[$i].'","Width":"'.$txtWidth[$i].'"},';
        }
        if($txtIns=="true"){
            $txtIns="PEN";
        }else{
            $txtIns="ACT";
        }
        $session = JFactory::getSession();
        $user=$session->get('user_casillero_id');

        if($user==$txtShipperNameId){
            $t1='true';
        }
        else{
            $t1='false';
        }
        if($user==$txtConsigneeId){
            $t2='true';
        }
        else{
            $t2='false';
        }
        if($user==$txtThirdPartyId){
            $t3='true';
        }
        else{
            $t3='false';
        }

        mb_internal_encoding('UTF-8');
        
        
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/InsertOrUpdatePoOrder';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","PickUpOrder_Id":"'.$quotid.'","IdCust":"'.$CustId.'","Shipment_Id":"'.$txtTypeOfShipperName.'","ServiceType_Id":"'.$txtServiceType.'","IdServ":"'.$txtIdServ.'","BitConSameAsCust":"'.$t2.'","BitShipperSameAsCust":"'.$t1.'","BitThirdPartySameAsCust":"'.$t3.'","MUnits":"'.$txtMeasurementUnits.'","WUnits":"'.$txtWeightUnits.'", "Note":"'.$txtNotes.'","ConsigneeId":"'.$txtConsigneeId.'","ConsigneeName":"'.$txtConsigneeName.'","ThirdPartyId":"'.$txtThirdPartyId.'","ThirdPartyName":"'.$txtThirdPartyName.'","ShipperId":"'.$txtShipperNameId.'","ShipperName":"'.$txtShipperName.'","ConsigneeAddress":"'.$txtConsigneeAddress.'","ThirdPartyAddress":"'.$txtThirdPartyAddress.'","ShipperAddress":"'.$txtShipperAddress.'","Total_Cost":"'.$txtquotationCost.'","Final_Cost":"'.$txtfinalCost.'","addtServiceCost":"'.$addservcost.'","addtServiceId":"'.$addservid.'","addtServiceItemCost":"'.$additemsCost.'","itemQty": "'.$txtqtynewstr.'","isShowInh": "'.$isShowInh.'","isShowOtherChrg": "'.$isShowOtherChrg.'","Discount":"'.$txtDiscount.'","TypeBusiness":"'.$bustype.'","PickUpInfo":{ "Name":"'.$txtName.'",	"PickupAddr":"'.$txtPickupAddress.'",	"PickupDate":"'.$txtPickupDate.'" },"Items_List":['.substr($rows,0,-1).']}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","PickUpOrder_Id":"'.$quotid.'","IdCust":"'.$CustId.'","Shipment_Id":"'.$txtTypeOfShipperName.'","ServiceType_Id":"'.$txtServiceType.'","IdServ":"'.$txtIdServ.'","BitConSameAsCust":"'.$t2.'","BitShipperSameAsCust":"'.$t1.'","BitThirdPartySameAsCust":"'.$t3.'","MUnits":"'.$txtMeasurementUnits.'","WUnits":"'.$txtWeightUnits.'", "Note":"'.$txtNotes.'","ConsigneeId":"'.$txtConsigneeId.'","ConsigneeName":"'.$txtConsigneeName.'","ThirdPartyId":"'.$txtThirdPartyId.'","ThirdPartyName":"'.$txtThirdPartyName.'","ShipperId":"'.$txtShipperNameId.'","ShipperName":"'.$txtShipperName.'","ConsigneeAddress":"'.$txtConsigneeAddress.'","ThirdPartyAddress":"'.$txtThirdPartyAddress.'","ShipperAddress":"'.$txtShipperAddress.'","Total_Cost":"'.$txtquotationCost.'","Final_Cost":"'.$txtfinalCost.'","addtServiceCost":"'.$addservcost.'","addtServiceId":"'.$addservid.'","addtServiceItemCost":"'.$additemsCost.'","itemQty": "'.$txtqtynewstr.'","isShowInh": "'.$isShowInh.'","isShowOtherChrg": "'.$isShowOtherChrg.'","Discount":"'.$txtDiscount.'","TypeBusiness":"'.$bustype.'","PickUpInfo":{ "Name":"'.$txtName.'",	"PickupAddr":"'.$txtPickupAddress.'",	"PickupDate":"'.$txtPickupDate.'" },"Items_List":['.substr($rows,0,-1).']}';
//      var_dump($result);exit;
		
        $msg=json_decode($result);
        return $msg->Msg;
    }


    
      /**
     * Insert payment for cod,stripe and paypal
     */
    public static function submitpayment($amtStr,$cardnumberStr,$txtccnumberStr,$MonthDropDownListStr,$txtNameonCardStr,$YearDropDownListStr,$invidkStr,$qtyStr,$wherhourecStr,$CustId,$specialinstructionStr,$cc,$pg,$shipservtStr,$consignidStr,$invf,$filenameStr,$articleStr,$priceStr,$tid,$inhouse,$inhouseid,$ratetype,$Conveniencefees,$addSerStr,$addSerCostStr,$CompanyId,$insuranceCost,$extAddSer,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$shipmentCost,$totalDecVal,$couponCodeStr,$couponDiscAmt,$repackLblStr,$invoice)
    {
        // echo '<pre>';
        // var_dump($invoice);
        // exit;
        
        $hostnameStr = $_SERVER['HTTP_HOST'];
        $hostnameStr = str_replace("www.","",$hostnameStr);
        $hostnameArr = explode(".",$hostnameStr);
        $domainurl = $hostnameArr[1].".".$hostnameArr[2];
        $domainname = $hostnameArr[0];
        
        $filenamesList=explode(",",$filenameStr);
        
        $nameStr = "";
        $extStr = "";
        
        for($i=0;$i < count($filenamesList);$i++){
           if($filenamesList[$i]!='0'){
               if($filenamesList[$i]!=''){
                    $nameExtAry=explode(".",$filenamesList[$i]);
                    $nameStr.=$nameExtAry[0].",";
                    $extStr.=".".$nameExtAry[1].",";
               }
                
           }else{
            $nameStr.="0,";
            $extStr.="0,";
           }
        }
        
        if(empty($Conveniencefees)){
            $Conveniencefees = 0;
        }
        
        if($consignidStr==""){
            $consignidStr=$CustId;
        }
        
        if($inhouseid){
            $cc='PayForCOD';
        }
        
        
        // additional service cost
        
            $addSerArr = explode(",",$addSerStr);
            if(count($addSerArr) > 1){
                $qntnew = $qtyStr.',';
            }else{
                $qntnew="";
            }
            $billformwrstr = $wherhourecStr.',';
            
            // end
            
            
        $wrhstrarr=explode(",",$wherhourecStr);
		$invidkarr=explode(",",$invidkStr);
		$qtyarr=explode(",",$qtyStr);
		$artstrarr=explode(",",$articleStr);
		$pricestrarr=explode(",",$priceStr);
        $repackstrarr=explode(",",$repackLblStr);

		$wrhs = array();
		for($i=0;$i< count($wrhstrarr);$i++){
		    
		    if(!array_key_exists($wrhstrarr[$i],$wrhs)){
		        $wrhs[$wrhstrarr[$i]] = array([$invidkarr[$i],$qtyarr[$i],$artstrarr[$i],$pricestrarr[$i],$repackstrarr[$i]]);
		    }else{
		        array_push($wrhs[$wrhstrarr[$i]],array($invidkarr[$i],$qtyarr[$i],$artstrarr[$i],$pricestrarr[$i],$repackstrarr[$i]));
		    }
		    
		}
		$wrhsloop = '';
		
		foreach($wrhs as $key => $value){
		    $idkstrwr = '';
		    $qntstrwr = '';
		    $artstrwr = '';
		    $pricestrwr = '';
            
		    foreach($value as $val){
		        if($val[0]!=""){
		        $idkstrwr .= $val[0].",";
		        }
		        if($val[1]!=""){
		        $qntstrwr .= $val[1].",";
		        }
		        if($val[2]!=""){
		        $artstrwr .= $val[2].",";
		        }
		        if($val[3]!=""){
		        $pricestrwr .= $val[3].",";
		        }
                $replblstrwr = $val[4];
		    }

            $artstrwr = rtrim($artstrwr,",");
		    
		    $wrhsloop .= '{"BillFormNo":"'.$key.'","idks":"'.$idkstrwr.'","qtys":"'.$qntstrwr.'","itemNames":"'.$artstrwr.'","totalPrices":"'.$pricestrwr.'","inhouseRepackLbl":"'.$replblstrwr.'"},';
		}

		$wrhsloop = rtrim($wrhsloop,",");
		
// 		$invfLoop = '';
// 		foreach($mulfiles as $invfile){
// 		    $invfLoop .= '{"idks":"'.$invfile[0].'","UploadedFile":"'.$invfile[1].'","fileName":"'.$invfile[2].'","fileExtension":"'.$invfile[3].'"},';
// 		}

// for cod page , seperated not required

if($invidkStr != ""){
    $invidkStr = $invidkStr.",";
}
if($qtyStr != ""){
    $qtyStr = $qtyStr.",";
}
if($articleStr != ""){
    $articleStr = $articleStr.",";
}
if($priceStr != ""){
    $priceStr = $priceStr.",";
}


    // paypal service
        
        
        $req='{"CompanyID":"'.$CompanyId.'","paymentOption":{"_amt":"'.$amtStr.'","_cardno":"'.$cardnumberStr.'","_ccno":"'.$txtccnumberStr.'","_index":"1","_month":"'.$MonthDropDownListStr.'","_nameoncard":"'.$txtNameonCardStr.'","_year":"'.$YearDropDownListStr.'"},"billFormIdsList":['.$wrhsloop.'],"idks":"'.$invidkStr.'","qtys":"'.$qtyStr.'","billFormIds":"'.$wherhourecStr.',","ShippingCost":"'.$amtStr.'","ConsigneeId":"'.$consignidStr.'","Comments":"'.$specialinstructionStr.'","PaymentType":"'.$cc.'","CustId":"'.$CustId.'","id_serv":"'.$shipservtStr.'","paymentgateway":"'.$pg.'","TransactionID":"'.$tid.'","UploadedFile":"'.$invf.'","fileName":"'.$nameStr.'","fileExtension":"'.$extStr.'","InHouseNo":"'.$inhouse.'","InhouseId":"'.$inhouseid.'","EachItemName":"'.$articleStr.'","EachItemQty":"'.$qtyStr.'","TotalitemsPrice":"'.$priceStr.'","id_rate_type":"'.$ratetype.'","Conveniencefees":"'.$Conveniencefees.'","InsuranceCost":"'.$insuranceCost.'","domainname":"'.$domainname.'","domainurl":"'.$domainurl.'","PromoCouponDiscountAmt":"'.$couponDiscAmt .'","PromoCouponCode":"'.$couponCodeStr.'","Addoninvoiceno":"'.$invoice.'"}';
    
    //  var_dump($req);exit;
        
		mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/PaymentTransaction';
        
        /** Debug **/
          
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","paymentOption":{"_amt":"'.$amtStr.'","_cardno":"'.$cardnumberStr.'","_ccno":"'.$txtccnumberStr.'","_index":"1","_month":"'.$MonthDropDownListStr.'","_nameoncard":"'.$txtNameonCardStr.'","_year":"'.$YearDropDownListStr.'"},"billFormIdsList":['.$wrhsloop.'],"idks":"'.$invidkStr.'","qtys":"'.$qtyStr.'","billFormIds":"'.$wherhourecStr.',","ShippingCost":"'.$amtStr.'","ConsigneeId":"'.$consignidStr.'","Comments":"'.$specialinstructionStr.'","PaymentType":"'.$cc.'","CustId":"'.$CustId.'","id_serv":"'.$shipservtStr.'","paymentgateway":"'.$pg.'","TransactionID":"'.$tid.'","UploadedFile":"'.$invf.'","fileName":"'.$nameStr.'","fileExtension":"'.$extStr.'","InHouseNo":"'.$inhouse.'","InhouseId":"'.$inhouseid.'","EachItemName":"'.$articleStr.'","EachItemQty":"'.$qtyStr.'","TotalitemsPrice":"'.$priceStr.'","id_rate_type":"'.$ratetype.'","Conveniencefees":"'.$Conveniencefees.'","InsuranceCost":"'.$insuranceCost.'","domainname":"'.$domainname.'","domainurl":"'.$domainurl.'","PromoCouponDiscountAmt":"'.$couponDiscAmt .'","PromoCouponCode":"'.$couponCodeStr.'","Addoninvoiceno":"'.$invoice.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		$msg=json_decode($result);
		
		$payments_log = "logs/payments.log";
        file_put_contents($payments_log,"\n -- Start -- \n Customer ID : ".$CustId."\n Company ID :".$CompanyId."\n Request : \n".$req."\n Response : \n".$result."\n -- End -- \n",FILE_APPEND);
		
		
        //  echo $url."<br>";
        //  echo $req."<br>";
        //  var_dump($result);
        //  exit;
	 
        
        
        if($msg->InvoiceId!=""){
            
                  
                    // isert billform
                    
        $lengthStrSum = array_sum(explode(",",$lengthStr));
        $widthStrSum = array_sum(explode(",",$widthStr));
        $heightStrSum = array_sum(explode(",",$heightStr));
        $grosswtStrSum = array_sum(explode(",",$grosswtStr));
        $volumeStrSum = array_sum(explode(",",$volumeStr));
        $volumetwtStrSum = array_sum(explode(",",$volumetwtStr));
                    
                    $billformwrstr = $msg->BillFormNo.",";
                    
                    mb_internal_encoding('UTF-8');
                    $content_params =JComponentHelper::getParams( 'com_userprofile' );
                    $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/insertBillFormAddiServices';
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,'{"InhouseNo":"'.$msg->InhouseNo.'","CustId":"'.$CustId.'","BillFormNo":"'.$billformwrstr.'","id_add_serv":"'.$addSerStr.'","id_add_serv_new":"'.$extAddSer.'","item_qty":"'.$qntnew.'","Cost":"'.$addSerCostStr.'","Length":"'.$lengthStrSum.'","Width":"'.$widthStrSum.'","Height":"'.$heightStrSum.'","GrossWeight":"'.$grosswtStrSum.'","Volume":"'.$volumeStrSum.'","VolumetricWeight":"'.$volumetwtStrSum.'","DeclaredValue":"'.$totalDecVal.'","ShipmentCost":"'.$shipmentCost.'","CompanyID":"'.$CompanyId.'"}');
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                    $result=curl_exec($ch);
                    
                    
                    $req='{"InhouseNo":"'.$msg->InhouseNo.'","CustId":"'.$CustId.'","BillFormNo":"'.$billformwrstr.'","id_add_serv":"'.$addSerStr.'","id_add_serv_new":"'.$extAddSer.'","item_qty":"'.$qntnew.'","Cost":"'.$addSerCostStr.'","Length":"'.$lengthStrSum.'","Width":"'.$widthStrSum.'","Height":"'.$heightStrSum.'","GrossWeight":"'.$grosswtStrSum.'","Volume":"'.$volumeStrSum.'","VolumetricWeight":"'.$volumetwtStrSum.'","DeclaredValue":"'.$totalDecVal.'","ShipmentCost":"'.$shipmentCost.'","CompanyID":"'.$CompanyId.'"}';
                    $biiform_log = "logs/billform.log";
                    file_put_contents($biiform_log,"\n -- Start -- \n Customer ID : ".$CustId."\n Company ID :".$CompanyId."\n Request : ".$req."\n Response : ".$result."\n Service URL :".$url."\n",FILE_APPEND);
                    //var_dump($req);exit;
                    
                    /** Debug **/
                        // echo $url;
                        // $req='{"InhouseNo":"'.$msg->InhouseNo.'","CustId":"'.$CustId.'","BillFormNo":"'.$billformwrstr.'","id_add_serv":"'.$addSerStr.'","id_add_serv_new":"'.$extAddSer.'","item_qty":"'.$qntnew.'","Cost":"'.$addSerCostStr.'","Length":"'.$lengthStrSum.'","Width":"'.$widthStrSum.'","Height":"'.$heightStrSum.'","GrossWeight":"'.$grosswtStrSum.'","Volume":"'.$volumeStrSum.'","VolumetricWeight":"'.$volumetwtStrSum.'","DeclaredValue":"'.$totalDecVal.'","ShipmentCost":"'.$shipmentCost.'","CompanyID":"'.$CompanyId.'"}';
                        // echo $url."##".$req;
                        // var_dump($result);
                        // exit;
                        
            
            return $msg->InvoiceId.':'.$cardnumberStr.':'.$msg->ResCode;
            
        }else{
            return $msg->Data = str_replace(":","##",$msg->Data);
        }
    }

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculationShipping($CustId,$units,$shiptype,$sertype,$source,$dt,$lg,$wd,$hi,$qty,$gwt,$wtunits,$bustype)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/calcQuotationCost';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","MUnits":"'.$units.'","ShipmentType":"'.$shiptype.'","ServiceType":"'.$sertype.'","Source":"'.$source.'","Destination":"'.$dt.'","Length":"'.$lg.'","Width":"'.$wd.'","Height":"'.$hi.'","ItemQty":"'.$qty.'","Gross_Wt_PerItem":"'.$gwt.'","wtunits":"'.$wtunits.'","TypeBusiness":"'.$bustype.'","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","MUnits":"'.$units.'","ShipmentType":"'.$shiptype.'","ServiceType":"'.$sertype.'","Source":"'.$source.'","Destination":"'.$dt.'","Length":"'.$lg.'","Width":"'.$wd.'","Height":"'.$hi.'","ItemQty":"'.$qty.'","Gross_Wt_PerItem":"'.$gwt.'","wtunits":"'.$wtunits.'","TypeBusiness":"'.$bustype.'","ActivationKey":"123456789"}';
// 		var_dump($result);exit;
		
        $msg=json_decode($result);
        if($msg->Response=="1")
        return $msg->Data->RatetypeIds.":".$msg->Data->VolumeMultiple.":".$msg->Data->VolWtMultiple.":".$msg->Data->quotationCost.":".$msg->Data->IdServ.":".$msg->Data->RateId.":".$msg->Data->discount.":".$msg->Data->addtServiceCost.":".$msg->Data->GrossWtMultiple.':'.$msg->Data->addtServiceId.':'.$msg->Data->addtServiceItemCost;
        else
        return $msg->Description;
        
    }

/**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculatingnewMesurements($type,$munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric,$state,$city,$zip,$address,$dstate,$dcity,$dzip,$daddress)
    {
        
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetRatesforCalculator?PaymentType='.$type.'&Quantity='.$qty.'&destination='.$dt.'&ShipmentType='.$stype.'&ActivationKey=123456789&Source='.$source.'&volume='.$value.'&VolWt='.$vmetric.'&MUnits=in&Grosswt='.$gwt.'&Length='.$length.'&Width='.$width.'&Height='.$height.'&Wtunits='.$wtunits.'&CompanyID='.$CompanyId;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $i=1;
        $re='';
        foreach($msg->Data as $rg){
            if($rg->ship_cost>0)
          $re.='<div><input type="radio" name="shipmentStr" id="shipmentStr" value="$'.$rg->ship_cost.'"><label>'.$rg->RateTypeName.'</label></div>';        
          $i++;
        }   

 

        $weight=explode(",",$gwt);        
        $length=explode(",",$length);        
        $width=explode(",",$width);        
        $height=explode(",",$height);        
        $dtunit=explode(",",$munits);        
        $dtunit=$dtunit[0];
        for($i=0;$i<count($length);$i++){
            if($weight[$i]=="undefined"){
                $weight[$i]=1;
            }
            $frg.='{"weight": "'.$weight[$i].'","length": "'.$length[$i].'","width": "'.$width[$i].'","height": "'.$height[$i].'","distance_unit": "'.$dtunit.'","mass_unit": "lb"  },';
        }
        
        //$to=Controlbox::getShipingAddress($CustId,$wherhourec);
        
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CalculatorAPI/CalculateRates';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $link='{"CompanyID":"'.$CompanyId.'","address_from": {"CompanyID":"'.$CompanyId.'","name": "madnasdasd","street1": "'.$address.'","street2": "","city": "'.$city.'","state": "'.$state.'","zip": "'.$zip.'","country": "'.$source.'","phone": "9440517013","email": "madanchunchu@gmail.com"}, "address_to": {"name": "madnasdasd","street1": "'.$daddress.'","street2": "","city": "'.$dcity.'","state": "'.$dstate.'","zip": "'.$dzip.'","country": "'.$dt.'","phone": "123456790","email": "madanchunchu@gmail.com" },"parcels": ['.$frg.'],"extra": { },"async": false}';
        //curl_setopt($ch, CURLOPT_POSTFIELDS,'{ "CompanyID":"'.$CompanyId.'","address_from": { "name": "JagadeeshJejji", "street1": "Address Line 1", "street2": "", "city": "Mia-flo-us", "state": "FL", "zip": "33101", "country": "US", "phone": "9440517013", "email": "jagadeesh2805@gmail.com"}, "address_to": { "name": "JagadeeshJejji", "street1": "Address Line 1", "street2": "", "city": "CABARET", "state": "Centre", "zip": "02368", "country": "HT", "phone": "9440517013", "email": "jagadeesh2805@gmail.com" }, "parcels": [ { "weight": "4.00", "length": "2.00", "width": "2.00", "height": "2.00", "distance_unit": "in", "mass_unit": "lb" } ], "extra": { }, "async": false }');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$link);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result=curl_exec($ch);
        
       
        // echo $url;
        // echo $link;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $i=1;
        foreach($msg->Rates as $rg){
          if($rg->TotalCost>0)
          $re.='<div class="rdo_rd1"><input type="radio" name="shipmentStr" id="shipmentStr" value="$'.$rg->TotalCost.'"><label>'.$rg->Carrier."&nbsp;".$rg->RateType.'</label></div>';        
          $i++;
        }
        
        if($re==""){
            return '<label>No service rates available for destination</label>';
        }else{
            return $re;
        }
         
    }
    
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalcudetailsforus($r)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CalculatorAPI/GetBranchAddress?CountryId='.$r.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		//var_dump($result);exit;
        $msg=json_decode($result);
        $msgs=$msg->Data;
        return $msgs[0]->State.':'.$msgs[0]->City.':'.$msgs[0]->ZipCode.':'.$msgs[0]->Address.':'.$msgs[0]->PhoneNumber;
    }


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculatorMesurements($units,$shiptype,$sertype,$source,$dt,$lg,$wd,$hi,$qty,$gwt,$wtunits)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CalculatorAPI/calcVolume';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","Length":"'.$lg.'","Height":"'.$hi.'","Width":"'.$wd.'","Quantity":"'.$qty.'","ShipmentTypeValue":"'.$shiptype.'","ActivationKey":"123456789","MeasurementUnits":"'.$units.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo '{"CompanyID":"'.$CompanyId.'","Length":"'.$lg.'","Height":"'.$hi.'","Width":"'.$wd.'","Quantity":"'.$qty.'","ShipmentTypeValue":"'.$shiptype.'","ActivationKey":"123456789","MeasurementUnits":"'.$units.'"}';
//         echo $url;
// 		var_dump($result);exit;

		
        $msg=json_decode($result);
        if(strpos($msg->Data->Volume,"E") !== false){ 
            return number_format($msg->Data->Volume,0,'','').":".$msg->Data->VolumetricWeight;
        }else{
            return $msg->Data->Volume.":".$msg->Data->VolumetricWeight;
        }
    }





     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserfieldsInfo($user,$usertype)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/GetAdditionaluserByCustomerId?id_cust='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $mlg='';
        foreach($msg->Data as $rg){
          if($usertype==$rg->addtAddrId)    
          $mlg.= $rg->userType.':'.$rg->id_cust.':'.$rg->name_f.':'.$rg->name_l.':'.$rg->id_cntry.':'.$rg->id_state.':'.$rg->id_city.':'.$rg->postal_addr.':'.$rg->AddtEmail.':'.$rg->Addr1Name.':'.$rg->Addr2Name.':'.$rg->IdentificationType.':'.$rg->IdentificationValue;
        }        
        return $mlg;
    }





     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserconsigInfo()
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/customerInfoApi/getAdditionalUser?UserType=ConsigneeUser&User=Consignee&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data->UserType.':'.$msg->Data->IdAdduser;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getaddusertypeInfo($user,$usertype)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/customerInfoApi/getAdditionalUser?UserType='.$usertype.'&User='.$user.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result); exit;
        
        /** Debug **/
        $msg=json_decode($result);
        return $msg->Data->UserType.':'.$msg->Data->IdAdduser;
    }


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculatingMesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CalculatorAPI/calculate';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        if($stype==5000){
            $stype='AIR';
        }
        if($stype==1728){
            $stype='OCEAN';
        }
        //echo '{"CompanyID":"'.$CompanyId.'","Destination":"'.$dt.'","GrossWeight":"'.$gwt.'","Source":"'.$source.'","VolumetricWeight":"'.$value.'","ActivationKey":"123456789","ShippingType":"'.$stype.'"}';        
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","Destination":"'.$dt.'","GrossWeight":"'.$gwt.'","Source":"'.$source.'","VolumetricWeight":"'.$vmetric.'","ActivationKey":"123456789","ShippingType":"'.$stype.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		//var_dump($result);exit;
        $msg=json_decode($result);
        /*$ser=Controlbox::getServiceList();
        $resService=$ser->AS_List;
        if($msg->Data[0]->ShippingCost>0){

            $charge=0;
            foreach($resService as $row){
                $charge+=$row->Cost+$msg->Data[0]->ShippingCost;
            }    
            return $msg->Data[0]->ChargeableWeight.":".$charge;
        }else{
            return $msg->Data[0]->ChargeableWeight.":No Service for Destination";
        }*/    
        if($msg->Data[0]->ShippingCost)
        return $msg->Data[0]->ChargeableWeight.":".$msg->Data[0]->ShippingCost;
        else
        return $msg->Data[0]->ChargeableWeight.":No Service for Destination";
        
    }


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCalculating2Mesurements($munits,$tos,$stype,$source,$dt,$length,$width,$height,$qty,$gwt,$wtunits,$value,$vmetric)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        if($stype==5000){
            $stype='AIR';
        }
        if($stype==1728){
            $stype='OCEAN';
        }
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getServiceCost?PaymentType=&WarehouseReceiptNo=WR-5631&InvIdks=2,&Qtys=1,&destination=BB&ShipmentType=AIR&ActivationKey=123456789&CustId=BX2718&Source=US&volume=0,&MUnits=in&CompanyID='.$CompanyId;
        //curl_setopt($ch, CURLOPT_POSTFIELDS,'{"Destination":"'.$dt.'","GrossWeight":"'.$gwt.'","Source":"'.$source.'","VolumetricWeight":"'.$vmetric.'","ActivationKey":"123456789","ShippingType":"'.$stype.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		
		
		//var_dump($result);exit;
		
        $msg=json_decode($result);
        if($msg->Data[0]->ShippingCost)
        return $msg->Data[0]->ChargeableWeight.":".$msg->Data[0]->ShippingCost;
        else
        return $msg->Data[0]->ChargeableWeight.":No Service for Destination";
        
    }


        /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function submitpayshopperassist($CustId,$amtStr,$cardnumberStr,$txtccnumberStr, $MonthDropDownListStr,  $txtNameonCardStr, $YearDropDownListStr,$txtSpecialInStr,$txtTaxesStr,$txtShippChargesStr,$ItemIdsStr,$ItemQuantityStr,$ItemSupplierIdStr,$txtPaymentMethod,$paymentgateway,$ack,$corid,$tid,$mulfilename,$mulimageByteStream)
    {    
        $hostnameStr = $_SERVER['HTTP_HOST'];
        $hostnameStr = str_replace("www.","",$hostnameStr);
        $hostnameArr = explode(".",$hostnameStr);
        $domainurl = $hostnameArr[1].".".$hostnameArr[2];
        $domainname = $hostnameArr[0];
        
     mb_internal_encoding('UTF-8');  
     $CompanyId = Controlbox::getCompanyId();
     
//   return '{"CompanyID":"'.$CompanyId.'","objCardDetails":{"_amt":"'.$amtStr.'","_cardno":"'.$cardnumberStr.'","_ccno":"'.$txtccnumberStr.'","_index":1,"_month":"'.$MonthDropDownListStr.'","_nameoncard":"'.$txtNameonCardStr.'","_year":"'.$YearDropDownListStr.'"},"Comments":"'.$txtSpecialInStr.'","CreatedBy":"portal","CustomerId":"'.$CustId.'","Id":"'.$ItemIdsStr.'","ItemQuantity":"'.$ItemQuantityStr.'","SupplierId":"'.$ItemSupplierIdStr.'",	"LocalShipCost":"'.$txtShippChargesStr.'","LocalTax":"'.$txtTaxesStr.'","PurchseType":"0","ItemUrl":",","Status":"yes","paymenttype":"'.$txtPaymentMethod.'","paymentgateway":"'.$paymentgateway.'","TransactionID":"'.$tid.'","CorrelationID":"'.$corid.'","ACK":"'.$ack.'"}';
// 	 exit;


        $ItemIds = rtrim($ItemIdsStr,",");
        $ItemQuantity = rtrim($ItemQuantityStr,",");
        $ItemSupplierId = rtrim($ItemSupplierIdStr,",");
        $amtStr = rtrim($amtStr,",");
        
        // for($j=0; $j<4; $j++){
        //         if(isset($filenameArr[$j])){
        //             $filename[$j]=pathinfo($filenameArr[$j], PATHINFO_FILENAME );
        //             $filext[$j] ='.'.pathinfo($filenameArr[$j], PATHINFO_EXTENSION );
        //             $imagebytestream[$j] = $invfArr[$j];
        //         }
        //      }

        $ItemIdsArr = explode(",",$ItemIds);
        $ItemQuantityArr = explode(",",$ItemQuantity);
        $ItemSupplierIdArr = explode(",",$ItemSupplierId);

     $liInventoryPurchasesVM = "";
     for($k=0;$k<count($ItemIdsArr);$k++){ 

            $imagebytestream=array();
            $itemimage=array();
            $filename=array();
            $filext=array();
            
           for($j=0; $j<4; $j++){
                if(isset($mulfilename[$k][$j])){
                        $itemimage[$j]=$mulfilename[$k][$j];
                        $filename[$j]=pathinfo($mulfilename[$k][$j], PATHINFO_FILENAME );
                        $filext[$j] ='.'.pathinfo($mulfilename[$k][$j], PATHINFO_EXTENSION );
                        $imagebytestream[$j] = $mulimageByteStream[$k][$j];
                     }
             }
        
      $liInventoryPurchasesVM .= '{"id":"'.trim($ItemIdsArr[$k]).'","ItemQuantity":"'.$ItemQuantityArr[$k].'","SupplierId":"'.$ItemSupplierIdArr[$k].'","ItemUrl":"ftp","ImageByteStream":"'.$imagebytestream[0].'","ImageByteStream1": "'.$imagebytestream[1].'",
  "ImageByteStream2": "'.$imagebytestream[2].'",
  "ImageByteStream3": "'.$imagebytestream[3].'",
  "ImageByteStream4": "",
  "ItemImage": "'.$itemimage[0].'",
  "ItemImage1": "'.$itemimage[1].'",
  "ItemImage2": "'.$itemimage[2].'",
  "ItemImage3": "'.$itemimage[3].'",
  "ItemImage4": "",
  "fileName": "'.$filename[0].'",  
  "fileName1": "'.$filename[1].'",  
  "fileName2": "'.$filename[2].'",  
  "fileName3": "'.$filename[3].'",  
  "fileName4": "",
  "fileExtension": "'.$filext[0].'",
  "fileExtension1": "'.$filext[1].'",
  "fileExtension2": "'.$filext[2].'",
  "fileExtension3": "'.$filext[3].'",
  "fileExtension4": ""},';

      }
 
//         echo '{"CompanyID":"'.$CompanyId.'","objCardDetails":{"_amt":"'.$amtStr.'","_cardno":"'.$cardnumberStr.'","_ccno":"'.$txtccnumberStr.'","_index":1,"_month":"'.$MonthDropDownListStr.'","_nameoncard":"'.$txtNameonCardStr.'","_year":"'.$YearDropDownListStr.'"},"Comments":"'.$txtSpecialInStr.'","CreatedBy":"portal","CustomerId":"'.$CustId.'","LocalShipCost":"'.$txtShippChargesStr.'","LocalTax":"'.$txtTaxesStr.'","PurchseType":"0","Status":"yes","paymenttype":"'.$txtPaymentMethod.'","paymentgateway":"'.$paymentgateway.'","TransactionID":"'.$tid.'","CorrelationID":"'.$corid.'","ACK":"'.$ack.'","domainurl":"'.$domainurl.'","domainname":"'.$domainname.'","liInventoryPurchasesVM": ['.$liInventoryPurchasesVM.']}';
//  		exit;
    
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShopperAssistAPI/PaymentTransaction';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","objCardDetails":{"_amt":"'.$amtStr.'","_cardno":"'.$cardnumberStr.'","_ccno":"'.$txtccnumberStr.'","_index":1,"_month":"'.$MonthDropDownListStr.'","_nameoncard":"'.$txtNameonCardStr.'","_year":"'.$YearDropDownListStr.'"},"Comments":"'.$txtSpecialInStr.'","CreatedBy":"portal","CustomerId":"'.$CustId.'","LocalShipCost":"'.$txtShippChargesStr.'","LocalTax":"'.$txtTaxesStr.'","PurchseType":"0","Status":"yes","paymenttype":"'.$txtPaymentMethod.'","paymentgateway":"'.$paymentgateway.'","TransactionID":"'.$tid.'","CorrelationID":"'.$corid.'","ACK":"'.$ack.'","domainurl":"'.$domainurl.'","domainname":"'.$domainname.'","liInventoryPurchasesVM": ['.$liInventoryPurchasesVM.']}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
        // echo $url;
        // echo '{"CompanyID":"'.$CompanyId.'","objCardDetails":{"_amt":"'.$amtStr.'","_cardno":"'.$cardnumberStr.'","_ccno":"'.$txtccnumberStr.'","_index":1,"_month":"'.$MonthDropDownListStr.'","_nameoncard":"'.$txtNameonCardStr.'","_year":"'.$YearDropDownListStr.'"},"Comments":"'.$txtSpecialInStr.'","CreatedBy":"portal","CustomerId":"'.$CustId.'","LocalShipCost":"'.$txtShippChargesStr.'","LocalTax":"'.$txtTaxesStr.'","PurchseType":"0","Status":"yes","paymenttype":"'.$txtPaymentMethod.'","paymentgateway":"'.$paymentgateway.'","TransactionID":"'.$tid.'","CorrelationID":"'.$corid.'","ACK":"'.$ack.'","domainurl":"'.$domainurl.'","domainname":"'.$domainname.'","liInventoryPurchasesVM": ['.$liInventoryPurchasesVM.']}';
        // var_dump($result);
        // exit;
       
		
        $msg=json_decode($result);
        
       if($txtPaymentMethod == "COD"){ 
            if($msg->Data!=""){
                return $msg->Data.':'.$cardnumberStr;
            }
            else{
                return $msg->Msg;
            }
       }else{
           
           if($msg->ResCode == 1){
                return $msg->InvoiceId.':'.$cardnumberStr.':'.$msg->ResCode;
            }else{
                return $msg->Msg;
            }
       }
    }
    

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function pickupAdditionalusers($CustId,$useridTxt,$usertypeTxt, $fnameTxt, $lnameTxt,$countryTxt, $stateTxt, $cityTxt, $PostalCode, $addressTxt, $emailTxt)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/addAdditionalUser';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        if($usertypeTxt=="Shipper"){ 
	        $usertype="Shipper";
        }elseif($usertypeTxt=="Consignee"){
            $usertype="Consignee";
        }else{
            $usertype="Third Party";
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","IdCust":"'.$CustId.'","addr1Name":"'.$addressTxt.'","IdAdduser":"'.$useridTxt.'","IdUSerType":"'.$usertypeTxt.'","UserType":"'.$usertype.'","email_name":"'.$emailTxt.'","postal_addr":"'.$PostalCode.'","id_cntry":"'.$countryTxt.'","id_state":"'.$stateTxt.'","id_city":"'.$cityTxt.'","name_f":"'.$fnameTxt.'","name_l":"'.$lnameTxt.'","commandtype":"InsertPortal"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","IdCust":"'.$CustId.'","addr1Name":"'.$addressTxt.'","IdAdduser":"'.$useridTxt.'","IdUSerType":"'.$usertypeTxt.'","UserType":"'.$usertype.'","email_name":"'.$emailTxt.'","postal_addr":"'.$PostalCode.'","id_cntry":"'.$countryTxt.'","id_state":"'.$stateTxt.'","id_city":"'.$cityTxt.'","name_f":"'.$fnameTxt.'","name_l":"'.$lnameTxt.'","commandtype":"InsertPortal"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Msg;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function updatePurchaseOrder($itemid,$customerid,$supplierid,$carrierid,$trackingid,$orderdate,$file,$imageByteStreamsingle,$itemname,$itemquantity,$price,$cost,$status,$countryTxt,$stateTxt,$filename,$filename1,$filename2,$filename3,$imageByteStream,$imageByteStream1,$imageByteStream2,$imageByteStream3,$txtOrderId,$txtRmaValue,$txtLength,$txtHeigth,$txtWidth,$inventoryTxt,$Package,$mulfilepath)
    {
        mb_internal_encoding('UTF-8');
        $itemimage = array();

        // for($j=1; $j<=4; $j++){
        //         $fname = $filename.$j;
        //         $imgbs = $imageByteStream.$j;
                
        //             $itemimage[$j]=$fname;
                    
        //             var_dump($itemimage[1]);exit;
                    
        //             $filename[$j]=pathinfo($fname, PATHINFO_FILENAME );
        //             $filext[$j] ='.'.pathinfo($fname, PATHINFO_EXTENSION );
        //             $imagebytestream[$j] = $imgbs;
             
                
        //     }
                    $itemimage = $filename;
                    $filename=pathinfo($itemimage, PATHINFO_FILENAME );
                    $filext ='.'.pathinfo($itemimage, PATHINFO_EXTENSION );
                 
                    $itemimage1 = $filename1;
                    $filename1=pathinfo($itemimage1, PATHINFO_FILENAME );
                    $filext1 ='.'.pathinfo($itemimage1, PATHINFO_EXTENSION );
                  
                    $itemimage2 = $filename2;
                    $filename2=pathinfo($itemimage2, PATHINFO_FILENAME );
                    $filext2 ='.'.pathinfo($itemimage2, PATHINFO_EXTENSION );
                    
                    $itemimage3 = $filename3;
                    $filename3=pathinfo($itemimage3, PATHINFO_FILENAME );
                    $filext3 ='.'.pathinfo($itemimage3, PATHINFO_EXTENSION );

        
        // if($filename1 !=''){
        // $file1Det = explode(".",$filename1);
        // $fileName1 = $file1Det[0];
        // $fileExt1 = ".".$file1Det[1];
        // }
        
        // if($filename2 !=''){
        // $file2Det = explode(".",$filename2);
        // $fileName2 = $file2Det[0];
        // $fileExt2 = ".".$file2Det[1];
        //  }
        
        // if($filename3 !=''){
        // $file3Det = explode(".",$filename3);
        // $fileName3 = $file3Det[0];
        // $fileExt3 = ".".$file3Det[1];
        // }
        
        // if($filename4 !=''){
        // $file4Det = explode(".",$filename4);
        // $fileName4 = $file4Det[0];
        // $fileExt4 = ".".$file4Det[1];
        // }
        
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/UpdatePurchaseOrder';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        if($stateTxt==0){
            $stateTxt="";
        }
        $req = '{"ImageByteStream":"","CompanyID":"'.$CompanyId.'","ItemId":"'.$itemid.'","CustomerId":"'.$customerid.'","SupplierId":"'.$supplierid.'","CarrierId":"'.$carrierid.'","TrackingId":"'.$trackingid.'","OrderDate":"'.$orderdate.'","ItemImage":"'.$mulfilepath[0].'","ItemImage1":"'.$mulfilepath[1].'","ItemImage2":"'.$mulfilepath[2].'","ItemImage3":"'.$itemimage3.'","ItemImage4":"'.$mulfilepath[3].'","fileName":"'.$filename.'","fileExtension":"'.$filext.'","Dest_Cntry":"'.$countryTxt.'","Dest_Hub":"'.$stateTxt.'","ItemName":"'.$itemname.'","ItemQuantity":"'.$itemquantity.'","ItemPrice":"'.$price.'","Cost":"'.$cost.'","ItemStatus":"'.$status.'","ItemUrl":"ftp","ActivationKey":"123456789","ImageByteStream1":"","ImageByteStream2":"","ImageByteStream3":"","ImageByteStream4":"","fileName1":"'.$filename1.'","fileName2":"'.$filename2.'","fileName3":"'.$filename3.'","fileName4":"'.$filename4.'","fileExtension1":"'.$filext1.'","fileExtension2":"'.$filext2.'","fileExtension3":"'.$filext3.'","fileExtension4":"'.$filext4.'","OrderIdNew":"'.$txtOrderId.'","RMAValue":"'.$txtRmaValue.'","length":"'.$txtLength.'","height":"'.$txtHeigth.'","width":"'.$txtWidth.'","type_busines": "'.$inventoryTxt.'","PackageType":"'.$Package.'"}';
        curl_setopt($ch, CURLOPT_POSTFIELDS,$req);        
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);

        $prealertUpdate_log = "logs/prealert_update.log";
        file_put_contents($prealertUpdate_log,"\n -- Start -- \n Customer ID : ".$customerid."\n Company ID :".$CompanyId."\n Request : \n".$req."\n Response : \n".$result."\n -- End -- \n",FILE_APPEND);
		
		 /** Debug **/
        // echo $url;
		// echo '{"ImageByteStream":"","CompanyID":"'.$CompanyId.'","ItemId":"'.$itemid.'","CustomerId":"'.$customerid.'","SupplierId":"'.$supplierid.'","CarrierId":"'.$carrierid.'","TrackingId":"'.$trackingid.'","OrderDate":"'.$orderdate.'","ItemImage":"'.$mulfilepath[0].'","ItemImage1":"'.$mulfilepath[1].'","ItemImage2":"'.$mulfilepath[2].'","ItemImage3":"'.$itemimage3.'","ItemImage4":"'.$mulfilepath[3].'","fileName":"'.$filename.'","fileExtension":"'.$filext.'","Dest_Cntry":"'.$countryTxt.'","Dest_Hub":"'.$stateTxt.'","ItemName":"'.$itemname.'","ItemQuantity":"'.$itemquantity.'","ItemPrice":"'.$price.'","Cost":"'.$cost.'","ItemStatus":"'.$status.'","ItemUrl":"ftp","ActivationKey":"123456789","ImageByteStream1":"","ImageByteStream2":"","ImageByteStream3":"","ImageByteStream4":"","fileName1":"'.$filename1.'","fileName2":"'.$filename2.'","fileName3":"'.$filename3.'","fileName4":"'.$filename4.'","fileExtension1":"'.$filext1.'","fileExtension2":"'.$filext2.'","fileExtension3":"'.$filext3.'","fileExtension4":"'.$filext4.'","OrderIdNew":"'.$txtOrderId.'","RMAValue":"'.$txtRmaValue.'","length":"'.$txtLength.'","height":"'.$txtHeigth.'","width":"'.$txtWidth.'","type_busines": "'.$inventoryTxt.'","PackageType":"'.$Package.'"}';
		// var_dump($result);exit;

        $msg=json_decode($result);
        return $msg->Description;
    }

    

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
   public static function getShippmentDetails($CustId,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue,$bustype,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$dvalueStr,$shipmentCost,$destCnt,$rateType,$repackLblStr)
    {
        //  service 1
        
      
         $fl=explode(",",$volres);
        $qt=explode(",",$qty);
        $totqnt = array_sum($qt);
        $str='';
        for($i=0;$i<count($qt);$i++){
           $str.=substr($fl[$i],0,5)*$qt[$i]*$service.",";
        }
        $volresStr=str_replace("E-","",$volres).',';
        if($repackLblStr != ''){
            $repackLblStr = $repackLblStr.",";
        }
        
        
        $selectedlist=Controlbox::getBillFormAdditionalServices($wherhourec,$qty,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$dvalueStr,$shipmentCost);
        $listArr=array();
        foreach($selectedlist as $list){
            $listArr[] = $list->id_AddnlServ;
        }
        
       
        
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getServiceCost?PaymentType='.$paymenttype.'&WarehouseReceiptNo='.$wherhourec.'&InvIdks='.$invidk.',&Qtys='.$qty.',&destination='.$destination.'&ShipmentType='.$shiptype.'&ActivationKey=123456789&CustId='.$CustId.'&Source='.$source.'&volume='.$volresStr.'&MUnits='.$munits.'&DeclaredValue='.$dvalue.'&InhouseRepacklbl='.$repackLblStr.'&CompanyID='.$CompanyId;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        
         	 $ratetype_log = "logs/ratetype.log";
             file_put_contents($ratetype_log,"\n -- Start -- \n Customer ID : ".$CustId."\n Company ID : ".$CompanyId."\n Request URL : \n".$url."\n Response : \n".$result."\n -- End --\n",FILE_APPEND);
        
        // echo $url;
        // echo $result;exit;
       
        $msg=json_decode($result);
        
        $res='<table class="table-responsive addselser" width="100%">';
        $res1='';
        $res2='';
        // var_dump($rateType);
        // exit;
        
     
        foreach($msg->Data as $msg){
            
                if($msg->shipping_cost != "Rates issue.Please contact administrator"){
                    if($msg->RateTypeDesc != '')
                    $rateTypeDes = '&nbsp;&nbsp;('.$msg->RateTypeDesc.')';
                    else
                    $rateTypeDes = '';
                    
                    if($rateType == $msg->RateType){
                        $selected = "checked";
                    }else{
                        $selected = "";
                    }
                    
                   
                $res.='<div class="rdo_rd1"><input type="radio" '.$selected.' id="shipmentNewStr" name="shipmentNewStr" value="'.$msg->ship_cost.':'.$msg->id_serv.':'.$msg->addserv_cost.':'.$msg->discount.':'.$msg->shipping_cost.':'.$msg->insurance_cost.':'.$msg->RateType.':'.$msg->RateTypeName.':'.$msg->fuelcharges.':'.$msg->StorageCharges.'">'.$msg->RateTypeName.$rateTypeDes.'</div>';
            
                }
            
            }
    
            
                        // service 2. additional services
                        
                        $result= Controlbox::getAdditionalServiceListOrder($CustId,$totqnt,$bustype,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$dvalueStr,$shipmentCost,$destCnt);
                        
                        // echo '<pre>';
                        // var_dump($result);exit;
                        
                        $res1='<div class="clearfix "></div><br>';
                             $res1.='<div class="row shipping_info_ed">
                                    <div class="col-md-12">
                                    <div class="form-group servc-chk">
                                    <div id="getservices" class="adisrves-blk">
                                    
                                    <label>'.Jtext::_('COM_USERPROFILE_ADDITIONAL_SERVICES').'</label>
                                    </div>';
                              $res1.='<tr><th>'.Jtext::_('COM_USERPROFILE_SERVICE_NAME').'</th><th>'.Jtext::_('COM_USERPROFILE_SERVICE_COST').'</th></tr>';      
                                    
                        
                        foreach($selectedlist as $rg){
                            
                                if($rg->Total_Cost != ''){
                                    if($rg->Total_Cost != '0.00')
                                        $res2.= '<tr><td><input type="checkbox"  checked="checked" name="txtService"  id="txtService" value="'.$rg->Total_Cost.':'.$rg->id_AddnlServ.':'.$rg->Cost.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label></td><td>$'.$rg->Total_Cost.'</td></tr>';
                                }  
                            
                            }  
                        
                         
                         if(count($result) > 0){
                             
                            foreach($result as $rg){
                                if(!in_array($rg->id_AddnlServ,$listArr)){
                                  if($rg->Total_Cost != ''){
                                     if($rg->Total_Cost != '0.00')
                                        $res2.='<tr><td><input type="checkbox"   name="txtService" class="unselecteServices"  id="txtService" value="'.$rg->Total_Cost.':'.$rg->id_AddnlServ.':'.$rg->Cost.'"><label for="option">&nbsp;'.$rg->Addnl_Serv_Name.'</label><td>$'.$rg->Total_Cost.'</td><tr><td>';
                                   }
                                }
                            }
                            
                            if($res2 !=''){
                                $addHeadingContent = $res1.$res2;
                            }else{
                                $addHeadingContent = "";
                            }
                            
                            $res.= $addHeadingContent;
                            $res.='</table></div></div></div>';
                        
                         }
        
            
        
        return $res;
        
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShippment2Details($CustId,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue)
    {
        $fl=explode(",",$volres);
        $qt=explode(",",$qty);
        $str='';
        for($i=0;$i<count($qt);$i++){
           $str.=substr($fl[$i],0,5)*$qt[$i]*$service.",";
        }
        $volresStr=str_replace("E-","",$volres).',';
        
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        //$url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getServiceCost?PaymentType='.$paymenttype.'&WarehouseReceiptNo='.$wherhourec.'&InvIdks='.$invidk.',&Qtys='.$qty.',&destination='.$destination.'&ShipmentType='.$shiptype.'&ActivationKey=123456789&CustId='.$CustId.'&Source='.$source.'&volume='.$volresStr.'&MUnits='.$munits.'&DeclaredVaue='.$dvalue;
        //$url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getServiceCost?PaymentType=&WarehouseReceiptNo=WR-5631&InvIdks=2,&Qtys=1,&destination=BB&ShipmentType=AIR&ActivationKey=123456789&CustId=BX2718&Source=US&volume=0,&MUnits=in';
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getServiceCost?PaymentType=&WarehouseReceiptNo='.$wherhourec.'&InvIdks='.$invidk.',&Qtys='.$qty.',&destination='.$destination.'&ShipmentType='.$shiptype.'&ActivationKey=123456789&CustId='.$CustId.'&Source='.$source.'&volume='.$volresStr.',&MUnits='.$munits.'&CompanyID='.$CompanyId;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $i=1;
        foreach($msg->Data as $rg){
            if($rg->shipping_cost>0)
            $re.='<div class="rdo_rd1"><input type="radio" name="shipmentStr" id="shipmentStr" value="'.$rg->ship_cost.':'.$rg->id_serv.':'.$rg->addserv_cost.':'.$rg->discount.':'.$rg->shipping_cost.':'.$rg->insurance_cost.':'.$rg->RateTypeName.':'.$rg->RateType.'"><label>'.$rg->RateTypeName.'</label></div>';        
            else
            $re.='<div class="rdo_rd1"><input type="radio" name="shipmentStr" id="shipmentStr" value="0:'.$rg->id_serv.':'.$rg->addserv_cost.':'.$rg->discount.':'.$rg->shipping_cost.':'.$rg->insurance_cost.':'.$rg->RateTypeName.':'.$rg->RateType.'"><label>'.$rg->RateTypeName.'</label></div>';        
          $i++;
        }   
        return $re;
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShippmentDhlDetails($CustId,$paymenttype,$wherhourec,$invidk,$qty,$destination,$volres,$munits,$source,$shiptype,$service,$dvalue,$weight,$dtunit,$length,$width,$height,$consignee)
    {

        $weight=explode(",",$weight);        
        $length=explode(",",$length);        
        $width=explode(",",$width);        
        $height=explode(",",$height);        
        $dtunit=explode(",",$munits);        
        $dtunit=$dtunit[0];
        for($i=0;$i<count($length);$i++){
            if($weight[$i]=="undefined"){
                $weight[$i]=1;
            }
            //if($i==0){
                $frg.='{"weight": "'.$weight[$i].'","length": "'.$length[$i].'","width": "'.$width[$i].'","height": "'.$height[$i].'","distance_unit": "'.$dtunit.'","mass_unit": "lb"  },';
            //}
        }
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CalculatorAPI/CalculateRates';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        //$from=Controlbox::getUserprofileDetails($CustId);
        $from=Controlbox::getUserpersonalDetails($CustId);
        if($consignee){
        $to=Controlbox::newAdditionalUsersDetails($CustId,$consignee);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","address_from": {
"name": "'.$from->AdditionalFirstName.''.$from->AdditionalLname.'","street1": "'.$from->AddressAccounts.'","street2": "'.$from->addr_2_name.'","city": "'.$from->City.'",
"state": "'.substr($from->State,0,2).'","zip": "'.$from->PostalCode.'","country": "'.$source.'","phone": "'.$from->PrimaryNumber.'","email": "'.$from->PrimaryEmail.'"  },
"address_to": {
"name": "'.$to->name_f.''.$to->name_l.'","street1": "'.$to->Addr1Name.'","street2": "'.$to->Addr2Name.'","city": "'.$to->CityName.'","state": "'.$to->id_state.'","zip": "'.$to->postal_addr.'",
"country": "'.$to->id_cntry.'","phone": "'.$from->PrimaryNumber.'","email": "'.$to->AddtEmail.'"  },
"parcels": ['.$frg.'],"extra": { },"async": false}');
       
        }else{
       
        $to=Controlbox::getShipingAddress($CustId,$wherhourec);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","address_from": {
"name": "'.$from->AdditionalFirstName.''.$from->AdditionalLname.'","street1": "'.$from->AddressAccounts.'","street2": "'.$from->addr_2_name.'","city": "'.$from->City.'",
"state": "'.substr($from->State,0,2).'","zip": "'.$from->PostalCode.'","country": "'.$source.'","phone": "'.$from->PrimaryNumber.'","email": "'.$from->PrimaryEmail.'"},
"address_to": {"name": "'.$to->name_f.''.$to->name_l.'","street1": "'.$from->AddressAccounts.'","street2": "'.$from->addr_2_name.'","city": "'.$to->CityId.'",
"state": "'.substr($to->StateId,0,2).'","zip": "'.$to->postal_addr.'","country": "'.$to->id_cntry.'","phone": "'.$to->PhoneNumber.'","email": "'.$from->PrimaryEmail.'"  },
"parcels": ['.$frg.'],"extra": { },"async": false}');
/*echo '{"CompanyID":"'.$CompanyId.'","address_from": {
"name": "'.$from->AdditionalFirstName.''.$from->AdditionalLname.'","street1": "'.$from->AddressAccounts.'","street2": "'.$from->addr_2_name.'","city": "'.$from->City.'",
"state": "'.substr($from->State,0,2).'","zip": "'.$from->PostalCode.'","country": "'.$source.'","phone": "'.$from->PrimaryNumber.'","email": "'.$from->PrimaryEmail.'"},
"address_to": {"name": "'.$to->name_f.''.$to->name_l.'","street1": "'.$from->AddressAccounts.'","street2": "'.$from->addr_2_name.'","city": "'.$to->CityId.'",
"state": "'.substr($to->StateId,0,2).'","zip": "'.$to->postal_addr.'","country": "'.$to->id_cntry.'","phone": "'.$to->PhoneNumber.'","email": "'.$from->PrimaryEmail.'"  },
"parcels": ['.$frg.'],"extra": { },"async": false}';*/

        }


        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		

        $msg=json_decode($result);

        $rs='';
        $i=1;
        foreach($msg->Rates as $rg){
            if($rg->Carrier=="DHL Express"){
                $rs=$rg->TotalCost.':0:0:0:'.$rg->TotalCost.':0';
            }      
          $i++;
        }
        return $rs;

    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function newAdditionalUsersDetails($user,$id)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/GetAdditionaluserByCustomerId?id_cust='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        $rs='';
        $i=1;
        foreach($msg->Data as $rg){
            if($rg->id_cust==$id){
                $rs=$rg;
            }      
          $i++;
        }        
        return $rs;
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShipingAddress($user,$bilform)
    {
        $bilform=explode(",",$bilform);
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsAPi/BindShipingAddress?CustId='.$user.'&Activationkey=123456789&billformnum='.$bilform[0].'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public function getShippmentDetailsValues($munits,$shiptype,$service,$source,$destination)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetValues?MUnits='.$munits.'&ShipmentType='.$shiptype.'&ServiceType='.$service.'&Source='.$source.'&destination='.$destination.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data->CubicValue;
    }

    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getchangepassword($user,$oldpwd,$pwd)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoAPI/ChangePassword';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{ "CompanyID":"'.$CompanyId.'","CustId":"'.$user.'", "OldPassword":"'.$oldpwd.'", "NewPassword":"'.$pwd.'", "ConfirmPassword":"'.$pwd.'", "ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{ "CompanyID":"'.$CompanyId.'","CustId":"'.$user.'", "OldPassword":"'.$oldpwd.'", "NewPassword":"'.$pwd.'", "ConfirmPassword":"'.$pwd.'", "ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg;
    }
 
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getnewpassword($user,$token,$pwd)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/AccountAPI/resetPassword';
        //echo '{ "CompanyID":"'.$CompanyId.'","Password":"'.$pwd.'","resetToken":"'.$token.'", "CustId":"'.$user.'", "ActivationKey":"123456789"}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{ "CompanyID":"'.$CompanyId.'","Password":"'.$pwd.'","resetToken":"'.$token.'", "CustId":"'.$user.'", "ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
        
        //**Debug
    //  	echo $url;
    //     echo '{ "CompanyID":"'.$CompanyId.'","Password":"'.$pwd.'","resetToken":"'.$token.'", "CustId":"'.$user.'", "ActivationKey":"123456789"}';
    //     var_dump($result);exit;
        $msg=json_decode($result);
        return $msg;
    }

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUserprofileDetails($user)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/dashboardapi/GetCustomerDetailsByCustId?CustId='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUserpersonalDetails($user)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/GetCustomerInfo?Custid='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
    // echo $url;    
    // var_dump($result);exit;
    
        $msg=json_decode($result);
        return $msg->Data;
    }
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function changepersonalinformation($CustId,$firstName,$lastName,$DailCode,$PrimaryNumber, $AlternativeNumber,$Fax, $PrimaryEmail, $AlternativeEmail, $AddressAccounts, $Country, $State, $City,$PostalCode,$profilepic,$imageByteStream,$fileName,$fileExt,$fileTxt,$DialCodeOther,$address2Txt,$emailNotifications,$dob)
    {
        
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/CustomerInfo';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"ImageByteStream":"","CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","AdditionalFname":"'.$firstName.'","AdditionalLname":"'.$lastName.'","PrimaryNumber":"'.$PrimaryNumber.'","AlternativeNumber":"'.$AlternativeNumber.'","Fax":"'.$Fax.'","DailCode":"'.$DailCode.'","PrimaryEmail":"'.$PrimaryEmail.'","AlternativeEmail":"'.$AlternativeEmail.'","AddressAccounts":"'.$AddressAccounts.'","addr_2_name":"'.$address2Txt.'","Country":"'.$Country.'","State":"'.$State.'","City":"'.$City.'","PostalCode":"'.$PostalCode.'", "custImage":"","fileName":"", "fileExtension":"","DialCodeOther":"'.$DialCodeOther.'","custImageURL":"", "ActivationKey":"123456789","email_notifications":"'.$emailNotifications.'","dob_cust":"'.$dob.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
       
        // echo '{"ImageByteStream":"","CompanyID":"'.$CompanyId.'","CustId":"'.$CustId.'","AdditionalFname":"'.$firstName.'","AdditionalLname":"'.$lastName.'","PrimaryNumber":"'.$PrimaryNumber.'","AlternativeNumber":"'.$AlternativeNumber.'","Fax":"'.$Fax.'","DailCode":"'.$DailCode.'","PrimaryEmail":"'.$PrimaryEmail.'","AlternativeEmail":"'.$AlternativeEmail.'","AddressAccounts":"'.$AddressAccounts.'","addr_2_name":"'.$address2Txt.'","Country":"'.$Country.'","State":"'.$State.'","City":"'.$City.'","PostalCode":"'.$PostalCode.'", "custImage":"","fileName":"", "fileExtension":"","DialCodeOther":"'.$DialCodeOther.'","custImageURL":"", "ActivationKey":"123456789","email_notifications":"'.$emailNotifications.'","dob_cust":"'.$dob.'"}';
        // echo $url;
 		// var_dump($result);exit;
		
        $msg=json_decode($result);
        return $msg->Description;
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduser($CustId, $typeuserTxt,$idTxt,$fnameTxt, $lnameTxt,$country2Txt, $state2Txt, $city2Txt, $PostalCode, $addressTxt,$address2Txt,$emailtxt,$idtypeTxt,$idvalueTxt)
    {
        mb_internal_encoding('UTF-8'); 
        
        if($typeuserTxt == "Consignee"){
            $userType = "ConsigneeUser";
        }
        if($typeuserTxt == "Shipper"){
            $userType = "ShipperUser";
        }
        if($typeuserTxt == "Delivery"){
            $typeuserTxt = "Third Party";
            $userType = "DeliveryUser";
        }
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/InsertOrUpdateAdditionalAddress';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","name_f":"'.$fnameTxt.'","name_l":"'.$lnameTxt.'","id_cntry":"'.$country2Txt.'","id_state":"'.$state2Txt.'","id_city":"'.$city2Txt.'","IdAdduser":"'.$idTxt.'","IdUserType":"'.$userType.'","UserType":"'.$typeuserTxt.'","_IdAdduser":"'.$idTxt.'","addr1Name":"'.$addressTxt.'","Addr2Name":"'.$address2Txt.'","CustId":"'.$CustId.'","ActivationKey":"123456789","email_name":"'.$emailtxt.'","postal_addr":"'.$PostalCode.'","IdentificationType":"'.$idtypeTxt.'","IdentificationValue":"'.$idvalueTxt.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
//         echo '{"CompanyID":"'.$CompanyId.'","name_f":"'.$fnameTxt.'","name_l":"'.$lnameTxt.'","id_cntry":"'.$country2Txt.'","id_state":"'.$state2Txt.'","id_city":"'.$city2Txt.'","IdAdduser":"'.$idTxt.'","IdUserType":"'.$userType.'","UserType":"'.$typeuserTxt.'","_IdAdduser":"'.$idTxt.'","addr1Name":"'.$addressTxt.'","Addr2Name":"'.$address2Txt.'","CustId":"'.$CustId.'","ActivationKey":"123456789","email_name":"'.$emailtxt.'","postal_addr":"'.$PostalCode.'","IdentificationType":"'.$idtypeTxt.'","IdentificationValue":"'.$idvalueTxt.'"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
    }
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getadduserpay($CustId,$gettypeuser,$getid,$getfname,$getlname,$getcountry,$getstate,$getcity,$getzip,$getaddress,$getaddress2,$getemail,$idtypeTxt,$idvalueTxt)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/InsertOrUpdateAdditionalAddress';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","name_f":"'.$getfname.'","name_l":"'.$getlname.'","id_cntry":"'.$getcountry.'","id_state":"'.$getstate.'","id_city":"'.$getcity.'","IdAdduser":"'.$getid.'","IdUserType":"ConsigneeUser","UserType":"'.$gettypeuser.'","_IdAdduser":"'.$getid.'","addr1Name":"'.$getaddress.'","Addr2Name":"'.$getaddress2.'","CustId":"'.$CustId.'","ActivationKey":"123456789","email_name":"'.$getemail.'","postal_addr":"'.$getzip.'","IdentificationType":"'.$idtypeTxt.'","IdentificationValue":"'.$idvalueTxt.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
	   //  echo $url;
    //      echo '{"CompanyID":"'.$CompanyId.'","name_f":"'.$getfname.'","name_l":"'.$getlname.'","id_cntry":"'.$getcountry.'","id_state":"'.$getstate.'","id_city":"'.$getcity.'","IdAdduser":"'.$getid.'","IdUserType":"ConsigneeUser","UserType":"'.$gettypeuser.'","_IdAdduser":"'.$getid.'","addr1Name":"'.$getaddress.'","Addr2Name":"'.$getaddress2.'","CustId":"'.$CustId.'","ActivationKey":"123456789","email_name":"'.$getemail.'","postal_addr":"'.$getzip.'","IdentificationType":"'.$idtypeTxt.'","IdentificationValue":"'.$idvalueTxt.'"}';
    //      var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
    }
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function geteditadduser($CustId,$fid, $typeuserTxt, $idTxt, $fnameTxt, $lnameTxt,$country2Txt, $state2Txt, $city2Txt, $PostalCode, $addressTxt,$address2Txt, $emailTxt, $idtypeTxt, $idvalueTxt)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/InsertOrUpdateAdditionalAddress';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","addtAddrId":"'.$fid.'","name_f":"'.$fnameTxt.'","name_l":"'.$lnameTxt.'","id_cntry":"'.$country2Txt.'","id_state":"'.$state2Txt.'","id_city":"'.$city2Txt.'","IdAdduser":"'.$idTxt.'","IdUserType":"ConsigneeUser","UserType":"'.$typeuserTxt.'","_IdAdduser":"'.$idTxt.'","addr1Name":"'.$addressTxt.'","addr2Name":"'.$address2Txt.'","IdCust":"'.$CustId.'",	"ActivationKey":"123456789","email_name":"'.$emailTxt.'","postal_addr":"'.$PostalCode.'","IdentificationType":"'.$idtypeTxt.'","IdentificationValue":"'.$idvalueTxt.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","addtAddrId":"'.$fid.'","name_f":"'.$fnameTxt.'","name_l":"'.$lnameTxt.'","id_cntry":"'.$country2Txt.'","id_state":"'.$state2Txt.'","id_city":"'.$city2Txt.'","IdAdduser":"'.$idTxt.'","IdUserType":"ConsigneeUser","UserType":"'.$typeuserTxt.'","_IdAdduser":"'.$idTxt.'","addr1Name":"'.$addressTxt.'","addr2Name":"'.$address2Txt.'","IdCust":"'.$CustId.'",	"ActivationKey":"123456789","email_name":"'.$emailTxt.'","postal_addr":"'.$PostalCode.'","IdentificationType":"'.$idtypeTxt.'","IdentificationValue":"'.$idvalueTxt.'"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getaddshippment($CustId, $mnameTxt, $carrierTxt,$carriertrackingTxt,$orderdateTxt,$anameTxt,$quantityTxt,$declaredvalueTxt,$totalpriceTxt,$itemstatusTxt,$countryTxt,$stateTxt,$rmavalue,$orderidTxt,$business_type,$mulfilename,$mulimageByteStream,$lengthTxt,$heightTxt,$widthTxt,$mulfilepath,$package)
    {
        $hostnameStr = $_SERVER['HTTP_HOST'];
        $hostnameStr = str_replace("www.","",$hostnameStr);
        $hostnameArr = explode(".",$hostnameStr);
        $domainurl = $hostnameArr[1].".".$hostnameArr[2];

        
        $loop='';
        for($i=0;$i<count($anameTxt);$i++){
            
            //$imagebytestream=array(); // not sending image bytestream in 2.7.4V
            $itemimage=array();
            $filename=array();
            $filext=array();
            $filepath = array();
                
            
            for($j=0; $j<4; $j++){
                
                   if(isset($mulfilename[$i][$j])){
                    $itemimage[$j]=$mulfilename[$i][$j];
                    $filename[$j]=pathinfo($mulfilename[$i][$j], PATHINFO_FILENAME );
                    $filext[$j] ='.'.pathinfo($mulfilename[$i][$j], PATHINFO_EXTENSION );
                    //$imagebytestream[$j] = $mulimageByteStream[$i][$j]; // not sending image bytestream in 2.7.4V
                    console.log($imagebytestream[$j]);
                    $filepath[$j] = $mulfilepath[$i][$j];

                }
                
            }

            
            $loop.='{"ItemName":"'.base64_encode($anameTxt[$i]).'","ItemQuantity":"'.$quantityTxt[$i].'","ItemPrice":"'.$declaredvalueTxt[$i].'","TotalPrice":"'.$totalpriceTxt[$i].'","ItemStatus":"'.$itemstatusTxt[$i].'","OrderIdNew":"'.$orderidTxt[$i].'","RMAValue":"'.$rmavalue[$i].'","fileName":"'.$filename[0].'","fileExtension":"'.$filext[0].'","fileName1":"'.$filename[1].'","fileExtension1":"'.$filext[1].'","fileName2":"'.$filename[2].'","fileExtension2":"'.$filext[2].'","fileName3":"'.$filename[3].'","fileExtension3":"'.$filext[3].'","ImageByteStream":"'.$imagebytestream[0].'","ImageByteStream1":"'.$imagebytestream[1].'","ImageByteStream2":"'.$imagebytestream[2].'","ImageByteStream3":"'.$imagebytestream[3].'","ItemImage":"'.$filepath[0].'","ItemImage1":"'.$filepath[1].'","ItemImage2":"'.$filepath[2].'","ItemImage3":"'.$filepath[3].'","length":"'.$lengthTxt[$i].'","height":"'.$heightTxt[$i].'","width":"'.$widthTxt[$i].'","Package": "'.$package[$i].'"},';    
        
        }
        
        
        if($stateTxt=="0"){
            $stateTxt='';
        }
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/AddPurchaseOrder1';
        
    
	    $req = '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.strtoupper($CustId).'","SupplierId":"'.$mnameTxt.'","CarrierId":"'.$carrierTxt.'","TrackingId":"'.$carriertrackingTxt.'", "OrderDate":"'.$orderdateTxt.'","Dest_Cntry":"'.$countryTxt.'","Dest_Hub":"'.$stateTxt.'","ItemUrl":"ftp","ActivationKey":"123456789","liInventoryPurchasesVM":['.$loop.'],"domainurl":"'.$domainurl.'","type_busines":"'.$business_type.'"}';
   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.strtoupper($CustId).'","SupplierId":"'.$mnameTxt.'","CarrierId":"'.$carrierTxt.'","TrackingId":"'.$carriertrackingTxt.'", "OrderDate":"'.$orderdateTxt.'","Dest_Cntry":"'.$countryTxt.'","Dest_Hub":"'.$stateTxt.'","ItemUrl":"ftp","ActivationKey":"123456789","liInventoryPurchasesVM":['.$loop.'],"domainurl":"'.$domainurl.'","type_busines":"'.$business_type.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		$prealert_log = "logs/prealert.log";
        file_put_contents($prealert_log,"\n -- Start -- \n Customer ID : ".$CustId."\n Company ID :".$CompanyId."\n Request : \n".$req."\n Response : \n".$result."\n -- End -- \n",FILE_APPEND);
		
		/** Debug **/
		// echo $url;
		// echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.strtoupper($CustId).'","SupplierId":"'.$mnameTxt.'","CarrierId":"'.$carrierTxt.'","TrackingId":"'.$carriertrackingTxt.'", "OrderDate":"'.$orderdateTxt.'","Dest_Cntry":"'.$countryTxt.'","Dest_Hub":"'.$stateTxt.'","ItemUrl":"ftp","ActivationKey":"123456789","liInventoryPurchasesVM":['.$loop.'],"domainurl":"'.$domainurl.'","type_busines":"'.$business_type.'"}';
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
    }

    // update profile pic
    
    public static function updateprofilepic($CustId,$fileName,$fileExt,$imageByteStream,$companyId,$itemimage)
    {
        mb_internal_encoding('UTF-8');

        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/UpdateCustomerProfilePic';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $req = '{"CustomerId":"'.$CustId.'","CompanyID":"'.$CompanyId.'","fileName":"'.$fileName.'","fileExtension":"'.$fileExt.'","ImageByteStream":"","ActivationKey":"123456789","CustImgUrl":"ftp","ItemImage":"'.$itemimage.'"}';
        curl_setopt($ch, CURLOPT_POSTFIELDS,$req);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);

		 /** Debug **/
		 
		 $updateProfPic_log = "logs/updateProfPic.log";
        file_put_contents($updateProfPic_log,"\n -- Start -- \n Customer ID : ".$CustId."\n Company ID :".$CompanyId."\n Request : \n".$req."\n Response : \n".$result."\n -- End -- \n",FILE_APPEND);
		
		 
		// echo $url;
		// echo '{"CustomerId":"'.$CustId.'","CompanyID":"130","fileName":"'.$fileName.'","fileExtension":"'.$fileExt.'","ImageByteStream":"","ActivationKey":"123456789","CustImgUrl":"ftp","ItemImage":"'.$itemimage.'"}';
		// var_dump($result);exit;

        $msg=json_decode($result);
        // return $msg->Description;

        return $msg->Response.":".$msg->Description;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function addShopperassist($CustId, $txtMerchantName, $txtMerchantWebsite,$txtItemName, $txtItemModel, $txtItemRefference, $txtColor, $txtSize,$txtQuantity,$txtDvalue,$txtTprice,$txtItemurl,$txtItemdescription,$mulfilename,$mulimageByteStream,$mulfilepath)
    {
       
       
       $loop='';
       for($i=0;$i<count($txtItemName);$i++){
           
            $imagebytestream=array();
            $itemimage=array();
            $filename=array();
            $filext=array();
            $filepath = array();
            
           for($j=0; $j<4; $j++){
                if(isset($mulfilename[$i][$j])){
                    $itemimage[$j]=$mulfilename[$i][$j];
                    $filename[$j]=pathinfo($mulfilename[$i][$j], PATHINFO_FILENAME );
                    $filext[$j] ='.'.pathinfo($mulfilename[$i][$j], PATHINFO_EXTENSION );
                    $imagebytestream[$j] = $mulimageByteStream[$i][$j];
                    $filepath[$j] = $mulfilepath[$i][$j];
                    
                     }
             }
           
             $loop.='{"ItemName":"'.base64_encode($txtItemName[$i]).'","ItemDesc":"'.$txtItemdescription[$i].'","ItemQuantity":"'.$txtQuantity[$i].'","ItemPrice":"'.$txtDvalue[$i].'","TotalPrice":"'.$txtTprice[$i].'","color":"'.$txtColor[$i].'","itemmodel":"'.$txtItemModel[$i].'","sku":"'.$txtItemRefference[$i].'","itemUrl":"'.$txtItemurl[$i].'","size":"'.$txtSize[$i].'","alertUrl":"ftp","ImageByteStream":"","ImageByteStream1":"","ImageByteStream2":"","ImageByteStream3":"","ImageByteStream4":"","ItemImage":"'.$filepath[0].'","ItemImage1":"'.$filepath[1].'","ItemImage2":"'.$filepath[2].'","ItemImage3":"'.$filepath[3].'","ItemImage4":"","fileName":"'.$filename[0].'","fileName1":"'.$filename[1].'","fileName2":"'.$filename[2].'","fileName3":"'.$filename[3].'","fileName4":"","fileExtension":"'.$filext[0].'","fileExtension1":"'.$filext[1].'","fileExtension2":"'.$filext[2].'","fileExtension3":"'.$filext[3].'","fileExtension4":""},';    
        
         }
             
           
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShopperAssistAPI/InsertItemDetails';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CommandType":"InsertPurchase","CreatedBy":"'.$CustId.'","CustomerId":"'.$CustId.'","Merchant_Website":"'.$txtMerchantWebsite.'","PurchaseType":"Abe","SupplierId":"'.$txtMerchantName.'","OrderDate":"","Company":"","EditLevel":"","TrackingId":"","CarrierId":"","liInventoryPurchasesVM":['.$loop.']}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		// echo $url;
		// echo '{"CompanyID":"'.$CompanyId.'","CommandType":"InsertPurchase","CreatedBy":"'.$CustId.'","CustomerId":"'.$CustId.'","Merchant_Website":"'.$txtMerchantWebsite.'","PurchaseType":"Abe","SupplierId":"'.$txtMerchantName.'","OrderDate":"","Company":"","EditLevel":"","TrackingId":"","CarrierId":"","liInventoryPurchasesVM":['.$loop.']}';
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Description;
    }

   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getShopperassistList($CustId)
    {

        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShopperAssistAPI/GetShopperAssistList';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CommandType":"GetPurchaseDet","CustomerId":"'.$CustId.'","purchaseType":"Shweeboinp"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		/** Debug **/
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","CommandType":"GetPurchaseDet","CustomerId":"'.$CustId.'","purchaseType":"Shweeboinp"}';
// 		var_dump($result);
// 		exit;
		
        $msg=json_decode($result);
        return $msg->Data;
    }
    
    public static function getShopperassistListCsv($user)
    {
        $data= json_decode(json_encode(Controlbox::getShopperassistList($user)),true);
        $keyArr = array("Merchant","Item Name","Quantity","Item Price","Declared Value");
            
            $csv = JPATH_ROOT.'/csvdata/shopperassist_list.csv';
            $file_pointer = fopen($csv, 'w');
             fputcsv($file_pointer, $keyArr);
           
            foreach($data as $i){
                
                // echo '<pre>';
                // var_dump($i);exit;
                
                $row = array(
                    "SupplierId" => $i["SupplierId"],
                    "ItemName" => $i["ItemName"],
                    "ItemQuantity" => $i["ItemQuantity"],
                    "ItemPrice" => $i["ItemPrice"],
                    "TotalPrice" => $i["TotalPrice"]
                    );
                
                    fputcsv($file_pointer, $row);
                
                
            }
               
            // Close the file pointer.
            fclose($file_pointer);
    }
    
    

   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getareturnshippment($CustId, $txtqty,$txtBackCompany, $txtReturnAddress,$txtReturnCarrier, $txtReturnReason, $txtOriginalOrderNumber,$txtMerchantNumber,$txtSpecialInstructions, $dest1,$txtWArehousid,$txtIdkid)
    {

        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/CreateBillFormReturnDiscardHold';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,'{"Qty":"'.$txtqty.'","bill_form_no":"'.$txtWArehousid.'","bill_no":"'.$txtOriginalOrderNumber.'","idkAlert":"'.$txtIdkid.'","item_Status":"Return", "merchant_mo":"'.$txtMerchantNumber.'","r_reason":"'.$txtReturnReason.'","r_s_carrier":"'.$txtReturnCarrier.'","returnAddr":"'.$txtReturnAddress.'","returnCompany":"'.$txtBackCompany.'","spe_instions":"'.$txtSpecialInstructions.'"}');
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","bill_form_no":"'.$txtWArehousid.'","bill_no":"'.$txtOriginalOrderNumber.'","item_Status":"Return","ActivationKey":"123456789","merchant_rno":"'.$txtMerchantNumber.'","r_reason":"'.$txtReturnReason.'","r_s_carrier":"'.$txtReturnCarrier.'","returnAddr":"'.$txtReturnAddress.'","returnCompany":"'.$txtBackCompany.'","spe_instions":"'.$txtSpecialInstructions.'","UploadedFail":"'.$dest1.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","bill_form_no":"'.$txtWArehousid.'","bill_no":"'.$txtOriginalOrderNumber.'","item_Status":"Return","ActivationKey":"123456789","merchant_rno":"'.$txtMerchantNumber.'","r_reason":"'.$txtReturnReason.'","r_s_carrier":"'.$txtReturnCarrier.'","returnAddr":"'.$txtReturnAddress.'","returnCompany":"'.$txtBackCompany.'","spe_instions":"'.$txtSpecialInstructions.'","UploadedFail":"'.$dest1.'"}'; 
//      var_dump($result);
//      exit;
        
        $msg=json_decode($result);
        return $msg->Description;
    }


   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getaholdshippment($CustId,$quantity,$txtReturnReason,$wareHouseids,$itemIdks)
    {
        
        $wareHouseidsStr = explode(",",rtrim($wareHouseids));
        $wareHouseidsUniq = array_unique($wareHouseidsStr);
        $bill_form_no = implode(", ", $wareHouseidsUniq);
         
        // var_dump( $bill_form_no);exit;
        $wrhstrarr=explode(",",rtrim($wareHouseids,","));
		$invidkarr=explode(",",$itemIdks);
		$qtyarr=explode(",",$quantity);
		$wrhss = array();
		for($i=0;$i< count($wrhstrarr);$i++){
		    
		    if(!array_key_exists($wrhstrarr[$i],$wrhss)){
		        $wrhss[$wrhstrarr[$i]] = array([$invidkarr[$i],$qtyarr[$i]]);
		    }else{
		        array_push($wrhss[$wrhstrarr[$i]],array($invidkarr[$i],$qtyarr[$i]));
		    }
		    
		}
        // echo '<pre>';
        // var_dump($wrhss);exit;
    $hold_ship = '';

    foreach($wrhss as $key => $value){
    $idkstrwrs = '';
    $qntstrwrs = '';

    foreach($value as $val){
        if($val[0]!=""){
            $idkstrwrs .= $val[0].",";
        }
        if($val[1]!=""){
            $qntstrwrs .= $val[1].",";
        }
    }

    $hold_ship .= '{"idks":"'.$idkstrwrs.'","qtys":"'.$qntstrwrs.'","BillFormNo":"'.$key.'"},';
}

       $hold_ship = rtrim($hold_ship,",");
		
      
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/CreateBillFormReturnDiscardHold';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $req = '{"CompanyID":"'.$CompanyId.'","bill_form_no":"'.$bill_form_no.',","item_Status":"Hold","r_reason":"'.$txtReturnReason.'","ItemIdk":"'.$itemIdks.'", "Qty":"'.$quantity.'","ActivationKey":"123456789","billFormIdsList":['.$hold_ship.']}';
        curl_setopt($ch, CURLOPT_POSTFIELDS,$req);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","bill_form_no":"'.$bill_form_no.',","item_Status":"Hold","r_reason":"'.$txtReturnReason.'","ItemIdk":"'.$itemIdks.'", "Qty":"'.$quantity.'","ActivationKey":"123456789","billFormIdsList":['.$hold_ship.']}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);


        $holditem_log = "logs/holditem.log";
        file_put_contents($holditem_log,"\n -- Start -- \n Customer ID : \n Company ID :".$CompanyId."\n Request : \n".$req."\n Response : \n".$result."\n -- End -- \n",FILE_APPEND);

		
		// echo $url;
		// echo '{"CompanyID":"'.$CompanyId.'","bill_form_no":"'.$bill_form_no.',","item_Status":"Hold","r_reason":"'.$txtReturnReason.'","ItemIdk":"'.$itemIdks.'", "Qty":"'.$quantity.'","ActivationKey":"123456789","billFormIdsList":['.$hold_ship.']}';
		// var_dump($result);exit;
		
        $msg=json_decode($result);
        return $msg->Description;
    }

   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getdiscardshippment($CustId,$txtQty, $txtReturnReason,$txtWArehousid,$txtIdkid)
    {

        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/CreateBillFormReturnDiscardHold';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","Qty":"'.$txtQty.'","bill_form_no":"'.$txtWArehousid.'","idkAlert":"'.$txtIdkid.'","item_Status":"Discard","ActivationKey":"123456789","r_reason":"'.$txtReturnReason.'"}');
        //echo '{"CompanyID":"'.$CompanyId.'","Qty":"'.$txtQty.'","bill_form_no":"'.$txtWArehousid.'","idkAlert":"'.$txtIdkid.'",	"item_Status":"Discard","ActivationKey":"123456789","r_reason":"'.$txtReturnReason.'"}';
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		//var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Description;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getadduserdeleteInfo($deleteadduserid)
    {
        mb_internal_encoding('UTF-8'); 
         
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/customerInfoApi/delAddtionalAddr?addtAddrId='.$deleteadduserid.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        // echo $url;
        
        $res = json_decode($result);
        // var_dump($res->Description);exit;
        if(strlen($res->Description)==19)
        return 1;
        else
        return 0;
    }       


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getUsersorderscount($user)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/dashboardapi/GetCustomerDetailsByCustId?CustId='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }       


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDialCodeList($countryid)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetDailCodes?ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
      
        return $result;
        
    }  


     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getQuotationProcess($id)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/PickupOrderAPI/GenPickupOrderFromQuo?idkQuotation='.$id.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $msg=json_decode($result);
        return $msg->Msg;
    }  


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersList($user)
    {
         mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetInvertoryPurchases';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data->liInventoryPurchasesVM;
   }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersPendingList($user)
    {
         mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetOrdersbyStatus';
          //$url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetOrdersbyStatus_old';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","PurchaseType":"My1","Status":"Received","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		$msg=json_decode($result);
		
		/** Debug **/
		
// echo $url;
// echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","PurchaseType":"My1","Status":"Received","ActivationKey":"123456789"}';
// var_dump($result);
// exit;

      
        return $msg->Data;
   }
   
     public static function getOrdersPendingListCsv($user)
    {
        $data= Controlbox::getOrdersPendingList($user);
        $keyArr = array("Warehouse Receipt#","Item Description","Quantity","Repack Label","Tracking Id","Merchant Name","Shipment Type","Source Hub","Destination Hub","Destination","Wt. Units","Dimention Units");
            
            $csv = JPATH_ROOT.'/csvdata/pending_list.csv';
            $file_pointer = fopen($csv, 'w');
             fputcsv($file_pointer, $keyArr);
             
             foreach($data as $repack){
                                if($repack->InhouseRepackLbl == ""){
                                     
                                    foreach($repack->WarehouseDetails as $res){
                                        $row = array();
                                        $row["BillFormNo"]=$res->BillFormNo;
                                        
                                        foreach($res->ItemDetails as $rg){
                                            $row["ItemName"]=$rg->ItemName;
                                            $row["ItemQuantity"]=$rg->ItemQuantity;
                                        }
                                        $row["InhouseRepackLbl"]=$repack->InhouseRepackLbl;
                                        $row["TrackingId"]=$res->TrackingId;
                                        $row["MerchantName"]=$res->MerchantName; 
                                        $row["ShipmentType"]=$res->ShipmentType;
                                        $row["SourceHub"]=$res->SourceHub;
                                        $row["DestinationHubName"]=$res->DestinationHubName;
                                        $row["DestinationCountryName"]=$res->DestinationCountryName;
                                        $row["WeightUnit"]=$res->WeightUnit;
                                        $row["DimUnits"]=$res->DimUnits;
                                         fputcsv($file_pointer, $row);
                                    }
                                        // var_dump($row);exit;
                                       
                                   
                                }else{
                                    
                                    foreach($repack->WarehouseDetails as $res){
                                        $row = array();
                                        $row["BillFormNo"]=$res->BillFormNo;
                                        
                                        foreach($res->ItemDetails as $rg){
                                            $row["ItemName"]=$rg->ItemName;
                                            $row["ItemQuantity"]=$rg->ItemQuantity;
                                        }
                                        $row["InhouseRepackLbl"]=$repack->InhouseRepackLbl;
                                        $row["TrackingId"]=$res->TrackingId;
                                        $row["MerchantName"]=$res->MerchantName; 
                                        $row["ShipmentType"]=$res->ShipmentType;
                                        $row["SourceHub"]=$res->SourceHub;
                                        $row["DestinationHubName"]=$res->DestinationHubName;
                                        $row["DestinationCountryName"]=$res->DestinationCountryName;
                                        $row["WeightUnit"]=$res->WeightUnit;
                                        $row["DimUnits"]=$res->DimUnits;
                                        fputcsv($file_pointer, $row);
                                        
                                    }
                                        // var_dump($row);exit;
                                        
                                }
                 
             }
              
            // Close the file pointer.
            fclose($file_pointer);
            
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersHoldList($user)
    {
         mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetOrdersbyStatus';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","PurchaseType":"My1","Status":"Hold","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		$msg=json_decode($result);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","PurchaseType":"My1","Status":"Hold","ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        
        return $msg->Data;
   }
   
    public static function getOrdersHoldListCsv($user)
    {
        $data= Controlbox::getOrdersHoldList($user);
        
        $keyArr = array("Warehouse Receipt#","Item Description","Quantity","Tracking Id","Merchant Name","Shipment Type","Source Hub","Destination Hub","Destination","Wt. Units","Dimention Units");
            
          
            $csv = JPATH_ROOT.'/csvdata/hold_list.csv';
            $file_pointer = fopen($csv, 'w');
            
            
             fputcsv($file_pointer, $keyArr);
             
             foreach($data as $repack){
                                if($repack->InhouseRepackLbl == ""){
                                     
                                    foreach($repack->WarehouseDetails as $res){
                                        $row = array();
                                        $row["BillFormNo"]=$res->BillFormNo;
                                        
                                        foreach($res->ItemDetails as $rg){
                                            $row["ItemName"]=$rg->ItemName;
                                            $row["ItemQuantity"]=$rg->ItemQuantity;
                                        }
                                        
                                        $row["TrackingId"]=$res->TrackingId;
                                        $row["MerchantName"]=$res->MerchantName; 
                                        $row["ShipmentType"]=$res->ShipmentType;
                                        $row["SourceHub"]=$res->SourceHub;
                                        $row["DestinationHubName"]=$res->DestinationHubName;
                                        $row["DestinationCountryName"]=$res->DestinationCountryName;
                                        $row["WeightUnit"]=$res->WeightUnit;
                                        $row["DimUnits"]=$res->DimUnits;
                                        fputcsv($file_pointer, $row);
                                        
                                    }
                                        // var_dump($row);exit;
                                        
                                   
                                }
                 
             }
             
             
               
            // Close the file pointer.
            fclose($file_pointer);
    }
    
    
   public static function getOrdersHistoryListCsv($user)
    {
        $data= Controlbox::getOrdersHistoryList($user,"All","History");
        $keyArr = array("Warehouse Receipt","Item Name","Quantity","Status","Carrier","Tracking Number","Creation Date");
            
            $csv = JPATH_ROOT.'/csvdata/history_list.csv';
            $file_pointer = fopen($csv, 'w');
             fputcsv($file_pointer, $keyArr);
             
             foreach($data as $repack){
                               
                                    foreach($repack->WarehouseDetails as $res){
                                        $row = array();
                                        $row["BillFormNo"]=$res->BillFormNo; 
                                        
                                        foreach($res->ItemDetails as $rg){
                                            $row["ItemName"]=$rg->ItemName;
                                            $row["ItemQuantity"]=$rg->ItemQuantity;
                                            $row["ItemStatus"]=$rg->ItemStatus;
                                        }
                                        
                                        $row["CarrierName"]=$res->CarrierName;
                                        $row["TrackingId"]=$res->TrackingId;
                                        $row["CreatedDate"]=$res->CreatedDate;
                                        fputcsv($file_pointer, $row);
                                    }
                                    
                                    
                 
             }
           
            // foreach($data as $i){
                
            //     // echo '<pre>';
            //     // var_dump($i);exit;
                
            //     $row = array(
            //         "Id" => $i["Id"],
            //         "BillFormNo" => $i["BillFormNo"],
            //         "dti_created" => $i["dti_created"],
            //         "ItemName" => $i["ItemName"],
            //         "Quantity" => $i["Quantity"],
            //         "carrier_name" => $i["carrier_name"],
            //         "TrackingId" => $i["TrackingId"],
            //         "Status" => $i["Status"]
            //         );
                
            //         fputcsv($file_pointer, $row);
                
                
            // }
            
            
               
            // Close the file pointer.
            fclose($file_pointer);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getOrdersHistoryList($user,$status,$purchasetype)
    {
         mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetOrdersbyStatus';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","ActivationKey":"123456789","PurchaseType":"'.$purchasetype.'","Status":"'.$status.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
            // echo $url;
            // echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","ActivationKey":"123456789","PurchaseType":"'.$purchasetype.'","Status":"'.$status.'"}';
            // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
   }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getInvertoryPurchasesList($user)
    {
         mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/AddPurchaseOrder';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
    // 		echo $url;
    // 		echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","ActivationKey":"123456789"}';
    //         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data->liInventoryPurchasesVM;
   }
   
   public static function getInvertoryPurchasesListCsv($user)
    {
        $data= json_decode(json_encode(Controlbox::getInvertoryPurchasesList($user)),true);
            $keyArr = array("Merchant Name","Article Name","Order Date","Quantity","Tracking ID","Cost","Order Id","RMA Value","Status");
            $csv = JPATH_ROOT.'/csvdata/pre_alerts_ind.csv';
            $file_pointer = fopen($csv, 'w');
            
            fputcsv($file_pointer, $keyArr);
              
            foreach($data as $i){
                
                $row = array(
                    "SupplierId" => $i["SupplierId"],
                    "ItemName" => $i["ItemName"],
                    "OrderDate" => $i["OrderDate"],
                    "ItemQuantity" => $i["ItemQuantity"],
                    "TrackingId" => $i["TrackingId"],
                    "cost" => $i["cost"],
                    "OrderIdNew" => $i["OrderIdNew"],
                    "RMAValue" => $i["RMAValue"],
                    "Status" => "In Progress"
                    );
                
                // Write the data to the CSV file
                if($i['Fnsku']=="0" || $i['Fnsku']=="")
                    fputcsv($file_pointer, $row);
                   
            }
               
            // Close the file pointer.
            fclose($file_pointer);
    }
    
    public static function getpendingProjectdetailsCsv($user)
    {
        $data= json_decode(json_encode(Controlbox::getInvertoryPurchasesList($user)),true);
            $keyArr = array("Merchant Name","Article Name","Order Date","Quantity","Tracking ID","Order Id","RMA Value","Status");
            $csv = JPATH_ROOT.'/csvdata/pending_projects.csv';
            $file_pointer = fopen($csv, 'w');
            
            fputcsv($file_pointer, $keyArr);
              
            foreach($data as $i){
                
                $row = array(
                    "SupplierId" => $i["SupplierId"],
                    "ItemName" => $i["ItemName"],
                    "OrderDate" => $i["OrderDate"],
                    "ItemQuantity" => $i["ItemQuantity"],
                    "TrackingId" => $i["TrackingId"],
                    "OrderIdNew" => $i["OrderIdNew"],
                    "RMAValue" => $i["RMAValue"],
                    "Status" => "In Progress"
                    );
                
                // Write the data to the CSV file
                if(strlen($i['Fnsku']) >= 1)
                    fputcsv($file_pointer, $row);
                   
            }
               
            // Close the file pointer.
            fclose($file_pointer);
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getInvoicedetailsList($user)
    {
         mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/getinvoicedetails';
        //var_dump($url);exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","CmdType":"getinvoicedetails","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","CmdType":"getinvoicedetails","ActivationKey":"123456789"}';
//      var_dump($result);
//      exit;
        
        $msg=json_decode($result);
        return $msg->InvoiceDetails;
   }
   
    public static function getInvoicedetailsListCsv($user)
    {
        $data= json_decode(json_encode(Controlbox::getInvoicedetailsList($user)),true);
        
        $keyArr = array("Invoice","Inhouse","Generated In","Consignee","Invoice Type");
            
            $csv = JPATH_ROOT.'/csvdata/invoice_list.csv';
            $file_pointer = fopen($csv, 'w');
             fputcsv($file_pointer, $keyArr);
           
            foreach($data as $i){
                
                $row = array(
                    "InvoiceNumber" => $i["InvoiceNumber"],
                    "FormNumber" => $i["FormNumber"],
                    "Date" => $i["Date"],
                    "ConsigneeName" => $i["ConsigneeName"],
                    "InvoiceType" => $i["InvoiceType"]
                    );
               
                    fputcsv($file_pointer, $row);
               
            }
               
            // Close the file pointer.
            fclose($file_pointer);
    }
    
    
    public static function getViewShipmentsListCsv($user)
    {
        
        mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/GetShipments?custId='.$user.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        $data= json_decode($result);
         
        //var_dump($res);exit;
        
        $keyArr = array("Quotation No.","Pickup Order Id","Warehouse Receipt","Status","Quantity","Pickup Date"); 
            
            $csv = JPATH_ROOT.'/csvdata/viewshipments_list.csv';
            $file_pointer = fopen($csv, 'w');
             fputcsv($file_pointer, $keyArr);
           
            foreach($data as $i){
                
                $row = array(
                    "number_quotation" => $i->number_quotation,
                    "number_pickup_order" => $i->number_pickup_order,
                    "bill_form_no" => $i->bill_form_no,
                    "status" => $i->status,
                    "totalQty" => $i->totalQty,
                    "dti_created" => $i->dti_created,
                    );
               
                    fputcsv($file_pointer, $row);
               
            }
               
            // Close the file pointer.
            fclose($file_pointer);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getQuotationShipmentsList($user)
    {
         mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/QuotationAPI/GetShipments?custId='.$user.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $rs='';
        $i=1;
        foreach($msg as $rg){
            $cv='';
            if($rg->number_pickup_order){
                $cv='<td class="convertToPickup" style="text-align:center;"><a data-id="'.$rg->number_quotation.':'.$rg->id_cust.'">Click Here</a></td>'; 
              }else{
                $cv='<td style="text-align:center;">-</td>';
              }
                if($pickup == "True" && $quotation == "True"){
                  $rs.= '<tr><td>'.$i.'</td><td>'.$rg->number_quotation.'</td>'.$cv.'<td>'.$rg->number_pickup_order.'</td><td>'.$rg->bill_form_no.'</td><td>'.$rg->status.'</td><td>'.$rg->totalQty.'</td><td>'.$rg->dti_created.'</td></tr>';
                }else if($pickup == "True"){
                    if($rg->number_pickup_order != '')
                    $rs.= '<tr><td>'.$i.'</td><td>'.$rg->number_pickup_order.'</td><td>'.$rg->bill_form_no.'</td><td>'.$rg->status.'</td><td>'.$rg->totalQty.'</td><td>'.$rg->dti_created.'</td></tr>';
  
                }elseif($quotation == "True"){    
                    if($rg->number_quotation != '')
                     $rs.= '<tr><td>'.$i.'</td><td>'.$rg->number_quotation.'</td>'.$cv.'<td class="convertToPickup"><a data-id="'.$rg->number_quotation.':'.$rg->id_cust.'">Click</a></td><td>'.$rg->bill_form_no.'</td><td>'.$rg->status.'</td><td>'.$rg->totalQty.'</td><td>'.$rg->dti_created.'</td></tr>';
                }          
                $i++;
        } 
        // var_dump($rs);exit;
        return $rs;
   }
   
   
   
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getPreAlertsList($user)
    {
         mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashBoardAPi/GetPreAlerts?custId='.$user.'&ActivationKEy=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        $rs='';
        $i=1;
        foreach($msg->Data as $rg){
          $rs.= '<tr><td>'.$i.'</td><td>'.$rg->parant_id.'</td><td>'.$rg->msg.'</td><td>'.$rg->dti_created.'</td></tr>';
          $i++;
        }        
        return $rs;
   }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAdditionalUsersSelect($user)
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/GetAdditionaluserByCustomerId?id_cust='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        // echo $url;
        // var_dump($result);exit;
        $msg=json_decode($result);

        $additionalUsersView= '<option value="0">'.Jtext::_('COM_USERPROFILE_SHIP_PLEASE_SELECT_ADDITIONAL_USER').'</option>';
        foreach($msg->Data as $rg){
          if($rg->NameAdduser!=" "){
           $additionalUsersView.= '<option value="'.$rg->id_cust.':'.$rg->id_cntry.'" '.$sel. '>'.$rg->NameAdduser.'</option>';
          }
        }
        return $additionalUsersView;
    }

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAdditionalUsersDetails($user)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/GetAdditionaluserByCustomerId?id_cust='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $rs='';
        $i=1;
        foreach($msg->Data as $rg){
          $rs.= '<tr><td>'.$i.'</td><td>'.$rg->NameAdduser.'</td><td>'.$rg->AddtEmail.'</td><td>'.$rg->userType.'</td><td>'.$rg->CntryName.'</td><td>'.$rg->StateName.'</td><td>'.$rg->CityName.'</td><td>'.$rg->postal_addr.'</td><td>'.$rg->Addr1Name.'</td><td>'.$rg->Addr2Name.'</td><td>'.$rg->IdentificationType.'</td><td>'.$rg->IdentificationValue.'</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id='.$rg->addtAddrId.' data-target="#ord_edit"><i class="fa fa-pencil-square-o"></i></a><a href="#" class="btn btn-danger"  data-toggle="modal"  data-id='.$rg->addtAddrId.' data-target="#ord_delete"><i class="fa fa-trash"></i></a></td></tr>';
          $i++;
        }        
        return $rs;
    }
    
    public static function getAdditionalUsersDetailsCsv($user)
    {
        
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/GetAdditionaluserByCustomerId?id_cust='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $data= json_decode(json_encode($msg->Data),true);
        $keyArr = array("Name","Email","Type of User","Country","State","City","Zipcode","Address1","Address2");
            
            $csv = JPATH_ROOT.'/csvdata/address_list.csv';
            $file_pointer = fopen($csv, 'w');
             fputcsv($file_pointer, $keyArr);
           
            foreach($data as $i){
                
                // echo '<pre>';
                // var_dump($i);exit;
                
                $row = array(
                    "NameAdduser" => $i["NameAdduser"],
                    "AddtEmail" => $i["AddtEmail"],
                    "userType" => $i["userType"],
                    "CntryName" => $i["CntryName"],
                    "StateName" => $i["StateName"],
                    "CityName" => $i["CityName"],
                    "postal_addr" => $i["postal_addr"],
                    "Addr1Name" => $i["Addr1Name"],
                    "Addr2Name" => $i["Addr2Name"]
                    );
                
                    fputcsv($file_pointer, $row);
                
                
            }
               
            // Close the file pointer.
            fclose($file_pointer);
    }
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getBindShipingAddress($user,$bilform)
    {
        mb_internal_encoding('UTF-8');  
         
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsAPi/BindShipingAddress?CustId='.$user.'&Activationkey=123456789&billformnum='.$bilform.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        // echo $url;
        // var_dump($result);exit;
        $msg=json_decode($result);
        return str_replace(",","<br>",$msg->Data->addres);
    }


    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getUserTickets($user)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api//SupportTicketAPI/GetTicketDetailsByCustomerId?id_cust='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg->Data;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getcitiesInfo($cid,$sid,$citid)
    {
        
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Cities?stateId='.$sid.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        $rescities = json_decode($result); 
        $cities='';
        foreach($rescities->Data as $rg){
          if($citid==$rg->CityCode){ 
              $cities.= $rg->CityDesc;
          }
        }        
        return $cities;
    }  

    public static function getstateInfo($cid,$sid)
    {
          mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/States?countryId='.$cid.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $states='';
        $result = json_decode($result); 
        foreach($result->Data as $rg){
          //echo  utf8_encode($sid).'------'.utf8_encode($rg->StateDesc).'<br>';
          if(utf8_encode(trim($sid))==utf8_encode(trim($rg->StateDesc))){ 
            $states=$rg->StatesId;
          }
        } 
        return $states;
    }  
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getCitiesListOne($countryid,$stateid,$cityid)
    {
        mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/Cities?stateId='.$stateid.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        $rescities = json_decode($result); 
        foreach($rescities->Data as $rg){
           if($cityid==$rg->CityCode){
               $cities= $rg->CityDesc;
           }
        }        
        return $cities;
    }  
    
    
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getFnameDetails($user,$f,$l)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoApi/GetAdditionaluserByCustomerId?id_cust='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        foreach($msg->Data as $rg){
          //echo $f.'0000'.$rg->name_f.'---'.$l.'00000'.$rg->name_l.'<br>';
          if($l==$rg->name_l && $f==$rg->name_f){
            return 1;
          }
          
        }

    }
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getpaypalcharge($u)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetConveniencefeesCost?PaymentGateway=Paypal&TotalCost='.$u.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $msg=$msg->Data;
        return $msg->CostwithConveniencefees.":".$msg->Conveniencefees;
    }
    
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getsquarecharge($u)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetConveniencefeesCost?PaymentGateway=Paypal&TotalCost='.$u.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        //echo $url;
        //var_dump($result);exit;
        
        $msg=json_decode($result);
        $msg=$msg->Data;
        return $msg->CostwithConveniencefees.":".$msg->Conveniencefees;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getconvcharge($u,$gateway)
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetConveniencefeesCost?PaymentGateway='.$gateway.'&TotalCost='.$u.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        /** Debug **/
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $msg=$msg->Data;
        return $msg->CostwithConveniencefees.":".$msg->Conveniencefees;
    }
    
    
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getServiceList()
    {
        mb_internal_encoding('UTF-8');  
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/CalculatorAPI/getDefaultAdditionalService?CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        //var_dump($result);exit;
        $msg=json_decode($result);
        return $msg;

    }
    
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getInvoicedetailsId($user)
    {
         mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/getinvoicedetails';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","CmdType":"getinvoicedetails","ActivationKey":"123456789"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
// 		echo '{"CompanyID":"'.$CompanyId.'","CustomerId":"'.$user.'","CmdType":"getinvoicedetails","ActivationKey":"123456789"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
         $i=0;
         $invView = $msg->Data;
        foreach($invView as $k){
            if($k->Date==base64_decode($pay)  || $i==0){
                $status[0]=$k->InvoiceNumber;
            }
            $i++;    
        }
        
        return $status[0];
   }
   
   
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAdditionalServiceList()
    {
         mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getCustAdditionalServices?CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
   }
   
   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getAdditionalServiceListOrder($CustId,$totqnt,$bustype,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$dvalueStr,$shipmentCost,$destCnt)
    {
        
        $lengthStrSum = array_sum(explode(",",$lengthStr));
        $widthStrSum = array_sum(explode(",",$widthStr));
        $heightStrSum = array_sum(explode(",",$heightStr));
        $grosswtStrSum = array_sum(explode(",",$grosswtStr));
        $volumeStrSum = array_sum(explode(",",$volumeStr));
        $volumetwtStrSum = array_sum(explode(",",$volumetwtStr));
        $dvalueStrSum = array_sum(explode(",",$dvalueStr));
        $destCntArr = explode(",",$destCnt);
       
         mb_internal_encoding('UTF-8');
         
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetCustomerAdditionalServices?CompanyID='.$CompanyId.'&Quantity='.$totqnt.'&customerId='.$CustId.'&type_business='.$bustype.'&Length='.$lengthStrSum.'&Width='.$widthStrSum.'&Height='.$heightStrSum.'&GrossWeight='.$grosswtStrSum.'&Volume='.$volumeStrSum.'&VolumetricWeight='.$volumetwtStrSum.'&DeclaredValue='.$dvalueStrSum.'&ShipmentCost='.$shipmentCost.'&Destination='.$destCntArr[0];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
   }
   
   
   
    /* Service to get all the existing branches */
    
    
    public static function getAllBranches()
    {
         mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashBoardAPI/getBranchDetails?CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
		
// 		echo $url;
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
   }
   
   
    /* Service to update the customer selected branch */
    
    
    public static function updateBranchAddress($branch,$custId)
    {
         mb_internal_encoding('UTF-8'); 
        
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/UpdateCustomerBranch?BranchId='.$branch.'&CustId='.$custId.'&CompanyId='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CustId":"'.$custId.'","BranchId":"'.$branch.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
		/** Debug **/
// 		echo $url;
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Response;
   }
   
   
   //  Project request form
   
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getpendingProjectdetails($user)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        //$url=$content_params->get( 'webservice' ).'/api/DashboardAPI/GetProjectRequestDetails?custId='.$user.'&Activationkey=123456789&Status=PEN&ProjectId=';
        $url=$content_params->get( 'webservice' ).'/api/DashBoardAPI/GetPendingProjectRequestDetails';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"AccountNumber":"'.$user.'","Status":"PEN","CompanyID":"'.$CompanyId.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result=curl_exec($ch);
        
        //  echo $url;
        //  echo '{"AccountNumber":"'.$user.'","Status":"PEN","CompanyID":"'.$CompanyId.'"}';
        //  var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }
    
     public static function getProjectRequestsCsv($user)
    {
        $data= json_decode(json_encode(Controlbox::getpendingProjectdetails($user)),true);
        
        //var_dump($data);exit;
        
        $keyArr = array("Account Number","Account Name","Project Name","Inventory","Date Requested");
            
            $csv = JPATH_ROOT.'/csvdata/prf_list.csv';
            $file_pointer = fopen($csv, 'w');
             fputcsv($file_pointer, $keyArr);
           
            foreach($data as $i){
                
                $row = array(
                    "AccountNumber" => $i["AccountNumber"],
                    "AccountName" => $i["AccountName"],
                    "ProjectName" => $i["ProjectName"],
                    "InventoryNeworOverstock" => $i["InventoryNeworOverstock"],
                    "RequestedDate" => $i["RequestedDate"]
                    );
               
                    fputcsv($file_pointer, $row);
               
            }
               
            // Close the file pointer.
            fclose($file_pointer);
    }
    
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getnewservices($CustId)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getCustAdditionalServices?CompanyID='.$CompanyId.'&CustId='.$CustId.'&type_business=';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $result = json_decode($result); 
        return $result;
    }  
    
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getnewservicesedit($user,$shiptype)
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getCustAdditionalServices?CompanyID='.$CompanyId.'&CustId='.$user.'&type_business='.$shiptype;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $result = json_decode($result); 
        
        $res='';
        foreach($result->Data as $rg){
            $res .='<div class="input-grp srvc-grp"><input type="checkbox"  name="ServiceTxt[]"  id="ServiceTxt[]" value="'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label></div>';
        }                  
        
        return $res;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getnewservices1($user,$shiptype)
    {
        mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getCustAdditionalServices?CompanyID='.$CompanyId.'&CustId='.$user.'&type_business='.$shiptype;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $result = json_decode($result); 
        
        $res='';
        foreach($result->Data as $rg){
            $res .='<div class="input-grp srvc-grp"><input type="checkbox"  name="txtService[]"  id="txtService[]" value="'.$rg->Cost.':'.$rg->id_AddnlServ.'"><label for="option">'.$rg->Addnl_Serv_Name.'</label></div>';
        }                  
        
        return $res;
    }
    
   
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getProjectautoid($user)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/AutoGenerateProjectId?CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getExistFnsku($tr)
    {
        mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shipmentsAPi/isExistFNSKU?FNSKUNO='.$tr.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        //$msg=json_decode($result);
        return $result;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getexistProjectname($pid)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetProjectName?ProjectName='.curl_escape($ch, $pid).'&ActivationKey=123456789&CompanyID='.$CompanyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Response;
    } 
    
      /**
     * Posts the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function postprojectrequest($customerid,$projectid,$txtAccountnumber,$txtInventory,$txtAccountname,$txtProjectname,$txtFnsku,$txtFnskuquanity,$dateTxt,$txtProductTitle,$txtUPC,$txtSKU)
    {
		
         mb_internal_encoding('UTF-8'); 
         $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/FBARequestForm';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"ProjectId":"'.$projectid.'","AccountNumber":"'.$customerid.'","AccountName":"'.$txtAccountname.'","InventoryNeworOverstock":"'.$txtInventory.'","ProjectName":"'.$txtProjectname.'","FNSKUNo":"'.$txtFnsku.',","QuantityperFNSKU":"'.$txtFnskuquanity.',","RequestedDate":"'.$dateTxt.'","ProductName":"'.$txtProductTitle.'","SKUNo":"'.$txtSKU.',","UPC":"'.$txtUPC.',","CreatedBy":"CUST","ActivationKey":"123456789","CompanyID":"'.$CompanyId.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		

// 		echo $url;
// 		echo '{"ProjectId":"'.$projectid.'","AccountNumber":"'.$customerid.'","AccountName":"'.$txtAccountname.'","InventoryNeworOverstock":"'.$txtInventory.'","ProjectName":"'.$txtProjectname.'","FNSKUNo":"'.$txtFnsku.',","QuantityperFNSKU":"'.$txtFnskuquanity.',","RequestedDate":"'.$dateTxt.'","ProductName":"'.$txtProductTitle.'","SKUNo":"'.$txtSKU.',","UPC":"'.$txtUPC.',","CreatedBy":"CUST","ActivationKey":"123456789","CompanyID":"'.$CompanyId.'"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Response.':'.$msg->Description;
   }
   
    /**
     * Posts the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function postprojectservicesrequest($customerid,$projectid,$txtService)
    {
         mb_internal_encoding('UTF-8');  
         $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/FBAAdditionalServices';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"ProjectId":"'.$projectid.'","AccountNumber":"'.$customerid.'","AdditionalServices":"'.$txtService.'","CreatedBy":"CUST","CompanyID":"'.$CompanyId.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"ProjectId":"'.$projectid.'","AccountNumber":"'.$customerid.'","AdditionalServices":"'.$txtService.'","CreatedBy":"CUST","CompanyID":"'.$CompanyId.'"}';
//         var_dump($result);exit;
        
        
        $msg=json_decode($result);
        return $msg->Description;
   }
   
    /**
     * Posts the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
     
      public static function postprojectlabelsrequest($projectid,$filenameArr,$byteStramArr)
    {
        
        $commaStr = "";
       for($i=0;$i<count($filenameArr);$i++){
           if($i == count($filenameArr)-1){
               $commaStr = "";
           }else{
               $commaStr = ",";
           }
           $loopFiles .= '{ "FileName": "'.$filenameArr[$i].'","FileData":"'.$byteStramArr[$i].'"}'.$commaStr;
       }
         mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/AddFBAFormLabels';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS,'{"ProjectId":"'.$projectid.'","Data": ['.$loopFiles.'],"CompanyID":"'.$CompanyId.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"ProjectId":"'.$projectid.'","Data": ['.$loopFiles.'],"CompanyID":"'.$CompanyId.'"}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
   }
//     public static function postprojectlabelsrequest($projectid,$filenameArr,$byteStramArr)
//     {
        
//          mb_internal_encoding('UTF-8'); 
//          $CompanyId = Controlbox::getCompanyId();
//         $content_params =JComponentHelper::getParams( 'com_userprofile' );
//         $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/AddFBAFormLabels';
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        
//       $commaStr = "";
//       for($i=0;$i<count($filenameArr);$i++){
//           if($i == count($filenameArr)-1){
//               $commaStr = "";
//           }else{
//               $commaStr = ",";
//           }
//           $loopFiles .= '{ "FileName": "'.$filenameArr[$i].'","FileData":"'.$byteStramArr[$i].'"}'.$commaStr;
//       }
       
      
//         curl_setopt($ch, CURLOPT_POSTFIELDS,'{"ProjectId":"'.$projectid.'","Data": ['.$loopFiles.'],"CompanyID":"'.$CompanyId.'"}');
//         curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
// 		$result=curl_exec($ch);
		
// // 		echo $url;
// // 		 echo '{"ProjectId":"'.$projectid.'","Data": ['.$loopFiles.'],"CompanyID":"'.$CompanyId.'"}';
// //         var_dump($result);exit;
        
//         $msg=json_decode($result);
//         return $msg->Description;
//   }
   
   
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getprojetdetails($user,$itd)
    {
        mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        //$url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getProjectDetailsByProjectId?Id_Project='.$itd.'&ActivationKey=123456789';
        $url=$content_params->get( 'webservice' ).'/api/DashBoardAPI/GetPendingProjectRequestDetails';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"AccountNumber":"'.$user.'","Status":"PEN","ProjectId":"'.$itd.'","CompanyID":"'.$CompanyId.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"AccountNumber":"'.$user.'","Status":"PEN","ProjectId":"'.$itd.'","CompanyID":"'.$CompanyId.'"}';
// 		var_dump($result);exit;
		
        $msg=json_decode($result);
		$msg=$msg->Data;
		//echo '<pre>';
	    $fnsv='';
        $fqt='';
        $fupc='';
        $fsku='';
        
        for($i=0;$i<count($msg[0]->FBAFormItems);$i++){
          $idk.=  $msg[0]->FBAFormItems[$i]->Idk.',';
          $fns.=  $msg[0]->FBAFormItems[$i]->FNSKUNo.',';
          $fqt.=  $msg[0]->FBAFormItems[$i]->QuantityperFNSKU.',';
          $fupc.= $msg[0]->FBAFormItems[$i]->UPC.',';
          $fsku.= $msg[0]->FBAFormItems[$i]->SKUNo.',';
        }
        
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/getAdditionalservicesByProjectId?Id_project='.$msg[0]->ProjectId.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $results=curl_exec($ch);
        
        // echo $url;
        // var_dump($results);exit;
        
        $msgs=json_decode($results);
        $msgs=$msgs->Data;
        $services='';
        foreach($msgs as $row){
            $services.=$row->AdditionalServices.',';
        }
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetFBAFormLabels?ProjectId='.$msg[0]->ProjectId.'&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $resultsw=curl_exec($ch);
        
        // echo $url;
        // var_dump($resultsw);
        // exit;
        
        $msgsw=json_decode($resultsw);
        $msgsw=$msgsw->Data;
        $labels='';
        foreach($msgsw->liFBAFormLabels as $row){
            $labels.=str_replace(":","@",$row->FNSKULabels).',';
        }
        $rda=date("m/d/Y", strtotime($msg[0]->RequestedDate));
        return $msg[0]->ProjectId.':'.$msg[0]->AccountNumber.':'.$msg[0]->AccountName.':'.$msg[0]->InventoryNeworOverstock.':'.$msg[0]->ProjectName.':'.$fns.':'.$fqt.':'.$services.':'.$rda.':'.$fupc.':'.$fsku.':'.$msg[0]->ProductName.':'.$idk.':'.$labels;
   }
   
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getDeleteproject($id)
    {
       
         mb_internal_encoding('UTF-8');
         $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        //$url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/DeleteMypurchase?Id='.$id;
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/delProjectRequestForm?Id_project='.$id.'&CompanyID='.$CompanyId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        if(strlen($result)==21){
            return 1;            
        }else
        return $msg->Response;       
    }
    
    
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function updateprojectrequest($itemid,$customerid,$idk,$AccountNumberTxt,$AccountNameTxt,$InventoryTxt,$ProjectnameTxt,$OrderDateTxt,$productnameTxt,$FnskuTxt,$FnskuquanityTxt,$upcTxt,$skuTxt,$ServiceTxt)
    {
        mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        //$url=$content_params->get( 'webservice' ).'/api/DashboardAPI/GetProjectRequestDetails?custId='.$user.'&Activationkey=123456789&Status=PEN&ProjectId=';
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/UpdateFBARequestForm';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","idk":"'.$idk.',","ProjectId":"'.$itemid.'","AccountNumber":"'.$AccountNumberTxt.'","AccountName":"'.$AccountNameTxt.'","InventoryNeworOverstock":"'.$InventoryTxt.'","ProjectName":"'.$ProjectnameTxt.'","FNSKUNo":"'.$FnskuTxt.',","QuantityperFNSKU":"'.$FnskuquanityTxt.',","RequestedDate":"'.$OrderDateTxt.'","CreatedBy":"CUST","ProductName":"'.$productnameTxt.'","SKUNo":"'.$skuTxt.',","UPC":"'.$upcTxt.',"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result=curl_exec($ch);
        
        // echo $url;
        // echo '{"CompanyID":"'.$CompanyId.'","idk":"'.$idk.',","ProjectId":"'.$itemid.'","AccountNumber":"'.$AccountNumberTxt.'","AccountName":"'.$AccountNameTxt.'","InventoryNeworOverstock":"'.$InventoryTxt.'","ProjectName":"'.$ProjectnameTxt.'","FNSKUNo":"'.$FnskuTxt.',","QuantityperFNSKU":"'.$FnskuquanityTxt.',","RequestedDate":"'.$OrderDateTxt.'","CreatedBy":"CUST","ProductName":"'.$productnameTxt.'","SKUNo":"'.$skuTxt.',","UPC":"'.$upcTxt.',"}';
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        if($msg->Response==200){
            $status=Controlbox::postprojectservicesrequest($customerid,$itemid,$ServiceTxt);
        }
        return $msg->Description;
    }
    
    
     public static function getFooter($CompanyId)
     {
    
            $config = JFactory::getConfig();
            $this->db = mysqli_connect($config->get('host'),$config->get('user'),$config->get('password'),$config->get('db'));
            if (mysqli_connect_errno())
            {
                return "Failed to connect to MySQL";
            }
            else{
                
                $query = "SELECT * FROM bnce7_footers";
                $results  = $this->db->query($query);
                foreach ($results as $row)
                {
                     $content = $row['html_content'];
                    
                }
                
                return $content;

            }

    }
    
    
      /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getStatusList()
    {
        $CompanyId = Controlbox::getCompanyId();
        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetStatusDetails?CompanyId='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res=json_decode($result);
        // echo $url;
        // var_dump($result);exit;
        
        return $res->Data;
    }
    
    
    // inventory alerts start
    
         /**
     * Posts the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function inventoryalertsform($CustId, $rqdate, $orderid,$shipmentdate,$sku,$fnsku,$disposition,$shippedquantity,$carrier,$trackingnumber,$upc,$proid,$rmavalue,$inventoryTxt)
    {
       $CompanyId = Controlbox::getCompanyId();
        $loop='';
        for($i=0;$i<count($rqdate);$i++){
           $loop.='{"CustomerId":"'.$CustId.'","RequestDate":"'.date("Y-m-d",strtotime($rqdate[$i])).'","OrderId":"'.$orderid[$i].'","ShipmentDate":"'.date("Y-m-d",strtotime($shipmentdate[$i])).'","SKU":"'.$sku[$i].'","FNSKU":"'.$fnsku[$i].'","Disposition":"'.$disposition[$i].'","ItemQuantity":"'.$shippedquantity[$i].'","Carrier":"'.$carrier[$i].'","TrackingNumber":"'.$trackingnumber[$i].'","FinalCost":"0","ItemCost":"0","ProjectId":"'.$proid.'","UPC":"'.$upc[$i].'","CompanyID" : "'.$CompanyId.'","OrderIdNew":"'.$orderid[$i].'","RMAValue":"'.$rmavalue[$i].'","TypeBusiness":"'.$inventoryTxt.'"},';    
         }
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/InsertBillformFromCSV';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $len=strlen($loop)-1;
        //echo '{"liBillformFromCSVVM":['.substr($loop,0,$len).']}';
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"liBillformFromCSVVM":['.substr($loop,0,$len).']}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		
// 		echo $url;
// 		echo '{"liBillformFromCSVVM":['.substr($loop,0,$len).']}';
//         var_dump($result);exit;
        
        $msg=json_decode($result);
        return $msg->Description;
   }
   
    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getProjectdetails($user)
    {
        $CompanyId = Controlbox::getCompanyId();
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashboardAPI/GetProjectRequestDetails?custId='.$user.'&Activationkey=123456789&Status=ACT&ProjectId=&CompanyID='.$CompanyId;
        //$url=$content_params->get( 'webservice' ).'/api/DashboardAPI/GetProjectRequestDetails?custId=BX-235270-2020&Activationkey=123456789&Status=PEN&ProjectId=';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);exit;
        
        
         $states='';
        $result = json_decode($result); 
        foreach($result->Data as $rg){
          $states.= '<option value="'.$rg->Id_Project.':'.$rg->Cust_Id.':'.$rg->Project_Name.'" '.$sel.'>'.$rg->Project_Name.'</option>';
        }        
        return $states;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getallProjectdetails($user,$pro)
    {
        $CompanyId = Controlbox::getCompanyId();
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/DashBoardAPI/GetPendingProjectRequestDetails';
        //$url=$content_params->get( 'webservice' ).'/api/DashboardAPI/GetProjectRequestDetails?custId=BX-235270-2020&Activationkey=123456789&Status=PEN&ProjectId=';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"CompanyID":"'.$CompanyId.'","AccountNumber":"'.$user.'","Status":"ACT","ProjectId":"'.$pro.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result=curl_exec($ch);
        
        // echo $url;
        // echo '{"CompanyID":"'.$CompanyId.'","AccountNumber":"'.$user.'","Status":"ACT","ProjectId":"'.$pro.'"}';
        // var_dump($result);exit;
        
        $msg=json_decode($result);
        $msg=$msg->Data;
        $states='';
        $msgs=$msg[0]->FBAFormItems;
        for($i=0;$i<count($msgs);$i++){
          $idk  .=  $msgs[$i]->Idk.',';
          $fns  .=  $msgs[$i]->FNSKUNo.',';
          $fqt  .=  $msgs[$i]->QuantityperFNSKU.',';
          $fupc .=  $msgs[$i]->UPC.',';
          $fsku .=  $msgs[$i]->SKUNo.',';
        }
        //return $msg[0]->ProjectId.':'.$msg[0]->AccountNumber.':'.$msg[0]->AccountName.':'.$msg[0]->InventoryNeworOverstock.':'.$msg[0]->ProjectName.':'.$fns.':'.$fqt.':'.$services.':'.$rda.':'.$fupc.':'.$fsku.':'.$msg[0]->ProductName.':'.$idk.':'.$labels;
        return $msg[0]->ProjectId.':'.$msg[0]->AccountNumber.':'.$msg[0]->AccountName.':'.$msg[0]->InventoryNeworOverstock.':'.$msg[0]->ProjectName.':'.$msg[0]->ProductName.':'.$fns.':'.$fqt.':'.$fupc.':'.$fsku.':'.$idk;
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getServiceType($src,$dest,$shiptype)
    {
        
        $srcArr=explode(",",$src);
        $destArr=explode(",",$dest);
        $src=$srcArr[0];
        $dest=$destArr[0];
        
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetServiceType?CompanyID='.$CompanyId.'&ActivationKey=123456789&Source='.$src.'&Destination='.$dest.'&ShipmentType='.$shiptype;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res= json_decode($result);
        
        $resultStr="";
        $resultStr.='<div class="rdo_cust"><div class="rdo_rd1">';
        
                        foreach($res->Data as $data){
                          $resultStr .='<input type="radio" id="" name="shipmentStr" value="'.$data->id_values.'">';
                          $resultStr .='<label>'.$data->desc_vals.'</label>';
                         }
                         
        $resultStr.="</div></div>";               
                      
        
        // echo '<pre>';
        // echo $url;
        // var_dump($res);exit;
        
        return $resultStr;
    } 
    
   /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    
    public static function getServiceTypeCalc($src,$dest,$shiptype)
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetServiceType?CompanyID='.$CompanyId.'&ActivationKey=123456789&Source='.$src.'&Destination='.$dest.'&ShipmentType='.$shiptype;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res= json_decode($result);
        
        // echo $url;
        // var_dump($res);exit;
        
        $resultStr="";
     
                        foreach($res->Data as $data){
                          $resultStr .='<option value="'.$data->id_values.'">'.$data->desc_vals.'</option>';
                          
                         }
                         
        //$resultStr.="";               
                      
        
        // echo '<pre>';
        // echo $url;
        // var_dump($res);exit;
        
        return $resultStr;
    } 
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
     public static function getDomainList()
    {
       
        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/RegistrationAPI/GetCompanyDetails?ActivationKey=123456789&CompanyId=';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res=json_decode($result);
        
        // var_dump($res);
        // exit;
        
        return $res->Data;
        
        
    }
    
     /** getpaymentmethods **/
     
     public static function getpaymentmethods()
    {
       
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetPaymentType?CompanyID='.$CompanyId.'&Activationkey=123456789';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res=json_decode($result);
        
        // echo $url;
        // var_dump($res);exit;
        
        return $res->Data;
    }
    
     /** getpaymentmethods **/
     
     public static function getpaymentgateways($paymentmethod,$agnPaymentTypeArr)
    {

       
       
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetPaymentType?CompanyID='.$CompanyId.'&Activationkey=123456789';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res=json_decode($result);
        
        // echo $url;
        // var_dump($res);
        // exit;
        
        
        $paymentgatewayStr = '';
        foreach($res->Data as $method){
            if($method->desc_vals){
                if($method->id_values == $paymentmethod){
                    foreach($method->PaymentGatewayDetail as $gateway){
                        if($method->desc_vals){
                            if(in_array("ALL",$agnPaymentTypeArr) || in_array($gateway->id_values,$agnPaymentTypeArr)){
                                $paymentgatewayStr .= '<input type="radio" name="cc" value="'.$gateway->id_values.'">';
                                $paymentgatewayStr .='<label>'.$gateway->desc_vals.'</label>';
                            }
                        }
                    }
                }
            }
        }
        
        return $paymentgatewayStr;
    }
    
    
     /** getpaymentmethods **/
     
     public static function shopgetpaymentgateways($paymentmethod,$paypal,$stripe,$authorize)
    {
       
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetPaymentType?CompanyID='.$CompanyId.'&Activationkey=123456789';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $res=json_decode($result);
        
        // echo $url;
        // var_dump($authorize);
        // exit;
        
        
         $paymentgatewayStr = '';
        foreach($res->Data as $method){
            if($method->desc_vals){
                if($method->id_values == $paymentmethod){
                    foreach($method->PaymentGatewayDetail as $gateway){
                        if($method->desc_vals){

                        if($gateway->id_values == "Paypal" && strtolower($paypal) == "act"){
                            $paymentgatewayStr .= '<input type="radio" name="cc" value="'.$gateway->id_values.'">';
                            $paymentgatewayStr .='<label>'.$gateway->desc_vals.'</label>';
                            
                        }
                        if($gateway->id_values == "Stripe" && strtolower($stripe) == "act"){
                            $paymentgatewayStr .= '<input type="radio" name="cc" value="'.$gateway->id_values.'">';
                            $paymentgatewayStr .='<label>'.$gateway->desc_vals.'</label>';
                        }
                        if($gateway->id_values == "authorize.net" && strtolower($authorize) == "act"){
                            $paymentgatewayStr .= '<input type="radio" name="cc" value="'.$gateway->id_values.'">';
                            $paymentgatewayStr .='<label>'.$gateway->desc_vals.'</label>';
                        }
                       
                        }
                    }
                }
            }
        }
        
        return $paymentgatewayStr;
    }
    
    
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getBillFormAdditionalServices($billformno,$qntstr,$lengthStr,$widthStr,$heightStr,$grosswtStr,$volumeStr,$volumetwtStr,$dvalueStr,$shipmentCost)
    {
        
        $billformnoStr = $billformno.',';
        $qntstr = $qntstr.',';
        $lengthStr = $lengthStr.',';
        $widthStr = $widthStr.',';
        $heightStr = $heightStr.',';
        $grosswtStr = $grosswtStr.',';
        $volumeStr = $volumeStr.',';
        $volumetwtStr = $volumetwtStr.',';
        
        
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/getBillFormAdditionalServices?billformno='.$billformnoStr.'&Quantity='.$qntstr.'&ActivationKey=123456789&CompanyId='.$CompanyId.'&Length='.$lengthStr.'&Width='.$widthStr.'&Height='.$heightStr.'&GrossWeight='.$grosswtStr.'&Volume='.$volumeStr.'&VolumetricWeight='.$volumetwtStr.'&DeclaredValue='.$dvalueStr.'&ShipmentCost='.$shipmentCost;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }       



// get service types in shopperassist page

 
    public static function getservicetypeshop()
    {
         mb_internal_encoding('UTF-8'); 
        $CompanyId = Controlbox::getCompanyId(); 
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/shopperassistapi/BindDropdown?idElem=SERVICEPRIORITY&CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
        $msg=json_decode($result);
        
        	/** Debug **/
// 		echo $url;
//      var_dump($msg);exit;
        
        return $msg;
   }
   
   public static function getdatapaypal($user)
    {   $userdata = '';
        $config = JFactory::getConfig();
        $db = mysqli_connect($config->get('host'),$config->get('user'),$config->get('password'),$config->get('db'));
        $query = "SELECT * FROM bnce7_paypal_data where custId='".$user."' ORDER BY id DESC LIMIT 1";
        $results  = $db->query($query);
                    foreach ($results as $row)
                    {
                        $userdata = $row['data_paypal'];
                        $qty = $row['qty'];
                    }
                    
         return $userdata.":".$qty;      
    }
    
     public static function insertTransactionId($user,$txn_id)
    {   $userdata = '';
        $config = JFactory::getConfig();
        $db = mysqli_connect($config->get('host'),$config->get('user'),$config->get('password'),$config->get('db'));
        $query = "INSERT INTO bnce7_paypal_data (custId,data_paypal,qty,txnId) VALUES ('".$user."','','','".$txn_id."')";
        $results  = $db->query($query);
        if($results){
            return true;
        }else{
             return false;
        }
      
    }
    
     public static function deletecustdata($user)
    {   $userdata = '';
        $config = JFactory::getConfig();
        $db = mysqli_connect($config->get('host'),$config->get('user'),$config->get('password'),$config->get('db'));
        $query = "DELETE FROM bnce7_paypal_data where custId='".$user."'";
        $results  = $db->query($query);
        return $results;
                      
    }
    
    
    
   

     public static function insertcustdata($user,$item_name,$item_number)
    { 
        $config = JFactory::getConfig();
        $db = mysqli_connect($config->get('host'),$config->get('user'),$config->get('password'),$config->get('db'));
        $query = "INSERT INTO bnce7_paypal_data (custId,data_paypal,qty) VALUES ('".$user."','".$item_name."','".$item_number."')";
        $results  = $db->query($query);
        echo "Success";      
    }
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getidentityList()
    {
        mb_internal_encoding('UTF-8');
        
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_register' );
        $url=$content_params->get( 'webservice' ).'/api/CustomerInfoAPI/getIdentityTypes?CompanyID='.$CompanyId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        return $result;
    }  
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function GetBusinessTypes($user)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/shipmentsapi/GetBusinessTypes?CustomerId='.$user.'&ActivationKey=123456789&CompanyID='.$CompanyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }     
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function dynamicpages()
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/AccountAPI/GetDynamicPages?ModuleType=frontend&CompanyID='.$CompanyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }     
    
     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function dynamicElements($page)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/AccountAPI/GetPageElements?PageName='.$page.'&CompanyID='.$CompanyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }   
    
    
     public static function createnewticket($custId,$ticketId,$email,$tktDesc,$tktStatus,$tktCmnts,$cmdtype,$trackingId)
    {
        $CompanyId = Controlbox::getCompanyId();
        mb_internal_encoding('UTF-8');  
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $url=$content_params->get( 'webservice' ).'/api/SupportTicketAPI/InsertTicket';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"number_ticket":"'.$ticketId.'","status_ticket":"'.$tktStatus.'","EmailId":"'.$email.'","id_cust":"'.$custId.'","reason_ticket":"'.$tktDesc.'","comments_ticket":"'.$tktCmnts.'","CommandType":"'.$cmdtype.'","CompanyID":"'.$CompanyId.'","TrackingNo":"'.$trackingId.'"}');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result=curl_exec($ch);
        $res=json_decode($result);
        
        // echo $url;
        // echo '{"number_ticket":"'.$ticketId.'","status_ticket":"'.$tktStatus.'","EmailId":"'.$email.'","id_cust":"'.$custId.'","reason_ticket":"'.$tktDesc.'","comments_ticket":"'.$tktCmnts.'","CommandType":"'.$cmdtype.'","CompanyID":"'.$CompanyId.'","TrackingNo":"'.$trackingId.'"}';
        // var_dump($res);
        // exit;
        
    
        return $res->Response.":".$res->Description;
        
    }
    
     public static function getTicketList($custId,$tid)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/SupportTicketAPI/GetTicketDetails?id_cust='.$custId.'&CompanyID='.$CompanyId;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }
    
      public static function getetTicketDetailsById($tid)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/SupportTicketAPI/GetTicketDetailsById?TicketNumber='.$tid.'&CompanyID='.$CompanyId; 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        $msg=json_decode($result);
        return $msg->Data;
    }
    
      public static function GetInvoicesCount($user,$invoiceType)
    {
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetInvoicesCount?CustomerID='.$user.'&InvoiceType='.$invoiceType.'&CompanyID='.$CompanyId; 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $msg=json_decode($result);
        
        // echo $url;
        // var_dump($result);
        // exit;
        
        return $msg;
    }
    
      public static function getlabels($langSel)
    {
       mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $ch = curl_init();
        //$url=$content_params->get( 'webservice' )."/api/ImgUpldFTP/ConvertResxXmlToJson?companyId=".$CompanyId."&language=".$langSel;
        $url = $content_params->get( 'webservice' )."/api/ImgUpldFTP/GetdataForLS?companyId=".$CompanyId."&language=".$langSel;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        
        // echo $url;
        // var_dump($resp);
        // exit;
        
        $res = json_decode($resp,true);
        return $res["Data"];
    }
    
      public static function getPromoCodes($user,$amount,$volmetStr,$volStr,$qtyStr,$wtStr,$shippingCost)
    {
        
        $volmetrtot = array_sum(explode(",",$volmetStr));
        $voltot = array_sum(explode(",",$volStr));
        $qtytot = array_sum(explode(",",$qtyStr));
        $wttot = array_sum(explode(",",$wtStr));
        
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        
        $ch = curl_init();
        $url=$content_params->get( 'webservice' )."/api/ShipmentsAPI/GetPromoCoupons?CompanyID=".$CompanyId."&CustomerId=".$user;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        $res = json_decode($resp);
        $getpromocodes = $res->Data;
        
        // echo $url;
        // var_dump($res);
        // exit;
        
        
        $respStr = "";
        //$respStr .= "<h4><strong>Applicable</strong></h4><br>";
        
        foreach($getpromocodes as $promocode){
            
            if($promocode->Discount_Type == "Flat"){
                
                if($promocode->Units){
                foreach($promocode->units_rates as $units){
                        if($promocode->Charge_apply_by == "finalcost"){
                            if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                               if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                               {
                                    $discountAmt = (float)$units->Discount_units;
                               }
                               if(!floatval($promocode->MinThreshold)){
                                   $discountAmt = (float)$units->Discount_units;
                               }
                             }
                         }
                         if($promocode->Charge_apply_by == "volumetricweight"){
                            if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                               if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                               {
                                    $discountAmt = (float)$units->Discount_units;
                               }
                               if(!floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                               }
                             }
                         }
                         if($promocode->Charge_apply_by == "Volume"){
                            if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }
                                 if(!floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }
                             }
                         }
                         if($promocode->Charge_apply_by == "grossweight"){
                            if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                 if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }
                                 if(!floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }
                             }
                         }
                         if($promocode->Charge_apply_by == "Quanity"){
                            if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                 if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }
                                 if(!floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }
                             }
                         }
                         if($promocode->Charge_apply_by == "shippingcost"){
                            if($shippingCost >= (float)$units->start_units && $shippingCost <= (float)$units->end_units){
                                if($shippingCost >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }if(!floatval($promocode->MinThreshold)){
                                    $discountAmt = (float)$units->Discount_units;
                                 }
                             }
                         } 
                    }
                }else{
                    
                                  if(floatval($promocode->MinThreshold)){
                                
                                       if($promocode->Charge_apply_by == "finalcost"){
                                           if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                                           {
                                                $discountAmt = $promocode->Promo_DiscountValue;
                                           }
                                        }
                                        if($promocode->Charge_apply_by == "volumetricweight"){
                                            
                                            if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                                           {
                                                $discountAmt = $promocode->Promo_DiscountValue;
                                           }
                                         }
                                        if($promocode->Charge_apply_by == "Volume"){
                                            if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = $promocode->Promo_DiscountValue;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "grossweight"){
                                            if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = $promocode->Promo_DiscountValue;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "Quanity"){
                                            if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = $promocode->Promo_DiscountValue;
                                             }
                                        }
                                        if($promocode->Charge_apply_by == "shippingcost"){
                                            if($shippingCost >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = $promocode->Promo_DiscountValue;
                                             }
                                        } 
                                        
                                  }else{
                                      $discountAmt = $promocode->Promo_DiscountValue;
                                  }
                }         
                
                
            }else{ // percentage
                
                        if($promocode->Units){
                            foreach($promocode->units_rates as $units){
                                    if($promocode->Charge_apply_by == "finalcost"){
                                        if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                             if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                 $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                             }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "volumetricweight"){
                                        if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                            if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "Volume"){
                                        if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                            if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "grossweight"){
                                        if($amount > (float)$units->start_units && $amount < (float)$units->end_units){
                                            if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "Quanity"){
                                        if($amount > (float)$units->start_units && $qtytot < (float)$units->end_units){
                                            if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$amount/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$amount/100;
                                            }
                                         }
                                    }
                                    if($promocode->Charge_apply_by == "shippingcost"){
                                        if($shippingCost > (float)$units->start_units && $shippingCost < (float)$units->end_units){
                                            if($shippingCost > $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$shippingCost/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$shippingCost/100;
                                            }
                                         }
                                    } 
                                }
                        }else{
                                        if($promocode->Charge_apply_by == "finalcost"){
                                            if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                            }
                                            
                                         }
                                         if($promocode->Charge_apply_by == "volumetricweight"){
                                             if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                            }
                                         }
                                         if($promocode->Charge_apply_by == "Volume"){
                                             if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "grossweight"){
                                             if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "Quanity"){
                                             if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "shippingcost"){
                                             if($shippingCost >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$shippingCost)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$shippingCost)/100;
                                             }
                                         }
                        }
                    
                    }
                 
            if($promocode->Transactions > 0){
                if(floatval($promocode->MaxDiscount) && $discountAmt > $promocode->MaxDiscount){
                    $discountAmt = $promocode->MaxDiscount;
                }
                if($discountAmt > 0){
                    $respStr .='<div class="cupn-cde-lst-bg-gray">
                               <h4><strong>'.$promocode->Promo_CouponCode.'</strong></h4>
                               <p >You can save $'.$discountAmt.'<a class="applyCode" style="cursor: pointer;" data-code="'.$promocode->Promo_CouponCode.'" data-val="'.$discountAmt.'" >Apply Coupon</a></p>
                               <p style="display:none;">You can save $'.$discountAmt.'<a class="removeCode" style="cursor: pointer;" data-code="'.$promocode->Promo_CouponCode.'" data-val="'.$discountAmt.'">Applied <i class="fa fa-times-circle" aria-hidden="true"></i> </a></p>
                            </div>';
                }
            }
              
        } 
        
      
        
        return $respStr;
    }

    
    public static function repackRequest($user,$wrhStr,$invidkStr,$qtyStr,$artstrarr,$pricestrarr,$statusRequest,$custSer,$repackDesc,$repackLblStr)
    {
        $articleStr = implode(",",$artstrarr);
        $priceStr = implode(",",$pricestrarr);

        $wrhstrarr=explode(",",$wrhStr);
		$invidkarr=explode(",",$invidkStr);
		$qtyarr=explode(",",$qtyStr);
		$repacklblarr=explode(",",$repackLblStr);
        
        $uploadFiles = "";
        for($i=0;$i<count($invidkarr);$i++){
            $uploadFiles .= "0,";
        }
		
		$wrhs = array();
		for($i=0;$i< count($wrhstrarr);$i++){
		    
		    if(!array_key_exists($wrhstrarr[$i],$wrhs)){
		        $wrhs[$wrhstrarr[$i]] = array([$invidkarr[$i],$qtyarr[$i],$artstrarr[$i],$pricestrarr[$i],$repacklblarr[$i]]);
		    }else{
		        array_push($wrhs[$wrhstrarr[$i]],array($invidkarr[$i],$qtyarr[$i],$artstrarr[$i],$pricestrarr[$i],$repacklblarr[$i]));
		    }
		    
		}
		$wrhsloop = '';
		
		foreach($wrhs as $key => $value){
		    $idkstrwr = '';
		    $qntstrwr = '';
		    $artstrwr = '';
		    $pricestrwr = '';
		    $repacklblwr='';
		    foreach($value as $val){
		        if($val[0]!=""){
		        $idkstrwr .= $val[0].",";
		        }
		        if($val[1]!=""){
		        $qntstrwr .= $val[1].",";
		        }
		        if($val[2]!=""){
		        $artstrwr .= $val[2].",";
		        }
		        if($val[3]!=""){
		        $pricestrwr .= $val[3].",";
		        }
		         if($val[4]!=""){
		        $repacklblwr .= $val[4];
		        }
		    }

            $artstrwr = rtrim($artstrwr,",");
		    
		    $wrhsloop .= '{"BillFormNo":"'.$key.'","idks":"'.$idkstrwr.'","qtys":"'.$qntstrwr.'","itemNames":"'.$artstrwr.'","totalPrices":"'.$pricestrwr.'","inhouseRepackLbl":"'.$repacklblwr.'"},';
		}
		$wrhsloop = rtrim($wrhsloop,",");
        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $CompanyId = Controlbox::getCompanyId();
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/InsertRepackingorConsolidation';


        $req = '{
            "CompanyID": "'.$CompanyId.'",
            "billFormIdsList": ['.$wrhsloop.'],
            "idks": "'.$invidkStr.',",
            "qtys": "'.$qtyStr.',",  
            "billFormIds": "'.$wrhStr.',",  
            "ShippingCost": "0.00",
            "ConsigneeId": "'.$user.'",
            "Comments": "",
            "PaymentType": "COD",
            "CustId": "'.$user.'",
            "id_serv": "'.$custSer.'",
            "UploadedFile": "'.$uploadFiles.'",
            "fileName": "'.$uploadFiles.'",
            "fileExtension": "'.$uploadFiles.'",
            "InHouseNo": "",
            "InhouseId": "",
            "EachItemName": "'.$articleStr.',",
            "EachItemQty": "'.$qtyStr.',",
            "TotalitemsPrice": "'.$priceStr.',",
            "domainname": "iblesoft",
            "domainurl": "inviewpro.com",
            "StatusRequest": "'.$statusRequest.'",
            "RepackComments":"'.$repackDesc.'"
          }';
          
    //   var_dump($req);exit;

        
          
        /** Debug **/

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$req);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result=curl_exec($ch);
   
		$msg=json_decode($result);
		
		// echo $url."##".$req;
        // var_dump($result);
        // exit;

        return $msg;
		
    }

    // unpack request

    public static function unpackRequest($wrhs,$repackId){

        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $CompanyId = Controlbox::getCompanyId();
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/UnpackorDeConsolidation?warehouseno='.$wrhs.',&inhouseRepackLbl='.$repackId.'&companyId='.$CompanyId.'&StatusRequest=unpack';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		$msg=json_decode($result);

        // echo $url;
        // var_dump($result);
        // exit;
       
        return $msg;

    }
    
    
    //get warehouse details 
    
     // unpack request

    public static function getWrhsDetails($wrhs,$user){

        mb_internal_encoding('UTF-8');
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        $CompanyId = Controlbox::getCompanyId();
        $url=$content_params->get( 'webservice' ).'/api/ShipmentsAPI/GetInventoryDetails?CustId='.$user.'&CompanyID='.$CompanyId.'&warehouseNo='.$wrhs;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result=curl_exec($ch);
		$msg=json_decode($result);
		
		$wrhsDetCont = "";
	foreach($msg->Data as $wrhsDet){
      $wrhsDetCont.='<div class="panel-group">
      
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a>'.$wrhsDet->BillFormno.'</a><span id="expand" class="expandPlus"></span>
          </h4>
        </div>
        <div id="test" class="panel-collapse collapse">
          <div class="panel-body"> 
          <div class="table-responsive">
          <table class="table table-bordered theme_table">
         <tr class="warehuse-bg"><td><strong>Quantity Received</strong> </td><td><strong>Quantity Shipped </strong></td><td><strong>Quantity Rejected </strong></td><td><strong>Quantity Delivered</strong> </td><td><strong>Quantity On Hand</strong></td><td style="width:100px;"><strong>Status</strong></td></tr>
         <tr><td>'.$wrhsDet->QtReceived.'</td><td>'.$wrhsDet->QtShipped.'</td><td>'.$wrhsDet->QtRejected.'</td><td>'.$wrhsDet->QtDeliverd.'</td><td>'.$wrhsDet->QtOnhand.'</td><td>'.$wrhsDet->Status.'</td></tr>
         
         </table>
         </div>
          </div>
        </div>
      </div>
    
    </div>';
      } //  <div class="panel-collapse collapse">
  return $wrhsDetCont;

    }

    //notification list

     /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function getmainpagedetails($categoryCode)
    {
        mb_internal_encoding('UTF-8');
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams('com_userprofile');
        $url=$content_params->get( 'webservice' ).'/api/DashBoardAPI/getmainpagedetails?CompanyID='.$CompanyId.'&Activationkey=123456789&CategoryCode='.$categoryCode;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result=curl_exec($ch);
        $msg=json_decode($result);
        
        /** Debug **/
        // echo $url;
        // var_dump($msg);
        // exit;
        
        return $msg->Data;
    }    
    
    public static function getCouponCodes($user,$amount,$volmetStr,$volStr,$qtyStr,$wtStr,$shippingCost,$couponCode){
        
        $volmetrtot = array_sum(explode(",",$volmetStr));
        $voltot = array_sum(explode(",",$volStr));
        $qtytot = array_sum(explode(",",$qtyStr));
        $wttot = array_sum(explode(",",$wtStr));
        
        mb_internal_encoding('UTF-8');  
        $CompanyId = Controlbox::getCompanyId();
        $content_params =JComponentHelper::getParams( 'com_userprofile' );
        
        $ch = curl_init();
        $url=$content_params->get( 'webservice' )."/api/ShipmentsAPI/GetPromoCoupons?CompanyID=".$CompanyId."&CustomerId=".$user;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        $res = json_decode($resp);
        $getpromocodes = $res->Data;
        
        foreach($getpromocodes as $promocode){
            
            if($promocode->Promo_CouponCode == $couponCode){
                
                if($promocode->Discount_Type == "Flat"){
                    
                    if($promocode->Units){
                    foreach($promocode->units_rates as $units){
                            if($promocode->Charge_apply_by == "finalcost"){
                                if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                   if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                                   {
                                        $discountAmt = (float)$units->Discount_units;
                                   }
                                   if(!floatval($promocode->MinThreshold)){
                                       $discountAmt = (float)$units->Discount_units;
                                   }
                                 }
                             }
                             if($promocode->Charge_apply_by == "volumetricweight"){
                                if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                   if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                                   {
                                        $discountAmt = (float)$units->Discount_units;
                                   }
                                   if(!floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                   }
                                 }
                             }
                             if($promocode->Charge_apply_by == "Volume"){
                                if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                    if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }
                                     if(!floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }
                                 }
                             }
                             if($promocode->Charge_apply_by == "grossweight"){
                                if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                     if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }
                                     if(!floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }
                                 }
                             }
                             if($promocode->Charge_apply_by == "Quanity"){
                                if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                     if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }
                                     if(!floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }
                                 }
                             }
                             if($promocode->Charge_apply_by == "shippingcost"){
                                if($shippingCost >= (float)$units->start_units && $shippingCost <= (float)$units->end_units){
                                    if($shippingCost >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }if(!floatval($promocode->MinThreshold)){
                                        $discountAmt = (float)$units->Discount_units;
                                     }
                                 }
                             } 
                        }
                    }else{
                        
                                      if(floatval($promocode->MinThreshold)){
                                    
                                           if($promocode->Charge_apply_by == "finalcost"){
                                               if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                                               {
                                                    $discountAmt = $promocode->Promo_DiscountValue;
                                               }
                                            }
                                            if($promocode->Charge_apply_by == "volumetricweight"){
                                                
                                                if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold))
                                               {
                                                    $discountAmt = $promocode->Promo_DiscountValue;
                                               }
                                             }
                                            if($promocode->Charge_apply_by == "Volume"){
                                                if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                    $discountAmt = $promocode->Promo_DiscountValue;
                                                 }
                                             }
                                             if($promocode->Charge_apply_by == "grossweight"){
                                                if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                    $discountAmt = $promocode->Promo_DiscountValue;
                                                 }
                                             }
                                             if($promocode->Charge_apply_by == "Quanity"){
                                                if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                    $discountAmt = $promocode->Promo_DiscountValue;
                                                 }
                                            }
                                            if($promocode->Charge_apply_by == "shippingcost"){
                                                if($shippingCost >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                    $discountAmt = $promocode->Promo_DiscountValue;
                                                 }
                                            } 
                                            
                                      }else{
                                          $discountAmt = $promocode->Promo_DiscountValue;
                                      }
                    }         
                    
                    
                }else{ // percentage
                
                        if($promocode->Units){
                            foreach($promocode->units_rates as $units){
                                    if($promocode->Charge_apply_by == "finalcost"){
                                        if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                             if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                 $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                             }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "volumetricweight"){
                                        if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                            if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "Volume"){
                                        if($amount >= (float)$units->start_units && $amount <= (float)$units->end_units){
                                            if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "grossweight"){
                                        if($amount > (float)$units->start_units && $amount < (float)$units->end_units){
                                            if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$units->Discount_units)*$amount)/100;
                                            }
                                         }
                                     }
                                     if($promocode->Charge_apply_by == "Quanity"){
                                        if($amount > (float)$units->start_units && $qtytot < (float)$units->end_units){
                                            if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$amount/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$amount/100;
                                            }
                                         }
                                    }
                                    if($promocode->Charge_apply_by == "shippingcost"){
                                        if($shippingCost > (float)$units->start_units && $shippingCost < (float)$units->end_units){
                                            if($shippingCost > $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$shippingCost/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = ((float)$units->Discount_units)*$shippingCost/100;
                                            }
                                         }
                                    } 
                                }
                        }else{
                                        if($promocode->Charge_apply_by == "finalcost"){
                                            if($amount >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                            }
                                            if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                            }
                                            
                                         }
                                         if($promocode->Charge_apply_by == "volumetricweight"){
                                             if($volmetrtot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                            }
                                         }
                                         if($promocode->Charge_apply_by == "Volume"){
                                             if($voltot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "grossweight"){
                                             if($wttot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "Quanity"){
                                             if($qtytot >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$amount)/100;
                                             }
                                         }
                                         if($promocode->Charge_apply_by == "shippingcost"){
                                             if($shippingCost >= $promocode->MinThreshold && floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$shippingCost)/100;
                                             }
                                             if(!floatval($promocode->MinThreshold)){
                                                $discountAmt = (((float)$promocode->Promo_DiscountValue)*$shippingCost)/100;
                                             }
                                         }
                        }
                    
                    }
            
                 
                if($promocode->Transactions > 0){
                    
                    if(floatval($promocode->MaxDiscount) && $discountAmt > $promocode->MaxDiscount){
                        $discountAmt = $promocode->MaxDiscount;
                    }
                    
                    if($discountAmt > 0){
                        return "1:".$discountAmt;
                    }
                }
            
            } 
        }
        
        return "0:0";
         
    }
   
    
    
    
} 
