<?php
/**
 * Plugin Name: RW WPForms Entries Pro
 * Description: Save the data submitted by WPForms Lite to a custom table and view it in the background.
 * Version: 1.0.0
 * Author: RobertWP (Robert South)
 * Author URI: https://robertwp.com
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: rw-postviewstats-pro
 * Domain Path: /languages
 */

namespace RobertWP\WPFormsEntriesPro;

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RWWEP_PLUGIN_NAME', 'RW WPForms Entries Pro' );
define( 'RW_POSTVIEWSTATS_PRO', true );
define( 'RWWEP_VERSION_OPTION', 'rwwep_version' );
define( 'RWWEP_PLUGIN_VERSION', '1.0.0' );
define( 'RWWEP_PLUGIN_FILE', __FILE__ );
define( 'RWWEP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RWWEP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RWWEP_ASSETS_URL', RWWEP_PLUGIN_URL . 'assets/' );

require_once RWWEP_PLUGIN_DIR . 'includes/core/plugin.php';


