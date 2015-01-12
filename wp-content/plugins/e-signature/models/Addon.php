<?php

/**
 * WP_E_addon a class for addons controller.
 *
 * .
 *
 * @version 1.1.4
 * @author Abu shoaib
 */

class WP_E_Addon extends WP_E_Model {
	
	public function __construct(){
		parent::__construct();
		
		$this->settings = new WP_E_Setting();
		$this->document = new WP_E_Document();
		// adding action 
		
	}
    
    public function esig_all_plugin_activation()
    {
            $array_Plugins = get_plugins();
				
				if(!empty($array_Plugins))
				{
				    foreach($array_Plugins as $plugin_file => $plugin_data) 
				     {
				       if(is_plugin_inactive($plugin_file)) 
				       {
                             $plugin_name=$plugin_data['Name'] ; 
                             
                            if($plugin_name !="WP E-Signature")
						    {  
						       if(preg_match("/WP E-Signature/",$plugin_name))
						       { 
                                     $success = $this->esig_addons_enable($plugin_file);
                               }
                            }
                       }
                       
                     }
                }
    
    }
    
    /* 
    * addons tab generate method
    *
    * Since 1.1.4
    */
    
    public function esig_addons_tabs( $current = 'all' ) {
    
             $tabs = array( 'all' => 'All', 'enable' => 'Enabled', 'disable' => 'Disabled','get-more'=>'Get More' );
            echo '<div id="icon-themes" class="icon32"><br></div>';
            echo '<h2 class="nav-tab-wrapper">';
            foreach( $tabs as $tab => $name ){
                $class = ( $tab == $current ) ? ' nav-tab-active esig-tab-active-background' : 'esig-nav-tab-border';
                echo "<a class='nav-tab $class' href='?page=esign-addons&tab=$tab'>$name</a>";

            }
            echo '</h2>';
    }
    
    public function esig_get_all_addons_list()
    {   
        $all_addons_list = $this->esig_get_premium_addons_list();
        $all_install=array();
        if($all_addons_list)
        {
             
             foreach($all_addons_list as $addonlist=>$addons)
             {
                  if($addonlist !="esig-price")
                  { 
                        if($addons->addon_name !='WP E-Signature')
                        {
                            if($addons->download_access == 'yes')
                             {
                                    // set all addon transients    
                                    $all_install[$addons->download_name]=$addons->download_link;
                             }
                        }
                  }
             }
        }
        
        return json_encode($all_install);
    
    }
    
    /***
    * esig get all addons list. 
    */
    
