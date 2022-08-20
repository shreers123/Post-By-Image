<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://in.linkedin.com/in/manoj-kumar-kumawat-6bb66117a
 * @since      1.0.0
 *
 * @package    Post_By_Image
 * @subpackage Post_By_Image/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Post_By_Image
 * @subpackage Post_By_Image/includes
 * @author     Manoj Kumawat <manoj@racksoftwares.com>
 */
class Post_By_Image_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'post-by-image',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
