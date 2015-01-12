<?php
/**
 *
 * @package ESIG_URL_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_ACTIVE_CAMPAIGN_Admin')) :
class ESIG_ACTIVE_CAMPAIGN_Admin {

	/**
	 * Instance of this class.
	 * @since    0.1
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 * @since    0.1
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 * @since     0.1
	 */
	private function __construct() 
	{
		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = ESIG_ACTIVE_CAMPAIGN::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		 
		 //filter 
		add_filter('esig-misc-form-data', array($this, 'active_campaign_settings_option'),10,2);

		//action 
		 add_action('admin_enqueue_scripts', array($this, 'queueScripts'));
		add_action('esig_misc_settings_save', array($this,'misc_settings_save'));
		add_action('esig_document_before_save', array($this, 'esig_active_campaign_add_meta_box'), 10, 1);
		add_action('esig_document_before_edit_save', array($this, 'esig_active_campaign_add_meta_box'), 10, 1);
		add_action('esig_signature_saved', array($this, 'esig_activecampaign_subscribe_email'),10,1);
		//add_action('esig_document_after_save', array($this, 'active_campaign_list_adding'), 10, 1);
	}
	
	
	public function queueScripts(){
	
		wp_enqueue_script('jquery');
		wp_enqueue_script('esig_active_campaign',plugins_url( '/assets/js/active_campaign.js', __FILE__), false, '1.0.1', true );
		wp_enqueue_script('esig_active_campaign1',plugins_url( '/assets/js/active_campaign_other.js', __FILE__), false, '1.0.1', true );
		
		wp_localize_script(
      'esig_active_campaign',
      'active_campaign_script',
      array( 'ajaxurl' => admin_url('admin-ajax.php?action=esigactivecampaign')));
	  
	  if(! function_exists('WP_E_Sig'))
				return ;
				
	  $esig = WP_E_Sig();
	  $api = $esig->shortcode;
	  $document_max_id=$api->document->document_max()+1;
	  wp_localize_script(
      'esig_active_campaign1',
      'esig_active_campaign_ajax_script',
      array( 'ajaxurl' => admin_url('admin-ajax.php?action=esigactivecampaigntagdelete'),
			 'esigdocid'=>$document_max_id));
	 
	}
	
	
