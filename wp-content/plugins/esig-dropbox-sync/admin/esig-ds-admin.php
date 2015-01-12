<?php
/**
 *
 * @package ESIG_DVN_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (!class_exists('ESIG_DS_Admin')) :
class ESIG_DS_Admin {

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
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = ESIG_DS::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		//filter adding . 
		add_filter('esig_admin_more_misc_contents', array($this, 'misc_extra_contents'), 10, 1);
		add_filter('esig_admin_advanced_document_contents', array($this, 'add_document_more_contents'), 10, 1);
		add_filter('esig-misc-form-data', array($this, 'dropbox_misc_settings'),10,1);
		// action start here 
		add_action('esig_misc_content_loaded',array($this,'misc_content_loaded'));
		add_action('esig_misc_settings_save',array($this,'misc_setting_save'));
		add_action('esig_document_after_save', array($this, 'document_after_save'), 10, 1);
		add_action('esig_signature_loaded', array($this, 'dropbox_pdf_document'),10,1);
		//esig_signature_loaded
	}
	
	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/esig-dropbox.js', __FILE__ ), array( 'jquery' ),'',false);
	}
	/*
	* dropbox naming option if pdf is not installed
	* Since 1.0.0
	*/
	public function dropbox_misc_settings($template_data)
	 {
     
	 if ( is_plugin_active('esig-save-as-pdf/esig-pdf.php')){
			 return $template_data ;
      }
			$settings = new WP_E_Setting();
			
			$esig_pdf_option=json_decode($settings->get_generic('esign_misc_pdf_name')) ;
			
			if(empty($esig_pdf_option)) 
						$esig_pdf_option=array();
			
			$html ='<label>How would you like to name your Dropbox documents?</label><select data-placeholder="Choose your naming format(s)" name="pdfname[]" style="margin-left:17px;width:350px;" multiple class="chosen-select-no-results" tabindex="11">
            <option value=""></option>
            <option value="document_name"'; if(in_array("document_name",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Document Name', 'esig-ds').'</option>
            <option value="unique_document_id" '; if(in_array("unique_document_id",$esig_pdf_option)) $html .= "selected";  $html .= '>'.__('Unique Document ID', 'esig-ds').'</option>
            <option value="esig_document_id" '; if(in_array("esig_document_id",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Esig Document ID', 'esig-ds').'</option>
            <option value="current_date"'; if(in_array("current_date",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Current Date', 'esig-ds').'</option>
			<option value="document_create_date"'; if(in_array("document_create_date",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Document Create Date', 'esig-ds').'</option>
          </select><span class="description"><br />e.g. "My-NDA-Document_10-12-2014.pdf"</span>' ; 
			
			//$template_data1 =array("other_form_element" => $html);
			$template_data['other_form_element']=$html;
			//$template_data = array_merge($template_data,$template_data1);
		    return $template_data ; 
			
	 }  
	/*
	* misc settings save start here 
	* Since 1.0.0
	*/
	public function misc_setting_save(){
	    
		$dropbox_defalut=isset($_POST['esig_dropbox_default'])? $_POST['esig_dropbox_default']: '' ; 
		 if(!function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		if(isset($_POST['esig_dropbox_default']))
				$api->setting->set('esig_dropbox_default',$dropbox_defalut);
		
		if (!is_plugin_active('esig-save-as-pdf/esig-pdf.php')){
			 
		$misc_data =array() ;
			if(isset($_POST['pdfname'])) {
			foreach ($_POST['pdfname'] as $key => $value)
			{
				 $misc_data[$key]=$value; 
			}
			}
			$misc_ready = json_encode($misc_data);
			$settings = new WP_E_Setting();
			$settings->set("esign_misc_pdf_name",$misc_ready);
			
			if(isset($_POST['esig_pdf_option']))
						$settings->set("esig_pdf_option",$_POST['esig_pdf_option']);
		}
	}
	
	/*
	*  pdf file naming 
	*  since 1.0.0
	*/
	public function pdf_file_name($document_id)
	{
			$settings = new WP_E_Setting();
			$this->document = new WP_E_Document;
			
			$document = $this->document->getDocumentById($document_id);
			
			$esig_pdf_option=json_decode($settings->get_generic('esign_misc_pdf_name'));
			
			$file_name='' ; 
			if(isset($esig_pdf_option)) 
			{
			
			foreach($esig_pdf_option as $names)
				{
					 
				    if($names == "document_name")
							$file_name=$file_name . str_replace( ' ', '-', strtolower( $document->document_title ) ); 
					elseif($names == "unique_document_id")
							$file_name=$file_name . "_" . $document->document_checksum ;
					elseif($names == "esig_document_id")
							$file_name=$file_name . "_" . $document->document_id ;
					elseif($names == "current_date")
							$file_name=$file_name . "_" . date("d-m-Y") ;
					elseif($names == "document_create_date")
							$file_name=$file_name . "_" . date("d-M-Y",strtotime($document->date_created)) ;
					
				}
				 
			}
			
			
         if(empty($file_name))	
				$file_name=$file_name . str_replace( ' ', '-', strtolower( $document->document_title ) ); 
		
		return $file_name ; 
	}
	
	
	public function dropbox_pdf_document($args)
	 {	
		$esig_dropbox = ESIGDS_Factory::get('dropbox');
		if(!$esig_dropbox->is_authorized()){
			 return ;
		}
		if(!function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		$this->document = new WP_E_Document;
		$this->signature = new WP_E_Signature;
		$this->invitation = new WP_E_Invite();
		$this->user = new WP_E_User;
			//$invitation = $args['invitation'];
			$doc_id = $args['document_id'];
			
			// if sad page then 
			$doc_table = $api->document->table_prefix . 'documents';
			$stand_table = $api->document->table_prefix  . 'documents_stand_alone_docs';
		    global $wpdb;
			$sad_document=$wpdb->get_var("SELECT document_type FROM " . $doc_table . " WHERE document_id='" . $doc_id ."'");
		    $page_id = get_the_ID(); 
		   if($sad_document=="stand_alone") 
					$doc_id=$wpdb->get_var("SELECT max(document_id) FROM " . $stand_table . " WHERE page_id='" .$page_id . "'");
			
           if(!$api->setting->get_generic('esig_dropbox'.$doc_id))
								return ; 
				
			$doc_id = $args['document_id'];
				$mydir = plugin_dir_path( __FILE__ ) ; 
				if (!is_dir($mydir . "/" . "esig_pdf")) {
						mkdir($mydir . "/" . "esig_pdf", 0777);
					}			
			
			$pdf=new mPDF('c','A4','','',10,10,25,35); 
				
			if(isset($doc_id)) {
				
				 $doc_status = $this->document->getSignatureStatus($doc_id);
				 if(is_array($doc_status['signatures_needed']) && (count($doc_status['signatures_needed']) > 0))
									return ; 
					
				 //if($this->document->getStatus($doc_id)!='signed') 
									//return ;
							
				  $document = $this->document->getDocumentById($doc_id);
				  $document_report = $api->auditReport($doc_id, $document);
				  
						
				$unfiltered_content=do_shortcode($this->signature->decrypt(ENCRYPTION_KEY,$document->document_content));
				
				$content=apply_filters('the_content',$unfiltered_content);
				  
				$dt = new DateTime($document->date_created);
				$date4sort= $dt->format('m/d/Y');
				$blogname=get_bloginfo('name') ; 
				$blog_url=get_bloginfo('url') ; 
				
				$header ="<div class=\"document-sign-page\"><div class='document_id'>Document ID:  {$document->document_checksum}</div>
<div class='document_date'>{$date4sort}</div>
<div class='signed_on'>Signed On :  {$blog_url}</div>" ; 
				
				
				$html = "

<div class='document-sign-page'>
	<p align='left' class='doc_title'>{$document->document_title}</p>
	<br />
	{$content}
</div>

<div class='signatures row'>
	
	" ; 
			$allinvitaions = $this->invitation->getInvitations($doc_id); 
			if(!empty($allinvitaions)) 
			{
				$small_img=plugin_dir_path( __FILE__ ) . "assets/images/sign-here_blank_small.jpg";
				 
				foreach($allinvitaions as $invite)
				{
					
					$fullname =$this->user->getUserFullName($invite->user_id); 
					
					$date=$this->signature->GetSignatureDate($invite->user_id,$doc_id);
					
					$html .='<div class="signature-left" align="left">
					<div class="signature-top" style="width:255px;background:transparent url('.$small_img.') bottom no-repeat;">
					
					<img src="'. plugins_url() .'/esig-dropbox-sync/includes/sigtoimage.php?uid='.$invite->user_id.'&doc_id='. $doc_id .'" width="255px" height="70px">
					
					</div>
					<div class="signature-top">' ;
					 $html .= "Signed By " . $fullname . "<br>" ; 
					 $html .= "Signed On: " . mysql2date('n/j/Y',$date) . "</div></div>"; 
				}
				
				if($document->add_signature){
			
					 $owner = $api->user->getUserBy('wp_user_id', $document->user_id);
                    
					$html .='<div class="signature-left" align="left">
					<div class="signature-top" style="width:255px;background:transparent url('.$small_img.') bottom no-repeat;">
					<img src="'. plugins_url() .'/esig-dropbox-sync/includes/sigtoimage.php?uid='.$owner->user_id.'&doc_id='. $doc_id .'&owner_id=1" width="255px" height="70px">
					</div><div class="signature-top">' ;
					$html .= "Signed By " . $owner->first_name . ' ' . $owner->last_name . "<br>" ; 
					 $html .= "Signed On: " . mysql2date('n/j/Y',
					$document->last_modified) . "</div></div>" ;
				
				}
			}
						$html .= "
						
</div></div> "; 


$nextpage ="
 <div class='audit-wrapper'>

	{$api->auditReport($doc_id,$document)}
	
	

</div>

"; 

$footer = "<div class='pdf-footer'>
		<div class='footer-left'>
		    Proudly signed using </br>
			<img src='". ESIGN_ASSETS_DIR_URI ."/images/logo.svg' width='150px' alt='WP E-Signature'/>
		</div>
		<div class='footer-right'>
			{$blogname} <br> Page {PAGENO} of {nb}
			<br/> Audit Signature ID# {$api->auditReport($doc_id, $document,true)}
		</div>
	</div>";
	
				$pdf->SetHTMLHeader($header);
				
				$pdf->SetHTMLFooter($footer);
				
				
				
				
				$stylesheet = file_get_contents( ESIGN_TEMPLATES_PATH . '/default/print_style.css'); // external css
				$pdf->WriteHTML($stylesheet,1);
				
				
				$pdf->WriteHTML($html);
				
				$pdf->SetHTMLHeader(' ');
				
				$pdf->AddPage();
				
				$pdf->WriteHTML($nextpage);
				$pdf_name=$this->pdf_file_name($doc_id) ; 
				$filename=plugin_dir_path( __FILE__ ) . "esig_pdf/"."$pdf_name";
				$pdf->Output($filename.'.pdf',F);
		    
			$config=ESIGDS_Factory::get('config');
			$esig_dropbox = ESIGDS_Factory::get('dropbox');
		
				if($esig_dropbox->upload_file($path='',$filename.'.pdf')){
						  $mydir = plugin_dir_path( __FILE__ ) ; 
						  $d = $mydir . "esig_pdf/*"; 
						  array_map('unlink', glob($d));
						}
		} // isset doc it check end here 
				
			
	 }
	
	/**
	*  action after saving document . 
	*  Since 1.0.0
	*/
	public function document_after_save($args){
	
	  $doc_id= $args['document']->document_id;
	  
      if(!function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
        if(isset($_POST['esig_dropbox']))
        {
        $api->setting->set('esig_dropbox'.$doc_id,$_POST['esig_dropbox']);
        }
	}
	
	/**
	*  add document more  contents filter . 
	*  Since 1.0.0
	*/
	
  public function add_document_more_contents($advanced_more_options){
    
	if(!function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		global $wpdb;
		
		
        $checked="checked";
        
		if($api->setting->get_generic('esig_dropbox_default'))
						$checked="checked";
		
         $checked=apply_filters('esig-dropbox-settings-checked-filter',$checked);
         
	       $assets_url=ESIGN_ASSETS_DIR_URI ;
		   
		$advanced_more_options .= <<<EOL
			<p id="esig_dropbox_option">
			<a href="#" class="tooltip">
					<img src="$assets_url/images/help.png" height="20px" width="20px" align="left" />
					<span>
					Automatically sync signed documents as PDFs with your Dropbox account.  Once all signatures have been collected a final PDF document will be added to your synced Dropbox account.
					</span>
					</a>
				<input type="checkbox" $checked id="esig_dropbox" name="esig_dropbox" value="1"> Sync PDF to Dropbox once document is singed by everyone .  
			</p>		
EOL;
        
		
    return $advanced_more_options ;
  }
	/**
	*  adding misc contents action when loaded misc . 
	*  Since 1.0.0
	*/
	public function misc_content_loaded(){
	  
	   if(isset($_GET['unlink']))
	{
		$esig_dropbox = ESIGDS_Factory::get('dropbox');
		$esig_dropbox->unlink_account();
		//$esig_dropbox->request_access_token();
		
		wp_redirect('admin.php?page=esign-misc-general');
	}
	 
	}
	
	/**
	*  adding misc extra contents . 
	*  Since 1.0.0
	*/
	public function misc_extra_contents($esig_misc_more_content){
	    
		if(!function_exists('WP_E_Sig'))
				return ;
                
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		$esig_dropbox = ESIGDS_Factory::get('dropbox');
        
		
		if ($esig_dropbox->auth_state() == 'request' || $esig_dropbox->auth_state() == '')
		{   
           if(get_option('esigds_options'))
            {
	        delete_option('esigds_options');
            }	
            $esig_dropbox->init();
	    $esig_misc_more_content .='<div style="padding:0 10px;"><div class="esig-settings-wrap"><p> '.__('Please authorize your Dropbox account', 'esig-ds').'</p>
		<a href="'. $esig_dropbox->get_authorize_url() . '" class="button-primary">'.__('Authorize', 'esig-ds').'</a></div></div>';
	    }
		elseif ($esig_dropbox->is_authorized()) // Dropbox authorized
		{
		$account_info = $esig_dropbox->get_account_info();
		$used = round(($account_info->quota_info->quota - ($account_info->quota_info->normal + $account_info->quota_info->shared)) / 1073741824, 1);
		$quota = round($account_info->quota_info->quota / 1073741824, 1);
		
		$esig_misc_more_content .='
		  <div class="esig-settings-column-wrap"><div class="esig-settings-wrap"><p>
			You have ' . $used .
			'<acronym title="Gigabyte">GB</acronym> of ' . 
			$quota . 'GB (' . round(($used / $quota) * 100, 0) . '%) free in your Dropbox account.			
		
		<a href="'. esigds_get_custom_menu_page() .'&unlink">Unlink</a> 		
		your '. $account_info->display_name .' Dropbox account.
		</p>
		';
		
		if($api->setting->get_generic('esig_dropbox_default')){
						$checked="checked";
           } else {
                     $checked="";
           }
		
	       $assets_url=ESIGN_ASSETS_DIR_URI ;
		   
		$esig_misc_more_content .= '
			<p id="esig_dropbox_option">
			<a href="#" class="tooltip">
					<img src="'. $assets_url .'/images/help.png" height="20px" width="20px" align="left" />
					<span>
					'. __('You can set your default Dropbox PDF Sync settings here but override them on each document.  Everytime a document is signed by ALL parties a PDF is generated and synced in your Dropbox apps folder.', 'esig-ds').'
					</span>
					</a>
				<input type="checkbox" ' . $checked . ' id="esig_dropbox_default" name="esig_dropbox_default" value="1"> '.__('Sync PDF to Dropbox once document is singed by everyone', 'esig-ds').' .  
			</p></div></div>		
';
		}
	   return $esig_misc_more_content ; 
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

