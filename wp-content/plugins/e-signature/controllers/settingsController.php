<?php
/**
 * settingsController
 * @since 0.1.0
 * @author Micah Blu
 */

class WP_E_SettingsController extends WP_E_appController {

	private $model;
	public function __construct(){
		parent::__construct();
		$this->model = new WP_E_Setting();
		$this->general = new WP_E_General();
		$this->settings = new stdClass();
		$this->settings->has_signature = false;
		$this->document = new WP_E_Document();
		$this->esigrole = new WP_E_Esigrole();
        $this->view = new WP_E_View();
        
	}

	public function calling_class(){
		return get_class();
	}

	private function queueScripts(){
		wp_register_script( "esign-settings", $this->getAssetDirectoryURI() . '/js/settings.js', array('jquery', 'signaturepad'), null, true);
		wp_localize_script( 'esign-settings', 'esigAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
		wp_enqueue_script('esign-settings');
	}

	/**
	 * Index 
	 * This method prepare the settings page form 
	 * @since 0.1.0
	 */
	public function index(){
        
		//queue scripts needed for this view
		$esig_license =$this->model->get_generic("esig_wp_esignature_license_active"); 
        if(empty($esig_license) || $esig_license == 'invalid'){
            $this->view->setAlert(array('type'=>'e-sign-red-alert alert e-sign-alert esig-updated', 'title'=>'', 'message'=>__("<strong>Urgent, License Needed:</strong> WP-Esignature add-on requires a valid license for critical security updates - <a href='admin.php?page=esign-licenses-general' class='e-sign-enter-license'>Enter License</a>", 'esig')));
        }
		$this->queueScripts(); 
		
		// Prepare Variables for the form view
		$wp_user_id = get_current_user_id();
        
		$userdata = $this->user->getUserByWPID($wp_user_id);
		
		$this->settings->default_display_page = $this->model->get("default_display_page");
		$this->settings->company_logo = $this->model->get("company_logo");
		$this->settings->force_ssl_enabled = $this->model->get("force_ssl_enabled");
		
		//check the default page is not exists . 
		$pageID = $this->model->get_generic('default_display_page');
		$page_data = get_page($pageID);
		if(isset($_GET['add_shortcode']) && $_GET['add_shortcode']){
		
		   $shortcode_content = ' [wp_e_signature] ' .  $page_data->post_content ;  
		   // Update post 37
		$my_post = array(
			'ID'=> $pageID,
      'post_content' =>$shortcode_content
			);
// Update the post into the database
		wp_update_post( $my_post );
		wp_redirect('admin.php?page=esign-docs');	
		}
		
		 $ext_error = $this->general->esig_requirement();
		
		if($ext_error !=''){
			$this->view->setAlert(array('type'=>'e-sign-red-alert alert e-sign-alert esig-updated', 'title'=>'', 'message'=> $ext_error ));
		}
		
		if($this->document->document_document_page_exists($pageID)){
			$this->view->setAlert(array('type'=>'e-sign-red-alert alert e-sign-alert esig-updated', 'title'=>'', 'message'=>__( "Oh snap! Your default document page has been deleted.", 'esig')));
		} 
		
		// if there is no short code this msg will be display
		if($page_data) :
        if (function_exists('has_shortcode'))
        {
		    if(!has_shortcode($page_data->post_content, 'wp_e_signature' ))
		    {
				    $page_title=$page_data->post_title ; 
				    $permalink="post.php?post={$pageID}&action=edit";
			    $this->view->setAlert(array('type'=>'e-sign-red-alert alert e-sign-alert esig-updated', 'title'=>'', 'message'=>sprintf( __( "Oh snap! Your default document page <a href='%1s'>%2s</a> shortcode  has been deleted :-<a href='admin.php?page=esign-settings&add_shortcode=1'>Add Shortcode</a>", 'esig'), $permalink, $page_title )));
		    }
        }
		endif ;
		
		if(!empty($userdata) && count($userdata) > 0){

			foreach($userdata as $field => $value){
				$this->settings->$field =stripslashes( $value);
			}
		
			$signature = new WP_E_Signature();
			
			if($signature->userHasSignature($this->settings->user_id)){
				$this->settings->output = $signature->getUserSignature($this->settings->user_id);
				$this->settings->has_signature = true;
			}
		}


		// If post is present process it
		if(count($_POST) > 0){ $this->update();}

		// Prepare template data
		$settings = $this->settings;
		$template_data = (array) $settings;
		$template_data["settings_tab_class"] = "nav-tab-active";
		$template_data["Licenses"]=$this->general->checking_extension() ;
		//prepare post select 
		$pages = $this->getPages();
		$post_select ='<select id="default_display_page" class="esig-select2" name="default_display_page"  style="width:288px;" >';
		$post_select .= '<option value="">-- Select a page --</option>';
		
		foreach($pages as $page) :
			//echo $page->post_title . "<br />";
			    if (function_exists('has_shortcode'))
            {
			        if(!has_shortcode($page->post_content, 'wp_e_signature_sad' ))
		        {
				        $post_select .= '<option value="' . $page->ID . '" ' . ( isset($pageID) && $pageID == $page->ID ? "selected"	 : "" 	) . '>' . $page->post_title  . '</option>';
		        }
		    }	 
		endforeach;
		
		$post_select .= '</select>';
		
		 if($this->model->get("force_ssl_enabled"))
		 {
		    $force_ssl_enabled='checked' ;
		 }
		 else {$force_ssl_enabled="";}
		
		
		if($this->esigrole->esig_current_user_can('set_esig_page')){
			$template_data["post_select"] = $post_select;
			$template_data["ssl_checked"] = $force_ssl_enabled;
		}
		
		
		$template_data["post_action"] = 'admin.php?page=esign-settings';
		$template_data["signature_classes"] = $this->settings->has_signature? "signed":"unsigned";
		$template_data["nonce"] = wp_create_nonce('save-sig'); 	
        // getting esign hide data 
		$esign_hide=$this->model->get_generic('esig_unlimited_hide_settings');
        
		$esign_hide_checked=(isset($esign_hide) && $esign_hide==1)?"checked":"";
        
        $template_data["esign_hide_data"]=$esign_hide_checked;
        
		$template_data["message"] = $this->view->renderAlerts();
        
		$template_data["extra_contents"] = $this->view->renderPartial('_rightside');
        
        if(!$this->model->exists('esig_default_page_hide')){
		    $template_data["esig_default_page_hide"]= 1 ;
		}else {// adding admin settings option 
          $template_data["esig_default_page_hide"] = $this->model->get_generic('esig_default_page_hide');
        }
		$new_common=new WP_E_Common();
        
		$template_data["esig_administrator"]=$new_common->esig_save_administrator();
		
		add_thickbox();
		$template_data = apply_filters('esig-settings-tab-data', $template_data);
		$this->fetchView("index", $template_data);
		
	}
	
	/**
	 * Update Ajax
	 *
	 * Ajax function to update admin signature
	 */
	public function update_ajax(){
		
		//Check nonce
		if(wp_verify_nonce($_POST['nonce'], 'save-sig' ) != 1){
			error_log(__FILE__ . " update_ajax: Bad nonce. Was " . $_POST['nonce']);
			return false;
		}
		
		if(!isset($_POST['sig'])){ return false; }
		
		$userID = $this->user->getCurrentUserID();
		
		// Save signature
		$signature = new WP_E_Signature();
		$signature->add($_POST['sig'], $userID);
		if(!$signature->userHasSignature($userID)){
			$this->settings->has_signature = true;
		}
		return true;
	}
	
	/**
	 * This is settings update method . 
	 *
	 * @return void 
	 *
	 */	
	public function update(){
		$errors = array();
		
		$required_vars = array('first_name', 'last_name', 'user_email', 'output');

		foreach($_POST as $field => $value){
			//$settings->$field = $value;
			 
			if(in_array($field, $required_vars) && ( $value == "" || $value == " " )){
				$field = $field == "output" ? "signature" : $field;
				$errors[] = ucfirst(str_replace("_", " ", $field)) . " cannot be empty"; 
			}
		}

		if(count($errors) < 1){
			if(!is_email($_POST['user_email'])){
				$errors['user_email'] = "Invalid email";
			}
		}

		if(count($errors) > 0){
			foreach($errors as $error){
				$error_msg = $error . "<br />\n";
			}
			$this->view->setAlert(array('type'=>'error', 'title'=>'Document Error', 'message'=>$error_msg));
			return false;
		}

		// store page in settings
		$pageID = $this->model->get('default_display_page');
		$page_data = get_page($pageID);
		$post_content = str_replace('[wp_e_signature]', '',$page_data->post_content);
		wp_update_post(array(
			'ID' =>$pageID,
			'post_content' => $post_content
		));
		
         $admin_user_id=$this->model->get_generic('esig_superadmin_user');
		   $wp_user_id = get_current_user_id();
		if($wp_user_id == $admin_user_id || $admin_user_id==null){
		    $this->model->update_generic("default_display_page",$_POST['default_display_page']);
		    // store force ssl information . 
		    if(!empty($_POST['force_ssl_enabled'])) {$force_ssl=$_POST['force_ssl_enabled'];}else{$force_ssl="";}
		    $this->model->set("force_ssl_enabled", $force_ssl);
            // recieving hide e-signature settings 
		    $hide_esig=isset($_POST['hide_esign'])?$_POST['hide_esign']:null;
		    $this->model->set('esig_unlimited_hide_settings',$hide_esig);
            // hide esig page 
             $hide_esig_page=isset($_POST['esig_hide_page'])?$_POST['esig_hide_page']:null;
            $this->model->set('esig_default_page_hide',$hide_esig_page);
        }
		// store company logo
		if(!empty($_POST['user_title'])) { $company=sanitize_text_field($_POST['user_title']);} else {$company="";}
		
		$this->model->set("company_logo",$company);
		
        
        
       
		// add or update user
		$userID = $this->user->getCurrentUserID();
		$WPuserID = $this->user->getCurrentWPUserID();

		// prepare user array for insert
		$userdata =  array(
			"first_name" => stripslashes($_POST['first_name']),
			"last_name"  =>stripslashes( $_POST['last_name']),
			"wp_user_id" => $WPuserID,
			"user_email" => $_POST['user_email'],
			"user_title" =>stripslashes( $_POST['user_title'])
 		);

		// if user id is empty, this is a new WP_E_SIGN User
		if( empty($userID) ){
			
			$userID = $this->user->insert($userdata);

			// We can set the initialized value to true
			$this->model->update_generic("initialized", "true");
			// If this is the first user, set as the super admin user
			$this->view->setAlert(array('type'=>'alert e-sign-alert esig-updated', 'title'=>'', 'message'=>__('<strong>Woot Woot!</strong> : Welcome aboard.  You are all set to upload documents and request signatures using WordPress. - <a href=admin.php?page=esign-view-document>Add new document</a>', 'esig')));
		}
		// Else this user needs to be udpated
		else{
			$userdata['user_id'] = $userID;
			$affected = $this->user->update($userdata);
			$this->model->update_generic("initialized", "true");
			$this->view->setAlert(array('type'=>'e-sign-alert esig-updated', 'title'=>'', 'message'=>__( '<strong>Well done sir</strong> :  Your E-Signature settings have been updated!', 'esig')));
		}

		foreach($userdata as $field => $value){
			$this->settings->$field = $value;
		}
				
		$this->settings->default_display_page = $_POST['default_display_page'];

		wp_update_post(array(
			'ID' => $this->settings->default_display_page,
			'post_content' => '[wp_e_signature]'
		));

		
		$signature = new WP_E_Signature();
		$signature->add($_POST['output'], $userID);
		if(!$signature->userHasSignature($userID)){
			$this->settings->has_signature = true;
		}

		$this->settings->output = $signature->getUserSignature($userID);
		
        if(!$pageID)
        {
                wp_redirect("admin.php?page=esign-licenses-general");
                exit;
        }

	}
    
    
}