public function esig_activecampaign_subscribe_email($args) {
	
	if(! function_exists('WP_E_Sig'))
					return ;
				
	  $esig = WP_E_Sig();
	  $api = $esig->shortcode;
	  
	if( $api->setting->get_generic('esign_active_campaign_api_url') !=null && $api->setting->get_generic('esign_active_campaign_api_key')!=null) {

	
        require_once('inc/includes/ActiveCampaign.class.php');
        
        $ac = new ActiveCampaign($api->setting->get_generic('esign_active_campaign_api_url'), $api->setting->get_generic('esign_active_campaign_api_key'));
		
		$invitation = $args['invitation'];
		$recipient=$args['recipient'];
		$document_id = $invitation->document_id;
		
			$doc_table = $api->document->table_prefix . 'documents';
			$stand_table = $api->document->table_prefix  . 'documents_stand_alone_docs';
			
		    global $wpdb;
			
			$sad_document=$wpdb->get_var("SELECT document_type FROM " . $doc_table . " WHERE document_id=$document_id");
		    $page_id = get_the_ID();
			if($sad_document=="stand_alone") 
					$document_id=$wpdb->get_var("SELECT document_id FROM " . $stand_table . " WHERE page_id=$page_id");
		
		
		if($api->setting->get_generic('esig-active-campaign-list-'.$document_id)!=null){
		$list=$api->setting->get_generic('esig-active-campaign-list-'.$document_id); 
		
		$list_dcode=json_decode($list);
		
		foreach($list_dcode as $key => $list_id){
		$subscriber = array(
                            "email" => $recipient->user_email,
                            "first_name" =>$recipient->first_name,
                            "last_name" =>$recipient->last_name,
                            "p[{$list_id}]" =>$list_id ,
                            "status[{$list_id}]" => 1,
                            );
        
		$subscriber_add = $ac->api("subscriber/add", $subscriber);
		
		$tag=$api->setting->get_generic('esig-active-campaign-tag-'.$document_id);
		
		$param = array(
		'email' => $recipient->user_email,
		'tags[]' => $tag ,
		);
		$contact_tag_add = $ac->api("subscriber/tag_add",$param);
		
		}
		
		}
		
	}

	return false;
}
	
	
	public function active_campaign_settings_option()
				{
				      $settings = new WP_E_Setting();
					  $esign_active_campaign_api_url= $settings->get_generic('esign_active_campaign_api_url') ;
					  $esign_active_campaign_api_key= $settings->get_generic('esign_active_campaign_api_key') ;
				      $html ='
						<div class="esig-settings-wrap">
							<p>
								<label>'.__('ActiveCampaign API URL', 'esig-active').'</label>
								<input name="esign_active_campaign_api_url" id="esign_active_campaign" size="35" type="text" value="'.$esign_active_campaign_api_url.'">
							</p>
							<p>
								<label>'.__('ActiveCampaign API KEY', 'esig-active').'</label>
								<input name="esign_active_campaign_api_key" id="esign_active_campaign" size="35" type="text" value="'.$esign_active_campaign_api_key.'">
							</p>
						</div>
					   ';
				      $template_data['active_campaign_options'] =$html;
					  return $template_data ;
				}
			
			
	 public function misc_settings_save()
	 {
			
			$settings = new WP_E_Setting();
			
			if(isset($_POST['esign_active_campaign_api_url']))
						$settings->set("esign_active_campaign_api_url",$_POST['esign_active_campaign_api_url']);
			if(isset($_POST['esign_active_campaign_api_key']))
						$settings->set("esign_active_campaign_api_key",$_POST['esign_active_campaign_api_key']);
			
	 }
	 
	 
	public  function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
		}
		if (is_array($d)) {
        return array_map(array($this,"objectToArray"), $d);
		} else {
        // Return array
        return $d;
		}
	
	}
	 
	 
	public function esig_active_campaign_add_meta_box ()
{
       if(! function_exists('WP_E_Sig'))
						return ;
						
		   $esig = WP_E_Sig();
			$api = $esig->shortcode;
			$content='';
			$api_url=$api->setting->get_generic('esign_active_campaign_api_url');
			$api_key=$api->setting->get_generic('esign_active_campaign_api_key');
		if(empty($api_url) && empty($api_key)){
			$file_name=plugins_url( 'assets\images/ac_logo.svg', __FILE__);
			$content=sprintf(__('<p align="center"><img src="%s"></p><p>You need to add your ActiveCampaign API credentials to use this feature. You can do this under the <a href="admin.php?page=esign-misc-general">Misc settings</a> tab. <br><br>Send newsletters and automate your email marketing with ActiveCampaign.</p> <p align="center" ><a href="http://www.activecampaign.com/?_r=U7521972" target="_blank" class="esig-red-btn"><span>Get a free account</span></a></p>', 'esig-active'), $file_name);
			}
			else {
		
  require_once("inc/includes/ActiveCampaign.class.php");
  
  $ac = new ActiveCampaign($api->setting->get_generic('esign_active_campaign_api_url'),$api->setting->get_generic('esign_active_campaign_api_key'));
 
 
 $ids="";
 for($nooflist=200;$nooflist>=1;$nooflist--)
 {
   if(empty($ids)) { $ids.=$nooflist ; }
	else {$ids.=",".$nooflist ;}
 }
$params = array(
    'api_output'   => 'json',
    'ids'          => $ids,
    'full'         => 0,
);
$query = "";
foreach( $params as $key => $value ) $query .= $key . '=' . urlencode($value) . '&';
$query = rtrim($query, '& ');
 
$response = $ac->api("list/list?".$query);

 //$nooflist=$api->setting->get_generic('eddactivecampaign_list');
 
	
	$document_id=$_GET['document_id'];
	
	$item_pluginname = 'esig-active-campaign-list-' . $document_id ;
	$tag_name = $api->setting->get_generic('esig-active-campaign-tag-'. $document_id);	

   $result=$this->objectToArray($response);
   $content='';
   
   
			$list_value=$api->setting->get_generic($item_pluginname);
			if($list_value != null){
					$list=json_decode($list_value);
				}
				else {
				 $list=array();
				}
					
  foreach($result as $key=>$value) 
	{
	   $active_id="" ; 
	   
	   if(is_array($value))
			{
			
			    	if(empty($item_pluginname))
					$item_pluginname='' ; 
			$content .='<input type=checkbox name="esig_active[]" value="'. $value['id'] .'"'; 
			 if (in_array($value['id'],$list)) $content .="checked"; 
			$content .= '>' . $value['name'] . '</br>';
			}
	}
	
	//$content .= '<form name="esigactivecampaign" id="esigactivecampaign" action="#" method="POST">';
		
				
			 
			  $content .= '<p><input type="textbox" class="require" name="esig_active_campaign_tag" value=""> 
			   <input type="button" name="Add-submit" id="esigactivecampaign" class="button-appme button" value="Add Tag" /></p>';
		if($tag_name==null) {
			   $content .= '<p class="tagchecklist" id="esig_active_campaign">Active campaign tag name.</p>';
			   }
			   else {
			  $content .= '<input type="hidden" name="esig_active_document_id" value="'.$document_id.'">';
			  $content .= '<p class="tagchecklist" id="esig_active_campaign"><span ><a href="#" id="urlid" class="ntdelbutton">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;</a>&nbsp;' . $tag_name . '</span></p>';
	 }
	}
				$file_name=plugins_url( 'assets\images/help.png', __FILE__);
				$title=' <a href="#" class="tooltip">
    <img src="'.$file_name.'" height="20px" align="left" />
    <span>
        '.__('Select the email newsletter list (or tags) in ActiveCampaign you would like your signer to be automatically assigned after signing this document.', 'esig-active').'
    </span>
</a> ActiveCampaign';
				
			   $api->view->setSidebar($title,$content,'esigactive','esigactiveinside');
			   echo   $api->view->renderSidebar(); 	
}
	
	
	public function active_campaign_list_adding($args)
	   {
	   
	      $esig = WP_E_Sig();
	     $api = $esig->shortcode;
	  
				$document_max_id =$args['document']->document_id;
				
	   
	    $active_campaign_list=array();
		
		if(isset($_POST['active'])){
             
		foreach ($_POST['active'] as $key=>$value) {
             echo $value . "you can do";  
            $active_campaign_list[]=$value ; 
        }
	}
		
	$api->setting->set('esig-active-campaign-list-'.$document_max_id,json_encode($active_campaign_list));
	    
	   }
	
