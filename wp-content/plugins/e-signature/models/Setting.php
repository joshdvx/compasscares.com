<?php
class WP_E_Setting extends WP_E_Model {

	private $table;

	public function __construct(){
		parent::__construct();
		$this->table = $this->prefix . "settings";
	}

	public function get($name, $user_id = null){

		if(!$user_id){
			$user_id = get_current_user_id();  // settings are user-specific
		}
		
		$setting = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT setting_value FROM " . $this->table . " WHERE user_id=%d and setting_name=%s",$user_id, $name
			)
		);
		if(isset($setting[0])) return $setting[0]->setting_value;
		else return false;
	}
	
	// Gets id of document page. If user exists, use that. Otherwise, get the main admin
	public function get_generic($name){
		
		//$user_id = get_current_user_id();  // settings are user unique
		//if($user_id){
		//return $this->get($name);
		//} else {
			$setting = $this->wpdb->get_results(
				$this->wpdb->prepare(
					"SELECT setting_value FROM " . $this->table . " WHERE setting_name=%s ORDER BY user_id DESC", $name
				)
			);
			
			if(isset($setting[0])) return $setting[0]->setting_value;
			else return false;	
		//}
	}

	public function exists($name){
		$user_id = get_current_user_id();  //wordpress method
		return $this->wpdb->query(
			$this->wpdb->prepare(
				"SELECT setting_id FROM " . $this->table . " WHERE user_id=%d and setting_name='%s'",$user_id,$name
			)
		);
	}

	public function set($name, $value){
	
	     
		if($this->exists($name)){
			$this->update($name, $value);
		}else{
			
			$user_id = get_current_user_id();  //wordpress method

			$this->wpdb->query(
				$this->wpdb->prepare(
					"INSERT INTO " . $this->table . " VALUES(null, %d, %s, %s)", 
					$user_id, 
					$name, 
					$value
				)
			);
		}
		return $this->wpdb->insert_id;
	}

	public function update($name, $value){
	
		if(!$this->exists($name)){
			$this->set($name, $value);
		}else{
			
			$user_id = get_current_user_id();  //wordpress method

			$this->wpdb->query(
				$this->wpdb->prepare(
					"UPDATE " . $this->table . " SET user_id=%d, setting_value='%s' WHERE setting_name='%s'", 
					$user_id, 
					$value, 
					$name
				)
			);
		}
		return $this->wpdb->insert_id;
	}
    
    public function update_generic($name, $value){
	
		if(!$this->exists($name)){
			$this->set($name, $value);
		}else{
			
			$user_id = get_current_user_id();  //wordpress method

			$this->wpdb->query(
				$this->wpdb->prepare(
					"UPDATE " . $this->table . " SET user_id=%d, setting_value='%s' WHERE user_id=%d and setting_name='%s'", 
					$user_id, 
					$value,
                    $user_id,
					$name
				)
			);
		}
		return $this->wpdb->insert_id;
	}
	
	
	public function delete($name){
	
		if($this->exists($name)){
			//wordpress method
		return	$this->wpdb->query(
				$this->wpdb->prepare(
					"DELETE from " . $this->table . " WHERE setting_name='%s'", 
					$name
				)
			);
		}
		 
	}
    
    public function esign_super_admin(){
            
             $wp_user_id = get_current_user_id();
             
             $admin_user_id = $this->get_generic('esig_superadmin_user');
              if($wp_user_id == $admin_user_id || $admin_user_id==null){
                    return true ;
              } else {
                return false ; 
              }
    }
    
    public function esign_hide_esig_menus(){
		
		$hide_esign=$this->get_generic('esig_unlimited_hide_settings');// getting hide settings from settings table 
		$esig_super_admin = $this->get_generic('esig_superadmin_user'); // getting e-signature super admin 
		$wp_user_id=get_current_user_id(); // getting current wp user id 
		
		if($esig_super_admin != $wp_user_id){ // checking super and current user match
       
			if($hide_esign == "1") { // checking hide setting true or false 
             
				return false ; 
			} else {
              return true ;
            }
		}
       return true ;
	}
}