<?php
/**
 * Determine if Stencil is installed and register plugin if possible
 *
 * @package Stencil
 * @subpackage Smarty2
 */

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( defined( 'STENCIL_PATH' ) ) {
	$stencil_root = STENCIL_PATH;
} else {
	// Allow for fork of stencil to have different plugin base name.
	$stencil_plugin_slug = apply_filters( 'stencil:plugin_slug', 'stencil' );
	$stencil_root        = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $stencil_plugin_slug;
}

$register = $stencil_root . DIRECTORY_SEPARATOR . 'register.php';

// Stencil can be found: register plugin.
if ( is_file( $register ) ) {

	include $register;

} else {

	if ( ! is_admin() ) {
		return;
	}

	if ( ! function_exists( '__stencil_not_installed' ) ) {
		/**
		 * Notify the admin user that the Stencil plugin is required
		 */
		function __stencil_not_installed() {
			$message = sprintf(
				__( 'implementation: Required plugin "<a href="%s" target="_blank">Stencil</a>" is not installed.', 'stencil' ),
				'https://wordpress.org/plugins/stencil/'
			);

			echo '<div class="error"><p>Stencil ' . $message . '</p></div>';
		}

		add_action( 'admin_notices', '__stencil_not_installed' );
	}
}
