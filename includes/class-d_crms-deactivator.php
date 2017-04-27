<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.doddletech.com
 * @since      1.0.0
 *
 * @package    D_crms
 * @subpackage D_crms/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    D_crms
 * @subpackage D_crms/includes
 * @author     Qamar <qamar065@gmail.com>
 */
class D_crms_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'rms_import' );
	}

}
