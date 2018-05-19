<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 5/18/2018
 * Time: 3:20 AM
 */

namespace gemarjRbac\helper;


class TemplateLoader {

	private static $PLUGIN_DIRECTORY;
	private static $TEMPLATE_DIRECTORY;
	private static $instance = null;

	public static function initialize($plugin_directory) {
		if (is_null(self::$instance)) {
			self::$instance = new TemplateLoader();
			self::$PLUGIN_DIRECTORY = $plugin_directory;
			self::$TEMPLATE_DIRECTORY = trailingslashit(realpath($plugin_directory . '/includes/templates/'));
		}
	}

	/**
	 * @return TemplateLoader
	 * @throws \Exception
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			throw new \Exception( "You forgot to initialize the singleton object" );
		}
		return self::$instance;
	}


	/**
	 * @param $templateFile
	 * @param bool $return_value
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function loadTemplate( $templateFile, $return_value = false ) {
		$fileToLoad = self::$TEMPLATE_DIRECTORY . "{$templateFile}.php";
		if ( file_exists( $fileToLoad ) ) {
			if ( ! $return_value ) {
				require $fileToLoad;
			} else {
				ob_start();
				require $fileToLoad;
				$retVal = ob_get_clean();

				return $retVal;
			}
		} else {
			throw new \Exception( "File template {$templateFile}.php doesn't exists" );
		}
	}
}