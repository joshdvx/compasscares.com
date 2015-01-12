<?php

function cerchez_core_admin_head_esig() {
	global $pagenow;

	//if( 'post.php' != $pagenow && 'post-new.php' != $pagenow ) return;

	$output = "<script type='text/javascript'>";
	$output .= 'var CERCHEZ_CORE_PLUGIN_PATH_URL="'. CERCHEZ_PLUGIN_URI .'";';

	$button_menu = array(
		'basic' => array(
				'title' => __('Basic', 'esig'),
				'type' => 'parent',
				'childs' => array (
					'alert' => array(
						'title' => __('Alert', 'esig'),
						'type' => 'addPopup',
						),
					'box' => array(
						'title' => __('Box', 'esig'),
						'type' => 'addInsert',
						'before' => '[box]',
						'after' => '[/box]',
						),
					'button' => array(
						'title' => __('Button', 'esig'),
						'type' => 'addPopup',
						),
					'call_to_action' => array(
						'title' => __('Call-to-action', 'esig'),
						'type' => 'addPopup',
						),
					'feature' => array(
						'title' => __('Feature', 'esig'),
						'type' => 'addPopup',
						),
					'highlight' => array(
						'title' => __('Highlight', 'esig'),
						'type' => 'addInsert',
						'before' => '[highlight]',
						'after' => '[/highlight]',
						),
					'social' => array(
						'title' => __('Social Link', 'esig'),
						'type' => 'addPopup',
						),
					),
				),
			'interactive' => array(
				'title' => __('Interactive', 'esig'),
				'type' => 'parent',
				'childs' => array (
					'accordion' => array(
						'title' => __('Accordions', 'esig'),
						'type' => 'addPopup',
						),
					'accordion_item' => array(
						'title' => __('Accordion Item', 'esig'),
						'type' => 'addPopup',
						),
					'projects' => array(
						'title' => __('Latest Projects', 'esig'),
						'type' => 'addPopup',
						),
					'news' => array(
						'title' => __('Latest News', 'esig'),
						'type' => 'addPopup',
						),
					'pricing' => array(
						'title' => __('Pricing Table', 'esig'),
						'type' => 'addPopup',
						),
					'slider' => array(
						'title' => __('Slideshow', 'esig'),
						'type' => 'addPopup',
						),
					'slider_item' => array(
						'title' => __('Slideshow Item', 'esig'),
						'type' => 'addPopup',
						),
					'tabs' => array(
						'title' => __('Tabs', 'esig'),
						'type' => 'addPopup',
						),
					'tab' => array(
						'title' => __('Tab Item', 'esig'),
						'type' => 'addPopup',
						),
					),
				),
			'containers' => array(
				'title' => __('Containers', 'esig'),
				'type' => 'parent',
				'childs' => array (
					'audio' => array(
						'title' => __('Audio Player', 'esig'),
						'type' => 'addPopup',
						),
					'lightbox' => array(
						'title' => __('Lightbox', 'esig'),
						'type' => 'addPopup',
						),
					'map' => array(
						'title' => __('Map Container', 'esig'),
						'type' => 'addPopup',
						),
					'video' => array(
						'title' => __('Video Container', 'esig'),
						'type' => 'addPopup',
						),
					),
				),
			'layout' => array(
				'title' => __('Layout', 'esig'),
				'type' => 'parent',
				'childs' => array (
					'grid' => array(
						'title' => __('Grid Column', 'esig'),
						'type' => 'addPopup',
						),
					'divider' => array(
						'title' => __('Divider', 'esig'),
						'type' => 'addInsert',
						'after' => '[divider]',
						),
					'clear' => array(
						'title' => __('Float clearing', 'esig'),
						'type' => 'addInsert',
						'after' => '[clear]',
						),
					),
				),

			);
	$button_menu = apply_filters('cerchez_core_shortcodes_button_menu', $button_menu);
	$output .= 'var CERCHEZ_CORE_PLUGIN_BUTTON_MENU = ' . json_encode($button_menu) . ';';
	$output .= '</script>';
	echo $output;
}
if(function_exists('cerchez_core_admin_head')) 
{
	remove_action('admin_head', 'cerchez_core_admin_head');
	add_action('admin_head', 'cerchez_core_admin_head_esig');
}



