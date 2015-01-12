<?php
/**
 * Document Model Class
 *
 * @since 0.1.0
 */
class WP_E_Document extends WP_E_Model {

	private $table;
	
	public $classname = 'Document';

	private $documentStateLog = 'documents_state_log';

	public function __construct(){
		parent::__construct();

		$this->table = $this->table_prefix . "documents";
		$this->usertable = $this->table_prefix . "users";
		$this->documentsSignaturesTable = $this->table_prefix . "documents_signatures";
		$this->eventsTable = $this->table_prefix . "documents_events";
		$this->invite = new WP_E_Invite;
		$this->signature = new WP_E_Signature;
		$this->user = new WP_E_User;
		$this->settings = new WP_E_Setting();
	}

	/**
	 * Return a Document row Array - TODO - Rewrite or rid
	 *
	 * @since 0.1.0
	 * @param Int ($id) 
	 * @return Object
	 */
	public function getDocument($id){

		$document = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE document_id=%s", $id
			)
		);
		return $document[0];
	}

	//changed to include csum
	public function getDocumentByID($id){
		
		$setting = new WP_E_Setting();
		$pageID =  $setting->get('default_display_page');
		//id this edit or email link?
			$document = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM " . $this->table . " WHERE (document_id=%s AND DATEDIFF(date_created,'2014-07-14')<0) OR ((document_id=%s AND DATEDIFF(date_created,'2014-07-14')>=0) )", $id, $id

		// "SELECT * FROM " . $this->table . " WHERE document_id=%s  AND document_checksum=%s", $id,$_GET["csum"]

		));
	
		
	
		if ($_GET['invite'] != NULL || $_GET['invite'] != "")
	{

	// invited checksum verify
	$checksum=$_GET['csum'];
	$document_content=$document[0]->document_content;
	$document_raw=$this->signature->decrypt(ENCRYPTION_KEY,$document_content) ; 
	
	$document_checksum = sha1($id .$document_raw);
			
	if($checksum!=$document_checksum){
		
		//failed checksum update then show error
		$affected = $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET document_checksum='%s' WHERE document_id=%d",
				$document_checksum,
				$id
			)
		);
		wp_redirect( home_url().'/e-signature-document/?page_id='.$pageID.'&docid=0&c_err=2');
		
		}

	}	
	/* Earls changes 9.12.14 version 1.0.14
		//in
		$current_user = wp_get_current_user();
		$this->esigrole = new WP_E_Esigrole;
	if ($this->esigrole->esig_current_user_can())
		{
		//allowed
		}
	  else
		{
		//not allowed
		wp_redirect( home_url().'/e-signature-document/?page_id='.$pageID.'&docid=0&c_err=1');
		}
		//out
	*/
		if($document[0]->document_content==NULL||$document[0]->document_content==""){
			wp_redirect( home_url().'/e-signature-document/?page_id='.$pageID.'&docid=0&c_err=3');
			}
		return $document[0];
		$document = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE document_id=%s", $id
			)
		);
		return $document[0];		
	}
 
	/**
	 * Return a Document row Array
	 *
	 * @since 0.1.0
	 * @param Int ($id) 
	 * @return Array
	 */
	public function getStatus($id){

		return $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT document_status FROM " . $this->table . " WHERE document_id=%s", $id
			)
		);
	}
	
	/**
	 * Return a Document type
	 *
	 * @since 0.1.0
	 * @param Int ($id) 
	 * @return Array
	 */
	public function getDocumenttype($id){

		return $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT document_type FROM " . $this->table . " WHERE document_id=%s", $id
			)
		);
	}
	
	/**
	 * Return a Document event date
	 *
	 * @since 1.0.1
	 * @param Int ($id) 
	 * @return Array
	 */
	public function getEventDate($id){

		$event_date=$this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT date FROM " . $this->eventsTable . " WHERE document_id=%s order by id DESC", $id
			)
		);
        
        if(empty($event_date)){
                $document = $this->getDocument($id);
                return $document->date_created;
        }
        else {
            return $event_date ;
        }
	}
	
	/**
	 * Return a Document view result
	 *
	 * @since 0.1.0
	 * @param Int ($id) 
	 * @return Array
	 */
	public function getViewresult($id,$userid){

		$events = $this->getEvents($id);
		foreach($events as $event){
			
			$data = json_decode($event->event_data);
			
			// Views
			if($event->event == 'viewed'){
				
				if($data->user==$userid){
					
					  return 1 ; 
				}
				else { return 0 ; }
				
			} 
				
		}
	}
	
	/**
	 * Return a Document signed result
	 *
	 * @since 0.1.0
	 * @param Int ($id) 
	 * @return Array
	 */
	public function getSignedresult($id){

		$events = $this->getEvents($id);
		foreach($events as $event){
		
			// Views
			if($event->event == 'all_signed'){
				
					  return 1 ; 
			} 		
		}
	}
	
	/**
	 * Return a Document All signed result
	 *
	 * @since 1.0.7
	 * @param Int ($id) 
	 * @return Array
	 */
	public function getSignedresult_eventdate($id){

		$events = $this->getEvents($id);
		foreach($events as $event){
		
			// Views
			if($event->event == 'all_signed'){
				
					  return $event->date ; 
			} 		
		}
		
		return ; 
	}

	/**
	 * Returns data regarding how many invitees vs how many have signed
	 *
	 * @since 0.1.0
	 * @param Int ($id) 
	 * @return Array
	 */	
	public function getSignatureStatus($id){
		
		$invites = $this->invite->getInvitations($id);
		$signatures = $this->signature->getDocumentSignatures($id);
		
		$signatures_needed = array();
		foreach($invites as $invite){
			$found = false;
			foreach($signatures as $signature){
				if($signature->user_id == $invite->user_id){
					$found = true;
				}
			}
			if(!$found){
				$signatures_needed[] = array(
					'id' => $invite->user_id,
					'user_email' => $invite->user_email
				);
			}
		}
		
		return array(
			'invitation_count' => count($invites),
			'signature_count' => count($signatures),
			'signatures_needed' => $signatures_needed,
			'invites' => $invites,
			'signatures' => $signatures
		);
	}

	/**
	 * Return Total Document Row Count
	 *
	 * @since 0.1.0
	 * @param null
	 * @return Int
	 */
	public function getDocumentsTotal($filter='all'){

		return $this->wpdb->get_var( "SELECT COUNT(*) FROM " . $this->table . ($filter != 'all' ?  " WHERE document_status='$filter'" : "") );

	}
	
	public function document_exists($doc_id){

		return $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT COUNT(*) as cnt FROM " . $this->table . " WHERE document_id=%s", $doc_id
			) );

	}
	
	/**
	 * This is method document_id_by_csum
	 *
	 * @param mixed $csum_id This is a description
	 * @return Document id This is the return value description
	 *
	 */	
	public function document_id_by_csum($csum_id){

		return $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT document_id FROM " . $this->table . " WHERE document_checksum=%s", $csum_id
			) );

	}
	
	/**
	 *  getting document check sum by document id 
	 *  Since 1.0.14 
	 */
	
	public function document_checksum_by_id($document_id){

		return $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT document_checksum FROM " . $this->table . " WHERE document_id=%s", $document_id
				) );

	}
	
	public function document_document_page_exists($page_id){
		$post_status='publish';
		$page_found= $this->wpdb->get_var(
				"SELECT COUNT(id) FROM " . $this->wpdb->prefix . "posts WHERE id='" . $page_id . "' and post_status='publish'"
			);
			
			if($page_found==0) 
					return true ;
			if($page_found >0)
					return false ; 

	}
	public function document_max(){

		return $this->wpdb->get_var("SELECT MAX(document_id) as cnt FROM " . $this->table);
	}
	
	/**
	 * Insert a Document row
	 *
	 * @since 0.1.0
	 * @param Array ($post) passed $_POST array
	 * @return Int 
	 */
	public function insert($post){

		// prepare vars
		$user_id = get_current_user_id();
		$post_id = 0; // future versions may allow document to be displayed on a specific page
		$notify  = isset($post['notify']) ? 1 : 0;
		$add_signature = isset($post['add_signature']) ? 1 : 0;
		$document_status = $_POST['document_action'] == 'save' ? 'draft' : 'pending';
		$document_type = 'normal';
		$document_hash = ""; // will be added after insert; will need document id 
		$document_uri = ""; // relies on checksum, will be created after checsum, then updated
		$date_created  = date("Y-m-d H:i:s");
		$document_title =stripslashes($post['document_title']); 
		
		$document_content_encrpt = stripslashes($post['document_content']); // Or shortcodes won't work
		$document_content=$this->signature->encrypt(ENCRYPTION_KEY,$document_content_encrpt) ; 	
		
		// query 
		$this->wpdb->query(
			$this->wpdb->prepare(
				"INSERT INTO " . $this->table . " (document_id, user_id, post_id, document_title, document_content, notify, add_signature, document_type, document_status, document_checksum, document_uri,  ip_address, date_created, last_modified) VALUES(null, %d,%d,%s,%s,%d,%d,%s,%s,%s,%s,%s,%s,%s)",
				$user_id,
				$post_id,
				$document_title,
				$document_content,
				$notify,
				$add_signature,
				$document_type,
				$document_status,
				$document_hash,
				$document_uri,
				$_SERVER['REMOTE_ADDR'],
				$date_created,
				$date_created
			)
		);

		// with doc id & doc content create sha1 checksum an update row
		$doc_id = $this->wpdb->insert_id;
		$document=$this->getDocument($doc_id);
		$document_raw=$this->signature->decrypt(ENCRYPTION_KEY,$document->document_content) ; 
		$document_checksum = sha1($doc_id . $document_raw);

		// create document uri
		// prepare URL the document is to be signed on
		$setting = new WP_E_Setting();
		$pageID =  $setting->get('default_display_page');

		$document_uri = get_site_url() . "/?page_id=" . $pageID . "&docid=" . $doc_id . "&csum=" . $document_checksum;

		$affected = $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET document_checksum='%s', document_uri='%s' WHERE document_id=%d",
				$document_checksum,
				$document_uri,
				$doc_id
			)
		);
		
		if($affected > 0) return $doc_id;
	}
	
	
	// Given the document id, make a copy and return the id of the new document
	public function copy($doc_id){
		// Get doc as associative array
		$doc = $this->wpdb->get_row($this->wpdb->prepare(
			"SELECT * FROM {$this->table} WHERE document_id = %d", 
			$doc_id), ARRAY_A);

		unset($doc['document_id']);
		
		// Insert new doc
		$this->wpdb->insert($this->table, $doc);
		$doc_id = $this->wpdb->insert_id;
		
		// Update checksum, etc
		$document_content=$this->signature->decrypt(ENCRYPTION_KEY,$doc['document_content']) ; 
		$document_checksum = sha1($doc_id . $document_content);
		$setting = new WP_E_Setting();
		$pageID =  $setting->get('default_display_page');
		$document_uri = get_site_url() . "/?page_id=" . $pageID . "&docid=" . $doc_id . "&csum=" . $document_checksum;
		
		$affected = $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET document_checksum='%s', document_uri='%s' WHERE document_id=%d",
				$document_checksum,
				$document_uri,
				$doc_id
			)
		);

		return $doc_id;
	}

	public function updateStatus($doc_id, $status){
		return $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET document_status='%s' WHERE document_id=%d",
				$status,
				$doc_id
			)
		);
	}

	public function update($post){

		// store doc in database
		$notify  = isset($post['notify']) ? 1 : 0;
		$add_signature = isset($post['add_signature']) ? 1 : 0;
		$document_type = 'normal';
		$document_status = $_POST['document_action'] == 'save' ? 'draft' : 'pending';
		$document_hash = ""; // !- Hasing Algorithm needed
		$last_modified  = date("Y-m-d H:i:s");
		$document_title =stripslashes($post['document_title']); 
		$document_content_encrpt = stripslashes($post['document_content']); // Or shortcodes won't work
		$document_content=$this->signature->encrypt(ENCRYPTION_KEY,$document_content_encrpt) ;

		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET 
				 document_title='%s',
				 document_content='%s',
				 notify=%d,
				 add_signature=%d,
				 document_type='%s',
				 document_status='%s',
				 last_modified='%s'
				 WHERE document_id=%d",
				 $document_title,
				 $document_content,
				 $notify,
				 $add_signature,
				 $document_type,
				 $document_status,
				 $last_modified,
				 $post['document_id']
			)
		);
	}

	private function setPreviousState($id, $state){

		$setting = new WP_E_Setting();

		if($setting->exists($this->documentStateLog)){
			$log = json_decode($setting->get($this->documentStateLog));
            
			if($state == 'archive'){
				$log->$id = array($log->$id, $state);
			}else{
				$log->$id = $state;
			}
            
			$setting->update($this->documentStateLog, json_encode($log));
		}else{
			$setting->set($this->documentStateLog, json_encode(array($id => $state)));
		}
	}

	private function getPreviousState($id){
		$setting = new WP_E_Setting();
        
		if($setting->exists($this->documentStateLog)){
			$log = json_decode($setting->get($this->documentStateLog));
            
			if(is_array($log->$id)){
               
				$states = $log->$id;

				$log->$id = $states[0];

				$setting->update($this->documentStateLog, json_encode($log));

				return $states[1];
			}else{
				return $log->$id;
			}
		}else{
			return false;
		}
	}

	public function archive($id){

		$current_state = $this->wpdb->get_var("SELECT document_status FROM " . $this->table . " WHERE document_id=$id");
		$this->setPreviousState($id, $current_state);

		return $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET document_status='archive' WHERE document_id=%d", $id
			)
		);
	}

	public function restore($id){

		$restore_state = $this->getPreviousState($id);
	   
		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET document_status='%s' WHERE document_id=%d", $restore_state, $id
			)
		);
	}
	
	public function trash($id){

		$current_state = $this->wpdb->get_var("SELECT document_status FROM " . $this->table . " WHERE document_id=$id");
		$this->setPreviousState($id, $current_state);

		return $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET document_status='trash' WHERE document_id=%d", $id
			)
		);
	}
	
	/**
	 * Delete a document. Must be in a trashed state in order to delete.
	 */
	public function delete($id){
		return $this->wpdb->query(
			$this->wpdb->prepare(
				"DELETE FROM " . $this->table . " WHERE document_status='trash' AND document_id=%d", $id
			)
		);
	}

	public function fetchAll(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->table . " WHERE document_status != 'trash' && document_status !='archive'");
	}

	public function fetchAllOnStatus($status){
		// get super admin 
		$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
		$wp_user_id=get_current_user_id(); // getting current wp user id
		
		if($status == 'all'){
			return $this->fetchAll();
		} else {
			// if match with super admin 
			if($admin_user_id == $wp_user_id){
				return $this->wpdb->get_results(
					$this->wpdb->prepare(
						"SELECT * FROM " . $this->table . " WHERE document_status=%s", $status
						)
					);	
			}else { //if not match 
				return $this->wpdb->get_results(
					$this->wpdb->prepare(
						"SELECT * FROM " . $this->table . " WHERE user_id=%d and document_status=%s",$wp_user_id,$status
						)
					);
			}
		}
	}

	public function fetchAllOnSearch($esig_document_search){
		// get super admin 
		$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
		$wp_user_id=get_current_user_id(); // getting current wp user id
		
		$search = '%'. $esig_document_search . '%';
		
		$all_sender = isset($_POST['esig_all_sender'])?$_POST['esig_all_sender']:null;
		// if match with super admin 
		if($admin_user_id == $wp_user_id){
			if($all_sender == 'All Sender'){
				// if only wanted to view admins result 
				$docs = $this->wpdb->get_results(
					$this->wpdb->prepare(
							"SELECT * FROM " . $this->table . " WHERE document_title like %s", $search
							)
						);	
			}else {
				// if search to view others user result 
				$docs = $this->wpdb->get_results(
					$this->wpdb->prepare(
							"SELECT * FROM " . $this->table . " WHERE user_id=%d and document_title like %s",$wp_user_id, $search
							)
						);
			}
		} else { //if not match 
			$docs = $this->wpdb->get_results(
					$this->wpdb->prepare(
						"SELECT * FROM " . $this->table . " WHERE user_id=%d and document_title like %s",$wp_user_id, $search
						)
					);
		}
		// if return result less than one search on signer 
		
		if($this->wpdb->num_rows <1){
			
			$docs = array();
			$users = $this->wpdb->get_results(
				$this->wpdb->prepare(
						"SELECT * FROM " . $this->usertable . " WHERE first_name like %s", $search 
						)
					);
			foreach($users as $signer){
				
				$invitation = $this->invite->get_all_Invitations_userID($signer->user_id);
				
				foreach($invitation as $invite){
					
					$document_id = $invite->document_id ; 
					
					if($admin_user_id == $wp_user_id)
					{
						if($all_sender == 'All Sender'){
							$docs1 = $this->wpdb->get_results(
								$this->wpdb->prepare(
										"SELECT * FROM " . $this->table . " WHERE  document_id=%s", $document_id
										)
									);
						}else {
							$docs1 = $this->wpdb->get_results(
								$this->wpdb->prepare(
										"SELECT * FROM " . $this->table . " WHERE user_id=%d and document_id=%s",$all_sender,$document_id
										)
									);	
						}
					}else {
					
						$docs1 = $this->wpdb->get_results(
							$this->wpdb->prepare(
									"SELECT * FROM " . $this->table . " WHERE user_id=%d and document_id=%s",$wp_user_id,$document_id
									)
								);
						
					}
					$docs = array_merge($docs1,$docs);
				}
			}
		}
		
		return $docs ; 
		
	}
	/**
	 * Creates an audit trail
	 *
	 * @since 0.1.0
	 * @param Int ($id)
	 * @return array
	 */
	public function auditReport($id, &$document){

		$invitations = $this->invite->getInvitations($id);
		
		$events = $this->getEvents($id);
		
		$signatures = $this->signature->getDocumentSignatures($id);
		
		$timeline = array();
		
		$signature_status = $this->getSignatureStatus($id);
		$signatures_needed_count = count($signature_status['signatures_needed']);
		
		if($document->document_status == 'draft'){
			$signature_status_label = 'Created';
		} else if($signature_status['invitation_count'] > 0){
			
			if($signatures_needed_count > 0){
				$signature_status_label = "Awaiting $signatures_needed_count signatures";
			} else {
				$signature_status_label = 'Completed';
			}
			
		}
		$document->signature_status = isset($signature_status_label)? $signature_status_label : '';

		// Created
		$creator = $this->user->getUserByWPID($document->user_id);
	
		$timeline[strtotime($document->date_created)-1] = array(
			"date"=>$document->date_created, 
			"log"=>"Document {$document->document_title}<br/>\n" . 
				"Uploaded by {$creator->first_name}  - {$creator->user_email}<br/>\n" .
				"IP: {$document->ip_address}<br/>\n"
		);
		
		// Invitations
		foreach($invitations as $invitation){
			
			$recipient = $this->user->getUserdetails($invitation->user_id, $invitation->document_id);
			$recipient_txt = $recipient->first_name . ' - ' . $recipient->user_email;
			$log = "Document sent for signature to $recipient_txt<br/>" ;
			if($invitation->invite_sent > 0){
				$timeline[strtotime($invitation->invite_sent_date)] = array( 
					'date' => $invitation->invite_sent_date,
					'log' => $log
				);
			}
			
		}
		
		
		$timeline = apply_filters('esig_audit_trail_view', 
				$timeline, array('event' => $events)
			);

		// Signatures
		foreach($signatures as $signature){
			
			$user= $this->user->getUserdetails($signature->user_id,$id);
			$user_txt = $user->first_name . ' - ' . $user->user_email;
			$log = "Document signed by $user_txt<br/>\n" .
				"IP: {$signature->ip_address}";
			$timekey=strtotime($signature->sign_date);
		   if(array_key_exists($timekey,$timeline)){
		     $timekey=strtotime($signature->sign_date)+1;
			}
			$timeline[$timekey] = array( 
				"date" => $signature->sign_date,
				"log" => $log 
			);
		} 
		
		foreach($events as $event){
			
		   if($event->event == "all_signed"){
					$log = __("The document has been signed by all parties and is now closed.", 'esig');
		   
		   $timekey=strtotime($event->date);
		   if(array_key_exists($timekey,$timeline)){
		     $timekey=strtotime($event->date)+1;
			}
			
			$timeline[$timekey] = array( 
				"date" => $event->date,
				"log" => $log 
			);	
			
			}		
		} 	
			
		return $timeline;
	}
	
	/**
	 * Get audit signature id . 
	 *
	 * @since 1.0.4
	 * @param Int ($id)
	 * @return array
	 */
	public function get_audit_signature_id($id, &$document){

		$invitations = $this->invite->getInvitations($id);
		
		$events = $this->getEvents($id);
		
		$signatures = $this->signature->getDocumentSignatures($id);
		
		$timeline = array();
		
		$signature_status = $this->getSignatureStatus($id);
		$signatures_needed_count = count($signature_status['signatures_needed']);
		
		if($document->document_status == 'draft'){
			$signature_status_label = 'Created';
		} else if($signature_status['invitation_count'] > 0){
			
			if($signatures_needed_count > 0){
				$signature_status_label = sprintf(__("Awaiting %s signatures", 'esig'), $signatures_needed_count);
			} else {
				$signature_status_label = 'Completed';
			}
			
		}
		$document->signature_status = isset($signature_status_label)? $signature_status_label : '';

		// Created
		$creator = $this->user->getUserByWPID($document->user_id);
	
		$timeline[strtotime($document->date_created)-1] = array(
			"date"=>$document->date_created, 
			"log"=>"Document {$document->document_title}<br/>\n" . 
				"Uploaded by {$creator->first_name}  - {$creator->user_email}<br/>\n" .
				"IP: {$document->ip_address}<br/>\n"
		);
		
		// Invitations
		foreach($invitations as $invitation){
			
			$recipient = $this->user->getUserdetails($invitation->user_id, $invitation->document_id);
			$recipient_txt = $recipient->first_name . ' - ' . $recipient->user_email;
			$log = "Document sent for signature to $recipient_txt<br/>" ;
			if($invitation->invite_sent > 0){
				$timeline[strtotime($invitation->invite_sent_date)] = array( 
					'date' => $invitation->invite_sent_date,
					'log' => $log
				);
			}
			
		}
		
		//event loop start here . 
		foreach($events as $event){
			
			$data = json_decode($event->event_data);
			
			// Views
			if($event->event == 'viewed'){
				
				if($data->user){
					$viewer = $this->user->getUserdetails($data->user,$event->document_id);				
					$viewer_txt = $viewer->first_name . ' - ' . $viewer->user_email;
				}
				$viewer_txt = $viewer_txt ? " by $viewer_txt" : '';
				$log = sprintf( __("Document viewed %1s<br/>\n IP: %2s\n", 'esig'), $viewer_txt, $data->ip);
				
			// Signed by all
			} else if($event->event == 'all_signed'){
			    
				$log = __("The document has been signed by all parties and is now closed.", 'esig');
			}
			
			$timeline[strtotime($event->date)] = array( 
				"date" => $event->date,
				"log" => $log 
			);
		}
		
		
			
		// Signatures
		foreach($signatures as $signature){
			
			$user= $this->user->getUserdetails($signature->user_id,$id);
			$user_txt = $user->first_name . ' - ' . $user->user_email;
			$log = sprintf( __("Document signed by %1s<br/>\n IP: %2s", 'esig'), $user_txt, $signature->ip_address);
			$timeline[strtotime($signature->sign_date)] = array( 
				"date" => $signature->sign_date,
				"log" => $log 
			);
		}
		

		// Set timezone
		date_default_timezone_set('UTC');
		
		

			$html = <<<EOL
				<div class="document-meta">
					<span class="doc_title">Audit Trail</span><br/>
					Document name: {$document->document_title}<br/>
					Unique document ID: {$document->document_checksum}<br/>
					Status: {$document->signature_status}
				</div>
				<ul class="auditReport">
EOL;

		// Sort
		ksort($timeline);
		
		$days = array();
		$audittrail="";
		
		$previous_day="";
		$html .= "<table class=\"day\">\n";
		foreach($timeline as $k => $val){
			//$date = date('l M jS h:iA e', $k);
			$val['timestamp'] = $k;
			$date4sort = date('Y:m:d', $k); 
			if($previous_day!=$date4sort){
				list($yyyy,$mm,$dd) = preg_split('/[: -]/',$date4sort);
				$day_timestamp = strtotime("$mm/$dd/$yyyy");
			
				$html .= "<th colspan=\"2\" class=\"day_label\">" . date("n/j/Y", $k) . "</th>\n";
			}
			
			// Creates Audit Trail Serial # Hash on Documents //
			$previous_day = $date4sort ; 
			
			$li = "<td class=\"time\">" . date("H:i:s e", $val['timestamp']) . "</td>";
			$li .= "<td class=\"log\">" . $val['log'] . "</td>";
			$html .= "<tr>$li</tr>";
			
			if((strpos($val['log'],"closed") > 0) && ($audittrail == "")){
				$audittrail = $html;
			}
		}
		
		$hash='';
		if($this->getSignedresult($id))
						$hash=wp_hash($audittrail); 
		
		return $hash ; 
	}
	
	
	/**
	 * Records a view event for a document.
	 *
	 * @since 0.1.0
	 * @param Int ($id)
	 * @return Int event id
	 */
	public function recordView($id, $user_id, $date=null){

		$date = $date ? $date : date("Y-m-d H:i:s");
					
		$event_data = array('user'=>$user_id, 'ip'=>$_SERVER['REMOTE_ADDR']);

		$this->wpdb->query(
			$this->wpdb->prepare(
				"INSERT INTO " . $this->eventsTable . " (id, document_id, event, event_data, date) VALUES (null, %d,%s,%s,%s)",
				$id,
				'viewed',
				json_encode($event_data),
				$date
			)
		);

		// with doc id & doc content create sha1 checksum an update row
		$event_id = $this->wpdb->insert_id;
		
		do_action('esig_record_view_save', array(
				'document_id' => $id,
				'user_id' => $user_id,
			));
		return $event_id;
	}
	

	/**
	 * Records a generic document event. Give it a msg. Event_data
	 *
	 * @since 1.0.1
	 * @param Int ($id) Document id (required)
	 * @param String ($msg) to be added to db into the event column (required)
	 * @param Object ($event_data) to be json encoded and added to db
	 * @param String ($date) Date i.e. date("Y-m-d H:i:s"). Defaults to now.
	 * @return Int event id
	 */	
	public function recordEvent($id, $msg=null, $event_data=null, $date=null){

		$date = $date ? $date : date("Y-m-d H:i:s");
		$event_data = $event_data ? $event_data: null;
		if(!$msg){
			error_log('Document->recordEvent: msg cannot be empty');
			return;
		}

		$this->wpdb->query(
			$this->wpdb->prepare(
				"INSERT INTO " . $this->eventsTable . " (id, document_id, event, event_data, date) VALUES (null, %d,%s,%s,%s)",
				$id,
				$msg,
				json_encode($event_data),
				$date
			)
		);

		// with doc id & doc content create sha1 checksum an update row
		$event_id = $this->wpdb->insert_id;
		
		return $event_id;
	}


	/**
	 * Returns all events for a document
	 *
	 * @since 0.1.0
	 * @param Int ($id) document id
	 * @return array
	 */	
	public function getEvents($id){
		$events = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->eventsTable . " WHERE document_id = %d", 
				$id
			)
		);
		return $events;
	}
	
	

}