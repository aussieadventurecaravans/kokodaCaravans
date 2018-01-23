<?php
/**
 * Plugin Name: Store Locator Plus Legacy : Enhanced Search
 * Plugin URI: https://www.storelocatorplus.com/product/slp4-enhanced-search/
 * Description: A premium add-on pack for Store Locator Plus that adds enhanced search features to the plugin.
 * Author: Store Locator Plus
 * Author URI: https://www.storelocatorplus.com.com/
 * Requires at least: 3.8
 * Tested up to : 4.6.1
 * Version: 4.4
 *
 * Text Domain: csa-slp-es
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// No SLP? Get out...
//
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
if ( !function_exists('is_plugin_active') ||  !is_plugin_active( 'store-locator-le/store-locator-le.php')) {
    return;
}

// Make sure the class is only defined once.
//
if (!class_exists('SLPEnhancedSearch'   )) {
	require_once( WP_PLUGIN_DIR . '/store-locator-le/include/base_class.addon.php');

    /**
     * The Enhanced Search Add-On Pack for Store Locator Plus.
     *
     * @package StoreLocatorPlus\EnhancedSearch
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2012-2016 Charleston Software Associates, LLC
     */
    class SLPEnhancedSearch extends SLP_BaseClass_Addon {
        public  $options                = array(
            'address_autocomplete'          => 'none'       ,
	        'address_autocomplete_min'      => '3'          ,
            'address_placeholder'           => ''           ,
            'allow_addy_in_url'             => '0'          ,
            'city'                          => ''           ,
            'ignore_radius'                 => '0'          , // used only to read in old value from versions < 4.1.05
            'radius_behavior'               => 'always_use' ,
            'city_selector'                 => 'hidden'     ,
            'country'                       => ''           ,
            'country_selector'              => 'hidden'     ,
            'hide_address_entry'            => '0'          ,
            'hide_search_form'              => '0'          ,
            'initial_results_returned'      => '999'        ,
            'installed_version'             => ''           ,
            'name_placeholder'              => ''           ,
            'label_for_find_button'             => ''           , // default set in init_options
            'label_for_city_selector'       => ''           , // default set in init_options
            'label_for_country_selector'    => ''           , // default set in init_options
            'label_for_state_selector'      => ''           , // default set in init_options
            'search_box_title'              => ''           , // default set in init_options
            'search_by_name'                => '0'          ,
            'searchlayout'                  => ''           ,
            'searchnear'                    => 'world'      ,
            'selector_behavior'             => 'use_both'   ,
            'state'                         => ''           ,
            'state_selector'                => 'hidden'     ,
            'append_to_search'              => ''           ,
        );

        /**
         * Invoke the Enhanced Search plugin.
         *
         * @static
         * @return SLPEnhancedSearch
         */
        public static function init() {
            static $instance = false;
            if ( !$instance ) {
                load_plugin_textdomain( 'csa-slp-es', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
                $instance = new SLPEnhancedSearch(
	                array(
		                'version'               => '4.4',
		                'min_slp_version'       => '4.4',
		                'name'                  => __( 'Enhanced Search Legacy', 'csa-slp-es' )     ,
		                'option_name'           => 'csl-slplus-ES-options'                   ,
		                'file'                  => __FILE__                                  ,

		                'activation_class_name'     => 'SLPES_Activation'                    ,
		                'admin_class_name'          => 'SLPEnhancedSearch_Admin'             ,
		                'ajax_class_name'           => 'SLPEnhancedSearch_AJAX'              ,
		                'userinterface_class_name'  => 'SLPEnhancedSearch_UI'                ,
	                )

                );
            }
            return $instance;
        }

        function initialize() {
            parent::initialize();
            if ( is_admin() ) {
                add_filter( 'plugin_row_meta', array( $this, 'add_meta_links' ), 10, 2 );
            }
        }

        /**
         * Add meta links.
         *
         * @param string[] $links
         * @param string   $file
         *
         * @return string
         */
        function add_meta_links( $links, $file ) {
            if ( $file == $this->slug ) {
                $links[] = sprintf( __( 'Get more with the %s add on.' , 'csa-slp-es' ) , $this->slplus->get_product_url( 'slp-experience' )  );
            }
            return $links;
        }

        /**
         * Initialize the options properties from the WordPress database.
         *
         */
        function init_options() {

            // Gettext string defaults for our options.
            //
            $this->option_defaults['label_for_find_button'      ] = __( 'Find Locations'  , 'csa-slp-es' );
            $this->option_defaults['search_box_title'           ] = __( 'Find Our Locations', 'csa-slp-es' );
            $this->option_defaults['label_for_city_selector'    ] = __( 'City'              , 'csa-slp-es' );
            $this->option_defaults['label_for_country_selector' ] = __( 'Country'           , 'csa-slp-es' );
            $this->option_defaults['label_for_state_selector'   ] = __( 'State'             , 'csa-slp-es' );

            // Go set the options as saved previously, or initialize them to defaults.
            //
	        parent::init_options();

            // Reset an empty search layout.
            //
            if ( empty( $this->options['searchlayout'] ) ) {
                $this->options['searchlayout'] =  $this->slplus->defaults['searchlayout']; 
            }
        }

    }

    // Hook to invoke the plugin.
    //
    add_action('init'           ,array('SLPEnhancedSearch','init'               ));
}
