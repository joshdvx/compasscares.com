<?php

class WP_E_Esigrole extends WP_E_Model {
	
	public function __construct(){
		parent::__construct();
		
		$this->settings = new WP_E_Setting();
		$this->user = new WP_E_User();
		// adding action 	
	}
	
	/**
	 * This is method esig_current_user_can
	 *
	 * @param mixed $cap This is a description
	 * @param mixed $user_id This is a description
	 * @return mixed This is the return value description
	 *
	 */	
	public function esig_current_user_can($cap=null,$user_id=null){
		
		if(empty($user_id)){
			$user_id=get_current_user_id();
		}
		// getting admin user id from settings table 
		$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
		if(!$this->settings->get_generic('esig_superadmin_user')){
			return true ; 
		}
		if($user_id == $admin_user_id){
			// return true if current user is wp super admin user 
			return true ; 
		} else {
			 // if not match with super admin user with current user 
			$cap_filter=apply_filters('esig_user_role_filter',$cap);
			return $cap_filter ; 
		}
		
	}
}

