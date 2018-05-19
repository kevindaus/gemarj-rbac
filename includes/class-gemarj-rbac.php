<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.linkedin.com/in/kevinflorenzdaus/
 * @since      1.0.0
 *
 * @package    Gemarj_Rbac
 * @subpackage Gemarj_Rbac/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Gemarj_Rbac
 * @subpackage Gemarj_Rbac/includes
 * @author     Kevin Florenz Daus <kevinflorenzdaus@gmail.com>
 */
class Gemarj_Rbac {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Gemarj_Rbac_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;
	/**
	 *
	 * @var \gemarjRbac\helper\TemplateLoader
	 */
	private $template_loader;
	public $plugin_directory;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $args ) {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_directory = $args['plugin_directory'];
		$this->plugin_name      = 'gemarj-rbac';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->template_loader = \gemarjRbac\helper\TemplateLoader::getInstance();

		/*add menu link under Tools*/
		add_action( 'admin_menu', array( $this, 'add_menu_link_to_tools' ) );
		/*Add Main Menu*/
		add_action( 'admin_menu', array( $this, 'add_main_gemarj_menu' ) );
		/*add settings page link */
		add_action( 'admin_menu', array( $this, 'gemarj_rbac_settings_page' ) );
		/*delete role event handler*/
		add_action( 'admin_post_gemarj_delete_role', array( $this, 'delete_role_handler' ) );
		/*update or create role event handler*/
		add_action( 'admin_post_gemarj_update_role', array( $this, 'update_role_handler' ) );

		add_action( 'admin_notices', array( $this, 'gemarj_rbac_notice' ) );
		/* assign role */
		add_action( 'admin_post_gemarj_rbac_assign_role', array(
			$this,
			'gemarj_rbac_assign_role_assign_role_to_user'
		) );
		/*Update settings*/
		add_action( 'admin_post_gemarj_rbac_update_options', array( $this, 'gemarj_rbac_update_options_handle_post' ) );
		add_shortcode( 'gemarj-rbac', array( $this, 'register_shortcode' ) );

	}

	public function gemarj_rbac_notice() {
		$message = null;
		if ( isset( $_COOKIE['gemarj_message'] ) ) {
			$message = $_COOKIE['gemarj_message'];
			echo <<<EOL
<div class="notice notice-success is-dismissible">
	<p> $message </p>
</div>
EOL;
		}

	}

	public function update_role_handler() {
		$message = null;
		if ( is_admin() ) {
			if ( isset( $_POST['roleNameOrig'] ) && ! empty( $_POST['roleNameOrig'] ) ) {
				/*update the role*/
				global $wp_roles;
				$roleKey                 = sanitize_text_field( $_POST['roleNameOrig'] );
				$newRoleDisplay          = sanitize_text_field( $_POST['roleRawName'] );
				$roleObj                 = $wp_roles->get_role( $roleKey );
				$val                     = get_option( 'wp_user_roles' );
				$val[ $roleKey ]['name'] = $newRoleDisplay;
				update_option( 'wp_user_roles', $val );
				/*add cookie*/
				$tempContainer         = [];
				$oldRoleDisplayNameArr = explode( '_', $roleObj->name );
				foreach ( $oldRoleDisplayNameArr as $currentRole ) {
					$tempContainer[] = ucfirst( $currentRole );
				}
				$oldRoleDisplayName = implode( " ", $tempContainer );
				$message            = "Role {$oldRoleDisplayName} is now updated to {$newRoleDisplay}";
			} else {
				global $wp_roles;
				$subcriber   = $wp_roles->get_role( 'subscriber' );
				$rawRoleName = $_POST['roleRawName'];
				$newRole     = sanitize_text_field( $_POST['roleRawName'] );
				$newRole     = strtolower( $newRole );
				$newRole     = str_replace( " ", "_", $newRole );
				/*create role*/
				add_role( $newRole, $rawRoleName, $subcriber->capabilities );
				$message = "Role $rawRoleName added";
			}
			setcookie( 'gemarj_message', $message, time() + 3600, '/' );
		}
		wp_safe_redirect( site_url() . "/wp-admin/admin.php?page=gemarj_menu" );
	}

	public function delete_role_handler() {
		$nonce        = $_REQUEST['_wpnonce'];
		$message      = null;
		$nonceVerfied = wp_verify_nonce( $_POST['_wpnonce'], 'delete_role' );
		if ( $nonceVerfied && ( current_user_can( 'manage_roles' ) || is_admin() ) ) {
			$roleToRemove = $_POST['role_to_delete'];

			$oldRoleDisplayNameArr = explode( '_', $roleToRemove );
			foreach ( $oldRoleDisplayNameArr as $currentRole ) {
				$tempContainer[] = ucfirst( $currentRole );
			}
			$oldRoleDisplayName = implode( " ", $tempContainer );

			remove_role( $roleToRemove );
			$message = "Role {$oldRoleDisplayName} removed";
		}
		setcookie( 'gemarj_message', $message, time() + 3600, '/' );
		wp_safe_redirect( site_url() . "/wp-admin/admin.php?page=gemarj_menu" );
	}

	public function gemarj_rbac_update_options_handle_post() {
		$nonce        = $_REQUEST['_wpnonce'];
		$message      = null;
		$nonceVerfied = wp_verify_nonce( $_POST['_wpnonce'], 'gemarj_rbac_update_settings' );
		if ( $nonceVerfied && is_admin() ) {
			if ( isset( $_POST['custom_error_message'] ) ) {
				$newCustomErrorMessage = $_POST['custom_error_message'];
				update_option( 'gemarj-rbac-error-message', $newCustomErrorMessage );
			}
		}
		wp_safe_redirect( wp_get_referer() );
	}

	public function gemarj_rbac_settings_page() {
		add_submenu_page(
			'gemarj_menu',
			'Gemarj Settings Page',
			'Settings',
			'manage_options',
			'gemarj_settings_page',
			function () {
				$this->template_loader->loadTemplate( 'gemarj_rbac_settings_page' );
			}
		);
	}


	public function register_shortcode( $attributes, $content ) {
		$currentLoggedInUser   = wp_get_current_user();
		$allowerdToViewContent = false;
		if ( isset( $attributes['role'] ) && ! empty( $attributes['role'] ) ) {
			$roleInput = sanitize_text_field( $attributes['role'] );
			if ( strpos( $roleInput, "," ) !== false ) {
				/* multiple role */
				$roleInput = explode( ",", $roleInput );
				foreach ( $roleInput as $currentRoleInput ) {
					if ( in_array( $currentRoleInput, $currentLoggedInUser->roles ) ) {
						$allowerdToViewContent = true;
						break;
					}
				}
			} else {
				if ( in_array( $roleInput, $currentLoggedInUser->roles ) ) {
					$allowerdToViewContent = true;
				}
			}
			if ( isset( $attributes['capability'] ) && ! empty( $attributes['capability'] ) ) {
				$capabilityInput = sanitize_text_field( $attributes['capability'] );
				if ( strpos( $capabilityInput, "," ) !== false ) {
					/* multiple capability */
					$capabilityInput = explode( ",", $capabilityInput );
					foreach ( $capabilityInput as $currentCapability ) {
						if ( $currentLoggedInUser->has_cap( $currentCapability ) ) {
							$allowerdToViewContent = true;
							break;
						}
					}
				} else {
					/* single capability */
					$allowerdToViewContent = $currentLoggedInUser->has_cap( $capabilityInput );
				}
			}
		}
		/*if allowed to view*/
		if ( $allowerdToViewContent ) {
			return $content;
		} else {
			if ( isset( $attributes['errorMessage'] ) && ! empty( $attributes['errorMessage'] ) ) {
				return sanitize_text_field( $attributes['errorMessage'] );
			} else {
				$defaultErrorMessage = get_option( 'gemarj-rbac-error-message', 'You are not allowed to view this content' );

				return $defaultErrorMessage;
			}
		}
	}

	public function gemarj_rbac_assign_role_assign_role_to_user() {
		if ( is_admin() ) {
			global $wp_roles;
			$userId         = intval( $_POST['selectedUser'] );
			$currentUserObj = get_userdata( $userId );
			foreach ( $currentUserObj->roles as $temp ) {
				$currentUserObj->remove_role( $temp );
			}
			$assignedRole = $_POST['assignedRole'];
			// reset all assigned role to current user
			/*loop through newly assigned role , assign to current user*/
			foreach ( $assignedRole as $currentAssignedRole ) {
				$currentUserObj->add_role( $currentAssignedRole );
			}
			if ( count( $assignedRole ) > 0 ) {
				$roleNames            = implode( " , ", $assignedRole );
				$tempMessageContainer = sprintf( "<strong>%s</strong> now has the following role :  <strong>%s</strong> ", $currentUserObj->display_name, $roleNames );
				setcookie( 'gemarj_message', $tempMessageContainer, time() + 3600, '/' );
			}
			wp_safe_redirect( wp_get_referer() );
		} else {
			wp_safe_redirect( site_url() );
		}
	}

	public function add_main_gemarj_menu() {
		$allUsers          = get_users();
		$userCollectionArr = [];
		foreach ( $allUsers as $currentUser ) {
			unset( $currentUser->data );
			$userCollectionArr[ $currentUser->ID ] = $currentUser;
		}
		wp_enqueue_script( "multiple-select-script", $this->plugin_directory . 'public/multiple-select-master/multiple-select.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "jquery-cookie", '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_style( "multiple-select-style", $this->plugin_directory . 'public/multiple-select-master/multiple-select.css' );
		wp_localize_script( "multiple-select-script", "AllUsers", $userCollectionArr );
		add_menu_page(
			'Gemarj RBAC',
			'Gemarj RBAC',
			'manage_options',
			'gemarj_menu',
			function () {
				$this->template_loader->loadTemplate( 'role_editor' );
				$this->template_loader->loadTemplate( 'gemarj-home-page' );
			}
		);
	}


	public function add_menu_link_to_tools() {
		add_submenu_page(
			'tools.php',
			'Gemarj RBAC',
			'Gemarj RBAC',
			'manage_options',
			'tools.php?page=gemarj-rbac-intro',
			function () {
				$this->template_loader->loadTemplate( 'tools-page' );
			}
		);
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Gemarj_Rbac_Loader. Orchestrates the hooks of the plugin.
	 * - Gemarj_Rbac_i18n. Defines internationalization functionality.
	 * - Gemarj_Rbac_Admin. Defines all hooks for the admin area.
	 * - Gemarj_Rbac_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gemarj-rbac-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gemarj-rbac-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gemarj-rbac-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-gemarj-rbac-public.php';

		$this->loader = new Gemarj_Rbac_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Gemarj_Rbac_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Gemarj_Rbac_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Gemarj_Rbac_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Gemarj_Rbac_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Gemarj_Rbac_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
