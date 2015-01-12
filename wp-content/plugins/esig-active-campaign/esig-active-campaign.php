<?php
/**
 * @package   	      WP E-Signature - ActiveCampaign
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - ActiveCampaign
 * Plugin URI:        http://www.approveme.me/wp-digital-e-signature
 * Description:       This add-on automatically subscribes (and tags) your signers to this powerful email marketing software which lets your create custom email sequences for your signers.
 * Version:           1.1.4
 * Author:            Approve Me
 * AuthorURI:        http://approveme.me/
 * TextDomain:       esig-active
 * DomainPath:       /languages
 * SettingPage:      admin.php?page=esign-misc-general
 * License/TermsandConditions: http://www.approveme.me/terms-conditions/
 * PrivacyPolicy: http://www.approveme.me/privacy-policy/
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Esig_AddOn_Updater' ) ) 
                include( dirname( __FILE__ ) . '/includes/class-addon-updater.php' );
                
    $esig_updater = new Esig_AddOn_Updater('Active Campaign','3491',__FILE__,'1.1.4');



/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-active-campaign.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_ACTIVE_CAMPAIGN', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_ACTIVE_CAMPAIGN', 'deactivate' ) );


//if (is_admin()) {
     
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-active-campaign-admin.php' );
	add_action( 'plugins_loaded', array( 'ESIG_ACTIVE_CAMPAIGN_Admin', 'get_instance' ) );

//}


/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function ActiveCampaign_load_textdomain() {
    
  load_plugin_textdomain('esig-active', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'ActiveCampaign_load_textdomain' );
