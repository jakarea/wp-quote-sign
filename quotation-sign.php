<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://giopio.com
 * @since             1.0.0
 * @package           Quotation Sign
 *
 * @wordpress-plugin
 * Plugin Name:       Quotation Sign
 * Plugin URI:        https://github.com/jakarea/wp-quote-sign.git
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.2
 * Author:            GioPio
 * Author URI:        https://giopio.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quotation-sign
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'Quotation_sign_Quotation_sign', 'quotation-sign' );
define( 'Quotation_sign_VERSION', '1.0.1' );
define( 'Quotation_sign_URL', plugin_dir_url( __FILE__ ) );
define( 'Quotation_sign_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Store plugin base dir, for easier access later from other classes.
 * (eg. Include, pubic or admin)
 */
define( 'Quotation_sign_BASE_DIR', plugin_dir_path( __FILE__ ) );

/********************************************
* RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE
*
* @tutorial run_code_on_plugin_upgrade_and_admin_notice.php
*/
define( 'Quotation_sign_BASE_NAME', plugin_basename( __FILE__ ) );
// RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE

/**
 * Initialize custom templater
 */
if( ! class_exists( 'Exopite_Template' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/libraries/class-exopite-template.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-quotation-sign-activator.php
 */
function activate_quotation_sign() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quotation-sign-activator.php';
	Quotation_sign_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-quotation-sign-deactivator.php
 */
function deactivate_quotation_sign() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quotation-sign-deactivator.php';
	Quotation_sign_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_quotation_sign' );
register_deactivation_hook( __FILE__, 'deactivate_quotation_sign' );

/*****************************************
 * CUSTOM UPDATER FOR PLUGIN
 * @tutorial custom_updater_for_plugin.php
 */
if ( is_admin() ) {

	/**
	 * A custom update checker for WordPress plugins.
	 *
	 * How to use:
	 * - Copy vendor/plugin-update-checker to your plugin OR
	 *   Download https://github.com/YahnisElsts/plugin-update-checker to the folder
	 * - Create a subdomain or a folder for the update server eg. https://updates.example.net
	 *   Download https://github.com/YahnisElsts/wp-update-server and copy to the subdomain or folder
	 * - Add plguin zip to the 'packages' folder
	 *
	 * Useful if you don't want to host your project
	 * in the official WP repository, but would still like it to support automatic updates.
	 * Despite the name, it also works with themes.
	 *
	 * @link http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
	 * @link https://github.com/YahnisElsts/plugin-update-checker
	 * @link https://github.com/YahnisElsts/wp-update-server
	 */
	if( ! class_exists( 'Puc_v4_Factory' ) ) {

		require_once join( DIRECTORY_SEPARATOR, array( Quotation_sign_BASE_DIR, 'vendor', 'plugin-update-checker', 'plugin-update-checker.php' ) );

	}

	$qoutation_sign_updater = Puc_v4_Factory::buildUpdateChecker(
		// CHANGE THIS FOR YOUR UPDATE URL
		'https://github.com/jakarea/wp-quote-sign/', //Metadata URL.
		__FILE__, //Full path to the main plugin file.
		'wp-quote-sign' //Plugin slug. Usually it's the same as the name of the directory.
	);

	// set the branch that contains the stable release.
	$qoutation_sign_updater->setBranch( 'master' );

	/**
	 * add plugin upgrade notification
	 * https://andidittrich.de/2015/05/howto-upgrade-notice-for-wordpress-plugins.html
	 */
	add_action( 'in_plugin_update_message-' . Quotation_sign_Quotation_sign . '/' . Quotation_sign_Quotation_sign .'.php', 'quotation_sign_show_upgrade_notification', 10, 2 );
	function quotation_sign_show_upgrade_notification( $current_plugin_metadata, $new_plugin_metadata ) {

		/**
		 * Check "upgrade_notice" in readme.txt.
		 *
		 * Eg.:
		 * == Upgrade Notice ==
		 * = 20180624 = <- new version
		 * Notice		<- message
		 *
		 */
		if ( isset( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) ) > 0 ) {

			// Display "upgrade_notice".
			echo sprintf( '<span style="background-color:#d54e21;padding:10px;color:#f9f9f9;margin-top:10px;display:block;"><strong>%1$s: </strong>%2$s</span>', esc_attr( 'Important Upgrade Notice', 'exopite-multifilter' ), esc_html( rtrim( $new_plugin_metadata->upgrade_notice ) ) );

		}
	}


}
// END CUSTOM UPDATER FOR PLUGIN

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-quotation-sign.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
// function run_quotation_sign() {

// 	$plugin = new Quotation_sign();
// 	$plugin->run();

// }
// run_quotation_sign();

/********************************************
 * THIS ALLOW YOU TO ACCESS YOUR PLUGIN CLASS
 * eg. in your template/outside of the plugin.
 *
 * Of course you do not need to use a global,
 * you could wrap it in singleton too,
 * or you can store it in a static class,
 * etc...
 *
 * @tutorial access_plugin_and_its_methodes_later_from_outside_of_plugin.php
 */
global $pbt_prefix_quotation_sign;
$pbt_prefix_quotation_sign = new Quotation_sign();
$pbt_prefix_quotation_sign->run();
// END THIS ALLOW YOU TO ACCESS YOUR PLUGIN CLASS
