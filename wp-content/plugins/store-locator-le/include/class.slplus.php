<?php

if ( ! class_exists( 'SLPlus' ) ) {
	require_once( SLPLUS_PLUGINDIR . 'include/base_class.object.php' );
	require_once( SLPLUS_PLUGINDIR . 'include/base/SLP_Object_With_Objects.php' );

	/**
	 * The base plugin class for Store Locator Plus.
	 *
	 * @package   StoreLocatorPlus
	 * @author    Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2012-2016 Charleston Software Associates, LLC
	 *
	 * @property        SLP_Activation           $Activation
	 * @property        SLPlus_AddOn_Manager        $add_ons                        The add on manager handles add on connections and data.
	 * @property        string                      $admin_page_prefix              Admin page prefix, needs to be changed if the
	 * @property        SLPlus_AdminUI              $AdminUI                        The Admin UI object.
	 * @property        SLP_AJAX                    $AjaxHandler                    The Ajax Handler object.
	 * @property        SLP_Country_Manager         $CountryManager
	 * @property        SLP_BaseClass_Addon         $current_addon                  The current addon being processed.
	 * @property        string                      $current_admin_page
	 * @property        SLPlus_Location             $currentLocation                The current location.
	 * @property        array                       $data                           The settings that impact how the plugin renders.   TODO: Elminate all references to this ASAP.
	 *                      These elements are ONLY WHEN wpCSL.helper.loadPluginData() is called.
	 *                      loadPluginData via 'getoption' always reads a single entry in the wp_options table
	 *                      loadPluginData via 'getitem' checks the settings RAM cache first, then loads single entries from wp_options
	 *                      BOTH are horrible ideas.  Serialized data is far better.
	 * @property        SLPlus_Data                 $database                       The data interface helper.
	 * @property        wpdb                        $db                             The global $wpdb object for WordPress.
	 * @property        string                      $dir                            Full path to this plugin directory.
	 * @property        SLP_Helper                  $helper
	 * @property        array                       $infoFetched                    Array of slugs + booleans for plugins we've already fetched info for. named array, key = slug, value = true
	 * @property        string                      $installed_version              The version that was installed at the start of the plugin (prior installed version).
	 * @property        boolean                     $javascript_is_forced           Quick reference for the Force Load JavaScript setting.
	 * @property        SLP_Location_Manager        $location_manager
	 * @property        string                      $name                           TODO: deprecate when no longer referenced use SLPLUS_NAME constant, but may need to exists if we "eat our own food" extending base classes.
	 * @property        array                       $options_default                The default options (before being read from DB)
	 * @property        SLP_Option_Manager          $option_manager                 Manage the SLP options.
	 * @property        array                       $options_nojs                   The options that the user has set for Store Locator Plus that are NOT localized to the slp.js script.
	 * @property        array                       $options_nojs_default           The default options_nojs (before being read from DB).
	 * @property        string                      $plugin_url                     The URL that reaches the home directory for the plugin.
	 * @property-read   SLP_REST_Handler            $rest_handler                   The WP REST API handler.
	 * @property        bool                        $shortcode_was_rendered
	 * @property        boolean                     $site_architect_active          Is Site Architect active?
	 * @property        boolean                     $slider_rendered                True if slider was rendered, preventing multiple inline CSS calls.
	 * @property        array                       $slp_items                      Holder for get_item call stored values.   TODO: remove this when all get_item() calls reference options or options_nojs (EM, EXP, GFL, MM, SLP)
	 * @property        string                      $slp_store_url                  The SLP Store URL.
	 * @property        string                      $slug                           What slug do we go by?    May need to exists if we "eat our own food" extending base classes
	 * @property        SLP_Options                 $smart_options                  The Smart Options
	 * @property        string                      $styleHandle                    The style handle for CSS invocation.  Set to  'slp_admin_style'.
	 * @property        string                      $support_url                    The SLP Support Site URL.
	 * @property        wpCSL_notifications__slplus $notifications
	 * @property        string                      $prefix                         TODO: deprecate when all references use the SLPLUS_PREFIX constant
	 * @property        SLP_getitem                 $settings                       TODO: deprecate when (GFL) convert to slplus->get_item() , better yet replace with options/options_nojs.
	 * @property        string[]                    $text_string_options            These are the options needing translation.  TODO: migrate to text_manager
	 * @property        SLP_Text_Manager            $text_manager                   Manage the SLP text strings.
	 * @property        SLP_Plugin_Style_Manager    $themes
	 * @property        SLPlus_UI                   $UI
	 * @property        string                      $url                            Full URL to this plugin directory.
	 * @property        SLPlus_WPML                 $WPML
	 *
	 * @property        array                       $defaults
	 *                      Default settings for the plugin.
	 *
	 *                      These elements are LOADED EVERY TIME the plugin starts.
	 *
	 *                      o layout - default html structure for slplus shortcode page view
	 *                      o maplayout - default html and shortcodes for map bubbles
	 *                      o resultslayout -  default html and shortcodes for search results
	 *                      o theme - the default theme if not previously set (new installs)
	 *
	 *                      o searchlayout -  default html and shortcodes for the search form
	 *                          Use the slp_search_element shortcode processor to hook in add-on packs.
	 *                          Look for the attributes add_on and location="..." to place items.
	 *
	 *                          TODO: update this to use slp_addon_sections like results_layout.  Chagne PRO, ES, GFI&GFL to use the add_on location="..." processing.
	 *
	 *                      o bubblelayout - default html and shortcodes for map bubbles
	 *                            Bubble Layout shortcodes have the following format:
	 *                            [<shortcode> <attribute> <optional modifier> <optional modifier argument>]
	 *
	 *                            shortcode:
	 *                               slp_location = marker data elements like the location name or address elements
	 *
	 *                                   attribute = a location attribute
	 *                                       same marker fields as enhanced results
	 *                                       'url' is a special marker built from store pages and web options to link to a web page
	 *
	 *                                   modifier  = one of the following:
	 *                                       suffix = append the noted HTML or text after the location attribute is output according to the modifier argument:
	 *                                           br     = append '<br/>'
	 *                                           space  = append ' '
	 *                                           comma  = append ','
	 *
	 *                                       wrap   = wrap the location attribute in a special tag or text according to the modifier arguement:
	 *                                           img   = make the location attribute the source for an <img /> tag.
	 *
	 *                               slp_option   = slplus options from the option property below
	 *
	 *                                   attribute = an option key name
	 *
	 *                                   modifier  = one of the following :
	 *                                       ifset = output only if the noted location attribute is empty, the location attribute is specified in the modifier argument:
	 *                                           for example [slp_option label_phone ifset phone] outputs the label_phone option only if the location phone is not empty.     *
	 *
	 * @property        array                       $options                        The options that the user has set for Store Locator Plus that get localized to the slp.js script.
	 *                     Key is the name of a supported option, value is the default value.
	 *
	 *                     NOTE: Booleans must be set as a string '0' or '1'.
	 *                     That is how serialize/deserialize stores and pulls them from the DB.
	 *
	 *                     Anything stored in here also gets passed to the slp.js via the slplus.options object.
	 *                     Reference the settings in the slp.js via slplus.options.<key>
	 *
	 *                     These elements are LOADED EVERY TIME the plugin starts.
	 *
	 *                     TODO : Create a new MASTER options list called something like master_options.
	 *                     Master options is an array of options names with various properties.
	 *                     The init_options() call sets up those properties for things like:
	 *                         needs_translation
	 *                         javascript / nojavascript
	 *
	 *                     public $master_options = array(
	 *                           'label_email' => array(   'javascript' => false , 'translate' => true , default_value=> 'Email' ) ,
	 *                     );
	 *
	 */
	class SLPlus {
		const earth_radius_km  = 6371;         // Earth Volumetric Mean Radius (km)
		const earth_radius_mi  = 3959;         // Earth Volumetric Mean Radius (km)
		const locationPostType = 'store_page'; // Define the location post type.
		const locationTaxonomy = 'stores';     // Define the location post taxonomy.

		public $min_add_on_versions = array(
			'slp-premier'                     => '4.2',
			'slp-power'                       => '4.3',
			'slp-experience'                  => '4.6.2',

			'slp-contact-extender'            => '4.2.01',
			'slp-directory-builder'           => '4.2',
			'slp-enhanced-map'                => '4.3',
			'slp-enhanced-results'            => '4.4',
			'slp-enhanced-search'             => '4.4',
			'slp-janitor'                     => '4.6.5',
			'slp-pages'                       => '4.4.04',
			'slp-pro'                         => '4.5.07',
			'slp-tagalong'                    => '4.5.07',
			'slp-widgets'                     => '4.3',

			'slp-event-location-manager'      => '4.5.02',
			'slp-extended-data-manager'       => '4.5.01',
			'slp-gravity-forms-integration'   => '4.5.01',
			'slp-gravity-forms-location-free' => '4.5.03',
			'slp-multi-map'                   => '99.99.99',  // unsupported
			'slp-social-media-extender'       => '4.5.01',
			'slp-user-managed-locations'      => '4.5.03',
		);

		public $options = array(
			'bubblelayout'               => '',
			'ignore_radius'              => '0', // Passed in as form var from Enhanced Search
			'map_domain'                 => 'maps.google.com',
			'map_end_icon'               => '',
			'map_home_icon'              => '',
			'no_autozoom'                => '0',
			'no_homeicon_at_start'       => '1', // EM has admin UI for this setting.
			'radius_behavior'            => 'always_use',
			'results_layout'             => '', // set in local_script by UI.set_ResultsLayout
			'slplus_version'             => SLPLUS_VERSION,
			'use_sensor'                 => '0',
		);

		public $options_nojs = array(
			'admin_locations_per_page' => '10',
			'broadcast_timestamp'      => '0',
			'build_target'             => 'production',
			'default_country'          => 'us',
			'extended_data_tested'     => '0',
			'force_load_js'            => '0',
			'geocode_retries'          => '3',
			'google_client_id'         => '',
			'google_private_key'       => '',
			'http_timeout'             => '10', // HTTP timeout for GeoCode Requests (seconds)
			'map_language'             => 'en',
			'next_field_id'            => 1,
			'next_field_ported'        => '',
			'no_google_js'             => '0',
			'php_max_execution_time'   => '600',
			'premium_user_id'          => '',
			'premium_subscription_id'  => '',
			'retry_maximum_delay'      => '5.0',
			'slplus_plugindir'         => SLPLUS_PLUGINDIR,
			'slplus_basename'          => SLPLUS_BASENAME,
			'theme'                    => 'twentysixteen_rev05',
			'themes_last_updated'      => '0',
			'ui_jquery_version'        => 'WP',
		);

		public $defaults = array(
			'layout'        =>
				'<div id="sl_div">[slp_search][slp_map][slp_results]</div>',
			'bubblelayout'  =>
				'<div id="slp_info_bubble_[slp_location id]" class="slp_info_bubble [slp_location featured]">
    <span id="slp_bubble_name"><strong>[slp_location name  suffix  br]</strong></span>
    <span id="slp_bubble_address">[slp_location address       suffix  br]</span>
    <span id="slp_bubble_address2">[slp_location address2      suffix  br]</span>
    <span id="slp_bubble_city">[slp_location city          suffix  comma]</span>
    <span id="slp_bubble_state">[slp_location state suffix    space]</span>
    <span id="slp_bubble_zip">[slp_location zip suffix  br]</span>
    <span id="slp_bubble_country"><span id="slp_bubble_country">[slp_location country       suffix  br]</span></span>
    <span id="slp_bubble_directions">[html br ifset directions]
    [slp_option label_directions wrap directions]</span>
    <span id="slp_bubble_website">[html br ifset url][slp_location web_link][html br ifset url]</span>
    <span id="slp_bubble_email">[slp_location email         wrap    mailto ][slp_option label_email ifset email][html closing_anchor ifset email][html br ifset email]</span>
    <span id="slp_bubble_phone">[html br ifset phone]
    <span class="location_detail_label">[slp_option   label_phone   ifset   phone]</span>[slp_location phone         suffix    br]</span>
    <span id="slp_bubble_fax"><span class="location_detail_label">[slp_option   label_fax     ifset   fax  ]</span>[slp_location fax           suffix    br]<span>
    <span id="slp_bubble_description"><span id="slp_bubble_description">[html br ifset description]
    [slp_location description raw]</span>[html br ifset description]</span>
    <span id="slp_bubble_hours">[html br ifset hours]
    <span class="location_detail_label">[slp_option   label_hours   ifset   hours]</span>
    <span class="location_detail_hours">[slp_location hours         suffix    br]</span></span>
    <span id="slp_bubble_img">[html br ifset img]
    [slp_location image         wrap    img]</span>
    <span id="slp_tags">[slp_location tags]</span>
    </div>',
			'maplayout'     =>
				'[slp_mapcontent][slp_maptagline]',
			'resultslayout' =>
				'<div id="slp_results_[slp_location id]" class="results_entry location_primary [slp_location featured]">
        <div class="results_row_left_column"   id="slp_left_cell_[slp_location id]"   >
            [slp_addon section=primary position=first]
            <span class="location_name">[slp_location name] [slp_location uml_buttons] [slp_location gfi_buttons]</span>
            <span class="location_distance">[slp_location distance_1] [slp_location distance_unit]</span>
            [slp_addon section=primary position=last]
        </div>
        <div class="results_row_center_column location_secondary" id="slp_center_cell_[slp_location id]" >
            [slp_addon section=secondary position=first]
            <span class="slp_result_address slp_result_street">[slp_location address]</span>
            <span class="slp_result_address slp_result_street2">[slp_location address2]</span>
            <span class="slp_result_address slp_result_citystatezip">[slp_location city_state_zip]</span>
            <span class="slp_result_address slp_result_country">[slp_location country]</span>
            <span class="slp_result_address slp_result_phone">[slp_location phone]</span>
            <span class="slp_result_address slp_result_fax">[slp_location fax]</span>
            [slp_addon section=secondary position=last]            
        </div>
        <div class="results_row_right_column location_tertiary"  id="slp_right_cell_[slp_location id]"  >
            [slp_addon section=tertiary position=first]
            <span class="slp_result_contact slp_result_website">[slp_location web_link]</span>
            <span class="slp_result_contact slp_result_email">[slp_location email_link]</span>
            <span class="slp_result_contact slp_result_directions"><a href="https://[slp_option map_domain]/maps?saddr=[slp_location search_address]&daddr=[slp_location location_address]" target="_blank" class="storelocatorlink">[slp_location directions_text]</a></span>
            <span class="slp_result_contact slp_result_hours">[slp_location hours]</span>
            [slp_location pro_tags]
            [slp_location iconarray wrap="fullspan"]
            [slp_location eventiconarray wrap="fullspan"]
            [slp_location socialiconarray wrap="fullspan"]
            [slp_addon section=tertiary position=last]
            </div>
    </div>'
		,
			'searchlayout'  =>
				'<div id="address_search" class="slp search_box">
        [slp_search_element add_on location="very_top"]
        [slp_search_element input_with_label="name"]
        [slp_search_element input_with_label="address"]
        [slp_search_element dropdown_with_label="city"]
        [slp_search_element dropdown_with_label="state"]
        [slp_search_element dropdown_with_label="country"]
        [slp_search_element selector_with_label="tag"]
        [slp_search_element dropdown_with_label="category"]
        [slp_search_element dropdown_with_label="gfl_form_id"]
        [slp_search_element add_on location="before_radius_submit"]
        <div class="search_item">
            [slp_search_element dropdown_with_label="radius"]
            [slp_search_element button="submit"]
        </div>
        [slp_search_element add_on location="after_radius_submit"]
        [slp_search_element add_on location="very_bottom"]
    </div>'
		,
		);

		public  $Activation;
		public  $add_ons;
		public  $admin_page_prefix      = SLP_ADMIN_PAGEPRE;
		public  $AdminUI;
		public  $AjaxHandler;
		public  $CountryManager;
		public  $current_addon;
		public  $current_admin_page     = '';
		public  $currentLocation;
		public  $data                   = array();
		public  $database;
		public  $db;
		private $dir;
		public  $helper;
		public  $infoFetched            = array();
		public  $installed_version;
		public  $javascript_is_forced   = true;
		public  $location_manager;
		public  $name                   = SLPLUS_NAME;
		public  $network_multisite      = false;
		public  $notifications;
		public  $options_default        = array();
		public  $option_manager;
		public  $options_nojs_default   = array();
		public  $plugin_url             = SLPLUS_PLUGINURL;
		public  $prefix                 = SLPLUS_PREFIX;
		private $rest_handler;
		public  $settings;
		public  $shortcode_was_rendered = false;
		public  $site_architect_active  = false;
		public  $slider_rendered        = false;
		public  $slp_items              = array();
		public  $slp_store_url          = 'https://www.storelocatorplus.com/';
		public  $slug;
		public  $smart_options;
		public  $styleHandle            = 'slp_admin_style';
		public  $support_url            = 'https://docs.storelocatorplus.com';
		public  $text_manager;
		public  $themes;
		public  $UI;
		public  $url;
		public  $WPML;

		/**
		 * Initialize a new SLPlus Object
		 */
		public function __construct() {

			// Properties set via define or hard calculation.
			//
			$this->dir               = plugin_dir_path( SLPLUS_FILE );
			$this->slug              = plugin_basename( SLPLUS_FILE );
			$this->url               = plugins_url( '', SLPLUS_FILE );
			$this->installed_version = get_option( SLPLUS_PREFIX . "-installed_base_version", '' );     // Single Installs, or Multisite Per-Site Activation, on MS Network Activation returns version of MAIN INSTALL only

			// Properties Set By Methods
			//
			$this->current_admin_page = $this->get_admin_page();
		}

		/**
		 * Update the base plugin if necessary.
		 */
		function activate_or_update_slplus() {
			if ( version_compare( $this->installed_version, SLPLUS_VERSION, '<' ) ) {
				$this->createobject_Activation();
				$this->Activation->update();
				if ( $this->Activation->disabled_experience ) {
					add_action(
						'admin_notices', create_function(
							'', "echo '<div class=\"error\"><p>" .
							    __( 'You must upgrade Experience add-on to 4.4.03 or higher or your site will crash. ', 'store-locator-le' ) .
							    "</p></div>';"
						)
					);
				}
			}

			global $site_architect;
			$this->site_architect_active = is_a( $site_architect, 'Site_Architect' );
		}

		/**
		 * Add meta links.
		 *
		 * TODO: ADMIN ONLY
		 *
		 * @param string[] $links
		 * @param string   $file
		 *
		 * @return string
		 */
		function add_meta_links( $links, $file ) {
			if ( $file == SLPLUS_BASENAME ) {
				$links[] = '<a href="' . $this->support_url . '" title="' . __( 'Documentation', 'store-locator-le' ) . '">' .
				           __( 'Documentation', 'store-locator-le' ) . '</a>';
				$links[] = '<a href="' . $this->slp_store_url . '" title="' . __( 'Buy Upgrades', 'store-locator-le' ) . '">' .
				           __( 'Buy Upgrades', 'store-locator-le' ) . '</a>';
				$links[] = '<a href="' . admin_url( 'admin.php?page=slp_experience' ) . '" title="' .
				           __( 'Settings', 'store-locator-le' ) . '">' . __( 'Settings', 'store-locator-le' ) . '</a>';
			}

			return $links;
		}

		/**
		 * Setup WordPress action scripts.
		 *
		 * Note: admin_menu is not called on every admin page load
		 * Reference: http://codex.wordpress.org/Plugin_API/Action_Reference
		 */
		function add_actions() {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this->notifications, 'display' ) );
				add_filter( 'plugin_row_meta', array( $this, 'add_meta_links' ), 10, 2 );
			}
			add_action( 'plugins_loaded', array( $this, 'initialize_after_plugins_loaded' ) );
		}

		/**
		 * Create the notifications object and attach it.
		 *
		 */
		private function attach_notifications() {
			if ( ! isset( $this->notifications ) ) {
				require_once( 'manager/SLP_Notification_Manager.php' );
				$this->notifications = new SLP_Notification_Manager();
			}
		}

		/**
		 * Instantiate Activation Object
		 */
		public function createobject_Activation() {
			if ( ! isset( $this->Activation ) ) {
				require_once( 'SLP_Activation.php' );
				$this->Activation = new SLP_Activation();
			}
		}

		/**
		 * Create and attach the add on manager object.
		 */
		public function createobject_AddOnManager() {
			if ( ! isset( $this->add_ons ) ) {
				require_once( 'manager/SLPlus_AddOn_Manager.php' );
				$this->add_ons = new SLPlus_AddOn_Manager();
			}
		}

		/**
		 * Create the AJAX procssing object and attach to this->ajax
		 */
		function createobject_AJAX() {
			if ( ! isset( $this->ajax ) ) {
				require_once( 'class.ajax.php' );
				$this->ajax        = new SLP_AJAX();
				$this->AjaxHandler = $this->ajax;
			}
		}

		/**
		 * Create the Country Manager object.
		 */
		public function create_object_CountryManager() {
			if ( ! isset( $this->CountryManager ) ) {
				require_once( 'class.country.manager.php' );
				$this->CountryManager = new SLP_Country_Manager();
			}
		}

		/**
		 * Create the help object and attach it.
		 */
		function create_object_helper() {
			if ( ! isset( $this->helper ) ) {
				require_once( 'class.helper.php' );
				$this->helper = new SLP_Helper();
			}
		}

		/**
		 * Create the smart options.
		 */
		public function create_object_location_manager() {
			if ( ! isset( $this->location_manager ) ) {
				require_once( 'module/locations/SLP_Location_Manager.php' );
				$this->location_manager = new SLP_Location_Manager();
			}
		}

		/**
		 * Create the option manager.
		 */
		private function create_object_option_manager() {
			if ( ! isset( $this->option_manager ) ) {
				require_once( 'manager/SLP_Option_Manager.php' );
				$this->option_manager = new SLP_Option_Manager();
			}
		}

		/**
		 * Create the settings object and attach it.
		 *
		 * Apparently only used for the get_item method.
		 *
		 * TODO: deprecate when (GFL) convert to slplus->get_item() , better yet replace with options/options_nojs.
		 *
		 */
		function create_object_settings() {
			if ( ! isset( $this->settings ) ) {
				require_once( 'class.getitem.php' );
				$this->settings = new SLP_getitem();
			}
		}

		/**
		 * Create the smart options.
		 */
		private function create_object_smart_options() {
			if ( ! isset( $this->smart_options ) ) {
				require_once( 'module/options/SLP_Options.php' );
				$this->smart_options = new SLP_Options();
			}
		}

		/**
		 * Create the text manager.
		 */
		function create_object_text_manager() {
			if ( ! isset( $this->text_manager ) ) {
				require_once( 'manager/SLP_Text_Manager.php' );
				$this->text_manager = new SLP_Text_Manager();
			}
		}

		/**
		 * Create the WPML object.
		 */
		function create_object_WPML() {
			if ( ! isset( $this->WPML ) ) {
				require_once( 'class.wpml.php' );
				$this->WPML = new SLPlus_WPML();
			}
		}

		/**
		 * Return a deprecated notification.
		 *
		 * TODO : move to a deprecated class, invoke with attach_deprecated.
		 *
		 * @param string $function_name name of function that is deprecated.
		 *
		 * @return string
		 */
		public function createstring_Deprecated( $function_name ) {
			return
				sprintf(
					__( 'The %s method is no longer available. ', 'store-locator-le' ), $function_name
				) .
				'<br/>' .
				__( 'It is likely that one of your add-on packs is out of date. ', 'store-locator-le' ) .
				'<br/>' .
				__( 'You need to <a href="%s" target="csa">upgrade</a> to the latest %s compatible version.' , 'store-locator-le' )
				;
		}

		/**
		 * Get the specified web page HTML with embedded hyperlinks.
		 *
		 * @deprecated Use slplus->text_manager->get_web_link( <slug> )
		 *
		 * @param    string $slug Which web page link to fetch.
		 *
		 * @return    string
		 */
		function get_web_link( $slug ) {
			$this->create_object_text_manager();
			return $this->text_manager->get_web_link( $slug );
		}

		/**
		 * Finish our starting constructor elements.
		 */
		public function initialize() {
			if ( class_exists( 'SLPlus_Location' ) == false ) {
				require_once( 'class.location.php' );
			}
			$this->currentLocation = new SLPlus_Location( array( 'slplus' => $this ) );

			$this->create_object_option_manager();
			$this->create_object_smart_options();

			// Attach objects
			//
			$this->attach_notifications();
			$this->create_object_helper();
			$this->create_object_settings();  // TODO: deprecate when GFL calls slplus->get_item directly.

			// Setup pointers and WordPress connections
			//
			$this->add_actions();

			$this->create_object_database();

			// REST Processing
			// Needs to be loaded all the time.
			// The filter on REST_REQUEST true is after rest_api_init has been called.
			//
			$this->load_rest_handler();
		}

		/**
		 * Things to do after all plugins are loaded.
		 */
		public function initialize_after_plugins_loaded() {
			load_plugin_textdomain( 'store-locator-le', false, plugin_basename( dirname( SLPLUS_PLUGINDIR . 'store-locator-le.php' ) ) . '/languages' );

			$this->create_object_WPML();

			$this->smart_options->initialize_after_plugins_loaded();

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$this->createobject_AJAX();
			}
		}

		/**
		 * Setup the database properties.
		 *
		 * latlongRegex = '^\s*-?\d{1,3}\.\d+,\s*\d{1,3}\.\d+\s*$';
		 *
		 * @global wpdb $wpdb
		 */
		private function create_object_database() {
			global $wpdb;
			$this->db = $wpdb;
			require_once( SLPLUS_PLUGINDIR . 'include/class.data.php' );
			$this->database = new SLPlus_Data();
		}

		/**
		 * Enqueue the Google Maps Script
		 */
		public function enqueue_google_maps_script() {
			wp_enqueue_script( 'google_maps', $this->get_google_maps_url(), array(), SLPLUS_VERSION, ! $this->javascript_is_forced );
		}

		/**
		 * Get the current admin page.
		 *
		 * @return string
		 */
		private function get_admin_page() {
			if ( isset( $_GET['page'] ) ) {
				$plugin_page = stripslashes( $_GET['page'] );

				return plugin_basename( $plugin_page );
			}

			return '';
		}

		/**
		 * Get the Google Maps URL
		 */
		private function get_google_maps_url() {
			// Google Maps API for Work client ID
			//
			$client_id = ! empty( $this->options_nojs['google_client_id'] ) ?
				'&client=' . $this->options_nojs['google_client_id'] . '&v=3' :
				'';

			// Google JavaScript API server Key
			//
			$server_key = ! empty( $this->smart_options->google_server_key->value ) ?
				'&key=' . $this->smart_options->google_server_key->value :
				'';

			// Set the map language
			//
			$language = 'language=' . $this->options_nojs['map_language'];
			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				$lang_var = ICL_LANGUAGE_CODE;
				if ( ! empty( $lang_var ) ) {
					$language = 'language=' . ICL_LANGUAGE_CODE;
				}
			}

			// Base Google API URL
			//
			$google_api_url = 'https://maps.googleapis.com/maps/api/js?';

			// Region
			//
			$this->create_object_CountryManager();
			if ( isset( $this->CountryManager->countries[ $this->options_nojs['default_country'] ] ) ) {
				$country = strtoupper( $this->CountryManager->countries[ $this->options_nojs['default_country'] ]->cctld );
			} else {
				$country = '';
			}
			$region = ! empty( $country ) ? '&region=' . $country : '';

			return $google_api_url . $language . $region . $client_id . $server_key;
		}

		/**
		 * Manage the old settings->get_item() calls.
		 *
		 * TODO: remove this when all get_item() calls reference options or options_nojs (GFL, MM, PRO, SLP)
		 *
		 * @deprecated
		 *
		 * @param            $name
		 * @param null       $default
		 * @param string     $separator
		 *
		 * @return mixed|void
		 */
		function get_item( $name, $default = null, $separator = '-' ) {
			$option_name = SLPLUS_PREFIX . $separator . $name;
			if ( ! array_key_exists( $option_name, $this->slp_items ) ) {
				if ( is_null( $default ) ) {
					$default = false;
				}
				$this->slp_items[ $option_name ] = $this->option_manager->get_wp_option( $option_name, $default );
			}

			return $this->slp_items[ $option_name ];
		}

		/**
		 * Get the product URL from the add-on manager.
		 *
		 * Make sure the add-on manager is invoked first.
		 *
		 * @param $slug
		 *
		 * @return string
		 */
		function get_product_url( $slug ) {
			$this->createobject_AddOnManager();

			return $this->add_ons->get_product_url( $slug );
		}

		/**
		 * Return true if the named add-on pack is active.
		 *
		 * TODO: Legacy code, move to class.addon.manager when UML is updated.
		 *
		 * @param string $slug
		 *
		 * @return boolean
		 */
		public function is_AddonActive( $slug ) {
			$this->createobject_AddOnManager();

			return $this->add_ons->is_active( $slug );
		}

		/**
		 * Determine if any add-on packs are installed that require legacy support.
		 *
		 * @param       string $fq_method The specific method we are checking in ClassName::MethodName format.
		 *
		 * @returns     boolean                    Whether or not an add-on is in use that requires legacy support.
		 */
		public function needed_for_addon( $fq_method ) {
			$this->createobject_AddOnManager();

			$needs_legacy_support = $this->add_ons->is_legacy_needed_for( $fq_method );
			if ( $needs_legacy_support ) {
				$this->helper->add_wp_admin_notification(
					$this->add_ons->get_recommendations_text(), 'update-nag'
				);
			}

			return $needs_legacy_support;
		}

		/**
		 * Return '1' if the given value is set to 'true', 'on', or '1' (case insensitive).
		 * Return '0' otherwise.
		 *
		 * Useful for checkbox values that may be stored as 'on' or '1'.
		 *
		 * @param        $value
		 * @param string $return_type
		 *
		 * @return bool|string
		 */
		public function is_CheckTrue( $value, $return_type = 'boolean' ) {
			if ( $return_type === 'string' ) {
				$true_value  = '1';
				$false_value = '0';
			} else {
				$true_value  = true;
				$false_value = false;
			}

			// No arrays allowed.
			//
			if ( is_array( $value ) ) {
				error_log( __('Array provided to SLPlus::is_CheckTrue()' , 'store-locator-le' ) );
				error_log( print_r(debug_backtrace(), TRUE) );
				return $false_value;
			}

			if ( strcasecmp( $value, 'true' ) == 0 ) {
				return $true_value;
			}
			if ( strcasecmp( $value, 'on' ) == 0 ) {
				return $true_value;
			}
			if ( strcasecmp( $value, '1' ) == 0 ) {
				return $true_value;
			}
			if ( $value === 1 ) {
				return $true_value;
			}
			if ( $value === true ) {
				return $true_value;
			}

			return $false_value;
		}

		/**
		 * Check if certain safe mode restricted functions are available.
		 *
		 * exec, set_time_limit
		 *
		 * @param $funcname
		 *
		 * @return mixed
		 */
		public function is_func_available( $funcname ) {
			static $available = array();

			if ( ! isset( $available[ $funcname ] ) ) {
				$available[ $funcname ] = true;
				if ( ini_get( 'safe_mode' ) ) {
					$available[ $funcname ] = false;
				} else {
					$d = ini_get( 'disable_functions' );
					$s = ini_get( 'suhosin.executor.func.blacklist' );
					if ( "$d$s" ) {
						$array = preg_split( '/,\s*/', "$d,$s" );
						if ( in_array( $funcname, $array ) ) {
							$available[ $funcname ] = false;
						}
					}
				}
			}

			return $available[ $funcname ];
		}

		/**
		 * Checks if a URL is valid.
		 *
		 * @param $url
		 *
		 * @return bool
		 */
		public function is_valid_url( $url ) {
			$url = trim( $url );

			return ( ( strpos( $url, 'http://' ) === 0 || strpos( $url, 'https://' ) === 0 ) &&
			         filter_var( $url, FILTER_VALIDATE_URL ) !== false );
		}

		/**
		 * Load the rest handler.
		 *
		 * The REST_REQUEST define is not ready this soon, so we need to do this on ALL WP loads. <sadness>
		 */
		private function load_rest_handler() {
			if ( ! isset( $this->rest_handler ) ) {
				require_once( 'class.handler.rest.php' );
				$this->rest_handler = new SLP_REST_Handler();
			}
		}

		/**
		 * Re-center the map as needed.
		 *
		 * Sets Center Map At ('map-center') and Lat/Lng Fallback if any of those entries are blank.
		 *
		 * Uses the Map Domain ('default_country') as the source for the new center.
		 */
		public function recenter_map() {
			if ( empty( $this->smart_options->map_center->value ) ) {
				$this->set_map_center();
			}
			if ( empty( $this->smart_options->map_center_lat->value ) ) {
				$this->set_map_center_fallback( 'lat' );
			}
			if ( empty( $this->smart_options->map_center_lng->value ) ) {
				$this->set_map_center_fallback( 'lng' );
			}
		}

		/**
		 * Set the Center Map At if the setting is empty.
		 */
		public function set_map_center() {
			$this->create_object_CountryManager();
			$this->options['map_center'] = $this->CountryManager->countries[ $this->options_nojs['default_country'] ]->name;
		}

		/**
		 * Set the map center fallback for the selected country.
		 *
		 * @param string $for latlng | lat | lng
		 */
		private function set_map_center_fallback( $for = 'latlng' ) {
			$this->create_object_CountryManager();

			// If the map center is set to the country.
			//
			if ( $this->options['map_center'] == $this->CountryManager->countries[ $this->options_nojs['default_country'] ]->name ) {

				// Set the default country lat
				//
				if ( ( $for === 'latlng' ) || ( $for === 'lat' ) ) {
					$this->smart_options->map_center_lat->value = $this->CountryManager->countries[ $this->options_nojs['default_country'] ]->map_center_lat;
					$this->options['map_center_lat'] = $this->smart_options->map_center_lat->value;
				}

				// Set the default country lng
				//
				if ( ( $for === 'latlng' ) || ( $for === 'lng' ) ) {
					$this->smart_options->map_center_lng->value = $this->CountryManager->countries[ $this->options_nojs['default_country'] ]->map_center_lng;
					$this->options['map_center_lng'] = $this->smart_options->map_center_lng->value;
				}
			}

			// No Lat or Lng in Country Data?  Go ask Google.
			//
			if ( empty( $this->smart_options->map_center_lng->value ) || empty( $this->smart_options->map_center_lat->value ) ) {
				$json = $this->currentLocation->get_LatLong( $this->smart_options->map_center->value );
				if ( ! empty( $json ) ) {
					$json = json_decode( $json );
					if ( is_object( $json ) && ( $json->{'status'} === 'OK' ) ) {
						if ( empty( $this->smart_options->map_center_lat->value ) ) {
							$this->smart_options->map_center_lat->value = $json->results[0]->geometry->location->lat;
							$this->options['map_center_lat'] = $this->smart_options->map_center_lat->value;
						}
						if ( empty( $this->smart_options->map_center_lng->value ) ) {
							$this->smart_options->map_center_lng->value = $json->results[0]->geometry->location->lng;
							$this->options['map_center_lng'] = $this->smart_options->map_center_lng->value;
						}
					}
				}
			}
		}

		/**
		 * Set the PHP max execution time.
		 */
		public function set_php_timeout() {
			ini_set( 'max_execution_time', $this->options_nojs['php_max_execution_time'] );
			if ( $this->is_func_available( 'set_time_limit' ) ) {
				set_time_limit( $this->options_nojs['php_max_execution_time'] );
			}
		}

		/**
		 * Set valid options from the incoming REQUEST
		 *
		 * @param mixed  $val - the value of a form var
		 * @param string $key - the key for that form var
		 */
		function set_ValidOptions( $val, $key ) {
			$this->create_object_smart_options();
			$this->smart_options->set_valid_option( $val, $key, $this->options, $this->options_default );
		}

		/**
		 * Set valid options from the incoming REQUEST
		 *
		 * Set this if the incoming value is not an empty string.
		 *
		 * @param mixed  $val - the value of a form var
		 * @param string $key - the key for that form var
		 */
		function set_ValidOptionsNoJS( $val, $key ) {
			$this->create_object_smart_options();
			$this->smart_options->set_valid_option( $val, $key, $this->options_nojs, $this->options_nojs_default );
		}
	}

}