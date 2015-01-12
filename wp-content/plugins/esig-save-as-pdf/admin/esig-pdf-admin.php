<?php
/**
 *
 * @package ESIG_PDF_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (!class_exists('ESIG_PDF_Admin')) :
class ESIG_PDF_Admin {

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
	public function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = ESIG_PDF::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// action list start here 
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_menu',array( $this, 'register_esig_pdf_page') );
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		
		
		add_action('init', array($this,'esig_frontend_pdf_save'));
		
		add_action('esig_misc_settings_save', array($this,'misc_settings_save'));
		
		add_action('esig_document_after_save', array($this, 'document_after_save'), 10, 1);
		// Ajax handlers
		// filter list star here . 
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
		add_filter('esig-document-footer-data', array($this, 'pdf_document_footer'),10,2);
		
		add_filter('esig-misc-form-data', array($this, 'pdf_misc_settings'),10,1);
		
		add_filter('esig-misc-form-data', array($this, 'document_add_pdf_option'),10,1);
		
		add_filter('esig-edit-document-template-data', array($this, 'document_add_pdf_option'), 10, 2);
		
		add_filter('esig_admin_more_document_actions', array($this, 'document_save_as_pdf_action'), 10,2);
		
	}
	
	
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
	
	
	/**
	 * This is method pdf_document creates 
	 *
	 * @return pdf file . 
	 *
	 */	
	
	public function pdf_document($document_id=null)
	 {	
		if(! function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		$this->document = new WP_E_Document;
		$this->signature = new WP_E_Signature;
		$this->invitation = new WP_E_Invite();
		$this->user = new WP_E_User;
				
				$pdf=new mPDF('c','A4','','',10,10,25,35); 
				
				$mydir = plugin_dir_path( __FILE__ ) ; 
				
					if (!is_dir($mydir . "/" . "esig_pdf")) {
							mkdir($mydir . "/" . "esig_pdf", 0777);
						}
						else 
						{
							$d = dir($mydir . "/" . "esig_pdf"); 
							while(false !== ($entry = $d->read())) { 
							if ($entry!= "." && $entry!= "..") { 
								$link=plugin_dir_path( __FILE__ ) . "esig_pdf/".$entry ; 
								$filedate = date("d M Y H:i:s",filemtime("$link")); 									
								if((strtotime("now")-strtotime($filedate))>900)
													unlink($link); 
							} 
						} 
							$d->close(); 
						
						}
						
		if($document_id == null){			
			$document_id=isset($_GET['did'])?$this->document->document_id_by_csum($_GET['did']): $_GET['document_id'] ;	
		}	
			 
		if($document_id) {
			
					
				    //rmdir($mydir); 
					
			$doc_id=$document_id; 
				
				 // if(!$this->document->getSignedresult($doc_id))
								//return ; 
							
					
				// if($this->document->getStatus($doc_id)!='signed') 
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
<div class='document_date'>{$date4sort}</div>" ; 
				
				
				$html = "
<div class='signed_on'>Signed On :  {$blog_url}</div>
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
					<img src="'. plugins_url() .'/esig-save-as-pdf/includes/sigtoimage.php?uid='.$invite->user_id.'&doc_id='. $doc_id .'" width="255px" height="70px">
					 </div><div class="signature-top">';
					 $html .= "Signed By " . $fullname . "<br>" ; 
					 $html .= "Signed On: " . mysql2date('n/j/Y',$date) . "</div></div>"; 
				}
				
				if($document->add_signature){
					
                    $owner = $api->user->getUserBy('wp_user_id', $document->user_id);
					
                    
					$html .='<div class="signature-left" align="left">
					<div class="signature-top" style="width:255px;background:transparent url('.$small_img.') bottom no-repeat;">
					
					<img src="'. plugins_url() .'/esig-save-as-pdf/includes/sigtoimage.php?uid='. $owner->user_id .'&doc_id='. $doc_id .'&owner_id=1" width="255px" height="70px">
					
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
			<img src='". ESIGN_ASSETS_DIR_URI ."/images/legally-signed.svg' alt='WP E-Signature'/>
		</div>
		<div class='footer-right'>
			{$blogname} <br> Page {PAGENO} of {nb}
			<br/><img src='". ESIGN_ASSETS_DIR_URI ."/images/lock.png' width='8' height='12' alt='Audit Lock'/> Audit Signature ID# {$api->auditReport($doc_id, $document,true)}
		</div>
	</div>";
                $stylesheet = file_get_contents( ESIGN_TEMPLATES_PATH . '/default/print_style.css'); // external css
                 $pdf->WriteHTML($stylesheet,1);
                 
                $pdf_front_page = apply_filters('esig_save_as_pdf_front_page','',$doc_id);
                
                if(!empty($pdf_front_page)){
                
                     $pdf_header=apply_filters('esig_save_as_pdf_header','',$doc_id);
                     
                      $pdf_footer=apply_filters('esig_save_as_pdf_footer','',$doc_id);
                    
                    $pdf->SetHTMLHeader($pdf_header);
				   
				    $pdf->SetHTMLFooter($pdf_footer);
                    
                    $pdf->AddPage();
                   
                    $pdf->WriteHTML($pdf_front_page);
                    $pdf->SetHTMLHeader($header);
				
				   // $pdf->SetHTMLFooter($footer);
                    $pdf->AddPage();
                }
       
              
				$pdf->SetHTMLHeader($header);
				
				$pdf->SetHTMLFooter($footer);
				
				
				
               
				
				
				$pdf->WriteHTML($html);
				
				$pdf->SetHTMLHeader(' ');
				
				$pdf->AddPage();
				
				$pdf->WriteHTML($nextpage);
				$pdf_name=$this->pdf_file_name($doc_id) ; 
				// output pdf file 
				return $pdf->Output($pdf_name,S);
				
				  }
				
			
	 }
	 
	public function document_save_as_pdf_action($more_actions, $args)
		{		
				$doc = $args['document'];
				if($doc->document_status=='signed')
						$more_actions .= '| <span class="save_as_pdf_link"><a href="admin.php?page=esigpdf&document_id='. $doc->document_id .'" title="Save as pdf">Save As PDF</a></span>';	
				
				return $more_actions ; 
		}
	
	public function pdf_document_footer($template_data)
	 {
			if(! function_exists('WP_E_Sig'))
							return ;
				
				$esig = WP_E_Sig();
				$api = $esig->shortcode;
				
				$document_id=$_GET['document_id'];
				
			$this->document = new WP_E_Document;
			$settings = new WP_E_Setting();
			$esig_pdf_button=$settings->get_generic('esig_pdf_option'.$document_id) ;
			 
			 if(empty($esig_pdf_button))
					$esig_pdf_button=$settings->get_generic('esig_pdf_option') ;	
			
			
		$csum=$this->document->document_checksum_by_id($document_id);
			
		$pdfurl=get_permalink() . '?esigtodo=esigpdf&did='. $csum .'" title="Save as pdf';
			
			
			if($this->document->getSignedresult($document_id) && $esig_pdf_button==1){
			
			$template_data['pdf_button']="<a href=\"$pdfurl\" class=\"agree-button\" id=\"downloadLink\">Save As PDF</a>";
		
		    return $template_data ; 
			}
			elseif($esig_pdf_button==2)
			{
		    return $template_data ; 
			}
			elseif($esig_pdf_button==3)
			{
			$template_data['pdf_button']="<a href=\"$pdfurl\" class=\"agree-button\" id=\"downloadLink\">Save As PDF</a>";
		    return $template_data ; 
			}
			else {
			return $template_data ; 		
			}
	 }
	 
	 /*
	 * Esig pdf saving option from front end 
	 * Since 1.0.9
	 */
 public function esig_frontend_pdf_save(){
			
		$esigtodo=isset($_GET['esigtodo'])? $_GET['esigtodo'] : null;
			
			if(isset($esigtodo)){
					$this->save_as_pdf_content();
			}else{
			  return ;
			}
	 }
	
	public function pdf_misc_settings($template_data)
	 {
	 
			$settings = new WP_E_Setting();
			
			$esig_pdf_option=json_decode($settings->get_generic('esign_misc_pdf_name')) ;
			
			if(empty($esig_pdf_option)) 
						$esig_pdf_option=array();
			
		$html ='<div class="esig-settings-wrap"><label>'.__('How would you like to name your PDF documents?', 'esig-pdf').'</label><select data-placeholder="'.__('Choose your naming format(s)', 'esig-pdf').'" name="pdfname[]" style="margin:17px;width:350px;" multiple class="esig-select2" tabindex="11">
            <option value=""></option>
            <option value="document_name"'; if(in_array("document_name",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Document Name', 'esig-pdf').'</option>
            <option value="unique_document_id" '; if(in_array("unique_document_id",$esig_pdf_option)) $html .= "selected";  $html .= '>'.__('Unique Document ID', 'esig-pdf').'</option>
            <option value="esig_document_id" '; if(in_array("esig_document_id",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Esig Document ID', 'esig-pdf').'</option>
            <option value="current_date"'; if(in_array("current_date",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Current Date', 'esig-pdf').'</option>
			<option value="document_create_date"'; if(in_array("document_create_date",$esig_pdf_option)) $html .= "selected"; $html .= '>'.__('Document Create Date', 'esig-pdf').'</option>
          </select><span class="description"><br />e.g. "My-NDA-Document_10-12-2014.pdf"</span></div>' ; 
			
			//$template_data1 =array("other_form_element" => $html);
			$template_data['other_form_element']=$html;
			//$template_data = array_merge($template_data,$template_data1);
		    return $template_data ; 
			
	 }  
	 
	 public function document_add_pdf_option($template_data)
	 {
			$settings = new WP_E_Setting();
			
			// defining variable . 
			$esig_pdf_button='';
			$esig_pdf_option1='';
			$esig_pdf_option2='';
			$esig_pdf_option3='';
			
			$document_id=isset($_GET['document_id'])?$_GET['document_id']:null ; 
			$esig_pdf_button=$settings->get_generic('esig_pdf_option'.$document_id) ;
           
			if(empty($esig_pdf_button))
            	       $esig_pdf_button= apply_filters('esig_pdf_button_filter','');
					
			
			 if(empty($esig_pdf_button))
					  $esig_pdf_option1="selected" ;	
			
			if(!empty($esig_pdf_button)) 
			{
					if($esig_pdf_button==1)
							$esig_pdf_option1="selected" ;
					if($esig_pdf_button==2)
							$esig_pdf_option2="selected" ;
					if($esig_pdf_button==3)
							$esig_pdf_option3="selected" ;
			}
			
			$html = sprintf( __( '<p>
			<label>Save as PDF <span class="description">default settings:</span></label>
         
				<select  style="width:500px;" data-placeholder="Choose a Option..." name="esig_pdf_option" class="esig-select2" tabindex="9">
					<option value=""></option>		
					<option value="1" %s>Only display Save as PDF button when document is signed by everyone</option>
					
					<option value="2" %s>Hide Save as PDF button always, no matter what.</option>
								
					<option value="3" %s>Display Save as PDF button always, no matter what.</option>
						  
				 </select>
 
			</p>
			', 'esig-pdf'), $esig_pdf_option1, $esig_pdf_option2, $esig_pdf_option3) ; 
			//$template_data1 =array("pdf_options" => $html);
			$template_data['pdf_options'] = $html;
			
		
		    return $template_data ; 
			
	 }  
	 
	 public function document_after_save($args) {
	 
				$settings = new WP_E_Setting();
				
				$doc_id = $args['document']->document_id;
				
				 if(isset($_POST['esig_pdf_option'])){
				$settings->set("esig_pdf_option".$doc_id , $_POST['esig_pdf_option']);
				}
				
				
	}
	 
	 
	 
	 public function misc_settings_save()
	 {
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

	
	

	/**
	 * Render the settings page for this plugin.
	 * @since    0.1
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}
	
	
	public function enqueue_admin_styles() {

		$screen = get_current_screen();
		$admin_screens = array(
			'admin_page_esign-misc-general'
		);

		if (in_array($screen->id, $admin_screens)) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/chosen.min.css', __FILE__ ), array(), ESIG_PDF::VERSION );
		}

	}
	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     0.1
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();
		 
		$admin_screens = array(
			'admin_page_esign-misc-general'
		);

		// Add/Edit Document scripts
		if(in_array($screen->id, $admin_screens)){
			
			wp_enqueue_script('jquery-ui-dialog');
			
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/chosen.jquery.js', __FILE__ ), array('jquery', 'jquery-ui-dialog'), ESIG_PDF::VERSION,true);
			wp_enqueue_script( $this->plugin_slug . '-admin-script1', plugins_url( 'assets/js/prism.js', __FILE__ ), array('jquery', 'jquery-ui-dialog'), ESIG_PDF::VERSION,true);
			wp_enqueue_script( $this->plugin_slug . '-admin-script2', plugins_url( 'assets/js/main.js', __FILE__ ), array('jquery', 'jquery-ui-dialog'), ESIG_PDF::VERSION,true);
			
		}
	}
	
	/**
	 * Add settings action link to the plugins page.
	 * @since    0.1
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=esign-misc-general' ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}
	
	/**
	* adding pdf menu page . 
	* Since 1.0.1
	**/
	public function register_esig_pdf_page() {
	add_links_page('E-signature save as pdf','', 'read', 'esigpdf',array($this,'save_as_pdf_content'));
	//add_menu_page('E-signature save as pdf','manage_options', 'esigpdf', array($this,'save_as_pdf_content'),'', 6 ); 
	}
	/**
	* pdf page content here 
	*
	* Since 1.0.1
	*/
	public function save_as_pdf_content($document_id=null) {
		
		$this->document = new WP_E_Document;
		if($document_id ==null){
			$document_id =isset($_GET['did'])? $this->document->document_id_by_csum($_GET['did']) : $_GET['document_id'];
		}	
       
       
			$pdf_buffer=$this->pdf_document() ;
           
			$pdf_name=$this->pdf_file_name($document_id).".pdf" ; 
			//$file=plugin_dir_path( __FILE__ ) . "esig_pdf/"."$pdf_name".".pdf";
			
			//if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($pdf_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . strlen($pdf_buffer));
    ob_clean();
    flush();
    echo $pdf_buffer;
    exit;
//} 
	}

}

endif;

