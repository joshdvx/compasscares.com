<?php
/**
 * invitationsController
 * @since 1.0.1
 * @author Micah Blu
 */
	class WP_E_invitationsController extends WP_E_appController {

	public function __construct(){
		parent::__construct();

		include_once ESIGN_PLUGIN_PATH . DS . "models" . DS . "Invite.php";
		$this->model = new WP_E_Invite();
	}

	public function calling_class(){
		return get_class();
	}

	/**
	 * Stores invitation and optionally emails the invite
	 *
	 * @since 0.1.0
	 * @param Array $invitation
	 * @return Boolean 
	 */
	public function saveThenSend($invitation, $document){

		// Save first, catch invitation id
		$invitation_id = $this->save($invitation);

		// Prepare invitation message
		$invite_template = file_get_contents(ESIGN_PLUGIN_PATH . DS . 'views' . DS . 'invitations' . DS . 'invite.php');

		$pageID = $this->setting->get_generic('default_display_page');

		$invite_hash = $this->model->getInviteHash($invitation_id);

		$invitationURL = add_query_arg(array('invite'=>$invite_hash, 'csum'=>$invitation['document_checksum']), get_permalink($pageID));
        // adding required filter 
        
        $esig_logo = apply_filters('esig_invitation_logo_filter','');
        if(empty($esig_logo)){
             $esig_logo = sprintf( __( '<a href="http://www.approveme.me/?ref=1" target="_blank"><img src="s%/images/logo.png" title="Wp E-signature"></a> ', 'esig'), ESIGN_ASSETS_DIR_URI )  ; 
        }
        
        $esig_header_tagline = apply_filters('esig_invitation_header_tagline_filter','');
        if(empty($esig_header_tagline)){
             $esig_header_tagline = __( 'Sign Legally Binding Documents using a WordPress website', 'esig')  ; 
        }
        
        $esig_footer_head = apply_filters('esig_invitation_footer_head_filter','');
        if(empty($esig_footer_head)){
             $esig_footer_head = __( 'What is WP E-Signature?', 'esig')  ; 
        }
        
        $esig_footer_text = apply_filters('esig_invitation_footer_text_filter','');
        if(empty($esig_footer_text)){
            $esig_footer_text = __( 'WP E-Signature by Approve Me is the
                                fastest way to sign and send documents
                                using WordPress. Save a tree (and a
                                stamp).  Instead of printing, signing
                                and uploading your contract, the
                                document signing process is completed
                                using your WordPress website. You have
                                full control over your data - it never
                                leaves your server. <br>
                                <b>No monthly fees</b> - <b>Easy to use
                                  WordPress plugin.</b><a style="color:#368bc6;text-decoration:none" href="http://www.approveme.me/wp-digital-e-signature/?ref=1" target="_blank"> Learn more</a> ', 'esig');
        }
        
        $admin_user = $this->user->getUserByWPID(get_current_user_id());
         $sender= $admin_user->first_name . " " .  $admin_user->last_name ;
        
        $sender =apply_filters('esig-sender-name-filter',$sender);
        
		$template_data = array(
            'esig_logo'=> $esig_logo , 
            'esig_header_tagline'=>$esig_header_tagline,
            'esig_footer_head'=>$esig_footer_head,
            'esig_footer_text'=>$esig_footer_text,
			'user_email' => $invitation['sender_email'],
			'user_full_name' => $sender,
			'recipient_name' => trim($invitation['recipient_name']),
			'document_title' => $invitation['document_title'],
			'document_checksum' => $document->document_checksum,
			'invite_url' => $invitationURL,
			'assets_dir' => ESIGN_ASSETS_DIR_URI,
		);
		
		

		$invite_message = $this->view->renderPartial('invite', $template_data, false, 'invitations');
		
       
        
		$subject = $invitation['document_title'] . " - Signature requested by " .  $sender ;

		$headers = array(
			"From: ". $sender ." <{$admin_user->user_email}>",
			"Reply-To: {$admin_user->first_name} {$admin_user->last_name} <{$admin_user->user_email}>"
		);
		
		// send Email
		add_filter( 'wp_mail_content_type', array($this, 'mailType') );
		$mailsent = wp_mail($invitation['recipient_email'], $subject, $invite_message, $headers);
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		
		if(!$mailsent) {
		  $headers  = 'MIME-Version: 1.0' . "\r\n";
		  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		  $headers .= 'From: '. $sender . '<'. $admin_user->user_email  .'>' . "\r\n";  
		 $mailsent = wp_mail($invitation['recipient_email'], $subject, $invite_message, $headers); 
		}
		// Record event: Document sent
		$this->model->recordSent($invitation_id);

		return $mailsent;
	}

	public function save($invitation){

		// Add hash to inivitation array, then insert record
		$hash = array_merge(range(0,100), range('a','z'));
		shuffle($hash);
		$hash = sha1(implode($hash));

		$invitation['hash'] = $hash;

		return $this->model->insert($invitation);

	}

	public function mailType($content_type){
		return 'text/html';
	}
}
