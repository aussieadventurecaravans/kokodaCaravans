<?php

if (!class_exists('SLPlus_AdminUI')) {

    /**
     * Store Locator Plus basic admin user interface.
     *
     * @package StoreLocatorPlus\AdminUI
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2012-2016 Charleston Software Associates, LLC
     *
     * @property-read   boolean     					$already_enqueue    True if admin stylesheet enqueued.
     * @property-read	SLP_Admin_Helper				$helper
     * @property-read   boolean     					$isOurAdminPage     True if we are on an admin page for the plugin.
     * @property       	string[]    					$admin_slugs        The registered admin page hooks for the plugin.
     * @property 		SLP_Admin_Experience 	        $Admin_Experience   Experience Tab Manager
     * @property-read 	SLP_Admin_General 	            $Admin_General      General Tab Manager
     * @property-read 	SLP_Admin_Info 			        $Admin_Info         Info Tab Manager
     * @property 		SLPlus_AdminUI_Locations 		$ManageLocations
     * @property 		SLPlus_AdminUI_UserExperience 	$MapSettings
     * @property 		string 							$styleHandle
     */
    class SLPlus_AdminUI extends SLP_Object_With_Objects {

	    protected $objects = array(
		    'Admin_Experience' => array(
			    'subdir' => 'include/module/admin_tabs/',
			    'object' => null,
		    ),
		    'Admin_General' => array(
			    'subdir' => 'include/module/admin_tabs/',
			    'object' => null,
		    ),
		    'Admin_Info' => array(
			    'subdir' => 'include/module/admin_tabs/',
			    'object' => null,
		    ),	    );

        private $already_enqueued = false;
        private $helper;
        public $Experience;
        private $is_our_admin_page = false;
        public $ManageLocations;
        public $MapSettings;
        public $slp_admin_slugs = array();
        public $styleHandle;

        /**
         * Add filters to save/restore important settings for the Janitor reset.
         */
        private function add_janitor_hooks() {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            if (!function_exists('is_plugin_active') || !is_plugin_active('slp-janitor/slp-janitor.php')) {
                return;
            }
            add_filter('slp_janitor_important_settings', array($this, 'set_janitor_important_fields'));
            add_action('slp_janitor_restore_important_setting', array($this, 'restore_janitor_important_fields'), 5, 2);
        }

	    /**
	     * Add a persistent admin notice.
	     */
        private function add_persistent_admin_notice() {
        	if (  $this->slplus->smart_options->admin_notice_dismissed->is_false )
	        	$text_message = $this->slplus->text_manager->get_web_link( 'persistent_notice' );
		        if ( ! empty( $text_message->sentence ) ) {
			        add_action( 'admin_notices', create_function( '', "echo '<div id=\"slp_persistent_notice\" class=\"notice notice-info is-dismissible\"><p>" . $this->slplus->text_manager->get_web_link( 'persistent_notice' ) . "</p></div>';" ) );
		        }
        }

        /**
         * Invoke the AdminUI class.
         */
        function initialize() {
	        parent::initialize();
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_stylesheet'));

            if ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] === 'slp_manage_locations' ) ) {
                add_action('admin_enqueue_scripts', array( $this->slplus, 'enqueue_google_maps_script' ) );
            }

            $this->styleHandle = $this->slplus->styleHandle;
            $this->add_janitor_hooks();

            // Called after admin_menu and admin_init when the current screen info is available.
            //
            add_action('current_screen', array($this, 'setup_admin_screen'));

            /**
             * HOOK: slp_admin_init_complete
             */
            do_action('slp_admin_init_complete');
        }

        /**
         * Sets $this->isOurAdminPage true if we are on a SLP managed admin page.  Returns true/false accordingly.
         *
         * @param string $hook
         * @return boolean
         */
        function is_our_admin_page($hook) {
            if (!is_admin()) {
                $this->is_our_admin_page = false;
                return false;
            }

            // Our Admin Page : true if we are on the admin page for this plugin
            // or we are processing the update action sent from this page
            //
			$this->is_our_admin_page = (
                    ( $hook == SLPLUS_PREFIX . '-options') ||
                    ( $hook === 'slp_info' )
                    );
            if ($this->is_our_admin_page) {
                return true;
            }


            // Request Action is "update" on option page
            //
			$this->is_our_admin_page = isset($_REQUEST['action']) &&
                    ($_REQUEST['action'] === 'update') &&
                    isset($_REQUEST['option_page']) &&
                    (substr($_REQUEST['option_page'], 0, strlen(SLPLUS_PREFIX)) === SLPLUS_PREFIX)
            ;
            if ($this->is_our_admin_page) {
                return true;
            }

            // This test allows for direct calling of the options page from an
            // admin page call direct from the sidebar using a class/method
            // operation.
            //
			// To use: pass an array of strings that are valid admin page slugs for
            // this plugin.  You can also pass a single string, we catch that too.
            //
			$this->set_admin_slugs();
            foreach ($this->slp_admin_slugs as $admin_slug) {
                $this->is_our_admin_page = ( $hook === $admin_slug);
                if ($this->is_our_admin_page) {
                    return true;
                }
            }

            return $this->is_our_admin_page;
        }

        /**
         * Set the admin slugs.
         */
        public function set_admin_slugs() {
            $this->slp_admin_slugs = array(
                'toplevel_page_slp-network-admin',
                'slp_general',
                'settings_page_csl-slplus-options',
                'slp_general',
	            $this->slplus->admin_page_prefix . 'slp_general',
                'slp_info',
	            $this->slplus->admin_page_prefix . 'slp_info',
                'slp_manage_locations',
	            $this->slplus->admin_page_prefix . 'slp_manage_locations',
                'slp_experience',
	            $this->slplus->admin_page_prefix . 'slp_experience',
            );
            $this->slp_admin_slugs = (array) apply_filters('wpcsl_admin_slugs', $this->slp_admin_slugs);
        }

        /**
         * Make options_nojs a setting we want to process during janitor reset settings.
         *
         * @param $field_array
         *
         * @return array
         */
        public function set_janitor_important_fields($field_array) {
            return array_merge($field_array, array('csl-slplus-options_nojs'));
        }

        /**
         * @param $option_name
         * @param $saved_setting
         */
        public function restore_janitor_important_fields($option_name, $saved_setting) {
            if ($option_name === 'csl-slplus-options_nojs') {
                $this->slplus->options_nojs = $this->slplus->option_manager->get_wp_option( 'nojs' );
                $this->slplus->options_nojs['next_field_id'] = $saved_setting['next_field_id'];
                $this->slplus->options_nojs['next_field_ported'] = $saved_setting['next_field_ported'];
                $this->slplus->option_manager->update_wp_option( 'nojs' , $this->slplus->options_nojs );
            }
        }

        /**
         * Build a query string of the add-on packages.
         *
         * @return string
         */
        public function create_addon_query() {
            $addon_slugs = array_keys($this->slplus->add_ons->instances);
            $addon_versions = array();
            foreach ($addon_slugs as $addon_slug) {
                if (is_object($this->slplus->add_ons->instances[$addon_slug])) {
                    $addon_versions[$addon_slug . '_version'] = $this->slplus->add_ons->instances[$addon_slug]->options['installed_version'];
                }
            }
            return
                    http_build_query($addon_slugs, 'addon_') . '&' .
                    http_build_query($addon_versions);
        }

        /**
         * Render the admin page navbar (tabs)
         *
         * @global mixed[] $submenu the WordPress Submenu array
         *
         * @return string
         */
        function create_Navbar() {
            global $submenu;
            $navbar_items = $submenu[SLPLUS_PREFIX];
	        if ( ! is_array( $navbar_items ) || empty( $navbar_items ) ) {
	        	return;
	        }

            $content =
                '<header id="myslp-header" class="panel-navbar">' .
                    '<ul class="navbar">';

            // Loop through all SLP sidebar menu items on admin page
            //
            foreach ($navbar_items as $slp_menu_item) {

                $current_class = ((isset($_REQUEST['page']) && ($_REQUEST['page'] === $slp_menu_item[2])) ? 'current' : '' );

	            $item_url = menu_page_url($slp_menu_item[2], false);
                $hyperlink = "<a class='navbar-link {$slp_menu_item[2]}' href='{$item_url}'>{$slp_menu_item[0]}</a>";

                $content .=  "<li class='navbar-item $slp_menu_item[2] {$current_class}'>{$hyperlink}</li>";
            }

            $content .= '</ul><div class="alert_box">' . $this->slplus->notifications->get() . '</div></header>';


            return $content;
        }

        /**
         * Create helper object.
         */
        private function create_object_helper() {
            if (!isset($this->helper)) {
                require_once(SLPLUS_PLUGINDIR . 'include/class.admin.helper.php');
                $this->helper = new SLP_Admin_Helper();
            }
        }

        /**
         * Create Locations object.
         *
         * @param   string  $current_screen_id      The current screen id (slug)
         */
        private function create_object_locations( $current_screen_id ) {
            if (!isset($this->ManageLocations)) {
                require_once( SLPLUS_PLUGINDIR . 'include/class.adminui.locations.php' );
                $this->ManageLocations = new SLPlus_AdminUI_Locations( array( 'screen' => $current_screen_id ) );
            }
        }

        /**
         * Return the icon selector HTML for the icon images in saved markers and default icon directories.
         *
         * @param string|null $inputFieldID
         * @param string|null $inputImageID
         * @return string
         */
        function CreateIconSelector($inputFieldID = null, $inputImageID = null) {
            $this->create_object_helper();
            return $this->helper->create_string_icon_selector($inputFieldID, $inputImageID);
        }

        /**
         * Enqueue the admin stylesheet when needed.
         *
         * @param string $hook Current page hook.
         */
        function enqueue_admin_stylesheet($hook) {

            if ( ! $this->is_our_admin_page($hook) || $this->already_enqueued ) {
                return;
            }

	        $this->add_persistent_admin_notice();

            // The CSS file must exists where we expect it and
            // The admin page being rendered must be in "our family" of admin pages
            //
			if (file_exists(SLPLUS_PLUGINDIR . 'css/admin/admin.css')) {
                $this->already_enqueued = true;

				wp_enqueue_style( 'font-awesome', SLPLUS_PLUGINURL . '/css/admin/font-awesome.min.css' );
                wp_enqueue_style('slp_admin_style', SLPLUS_PLUGINURL . '/css/admin/admin.css');

                if (file_exists(SLPLUS_PLUGINDIR . 'include/admin.js')) {
                    wp_enqueue_script('slp_admin_script', SLPLUS_PLUGINURL . '/include/admin.js', 'jquery', SLPLUS_VERSION, true);
                    $admin_js_settings = array(
                        'plugin_url'              => SLPLUS_PLUGINURL,
                        'text_are_you_sure'       => $this->slplus->text_manager->get_text_string( array( 'admin' , 'are_you_sure' ) ),
                        'text_location_warning'   => __('Some servers cannot handle lists of this size. ' , 'store-locator-le' ) .
                                                           __('Are you sure you want to do this? ' , 'store-locator-le' ),

                    );
                    wp_localize_script( 'slp_admin_script' , 'SLP_ADMIN_settings' , $admin_js_settings );
                }
            }

            wp_enqueue_script('jquery-ui-dialog');
        }

        /**
         * Render the experience tab.
         */
        function render_experience_tab() {
            $this->Admin_Experience->display();
        }

        /**
         * Render the Info page.
         *
         */
        function render_info_tab() {
            $this->Admin_Info->display();
        }

        /**
         * Render the General Settings page.
         *
         */
        function renderPage_GeneralSettings() {
            $this->instantiate('Admin_General');
            $this->Admin_General->render_adminpage();
        }

        /**
         * Render the Locations admin page.
         */
        function renderPage_Locations() {
            $this->slplus->set_php_timeout();
            $this->ManageLocations->render_adminpage();
        }

        /**
         * Attach the wanted screen object and save the settings if appropriate.
         *
         * @param   WP_Screen       $current_screen         The current screen object.
         */
        function setup_admin_screen($current_screen) {
            switch ($current_screen->id) {

                // Experience Tab
                //
                case $this->slplus->admin_page_prefix . 'slp_experience':
                    $this->instantiate('Admin_Experience');
					$this->Experience = $this->Admin_Experience;    // TODO: Drop when EM, ER, ES reference Admin_Experience instead
                    if (isset($_POST) && !empty($_POST)) {
                        $this->Admin_Experience->save_options();
                    }
                    break;

                // General Tab
                //
                case 'toplevel_page_slp-network-admin-network':
                case $this->slplus->admin_page_prefix . 'slp_general':
                    $this->instantiate('Admin_General');
                    if (isset($_POST) && !empty($_POST)) {
                        $this->Admin_General->save_options();
                    }
                    break;

                // Info Tab
                //
                case $this->slplus->admin_page_prefix . 'slp_info':
                    $this->instantiate( 'Admin_Info' );
                    break;

                // Locations Tab
                case $this->slplus->admin_page_prefix . 'slp_manage_locations':
                    $this->create_object_locations( $current_screen->id );
                    break;

                // Unknown
                //
                default:
                    break;
            }
        }

        /**
         * Merge existing options and POST options, then save to the wp_options table.
         *
         * NOTE: USED BY ADD ON PACKS ONLY
         *
         * TODO: Move to base_class.admin.php and rewire calls from add on packs afterwards (ES, ELM, POWER, PRO, SME, UML)
         *
         * Typically used to merge post options from admin interface changes with
         * existing options in a class.
         *
         * @param string $optionName name of option to update
         * @param mixed[] $currentOptions current options as a named array
         * @param string[] $cbOptionArray array of options that are checkboxes
         * @return mixed[] the updated options
         */
        function save_SerializedOption($optionName, $currentOptions, $cbOptionArray = null) {

            // If we did not send in a checkbox Array
            // AND there are not post options
            // get the heck out of here...
            //
	        if (
                    ( $cbOptionArray === null ) &&
                    !isset($_POST[$optionName])
            ) {
                return $currentOptions;
            }


            // Set a blank array if the post option name is not set
            // We can only get here with a blank post[optionname] if
            // we are given a cbOptionArray to process
            //
	        $optionValue = ( isset($_POST[$optionName]) ) ?
                    $_POST[$optionName] :
                    array();

            // Checkbox Pre-processor
            //
	        if (!is_null($cbOptionArray) && !empty($cbOptionArray)) {
                foreach ($cbOptionArray as $cbname) {
                    if (!isset($optionValue[$cbname])) {
                        $optionValue[$cbname] = '0';
                    }
                }
            }

            // Merge new options from POST with existing options
            //
	        $optionValue = stripslashes_deep(array_merge($currentOptions, $optionValue));

            // Make persistent, write back to the wp_options table
            // Only write if something has changed.
            //
	        if ($currentOptions != $optionValue) {
                $this->slplus->option_manager->update_wp_option( $optionName , $optionValue );
            }

            // Send back the updated options
            //
	        return $optionValue;
        }

    }

}