/** 
*  add recipients from edit documents 
*
* Since 1.0.4 
*/

add_action('wp_ajax_addRecipient', 'esig_addRecipient');
add_action('wp_ajax_nopriv_addRecipient', 'esig_addRecipient');
/**
	* Signer edit popup window ajax 
	*
	* Since 1.0.4 
	*/
function esig_addRecipient(){
	
	$documentcontroller=new WP_E_DocumentsController(); 
	
	
	$docmodel = new WP_E_Document();
	$docuser = new WP_E_User();
	$docinvite = new WP_E_Invite();
	
	$doc = $docmodel->getDocument(isset($_POST['document_id']));
	
	// grab the owner of this invitation
	//$owner = $docuser->getUserByID($doc->user_id);
	//$send=$_POST['document_action'] == "send" ? 1 : 0;	
	$recipients = array();
	$invitations = array();
	$content ='';
	
	$document_id = isset($_POST['document_id'])?$_POST['document_id']: $docmodel->document_max()+1 ; 
	if($docinvite->getInvitationExists($document_id) > 0)
	{
		$docinvite->deleteDocumentInvitations($document_id) ; 
	}
	
	for($i=0; $i < count($_POST['recipient_emails']); $i++){
		
		
		if(!$_POST['recipient_emails'][$i]) continue; // Skip blank emails
		
		
		$user_id = $docuser->getUserID($_POST['recipient_emails'][$i]);

		if(!empty($_POST['recipient_fnames'])) {$fname=$_POST['recipient_fnames']; } else {$fname="";}
		if(!empty($_POST['recipient_lnames'])) {$lname=$_POST['recipient_lnames'] ;} else {$lname="";}
		
		
		$recipient = array(
			"user_email" => $_POST['recipient_emails'][$i],
			"first_name" => $fname[$i],
			"wp_user_id"=>  '0',
			"user_title"=> '',
			"document_id" => $document_id,
			"last_name" => $lname ? $lname[$i] : ''
			);
		
		
		$recipient['id'] = $docuser->insert($recipient);
		
		$invitationsController = new WP_E_invitationsController;
		
		
		$recipients[] = $recipient;
		
		$invitation = array(
			"recipient_id" => $recipient['id'],
			"recipient_email" => $recipient['user_email'],
			"recipient_name" => $recipient['first_name'],
			"document_id" =>$document_id,
			"document_title" => '',
			"sender_name" => '',
			"sender_email" =>'',
			"sender_id" => $_SERVER['REMOTE_ADDR'],
			"document_checksum" => ''
			);
		$invitations[] = $invitation;
		$invitationsController->save($invitation);
		
		$content .= '<p>
					<input type="text" name="recipient_fnames_ajax[]" placeholder="Signers Name" value="'.$fname[$i].'" readonly />
					<input type="text" name="recipient_emails_ajax[]" placeholder="Signers Email" value="'.$_POST['recipient_emails'][$i].'" readonly />';
		$content .= '</p>'; 
	} 
	if(!empty($content))
	echo $content ; 

	die();
} 



/** 
* removing all theme style 
* Since 1.0.7 
*/
function esig_remove_styles() {
	global $wp_styles;
	$current_page = get_queried_object_id();
	global $wpdb;
	
	$table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';
	$default_page=array();
	if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
		$default_page= $wpdb->get_col("SELECT page_id FROM {$table}");
	}
	$setting = new WP_E_Setting();
	$default_normal_page=$setting->get_generic('default_display_page');
	
	$esig_handle= array(
		'jquery-validate',
		'signdoc',
		'signaturepad',
		'page-loader',
		'thickbox',
		'esig-tooltip-jquery',
		'bootstrap',
		'bootstrap-theme',
		);
	// If we're on a stand alone page
	
	if( is_page($current_page) && in_array($current_page,$default_page)){
		foreach( $wp_styles->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_deregister_style($handle);
						wp_dequeue_style($handle);
					}
				}
			}	   
			endforeach;
	}
	else if( is_page($current_page) && $current_page == $default_normal_page){
		foreach( $wp_styles->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_deregister_style($handle);
						wp_dequeue_style($handle);
					}
				}
			}	   
			endforeach;
	}
}
add_action('wp_print_styles', 'esig_remove_styles',100 );
/** 
	* removing all theme scripts
	* Since 1.0.11 
	*/ 
