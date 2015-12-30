<?php
/**
Plugin Name: Stencil: Smarty 2.x Implementation
Plugin URI: http://www.jipmoors.nl/stencil/
Description: Stencil Implementation. This addon enables the use of Smarty 2.x in your theme. This addon requires the plugin Stencil to be installed and activated.
Version: 1.0.0
Author: jipmoors
Author URI: http://www.jipmoors.nl
Text Domain: stencil
License: GPL2

Stencil - Smarty 2.x addon is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation, either version 2 of the
License, or any later version.

Stencil - Smarty 2.x addon is distributed in the hope that it will
be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
General Public License for more details.

You should have received a copy of the GNU General Public License
along with Stencil - Smarty 2.x addon.
If not, see http://www.gnu.org/licenses/gpl-2.0.html.
 *
 * @package Stencil
 * @subpackage Smarty2
 */

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

include( 'stencil-dependency.php' );

add_filter( 'stencil:register-engine', '__stencil_smarty2_register' );

/**
 * Register this implementation to Stencil
 *
 * @param array $engines Currently registered engines.
 *
 * @return array
 */
function __stencil_smarty2_register( $engines ) {
	$engine    = 'Smarty 2.x';
	$engines[] = $engine;

	add_action( 'stencil.activate-' . $engine, '__stencil_smarty2_activate' );

	return $engines;
}

/**
 * On activation, include engine file.
 */
function __stencil_smarty2_activate() {
	include_once( 'stencil-engine.php' );
}

/**
 * Load plugin textdomain
 */
add_action(
	'plugins_loaded',
	create_function(
		'',
		"load_plugin_textdomain( 'stencil', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );"
	)
);
