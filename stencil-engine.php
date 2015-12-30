<?php
/**
 * Engine code
 *
 * @package Stencil
 * @subpackage Smarty2
 */

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( class_exists( 'Stencil_Implementation' ) ) :

	add_action( 'init', create_function( '', 'new Stencil_Smarty_2();' ) );

	/**
	 * Class StencilSmarty2
	 *
	 * Implementation of the "Smarty 2.x" templating engine
	 */
	class Stencil_Smarty_2 extends Stencil_Implementation {

		/**
		 * Initialize Smarty 2 and set default
		 */
		public function __construct() {
			parent::__construct();

			require_once( 'lib/Smarty/Smarty.class.php' );

			$this->engine               = new Smarty();
			$this->engine->template_dir = $this->template_path;
			$this->engine->compile_dir  = $this->compile_path;
			$this->engine->cache_dir    = $this->cache_path;

			/**
			 * For config see:
			 * http://www.smarty.net/docs/en/config.files.tpl
			 */

			 /*
			  * $this->engine->setConfigDir( $template_dir . 'configs/');
			  */

			// Add custom plugins to smarty (per template).
			$plugin_dir = apply_filters( 'smarty2-template-plugin-dir', 'smarty-plugins' );

			/**
			 * Add theme plugins & child-theme plugins
			 */
			if ( ! empty( $plugin_dir ) ) {
				$template_root = get_template_directory();
				$plugin_bases  = array( $template_root );

				$child_root = get_stylesheet_directory();
				if ( $child_root !== $template_root ) {
					$plugin_bases[] = $child_root;
				}

				foreach ( $plugin_bases as $plugin_base ) {
					$plugin_dir = implode( DIRECTORY_SEPARATOR, array( $plugin_base, $plugin_dir, '' ) );
					if ( is_dir( $plugin_dir ) ) {
						$this->engine->plugins_dir[] = $plugin_dir;
					}
				}
			}

			/**
			 * Caching - when and how?
			 * http://www.smarty.net/docsv2/en/caching.tpl
			 */

			$this->ready();
		}

		/**
		 * Sets the variable to value
		 *
		 * @param string $variable Variable to set.
		 * @param mixed  $value Value to apply.
		 *
		 * @return mixed|void
		 */
		function set( $variable, $value ) {
			if ( is_object( $value ) ) {
				$this->engine->assign_by_ref( $variable, $value );
			} else {
				$this->engine->assign( $variable, $value );
			}

			return $this->get( $variable );
		}

		/**
		 * Gets the value of variable
		 *
		 * @param string $variable Variable to read.
		 *
		 * @return mixed|string
		 */
		function get( $variable ) {
			return $this->engine->get_template_vars( $variable );
		}

		/**
		 * Fetches the Smarty compiled template
		 *
		 * @param string $template Template file to get.
		 *
		 * @return string
		 */
		public function fetch( $template ) {
			return $this->engine->fetch( $template );
		}
	}

endif;
