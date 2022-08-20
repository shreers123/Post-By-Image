<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://in.linkedin.com/in/manoj-kumar-kumawat-6bb66117a
 * @since             1.0.0
 * @package           Post_By_Image
 *
 * @wordpress-plugin
 * Plugin Name:       Post By Image
 * Plugin URI:        http://racksoftwares.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Manoj Kumawat
 * Author URI:        https://in.linkedin.com/in/manoj-kumar-kumawat-6bb66117a
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-by-image
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'POST_BY_IMAGE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post-by-image-activator.php
 */
function activate_post_by_image() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-by-image-activator.php';
	Post_By_Image_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post-by-image-deactivator.php
 */
function deactivate_post_by_image() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-by-image-deactivator.php';
	Post_By_Image_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_post_by_image' );
register_deactivation_hook( __FILE__, 'deactivate_post_by_image' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-post-by-image.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_post_by_image() {

	$plugin = new Post_By_Image();
	$plugin->run();

}
run_post_by_image();
