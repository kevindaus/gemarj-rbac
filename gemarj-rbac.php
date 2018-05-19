<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/kevinflorenzdaus/
 * @since             1.0.0
 * @package           Gemarj_Rbac
 *
 * @wordpress-plugin
 * Plugin Name:       Gemarj RBAC Content
 * Plugin URI:        https://github.com/kevindaus/gemarj-rbac
 * Description:       This plugin allows you to restrict the content shown base on the current user's role using a shortcode.
 * Version:           1.0.0
 * Author:            Kevin Florenz Daus
 * Author URI:        https://www.linkedin.com/in/kevinflorenzdaus/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gemarj-rbac
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Load classes
 */
require_once 'vendor/autoload.php';


/**
 * Initialize class settings
 */
\gemarjRbac\helper\TemplateLoader::initialize(plugin_dir_path( __FILE__ ));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEMARJ_RBAC_PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gemarj-rbac-activator.php
 */
function activate_gemarj_rbac() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gemarj-rbac-activator.php';
	Gemarj_Rbac_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gemarj-rbac-deactivator.php
 */
function deactivate_gemarj_rbac() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gemarj-rbac-deactivator.php';
	Gemarj_Rbac_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gemarj_rbac' );
register_deactivation_hook( __FILE__, 'deactivate_gemarj_rbac' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gemarj-rbac.php';

/**
 * Settings link for the plugin
 */
function plugin_add_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=gemarj_settings_page">' . __( 'Settings' ) . '</a>';
	array_push( $links, $settings_link );
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gemarj_rbac() {

	$plugin = new Gemarj_Rbac([
		'plugin_directory'=>plugin_dir_url( __FILE__ )
	]);
	$plugin->run();

}
run_gemarj_rbac();
