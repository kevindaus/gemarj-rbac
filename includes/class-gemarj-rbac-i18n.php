<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.linkedin.com/in/kevinflorenzdaus/
 * @since      1.0.0
 *
 * @package    Gemarj_Rbac
 * @subpackage Gemarj_Rbac/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Gemarj_Rbac
 * @subpackage Gemarj_Rbac/includes
 * @author     Kevin Florenz Daus <kevinflorenzdaus@gmail.com>
 */
class Gemarj_Rbac_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'gemarj-rbac',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
