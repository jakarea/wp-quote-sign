<?php

/**
 * Fired during plugin deactivation
 *
 * @link              https://giopio.com
 * @since             1.0.0
 *
 * @package     Quotation Sign
 * @subpackage  Quotation Sign/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Quotation Sign
 * @subpackage Quotation Sign/includes
 * @author     Md Arif <arifypp@gmail.com>
 */
class Quotation_sign_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        /**
         * This only required if custom post type has rewrite!
         */
        flush_rewrite_rules();

	}

}
