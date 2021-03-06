<?php //include($this->rootDir . DS . 'partials/_tab-nav.php'); ?>

<?php 
 $this->setting = new WP_E_Setting();
if (array_key_exists('messages', $data)) { echo $data['messages']; } ?>


<h3><?php _e('Premium Add-on Extensions', 'esig'); ?></h3>

<p class="esig-add-on-description"><?php _e('Add-ons are customizable features that you can add or remove depending on your specific needs. Signing documents should only be as automated/customizable as you need it to be. Visit the Get More tab to see what else ApproveMe can do for you.','esig') ; ?></p>

<?php

   $tab= isset($_GET['tab'])?$_GET['tab']:'all';
   
   $documentation_page = '';
   $settings_page = '' ; 
  
   
   $this->model->esig_addons_tabs($tab);
   echo '<div class="esig-add-ons-wrapper">';
   // tab content start here 
   if($tab=='all') {
       
   if($this->setting->get_generic('esig_wp_esignature_license_key') && ($this->setting->get_generic('esig_wp_esignature_license_active')) =='valid')
   {
         $license_key ='yes';
     $all_addons_list = $this->model->esig_get_premium_addons_list();
   }
   else 
   {
      $license_key ='no';
     $all_addons_list = $this->model->esig_get_addons_list();
   }
   
   if($all_addons_list)
   {
        $total=0 ; 
        $all_install=array();
       foreach($all_addons_list as $addonlist=>$addons)
       {
          if($addonlist =="esig-price")
          {
                   if(($esig_license_type =$this->setting->get_generic('esig_wp_esignature_license_type') ) !='Business License')
                        {
                      
                          $buisness_price=is_array($addons)? $addons[0]->amount : null;
                          $professional_price=is_array($addons)?$addons[1]->amount:null;
                          $individual_price=is_array($addons)?$addons[2]->amount:null;
                      
                       
                              if(($esig_license_type =$this->setting->get_generic('esig_wp_esignature_license_type') ) =='Professional License' && $license_key !='no') 
                              { 
                                    $price = $buisness_price- $professional_price;
                              }
                              elseif(($esig_license_type =$this->setting->get_generic('esig_wp_esignature_license_type') ) =='Individual License' && $license_key !='no') 
                              {
                                    $price = $buisness_price- $individual_price;
                              }
                              else
                              {
                                    $price = $buisness_price;
                              }
                            
                        ?>
     

                    <div class="esig-add-on-block esig-pro-pack open">
					    <?php echo sprintf( __('<h3>Get the E-Signature Buisness Pack<span><a href="#">Learn More</a></span></h3>

					        <p>The Business Pack gets you access to WP E-Signature add-ons that unlock so much more functionality and features that WP E-Signature can do... like Dropbox Sync, Signing Reminders, Save as PDF, Stnad Alone Documents, URL Redirect After Signing, Custom Fields and more. With the Business Pack, you get access to all our ApproveMe built WP E-Signature Add-ons plus any more we build in the next year (which will be a ton).</p>
					        <a class="esig-btn-pro" href="http://www.approveme.me/e-signature-upgrade-license/" target="_blank">Get all our add-ons for $%s </php></php></a><a href="#" class="esig-dismiss">No thanks</a>',$price,'esig'),$price); ?>

				        </div>

     
                        <?php
                        }
                        
                 if($this->setting->get_generic('esig_wp_esignature_license_key') && ($this->setting->get_generic('esig_wp_esignature_license_active')) =='valid')
                {
                
                   if(($esig_license_type =$this->setting->get_generic('esig_wp_esignature_license_type') ) =='Business License')
                        {
                       
                   ?>
                 
                         <div class="esig-add-on-block esig-pro-pack open">
					                <?php _e(' <h3>Save Time...Install everything with one click</h3>
					                    <p>Since you have access to the Buisness Pack you can save time by installing 
                                        all add-ons at once . 
                                        Please Note: The installation process can take few minutes to complete.</p>
					                    <a class="esig-btn-pro" id="esig-install-alladdons" href="?page=esign-addons&esig_action=installall">Install all Add-ons Now</a>','esig'); ?>
				                    </div>
                 
          <?php         }
                }
                    
            }
            elseif($addons->addon_name !='WP E-Signature')
            {
                    
            
                $plugin_root_folder= trim($addons->download_name, ".zip");
                
                                
                
                $plugin_file = $this->model->esig_get_addons_file_path($plugin_root_folder);
                $esig_update_link = '';
                if($plugin_file)
                {   
                      
                    
                
                    $plugin_data=get_plugin_data(WP_PLUGIN_DIR ."/".$plugin_file);
                    $plugin_name =$plugin_data['Name']; 
                    
                    if(is_plugin_active($plugin_file)) 
				    {
                      $esig_name = preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', "WP E-Signature - ". $addons->addon_name ) );
                      if(get_option($esig_name."_documentation"))
                      {
                            $documentation_page = '<span class="esig-add-on-author"><a href="'. get_option($esig_name.'_documentation') .'" target="_blank">'. __('Documentation','esig') .'</a></span>';     
                      }
                      else
                      {
                            $documentation_page = '';
                      }
                      // settings page . 
                      if(get_option($esig_name."_setting_page"))
                      {
                            $settings_page = '<div class="esig-add-on-settings"><a href="'. get_option($esig_name."_setting_page") .'"></a></div>';     
                      }
                      else
                      {
                            $settings_page = '';
                      }
                      
                      $esig_action_link = '<div class="esig-add-on-enabled"><a data-text-disable="Disable" data-text-enabled="Enabled" href="?page=esign-addons&tab=enable&esig_action=disable&plugin_url='.urlencode($plugin_file) .'&plugin_name='. $plugin_name .'">'. __('Enabled','esig') .'</a></div>';  
                    }
                    elseif(is_plugin_inactive($plugin_file)) 
				    {
                      $esig_action_link = '<div class="esig-add-on-disabled"><a data-text-enable="Enable" data-text-disabled="Disabled" href="?page=esign-addons&tab=disable&esig_action=enable&plugin_url='.urlencode($plugin_file) .'&plugin_name='. $plugin_name .'">'. __('Disabled','esig') .'</a></div>';
                    }
                    
                    if( version_compare( $plugin_data['Version'], $addons->new_version, '<' ) ) 
                    {
                      $esig_update_link ='<div class="esig-add-on-disabled"><a  href="?page=esign-addons&esig_action=install&download_url='.urlencode($addons->download_link) .'&download_name='. $addons->download_name .'" class="eisg-addons-update">'. __('Update Now','esig') .'</a></div>'; 
                    }
                    
                }
                else
                {       
                      if($addons->download_access == 'yes')
                      {
                            // set all addon transients 
                                
                            $all_install[$addons->download_name]=$addons->download_link;
                               
                            $esig_action_link ='<div class="esig-add-on-disabled"><a  href="?page=esign-addons&esig_action=install&download_url='.urlencode($addons->download_link) .'&download_name='. $addons->download_name .'" class="eisg-addons-install">'. __('Install Now','esig').'</a></div>';
                      }
                      else
                      {
                         $esig_action_link ='<div class="esig-add-on-actions"><div class="esig-add-on-price">

                        <span class="esig-regular-price">$'. $price  .'</span>
                        </div><div class="esig-add-on-buy-now"><a href="https://www.approveme.me/wp-digital-e-signature#pricingPlans" target="_blank" class="eisg-addons-upgrade">'. __('Buy Now','esig').'</a></div></div>';
                      }
                }
                
                $total++; ?>

                
                <div class="esig-add-on-block">
                

					<div class="esig-add-on-icon">
						<div class="esig-image-wrapper">
							<img src="<?php echo $addons->addon_image[0];  ?>" width="50px" height="50px" alt="">
						</div>
					</div>

					<div class="esig-add-on-info">
						<h4><a href="<?php echo $addons->download_page_link ;  ?>" target="_blank"><?php echo "WP E-Signature - ". $addons->addon_name ; ?></a></h4>
						<span class="esig-add-on-author"> <?php _e('by','esig'); ?> <a href="http://www.esign.approveme.me/buy-wordpress-electronic-signature/" target="_blank"><?php _e('Approveme','esig'); ?></a></span>
                        <?php echo $documentation_page; ?>
                        <span class="esig-add-on-author"> <?php  echo "Version ". $addons->new_version; ?> </span>
                        		        
                                        <p class="esig-add-on-description"><?php echo $addons->addon_description; ?></p>
					</div>

					<div class="esig-add-on-actions">

							    <?php echo $esig_action_link; ?>
                                <?php echo $settings_page ; ?>
						
					</div>
                </div>
                

      <?php         
       }
       
       } //foreach end here 
       
       
       // setting transient for all addons array . 
        set_transient( 'esig-all-addons-install',json_encode($all_install), 12 * HOUR_IN_SECONDS );
       
       if($total == 0)
       {
            echo '<div> ' .  _e('You have already installed all addons.','esig') .'</div>';
       }
   }
   
  
?>


<?php 
 }
 // all tab end here 
 // enable tab start here 
 if($tab == "enable"){
        
   $array_Plugins = get_plugins();
				$total = 0;
				if(!empty($array_Plugins))
				{
				    foreach($array_Plugins as $plugin_file => $plugin_data) 
				     {
				       if(is_plugin_active($plugin_file)) 
				       {
                             $plugin_name=$plugin_data['Name'] ; 
                             
						       if(preg_match("/WP E-Signature/",$plugin_name))
						       { 
                                    if($plugin_name!="WP E-Signature")
						            { 
                                        $total++;
                                        
                                        list($folder_name, $file_name) = explode('/', $plugin_file);
                                       
                                        // $plugin_name= trim($plugin_name, "WP E-Signature");
                                        $esig_name = preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', $plugin_name ) );
                                       
                                          if(get_option($esig_name."_documentation"))
                                          {
                                                $documentation_page = '<span class="esig-add-on-author"><a href="'. get_option($esig_name.'_documentation') .'" target="_blank">'. __('Documentation','esig') .'</a></span>';     
                                          }
                                          else
                                          {
                                                $documentation_page = '';
                                          }
                                          // settings page . 
                                          if(get_option($esig_name."_setting_page"))
                                          {
                                                $settings_page = '<div class="esig-add-on-settings"><a href="'. get_option($esig_name."_setting_page") .'"></a></div>';     
                                          }
                                          else
                                          {
                                                $settings_page = '';
                                          }
                                          
                                        ?>
                                            <div class="esig-add-on-block">
                
					                            <div class="esig-add-on-icon">
						                            <div class="esig-image-wrapper">
							                            <img src="<?php echo ESIGN_ASSETS_DIR_URI . '/images/add-ons/'. $folder_name .'.png'; ?>" width="50px" height="50px" alt="">
						                            </div>
					                            </div>

					                            <div class="esig-add-on-info">
						                            <h4><?php echo $plugin_name ; ?></h4>
						                            <span class="esig-add-on-author"> <?php _e('by','esig'); ?> <a href="http://approveme.me"><?php _e('Approveme','esig'); ?></a></span>
                        		                    <?php echo $documentation_page ; ?>
                                                    <span class="esig-add-on-author"> <?php  echo "Version ". $plugin_data['Version']; ?> </span>
                                                                    <p class="esig-add-on-description"><?php echo $plugin_data['Description']; ?></p>
					                            </div>

					                            <div class="esig-add-on-actions">
							                                <div class="esig-add-on-enabled"><?php echo '<a data-text-disable="Disable" data-text-enabled="Enabled" href="?page=esign-addons&tab=enable&esig_action=disable&plugin_url='.urlencode($plugin_file) .'&plugin_name='. $plugin_name .'" class="eisg-addons-disable">'. __('Enabled','esig') .'</a>'; ?></div>
						                                    <?php echo $settings_page; ?>
					                            </div>
                                              </div>
                                        
                                        
                               <?php       
                                    }
                               }       
                       }
                       
                     }
                }
                
       if($total == 0)
       {
           echo '<div class="esig-addons-achievement">
				<p><h2>' . _e('No add-ons are currently enabled','esig') .'</h2></p>
				<p class="esig-addon-enable-now"><a href="?page=esign-addons&tab=disable" class="esig-addon-enable-now">'. __('Go enable Add-Ons','esig') .'</a></p>
				
			    </div>';
       }
 
 ?>

 <?php
 } // enable tab end here 
 // disable tab start here 
 if($tab =='disable') {
 
 $array_Plugins = get_plugins();
				$total = 0;
				if(!empty($array_Plugins))
				{
				    foreach($array_Plugins as $plugin_file => $plugin_data) 
				     {
				       if(is_plugin_inactive($plugin_file)) 
				       {
                             $plugin_name=$plugin_data['Name'] ; 
						
						       if(preg_match("/WP E-Signature/",$plugin_name))
						       { 
                                    if($plugin_name!="WP E-Signature")
						            { 
                                        $total++;
                                        // $plugin_name= trim($plugin_name, "WP E-Signature");
                                        list($folder_name, $file_name) = explode('/', $plugin_file);
                                       ?>
                                            <div class="esig-add-on-block">
                
					                            <div class="esig-add-on-icon">
						                            <div class="esig-image-wrapper">
							                            <img src="<?php echo ESIGN_ASSETS_DIR_URI . '/images/add-ons/'. $folder_name .'.png'; ?>" width="50px" height="50px" alt="">
						                            </div>
					                            </div>

					                            <div class="esig-add-on-info">
						                            <h4><?php echo $plugin_name ; ?></h4>
						                            <span class="esig-add-on-author"> <?php _e('by','esig'); ?> <a href="http://approveme.me"><?php _e('Approveme','esig'); ?></a></span>
                                                    <span class="esig-add-on-author"> <?php  echo "Version ". $plugin_data['Version']; ?> </span>
                        		        
                                                                    <p class="esig-add-on-description"><?php echo $plugin_data['Description']; ?></p>
					                            </div>

					                            <div class="esig-add-on-actions">
							                                <div class="esig-add-on-disabled"><?php echo '<a data-text-enable="Enable" data-text-disabled="Disabled" href="?page=esign-addons&tab=disable&esig_action=enable&plugin_url='.urlencode($plugin_file) .'&plugin_name='. $plugin_name .'" class="eisg-addons-enable">' . __('Disabled','esig') . '</a>'; ?></div>
						
					                            </div>
                                              </div>
                                        
                                        
                               <?php      
                                    }
                               }       
                       }
                       
                     }
                }
       if($total == 0)
       {
           echo '<div class="esig-addons-achievement">
				<h2>'. __('No add-ons are currently disabled','esig') .'</h2>
				
			    </div>';
       }    
 ?>
 
 <?php 
 } // disable tab end here 
 // get-more tab start here 
 if($tab == 'get-more')
 { 
 
 
 if($this->setting->get_generic('esig_wp_esignature_license_key') && ($this->setting->get_generic('esig_wp_esignature_license_active')) =='valid')
   {
         $license_key ='yes';
     
   }
   else
   {
     $license_key='no';
   }

 $all_addons_list = $this->model->esig_get_addons_list();
 
   if($all_addons_list)
   {
        $total=0 ; 
       
       foreach($all_addons_list as $addonlist=>$addons)
       {
           if($addonlist =="esig-price")
          {
              if(($esig_license_type =$this->setting->get_generic('esig_wp_esignature_license_type') ) !='Business License')
                        {
                      
                          $buisness_price=is_array($addons)? $addons[0]->amount : null;
                          $professional_price=is_array($addons)?$addons[1]->amount:null;
                          $individual_price=is_array($addons)?$addons[2]->amount:null;
                      
                       
                              if(($esig_license_type =$this->setting->get_generic('esig_wp_esignature_license_type') ) =='Professional License' && $license_key !='no') 
                              { 
                                    $price = $buisness_price- $professional_price;
                              }
                              elseif(($esig_license_type =$this->setting->get_generic('esig_wp_esignature_license_type') ) =='Individual License' && $license_key !='no') 
                              {
                                    $price = $buisness_price- $individual_price;
                              }
                              else
                              {
                                    $price = $buisness_price;
                              }
                            
                        ?>
     

                    <div class="esig-add-on-block esig-pro-pack open">
					     <?php echo sprintf(__('<h3>Get the E-Signature Buisness Pack</h3>
					        <p style="display:block;">The Business Pack gets you access to WP E-Signature add-ons that unlock so much more functionality and features that WP E-Signature can do... like Dropbox Sync, Signing Reminders, Save as PDF, Stnad Alone Documents, URL Redirect After Signing, Custom Fields and more. With the Business Pack, you get access to all our ApproveMe built WP E-Signature Add-ons plus any more we build in the next year (which will be a ton).</p>
					        <a class="esig-btn-pro" href="http://www.approveme.me/e-signature-upgrade-license/" target="_blank">Get all our add-ons for $%s</a> ',$price,'esig'),$price); ?>

				        </div>

     
                        <?php
                        }
                   
          }
            
            elseif($addons->addon_name !='WP E-Signature')
            {
                $plugin_root_folder= trim($addons->download_name, ".zip");
                
                $plugin_file = $this->model->esig_get_addons_file_path($plugin_root_folder);
                $esig_update_link = '';
                if($plugin_file)
                {   
                    
                    if(is_plugin_active($plugin_file)) 
				    {
                       continue ;
                    }
                    if(is_plugin_inactive($plugin_file)) 
				    {
                       continue ;
                    }
                  
                }
                else 
                {
                     if($addons->download_access == 'yes')
                      {
                           
                                
                            $esig_action_link ='<div class="esig-add-on-disabled"><a  href="?page=esign-addons&esig_action=install&download_url='.urlencode($addons->download_link) .'&download_name='. $addons->download_name .'" class="eisg-addons-install">'. __('Install Now','esig') .'</a></div>';
                      }
                      else
                      {
                         $esig_action_link ='<div class="esig-add-on-actions"><div class="esig-add-on-price">
<span class="esig-regular-price">$'. $price  .'</span>
</div><div class="esig-add-on-buy-now"><a href="https://www.approveme.me/wp-digital-e-signature#pricingPlans" target="_blank" class="eisg-addons-upgrade">'. __('Buy Now','esig') .'</a></div></div>';
                      }
                      
                           $total++;
                ?>
                
                <div class="esig-add-on-block">
                
					<div class="esig-add-on-icon">
						<div class="esig-image-wrapper">
							<img src="<?php echo $addons->addon_image[0];  ?>" width="50px" height="50px" alt="">
						</div>
					</div>

					<div class="esig-add-on-info">
						<h4><a href="<?php echo $addons->download_page_link ;  ?>" target="_blank"><?php echo "WP E-Signature - ".$addons->addon_name ; ?></a></h4>
						<span class="esig-add-on-author"> <?php _e('by','esig'); ?> <a href="http://www.esign.approveme.me/buy-wordpress-electronic-signature/"><?php _e('Approveme','esig'); ?></a></span>
                        <span class="esig-add-on-author"> <?php  echo "Version ". $addons->new_version; ?> </span>
                        		        
                                        <p class="esig-add-on-description"><?php echo $addons->addon_description; ?></p>
					</div>

					<div class="esig-add-on-actions">
							    <div class="esig-add-on-disabled"><?php echo $esig_action_link; ?></div>
						
					</div>
                </div>
                   
                   
               <?php
               }
            }
       }
       
       if($total == 0)
       {
           echo '<div class="esig-addons-achievement">
				<h2>'. __('Awesome! Looks like you have everything installed. Well done.','esig') .'</h2>
				<p><img src="'.ESIGN_ASSETS_DIR_URI . '/images/boss.svg" width="244" height="245"></p>
				<p><img src="'.ESIGN_ASSETS_DIR_URI . '/images/logo.png" width="243" height="55"></p>
				
			    </div>';
       }  
       
   }
   
   
 ?>
  
 <?php
 } // get-more tab end here 

 ?>
 </div>

 <div class="esig-addon-devbox" style="display:none;">

  <div class="esig-addons-wrap">
    <div class="progress-wrap">
      <div class="progress">
        <span class="countup"></span>
      </div>  
    </div>
  </div>
</div>