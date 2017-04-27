<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.doddletech.com
 * @since             1.0.0
 * @package           D_crms
 *
 * @wordpress-plugin
 * Plugin Name:       Current RMS Integration
 * Plugin URI:        http://www.doddletech.com
 * Description:       Import from Current RMS(Rental Management System) to Wordpress.
 * Version:           1.0.0
 * Author:            Qamar Azam
 * Author URI:        http://www.doddletech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       d_crms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-d_crms-activator.php
 */
function activate_d_crms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-d_crms-activator.php';
	D_crms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-d_crms-deactivator.php
 */
function deactivate_d_crms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-d_crms-deactivator.php';
	D_crms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_d_crms' );
register_deactivation_hook( __FILE__, 'deactivate_d_crms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-d_crms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_d_crms() {

	$plugin = new D_crms();
	$plugin->run();

}
run_d_crms();