    public function esig_get_premium_addons_list() {

		global $wp_version;

		  if (!function_exists('WP_E_Sig'))
					return ;
					
			     $esig = WP_E_Sig(); 
		            
		$api_params = array(
            'esig-remote-request'=> 'on',
			'esig_action' 	=> 'addons_list',
			'license_key' 		=> trim( $esig->setting->get_generic('esig_wp_esignature_license_key')),
			'url' 			=> 'http://www.approveme.me/',
			'author'		=> 'ApproveMe',
		);
        
		$request = wp_remote_post('http://www.approveme.me/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( !is_wp_error( $request ) ):
			$request = json_decode( wp_remote_retrieve_body( $request ) );	
            return $request; 
		else:
			return false;
		endif;
        
       
	}
    
    /***
    * esig get all addons list. 
    */
    
    public function esig_get_addons_list() {

		global $wp_version;

		  if (!function_exists('WP_E_Sig'))
					return ;
					
			     $esig = WP_E_Sig(); 
		             
		$api_params = array(
            'esig-remote-request'=> 'on',
			'esig_action' 	=> 'addons_list_basic',
			'license_key' 		=> trim( $esig->setting->get_generic('esig_wp_esignature_license_key')),
			'url' 			=> 'http://www.approveme.me/',
			'author'		=> 'Approve Me',
		);
        
		$request = wp_remote_post('http://www.approveme.me/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( !is_wp_error( $request ) ):
			$request = json_decode( wp_remote_retrieve_body( $request ) );	
            
            return $request; 
		else:
			return false;
		endif;
       
	}
    
    public function esig_addons_installall()
    {       
            set_time_limit(0);
            if(!function_exists('WP_E_Sig'))
					    return ;
				
			 $esig = WP_E_Sig(); 
            
           $all_addons=json_decode(get_transient( 'esig-all-addons-install'));
           // add onlist is empty then return false . 
           if(!$all_addons)
           {
                $all_addons=json_decode($this->esig_get_all_addons_list());
                
                if(!$all_addons)
                {
                    return false ; 
                }
           }
           
           foreach($all_addons as $download_name => $source)
           {
           
                
                // setting destination to plugin folder .
                $destination =WP_PLUGIN_DIR ."/" . $download_name ; 
                // download addon to temp folder . 
                $temp_file = download_url($source);
                
                if ( is_wp_error($temp_file ) )
                {  
                    continue ; 
                }
                
                // copy temple file to plugin folder . 
                if(copy( $temp_file ,  $destination ))
                {   
                    unlink($temp_file); // unlink temp file . 
                    WP_Filesystem(); // initilize wp filesystem . 
                    $unzip_file= unzip_file($destination,WP_PLUGIN_DIR ."/"); // unzip the folder . 
                    if($unzip_file)
                    {   
                        unlink( $destination); // remove zip folder . 
                        
                    }
                }  
            }
            
           return true ;
    }
    
    public function esig_addons_install($source,$download_name)
    {
            if(!function_exists('WP_E_Sig'))
					    return ;
				
			 $esig = WP_E_Sig(); 
             
            // setting destination to plugin folder .
            $destination =WP_PLUGIN_DIR ."/" . $download_name ; 
            // download addon to temp folder . 
            $temp_file = download_url($source);
            
           if ( is_wp_error($temp_file ) )
            {  
               return false ; 
            }
            
            // copy temple file to plugin folder . 
            if(copy( $temp_file ,  $destination ))
            {   
                unlink($temp_file); // unlink temp file . 
                WP_Filesystem(); // initilize wp filesystem . 
                $unzip_file= unzip_file($destination,WP_PLUGIN_DIR ."/"); // unzip the folder . 
                if($unzip_file)
                {   
                    unlink( $destination); // remove zip folder . 
                    //going to activate the plugin .
                  //  $plugin_root_folder= trim($download_name, ".zip");
                  //  $plugin_file = $this->esig_get_addons_file_path($plugin_root_folder);
                  //  $activate_success=$this->esig_addon_activate($plugin_file);
                }
            }
            
           return true ;
    }
  
    public function esig_get_addons_file_path($plugin_root_folder)
    {
                    $plugin_files ='';
                    if(!is_dir(WP_PLUGIN_DIR ."/".$plugin_root_folder))
                    {
                        return ; 
                    }
                    
                    if ($handle = opendir(WP_PLUGIN_DIR ."/".$plugin_root_folder)) 
                    {
                        while (false !== ($entry = readdir($handle))) 
                        {
                            if ( substr($entry, 0, 1) == '.' )
                                          continue;
                            if ( substr($entry, -4) == '.php')
                                    $plugin_data = get_plugin_data(WP_PLUGIN_DIR ."/$plugin_root_folder/$entry", false, false ); 
                            if ( empty ( $plugin_data['Name'] ) )
                             {
                               continue;
                             }
                             else 
                             {
                                $plugin_files="$plugin_root_folder/$entry";
                                break;
                             }
                        }
                        closedir($handle);
                    }
                    else 
                    {
                        return false ; 
                    }
                    
              return $plugin_files; 
    }
    
    public function esig_addon_activate($plugin_file)
    {
            if ( ! current_user_can('activate_plugins') )
		                wp_die(__('You do not have sufficient permissions to activate plugins for this site.'));
	       
            $plugins = FALSE;
	        $plugins = get_option('active_plugins'); // get active plugins
	        
	        if ( $plugins ) {
		        // plugins to active
		        $pugins_to_active = array(
			        $plugin_file,  
		        );
		
		        foreach ( $pugins_to_active as $plugin ) {
			        if ( ! in_array( $plugin, $plugins ) ) {
				        array_push( $plugins, $plugin );
				        update_option( 'active_plugins', $plugins );
			        }
		        }
		
	        } // end if $plugins
            
            return true ; 
    }
    
    /***
    *  Deactivating addons 
    *  Since 1.1.4
    */
    public function esig_addons_disable($plugin_file)
    {
                if ( ! current_user_can('activate_plugins') )
		                wp_die(__('You do not have sufficient permissions to deactivate plugins for this site.'));
           
            deactivate_plugins( $plugin_file);
           
            return true ; 
    }
    
    /***
    *  Deactivating addons 
    *  Since 1.1.4
    */
    public function esig_addons_enable($plugin_file)
    {
                if ( ! current_user_can('activate_plugins') )
		                wp_die(__('You do not have sufficient permissions to deactivate plugins for this site.'));
            
            activate_plugins( $plugin_file);
            
            return true ; 
    }
    
    
 public function get_package_price()
 {
          global $wp_version;

		  if (!function_exists('WP_E_Sig'))
					return ;
					
			     $esig = WP_E_Sig(); 
		             
		$api_params = array(
            'esig-remote-request'=> 'on',
			'esig_action' 	=> 'pk_price',
			'url' 			=> 'http://www.approveme.me/',	
		);
        
		$request = wp_remote_post('http://www.approveme.me/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( !is_wp_error( $request ) ):
        
			$request = json_decode( wp_remote_retrieve_body( $request ) );	
          
             if($this->settings->get_generic('esig_wp_esignature_license_key') && ($this->settings->get_generic('esig_wp_esignature_license_active')) =='valid')
               {
                     $license_key ='yes';
               }
               else
               {
                 $license_key='no';
               }
         foreach($request as $addonlist=>$addons)
            { 
                    if($addonlist =="esig-price")
                    {
                        
                        if(($esig_license_type =$this->settings->get_generic('esig_wp_esignature_license_type') ) !='Business License')
                        {
                            $buisness_price=is_array($addons)? $addons[0]->amount : null;
                            $professional_price=is_array($addons)?$addons[1]->amount:null;
                            $individual_price=is_array($addons)?$addons[2]->amount:null;
                      
                       
                              if(($esig_license_type =$this->settings->get_generic('esig_wp_esignature_license_type') ) =='Professional License' && $license_key !='no') 
                              { 
                                    $price = $buisness_price- $professional_price;
                              }
                              elseif(($esig_license_type =$this->settings->get_generic('esig_wp_esignature_license_type') ) =='Individual License' && $license_key !='no') 
                              {
                                    $price = $buisness_price- $individual_price;
                              }
                              else
                              {
                                    $price = $buisness_price;
                              }
                              
                              set_transient( 'esig-addons-price', $price, 12 * HOUR_IN_SECONDS );
                              
                              return $price;
                        }
                        else
                        {
                                set_transient( 'esig-addons-price',"buisness", 12 * HOUR_IN_SECONDS );
                                return "buisness" ; 
                        }   
                        
                    }
            }
		else:
			return false;
		endif;
 }
  
}