function esig_remove_scripts() {
	global $wp_scripts;
	$current_page = get_queried_object_id();
	global $wpdb;
	
	$table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';
	$default_page=array();
	if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
		$default_page= $wpdb->get_col("SELECT page_id FROM {$table}");
	}
	$setting = new WP_E_Setting();
	
	$default_normal_page=$setting->get_generic('default_display_page');
	
	$esig_handle= array(
		'jquery-validate',
		'signdoc',
		'jquery',
		'thickbox',
		'signaturepad',
		'page-loader',
		'esig-tooltip-jquery',
		'bootstrap',
		'bootstrap-theme',
		);
	// If we're on a stand alone page
	
	if( is_page($current_page) && in_array($current_page,$default_page)){
		foreach( $wp_scripts->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_dequeue_script($handle);
						
					}
				}
			}		
			endforeach;
	}
	else if( is_page($current_page) && $current_page == $default_normal_page){
		foreach( $wp_scripts->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_dequeue_script($handle);
					}
				}
			}
			endforeach;
	}
	
}
add_action('wp_print_scripts', 'esig_remove_scripts',100 );

function remove_template(){
	if(has_filter('template_include'))
	remove_all_filters( 'template_include',9999);	// we want this to run after everything else that filters template_include() 
}

/***
 * adding ajax scripts for saving admin role details 
 * Since 1.0.13 
 * */

add_action('wp_ajax_esig_admin_saving','esig_admin_saving_ajax');
add_action('wp_ajax_nopriv_esig_admin_saving','esig_admin_saving_ajax');
function esig_admin_saving_ajax(){
	
	$admin_user_id = $_POST['admin_user_id'];
	//getting settings class 
	$settings=new WP_E_Setting();
	$settings->set('esig_superadmin_user' , $admin_user_id );
	// getting admin environment .php
	$wp_config_write=new WP_E_Adminenvironment();
	$wp_config_write->esign_config_remove_directive();
	$wp_config_write->esign_config_save_directive();
	die();
}

/***
 * adding ajax scripts for getting terms and conditions
 * Since 1.0.13 
 * */

add_action('wp_ajax_esig_terms_condition','esig_terms_condition_ajax');
add_action('wp_ajax_nopriv_esig_terms_condition','esig_terms_condition_ajax');
function esig_terms_condition_ajax(){
	
	 $common = new WP_E_Common();
        
     $terms=$common->esig_get_terms_conditions();
     $content_terms = apply_filters('the_content', $terms);
     echo $content_terms;
	die();
}

/***
 * ajax for latest version compare and display out date msg . 
 * Since 1.1.3
 * */

add_action('wp_ajax_esig_out_date_msg','esig_out_date_msg_ajax');
add_action('wp_ajax_nopriv_esig_out_date_msg','esig_out_date_msg_ajax');
function esig_out_date_msg_ajax(){
	
	 $common = new WP_E_Common();
     $user = new WP_E_User();
     $admin_user = $user->getUserByWPID(get_current_user_id());   
     $new_version=$common->esig_latest_version();
     
     $old_version = esig_plugin_name_get_version();
     if($new_version){
            
     if( version_compare($old_version, $new_version, '<' ) ){
            echo '<p id="report-bug-radio-button">  '.  $admin_user->first_name .' it looks WP e-Signature is out of date.  Since bugs are often fixed in our newer releases please update your plugin(s) before submitting a bug request</p></div>';
     }else {
        echo 'updateok';
     } 
     
     } else  {
         echo '<p id="report-bug-radio-button">  '.  $admin_user->first_name .', it looks You do not have valid E-signature license. <ol><li>To retreive your license follow these <a href="/wp-admin/admin.php?page=esign-licenses-general">three simple steps</a>.</li><br><li>To renew your license visit <a href="http://www.approveme.me/profile" target="blank">www.approveme.me</a></li><ol></p></div>';
     }
	die();
}


