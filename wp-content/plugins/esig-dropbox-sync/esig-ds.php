<?php
/**
 * @package   	      WP E-Signature - Dropbox Sync
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Dropbox Sync
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       This powerful add-on generates in real-time a PDF of your signed document and automatically (some might say magically) syncs the signed document with your Dropbox account.
 * Version:           1.1.4
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-ds
 * Domain Path:       /languages
 * License/Terms & Conditions: http://www.approveme.me/terms-conditions/
 * Privacy Policy: http://www.approveme.me/privacy-policy/
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!defined('ESIG_DROPBOX_LABEL')) define('ESIG_DROPBOX_LABEL', 'ESIG_Dropbox');
if (!defined('ESIG_DROPBOX_SYNC_VERSION')) define('ESIG_DROPBOX_SYNC_VERSION', '1.0.0');
if (!defined('ESIG_DROPBOX_SYNC_DATABASE_VERSION')) define('ESIG_DROPBOX_SYNC_DATABASE_VERSION', '1.0.0');

if ( ! class_exists( 'Esig_AddOn_Updater' ) ) 
                include( dirname( __FILE__ ) . '/includes/class-addon-updater.php' );
                
    $esig_updater = new Esig_AddOn_Updater('Dropbox Sync','69',__FILE__,'1.1.4');


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-ds.php' );

if(!function_exists('sigJsonToImage'))
			require_once ( plugin_dir_path( __FILE__ ). 'includes/signature-to-image.php');

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_DS', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_DS', 'deactivate' ) );


//if (is_admin()) {
     
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-ds-admin.php' );
	add_action( 'plugins_loaded', array( 'ESIG_DS_Admin', 'get_instance' ) );

//}

if (function_exists('spl_autoload_register')) {
	spl_autoload_register('esig_dropbox_sync_autoload');
} else {
	require_once 'dropbox/Dropbox/API.php';
	require_once 'dropbox/Dropbox/OAuth/Consumer/ConsumerAbstract.php';
	require_once 'dropbox/Dropbox/OAuth/Consumer/Curl.php';
	require_once 'includes/DropboxFacade.php';
	require_once 'includes/Config.php';
	require_once 'includes/Factory.php';
}

/*
	*  loading class autoload 
	*   since 1.0.0
	*/
function esig_dropbox_sync_autoload($className)
{
	$fileName = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	if (preg_match('/^ESIGDS/', $fileName)) {
		$fileName = 'includes' . str_replace('ESIGDS', '', $fileName);
	} elseif (preg_match('/^Dropbox/', $fileName)) {
		$fileName = 'Dropbox' . DIRECTORY_SEPARATOR . $fileName;
	} else {
		return false;
	}

	$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName;

	if (file_exists($path)) {
		require_once $path;
	}
}

function esigds_get_custom_menu_page()
{
	return admin_url('admin.php?page=esign-misc-general');
}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_ds_load_textdomain() {
    
  load_plugin_textdomain('esig-ds', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_ds_load_textdomain');