/**
	 * Return an instance of this class.
	 * @since     0.1
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

endif;

// ajax part start here 
add_action('wp_ajax_esigactivecampaign', 'esigactivecampaign');
add_action('wp_ajax_nopriv_esigactivecampaign', 'esigactivecampaign');
function esigactivecampaign(){  
	
	if(! function_exists('WP_E_Sig'))
					return ;
					
					
	  $esig = WP_E_Sig();
	  $api = $esig->shortcode;
      
	   if(!isset($_POST['esig_active_document_id'])){
				$document_max_id = $api->document->document_max()+1;
				}
				else {
				$document_max_id=$_POST['esig_active_document_id'] ; 
				}
                
	   $active_campaign_list=array();
		
		if(isset($_POST['esig_campaign_list'])){
             
		    foreach ($_POST['esig_campaign_list'] as $key=>$value) {
             
                $active_campaign_list[]=$value ; 
            }
	    }
		
	$api->setting->set('esig-active-campaign-list-'.$document_max_id,json_encode($active_campaign_list)); 
	    
	
	 $api->setting->set('esig-active-campaign-tag-'.$document_max_id,$_POST['esig_active_campaign_tag']);
	 
    echo '<span ><a href="#" id="urlid" class="ntdelbutton">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;</a>&nbsp;' . $api->setting->get_generic('esig-active-campaign-tag-'.$document_max_id) . '</span>';
    
 die(); 
 } 
 
 
add_action('wp_ajax_esigactivecampaigntagdelete', 'esigactivecampaigntagdelete');
add_action('wp_ajax_nopriv_esigactivecampaigntagdelete', 'esigactivecampaigntagdelete');

function esigactivecampaigntagdelete(){  
	
	  $esigdocid=$_GET['esigdocid'];
	   if(! function_exists('WP_E_Sig'))
					return ;
					
					
	  $esig = WP_E_Sig();
	  $api = $esig->shortcode;
	  
	  if(!$api->setting->get_generic('esig-active-campaign-tag-'.$esigdocid))
					 {
					  echo "This tag not exists";
					 }
					 else
					 {
					 $api->setting->delete('esig-active-campaign-tag-'.$esigdocid) ;					 
					 }

   echo  'ActiveCampaign tag name.';
    
 die(); 
 }   