// this filter has been used to remove esig 
// default page form main navigation menu 

 function ep_exclude_esig_default_page($pages,$r) {
 
	     $setting = new WP_E_Setting();
	   
         $hide_default_page =$setting->get('esig_default_page_hide'); 
         
        if($hide_default_page == 1 ){
         
            $default_display_page =$setting->get('default_display_page'); 
		    //for ($i = 0; $i < sizeof($pages); $i++) {
			    $i = 0;
			   foreach($pages as $page) {
                    
                    if($default_display_page == $page->ID){
				    unset( $pages[$i] );
                    }
                    
                    $i++;
		    }
 
	  }
 
	return $pages;
 
}

if ( ! is_admin() ) {
add_filter("get_pages", "ep_exclude_esig_default_page", 100,2);
}

// post type
add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'esign',
    array(
      'labels' => array(
        'name' => __( 'E-signature'),
        'singular_name' => __( 'E-signature')
      ),
      'public' => true,
      'show_ui'            => false,
      'show_in_menu'=>'edit.php?post_type=esign',
      'rewrite' => array('slug' => 'esign'),
    )
  );
}

// apply bull action start here 
function esig_apply_bulk_action(){

        $screen = get_current_screen();
		$current_screen = $screen->id;
        
        $admin_screens = array(
			
			'toplevel_page_esign-docs',
          
		);
    
        // bulk action submit .
        if (in_array($screen->id, $admin_screens)) {
        if(isset($_POST['esigndocsubmit']) && $_POST['esigndocsubmit']=='Apply'){
                     
                    $apidoc = new WP_E_Document();
                    
                if(isset($_POST['esig_bulk_option'])){
                
                        // trash start here 
                
                        if($_POST['esig_bulk_option'] == 'trash'){
                        
                                for($i=0; $i < count($_POST['esig_document_checked']); $i++){
                                        $document_id =$_POST['esig_document_checked'][$i] ;
                                        
                                       $apidoc->trash($document_id);
                                }
                        }
                        
                        // permanenet delete start here 
                        if($_POST['esig_bulk_option'] == 'del_permanent'){
                        
                                for($i=0; $i < count($_POST['esig_document_checked']); $i++){
                                        $document_id =$_POST['esig_document_checked'][$i] ;
                                        
                                        $apidoc->delete($document_id);
                                }
                        }
                        
                        // restore start here 
                        if($_POST['esig_bulk_option'] == 'restore'){
                        
                                for($i=0; $i < count($_POST['esig_document_checked']); $i++){
                                        $document_id =$_POST['esig_document_checked'][$i] ;
                                        
                                        $apidoc->restore($document_id);
                                }
                        }
                }
        }
    }
   
}

add_action('esig-init','esig_apply_bulk_action');

//Add "esig" Prefix to ALL Alert messages and only display our own messages #258
function remove_admin_header_footer(){
    $admin_screens = array(
			'esign-add-document',
			'esign-settings',
			'esign-edit-document',
			'esign-view-document',
			'esign-misc-general',
			'esign-unlimited-sender-role',
			'esign-docs',
            'esign-systeminfo-about',
            'esign-addons-general',
            'esign-about',
            'esign-licenses-general',
            'esign-support-general',
            'esign-upload-logo-branding',
            'esign-upload-success-page',
            'esign-addons'
		);
        $current_screen =isset($_GET['page'])?$_GET['page']:'';
        if (in_array($current_screen, $admin_screens)) {
                remove_all_actions('admin_footer',10);
                remove_all_actions('admin_header',10);
        }
}
add_action('esig-init','remove_admin_header_footer');
