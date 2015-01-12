<?php
/**
 * @package   	      WP E-Signature Save As PDF
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Save As PDF
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       This add-on gives you the ability to add a "Save Document" button to your signed documents which generates a downloadable PDF of your document.
 * Version:           1.1.7
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-pdf
 * Domain Path:       /languages
 * License/Terms & Conditions: http://www.approveme.me/terms-conditions/
 * Privacy Policy: http://www.approveme.me/privacy-policy/
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! class_exists( 'Esig_AddOn_Updater' ) ) 
                include( dirname( __FILE__ ) . '/includes/class-addon-updater.php' );
                
$esig_updater = new Esig_AddOn_Updater('Save As PDF','2504',__FILE__,'1.1.7');


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-pdf.php' );

require_once ( plugin_dir_path( __FILE__ ). 'mpdf/mpdf.php');

if(!function_exists('sigJsonToImage'))
		//require_once ( plugin_dir_path( __FILE__ ). 'includes/signature-to-image.php');

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'ESIG_PDF', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_PDF', 'deactivate' ) );

//add_action( 'plugins_loaded', array( 'ESIG_PDF', 'get_instance' ) );


//if (is_admin()) {
     
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-pdf-admin.php' );
	add_action( 'plugins_loaded', array( 'ESIG_PDF_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_pdf_load_textdomain() {
    
  load_plugin_textdomain('esig-pdf', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_pdf_load_textdomain');


