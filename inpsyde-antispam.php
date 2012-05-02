<?php 
/**
 * Plugin Name: Inpsyde Antispam
 * Plugin URI:  http://wordpress.org/extend/plugins/js-antispam/
 * Description: Simple Antispam honeypot solution.
 * Author:      Inpsyde GmbH
 * Author URI:  http://inpsyde.com
 * License:     GPLv3
 * License URI: license.txt
 * Version:     2.1.0
 * Text Domain: inps-antispam
 * Domain Path: /languages
 */

// check for right php version
$correct_php_version = version_compare( phpversion(), '5.3.0', '>=' );

if ( ! $correct_php_version ) {
	echo 'Inpsyde Inpsyde Multisite Feed Plugin requires <strong>PHP 5.3</strong> or higher.<br>';
	echo 'You are running PHP ' . phpversion();
	exit;
}

require_once( 'antispam.php' );
