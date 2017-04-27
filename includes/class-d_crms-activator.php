<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.doddletech.com
 * @since      1.0.0
 *
 * @package    D_crms
 * @subpackage D_crms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    D_crms
 * @subpackage D_crms/includes
 * @author     Qamar <qamar065@gmail.com>
 */
class D_crms_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		

		if (! wp_next_scheduled ( 'rms_import' )) {

			

			wp_schedule_event( time(), 'weekly', 'rms_import' );


		}

		

	}	

}
