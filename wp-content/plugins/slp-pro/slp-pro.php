<?php
/**
 * Plugin Name: Store Locator Plus :: Pro Pack
 * Plugin URI: https://www.storelocatorplus.com/product/slp4-pro/
 * Description: A premium add-on pack for Store Locator Plus that provides more admin power tools for wrangling locations.
 * Author: Store Locator Plus
 * Author URI: https://www.storelocatorplus.com/
 * Requires at least: 4.0
 * Tested up to : 4.6.1
 * Version: 4.6.4
 *
 * Text Domain: slp-pro
 * Domain Path: /languages/
 */

// Exit if access directly, dang hackers
if (!defined('ABSPATH')) {
	exit;
}

// Since this is an add-on for Store Locator Plus
// we only want to "get started" after all plugins are loaded.
//
// This allows us to check our dependencies knowing SLP
// should be loaded into the PHP stack by now.
//
// Concept Credits: @pippinsplugins, @ipstenu
// @see https://pippinsplugins.com/checking-dependent-plugin-active/
// @see https://make.wordpress.org/plugins/2015/06/05/policy-on-php-versions/
//
function SLPPro_loader() {
	$this_plugin_name = __( 'Store Locator Plus Pro' , 'slp-pro' );
	$min_wp_version   = '4.0';


    // Requires SLP
    //
    if ( ! defined( 'SLPLUS_PLUGINDIR' ) ) {
        add_action(
            'admin_notices',
            create_function(
                '',
                "echo '<div class=\"error\"><p>".
                sprintf(
                    __( '%s requires Store Locator Plus to function properly. ' , 'slp-pro' ) ,
                    $this_plugin_name
                ).'<br/>'.
                __( 'This plugin has been deactivated.'                         , 'slp-pro' ) .
                __( 'Please install Store Locator Plus.'                        , 'slp-pro' ) .
                "</p></div>';"
            )
        );
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        deactivate_plugins( plugin_basename( __FILE__ ) );
        return;
    }

	// Check minimum WP version
	//
	global $wp_version;
	if ( version_compare( $wp_version , $min_wp_version , '<' ) ) {
		add_action(
			'admin_notices',
			create_function(
				'',
				"echo '<div class=\"error\"><p>".
				sprintf(
					__( '%s requires WordPress %s to function properly. ' , 'slp-pro' ) ,
					$this_plugin_name,
					$min_wp_version
				).
				__( 'This plugin has been deactivated.'                                           , 'slp-pro' ) .
				__( 'Please upgrade WordPress.'                                                   , 'slp-pro' ) .
				"</p></div>';"
			)
		);
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		return;
	}

	// Define some path constants in an attempt to bypass depth-driven confusion.
	//
	if ( ! defined( 'SLPPRO_REL_DIR'  ) ) { define( 'SLPPRO_REL_DIR'    , plugin_basename( dirname( __FILE__ ) ) ); }  // Relative directory for this plugin in relation to wp-content/plugins
	if ( ! defined( 'SLPPRO_FILE'     ) ) { define( 'SLPPRO_FILE'       ,  __FILE__                              ); }  // FQ File name for this file.

	// Go forth and sprout your tentacles...
	// Get some Store Locator Plus sauce.
	//
	require_once( 'include/class.slp-pro.php' );
    SLPPro::init();
    add_action('cron_csv_import' , array( 'SLPPro' , 'process_cron_job') , 10 , 2 );
}

add_action('plugins_loaded', 'SLPPro_loader');
