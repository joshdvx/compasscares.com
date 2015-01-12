<?php
/*
 * generalsController
 * @since 1.0.1
 * @author Michael Medaglia
 * For use with static pages
 */

class WP_E_generalsController extends WP_E_appController {

	public function __construct(){
		parent::__construct();
	
	      $this->model = new WP_E_General();
		  
		  $this->settings = new WP_E_Setting();
		  
		  $this->user= new WP_E_User();
		  $this->queueScripts();
		//add_filter('esig-document-index-data', array($this, 'check_license_validity'),99);
		//include ESIGN_PLUGIN_PATH . DS . "models" . DS . "Recipient.php";
		//$this->model = new Recipient();
	}
    
     private function queueScripts(){
		//wp_enqueue_style('tabs', ESIGN_ASSETS_DIR_URI . DS . "css/jquery.tabs.css");
		wp_enqueue_script('jquery');
		wp_enqueue_script('addons-js', ESIGN_ASSETS_DIR_URI . DS . "/js/addons.js");
		
	}
	
	public function calling_class(){
		return get_class();
	}

	public function index(){
	}

	public function licenses(){
	
	          if($_SERVER['REQUEST_METHOD'] == "POST") {
              
					 $msg='' ; 
                     $addon_msg='' ; 
					  $esig_license_msg = get_option( 'esig_license_msg' );
                      
					  if($esig_license_msg=='valid')
                      {
							$msg=__('<strong>Well done Sir</strong> : Your e-signature settings have been updated.', 'esig'); 
                            
                            $esig_license_type =$this->settings->get_generic('esig_wp_esignature_license_type') ;
                            
                            if($esig_license_type =="Business License")
                            {
                               
                             $addon_msg='<div class="esig-add-on-block esig-pro-pack open">
					                    <h3>Save Time...Install everything with one click</h3>
					                    <p style="display:block;">Since you have access to the '. $esig_license_type .' Pack you can save time by installing 
                                        all add-ons at once . 
                                        Please Note: The installation process can take few minutes to complete.</p>
					                    <a class="esig-btn-pro" id="esig-install-alladdons" href="?page=esign-addons&esig_action=installall">Install all Add-ons Now</a><a href="#" class="esig-dismiss">No thanks</a>
				                    </div>'; 
                             }
                      }
					  else if($esig_license_msg=='invalid')
                      {
							$msg=__( 'The license key you entered is invalid.  Please try again....', 'esig') ; 
                      }
					  else if($esig_license_msg=='deactivated')
                      {
							$msg=__( 'Your license key has been deactivated', 'esig') ;
                      }
					
                    $this->view->setAlert(array('type'=>'', 'title'=>'', 'message'=>$addon_msg));		  
			        $this->view->setAlert(array('type'=>'alert e-sign-alert esig-updated', 'title'=>'', 'message'=>$msg));		
					delete_option('esig_license_msg' );
					
					}
			
		  $template_data=  array(
		    "post_action" =>'admin.php?page=esign-licenses-general',
			"Licenses" => $this->model->checking_extension(),
			"licenses_tab_class"  =>"nav-tab-active",
			"License_form" => $this->model->making_license_form() 
 		);
		
		 $template_data["message"] = $this->view->renderAlerts();
		// apply filter for license page template data 
		$template_data = apply_filters('esig-license-tab-data', $template_data);
		$this->fetchView("licenses", $template_data);
		
	}

	public function support(){
	
	      $template_data=array(
					
					"support_tab_class"=>'nav-tab-active',
					"Licenses"=>$this->model->checking_extension() ,
					
					); 
		// apply filter for support page template data 
		$template_data = apply_filters('esig-support-tab-data', $template_data);
		$this->fetchView("support",$template_data);
	}

	public function misc()
	{			
		 if(isset($_POST['misc-submit']))
			 {
			   $this->model->misc_settings();
			$this->view->setAlert(array('type'=>'alert e-sign-alert esig-updated', 'title'=>'', 'message'=>__( '<strong>Well done Sir</strong> : Your e-signature settings have been updated.', 'esig')));
			  do_action('esig_misc_settings_save'); 
			}
			 
			if($this->settings->get("esign_remove_all_data")){ $check_remove="checked"; }
			else {$check_remove="";}
			
			$print_option_one= $this->settings->get("esig_print_option")==1 ? "selected" : '' ;
			$print_option_two= $this->settings->get("esig_print_option")==2 ? "selected" : '' ;
			$print_option_three=$this->settings->get("esig_print_option")==3 ? "selected" : '' ; 
			$print_option_four= $this->settings->get("esig_print_option")==4 ? "selected" : '' ;
			
			if($print_option_one==null && $print_option_two==null && $print_option_three==null && $print_option_four==null)
										$print_option_three="selected";
			$misc_more_actions = apply_filters('esig_misc_more_document_actions','');
             
              $class=(isset($_GET['page']) && $_GET['page']=='esign-misc-general')?'misc_current':'';
			$template_data=array(
					"post_action" =>'admin.php?page=esign-misc-general',
					"misc_tab_class"=>'nav-tab-active',
                    "customizztion_more_links"=> $misc_more_actions,
					"Licenses"=>$this->model->checking_extension(),
					"esign_remove_data"=>$check_remove,
                    "link_active"=>$class,
					"selected1"=>$print_option_one,
					"selected2"=>$print_option_two,
					"selected3"=>$print_option_three,
					"selected4"=>$print_option_four,
					); 
		
		$template_filter = apply_filters('esig-misc-form-data',$template_data,array());
		$template_data = array_merge($template_data,$template_filter);
        
        // Hook to add more row actions
	   
		
		$esig_misc_more_content = apply_filters('esig_admin_more_misc_contents','');
		
		do_action('esig_misc_content_loaded');
        
		$template_data["misc_extra_content"] = $esig_misc_more_content;
		$template_data["message"] = $this->view->renderAlerts();
		
		$this->fetchView("misc", $template_data);
	}

	
	public function about(){
	
	  $template_data=array(
					"user_email"=>$this->user->getUserEmail(),
					"user_first_name"=>$this->user->getUserFullName() ,
					"user_last_name"=>$this->user->getUserLastName() ,
					"Licenses"=>$this->model->checking_extension() ,
					
					); 
	     
		$this->fetchView("about",$template_data);
	}
	
	public function terms(){
		$this->fetchView("terms");
	}
	
	public function privacy(){
		$this->fetchView("privacy-policy");
	} 
	 
	
}

