<?php
if ( ! class_exists( 'SLP_Admin_Experience' ) ) {

	/**
	 * Store Locator Plus User Experience admin user interface.
	 *
	 * @package   StoreLocatorPlus\Admin\Experience
	 * @author    Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2012-2016 Charleston Software Associates, LLC
	 *
	 * @property-read   array                       $group_params
	 * @property-read   string[]                    $map_languages        The Map Languages supported by the base plugin.
	 * @property-read   string[]                    $option_cache
	 * @property-read   string[]                    $update_info          A string array store user notify message
	 *
	 * @property-read   SLP_Admin_Experience_Text   $Admin_Experience_Text
	 * @property-read   SLP_Plugin_Style_Manager    $Plugin_Style_Manager
	 * @property-read   SLP_Settings                $Settings
	 *
	 */
	class SLP_Admin_Experience extends SLP_Object_With_Objects {
		private $group_params  = array();
		private $map_languages = array();
		private $option_cache  = array();
		private $update_info   = array();

		protected $objects = array(
			'Admin_Experience_Text' => array(
				'auto_instantiate' => true,
				'subdir' => 'include/module/admin_tabs/',
				'object' => null,
			),
			'Plugin_Style_Manager' => array(
				'auto_instantiate' => true,
				'subdir' => 'include/manager/',
				'object' => null,
			),
			'Settings' => array(
				'auto_instantiate' => true,
				'subdir' => 'include/module/admin_tabs/',
				'object' => null,
			),
		);

		/**
		 * Setup the group params at startup.
		 */
		function initialize() {
			parent::initialize();
			$this->group_params = array( 'plugin' => $this->slplus, 'section_slug' => null, 'group_slug' => null );
		}

		/**
		 * Add the map panel to the map settings page on the admin UI.
		 */
		function add_map_subtab() {
			$this->slplus->createobject_AddOnManager();

			$section_params['name'] = __( 'Map', 'store-locator-le' );
			$section_params['slug'] = 'map';
			$this->Settings->add_section( $section_params );

			$this->add_group_map_functionality( $section_params );
			$this->add_group_map_markers( $section_params );

			/**
			 * ACTION: slp_ux_modify_adminpanel_map
			 *
			 * @param   SLP_Settings $settings
			 * @param   string $section_name
			 * @param   array $section_params ['name'] = string of name, ['slug'] = string of unique slug
			 */
			do_action( 'slp_ux_modify_adminpanel_map', $this->Settings, $section_params['name'] , $section_params );
		}

		/**
		 * Create the results settings panel
		 *
		 */
		function add_results_subtab() {
			$section_params['name'] = __( 'Results', 'store-locator-le' );
			$section_params['slug'] = 'results';
			$this->Settings->add_section( $section_params );
			$this->add_group_results_appearance( $section_params );

			/**
			 * ACTION: slp_ux_modify_adminpanel_results
			 *
			 * @param   SLP_Settings $settings
			 * @param   string $section_name
			 * @param   array $section_params ['name'] = string of name, ['slug'] = string of unique slug
			 */
			do_action( 'slp_ux_modify_adminpanel_results', $this->Settings, $section_params['name'], $section_params );
		}

		/**
		 * Add the search form panel to the map settings page on the admin UI.
		 */
		function add_search() {
			$section_params['name'] = __( 'Search', 'store-locator-le' );
			$section_params['slug'] = 'search';
			$this->Settings->add_section( $section_params );

			$this->group_params[ 'section_slug' ] = 'search';

			$this->add_search_functionality();
			$this->add_search_appearance();

			/**
			 * FILTER: slp_ux_modify_adminpanel_search
			 *
			 * Add items to the Experience / Search admin tab.
			 *
			 * The section name is 'Search'
			 * The group names in the base plugin are:
			 *     'Search Features'
			 *     'Search Labels'
			 */
			do_action( 'slp_ux_modify_adminpanel_search', $this->Settings, $section_params['name'], $section_params );
		}

		/**
		 * Search / Appearance
		 */
		private function add_search_appearance() {
			$this->group_params[ 'group_slug' ] = 'appearance';
			$this->Settings->add_group( $this->group_params );
		}

		/**
		 * Search / Functionality
		 */
		private function add_search_functionality() {
			$this->group_params[ 'group_slug' ] = 'functionality';

			/**
			 * FILTER: slp_radius_behavior_description
			 *
			 * Extend the admin panel radius behavior description text.
			 *
			 * @param string $radius_behavior_description gets the current string, extend it.
			 *
			 * @return string
			 */
			$radius_behavior_description = __( "Always Use - show locations within the selected radius.<br/>", 'store-locator-le' );
			$radius_behavior_description = apply_filters( 'slp_radius_behavior_description', $radius_behavior_description );
			$radius_behavior_description .= $this->slplus->text_manager->get_web_link( 'docs_for_' . 'radius_behavior' );

			/**
			 * FILTER: slp_radius_behavior_selections
			 *
			 * Extend the admin panel radius behavior selections.
			 *
			 * @param array $selections as array ( array( 'label' => __( 'text', 'textdomain') , 'value' => 'slug' ) )
			 *
			 * @return array
			 */
			$radius_behavior_selections = array(
				array(
					'label' => __( 'Always Use', 'store-locator-le' ),
					'value' => 'always_use',
				),
			);
			$radius_behavior_selections = apply_filters( 'slp_radius_behavior_selections', $radius_behavior_selections );

			$this->Settings->add_ItemToGroup( array(
				'group_params' => $this->group_params,
				'label'        => $this->slplus->text_manager->get_text_string( array( 'label', 'radius_behavior' ) ),
				'description'  => __( 'How should the radius be handled on the location search.<br/>', 'store-locator-le' ) . $radius_behavior_description,
				'option'       => 'radius_behavior',
				'type'         => 'dropdown',
				'custom'       => $radius_behavior_selections,
			) );

		}

		/**
		 * Add View Subta
		 */
		function add_view_subtab() {
			$section_params['name'] = __( 'View', 'store-locator-le' );
			$section_params['slug'] = 'view';
			$this->Settings->add_section( $section_params );

			$this->Plugin_Style_Manager->add_settings( $this->Settings );

			/**
			 * ACTION: slp_uxsettings_modify_viewpanel
			 *
			 * @param   SLP_Settings $settings
			 * @param   string $section_name
			 * @param   array $section_params ['name'] = string of name, ['slug'] = string of unique slug
			 */
			do_action( 'slp_uxsettings_modify_viewpanel', $this->Settings, $section_params['name'] );
		}

		/**
		 * Add Experience / Map / Functionality
		 *
		 * @param string[] $section_params
		 */
		private function add_group_map_functionality( $section_params ) {
			$group_params[ 'section_slug' ] = $section_params[ 'slug' ];
			$group_params[ 'group_slug'   ] = 'functionality';
			$group_params[ 'plugin'       ] = $this->slplus;

			/**
			 * @var SLP_Country $country
			 */
			$this->slplus->create_object_CountryManager();
			$selections = array();
			foreach ( $this->slplus->CountryManager->countries as $country_slug => $country ) {
				$selections[] = array(
					'label' => "{$country->name} ({$country->google_domain})",
					'value' => $country_slug,
				);
			}
			$this->Settings->add_ItemToGroup( array(
				'group_params' => $group_params,
				'option_name' => 'options_nojs',
				'option'     => 'default_country',
				'type'        => 'dropdown',
				'label'       => __( 'Map Domain', 'store-locator-le' ),
				'description' => __( 'Select the Google maps API url to be used by default for location queries. ', 'store-locator-le' ),
				'selectedVal' => $this->slplus->options_nojs['default_country'],
				'custom'      => $selections,

			) );

			// Language Selection
			//
			$selections = array();
			$this->set_map_languages();
			foreach ( $this->map_languages as $label => $value ) {
				$selections[] = array( 'label' => $label, 'value' => $value );
			}
			$this->Settings->add_ItemToGroup( array(
				'group_params' => $group_params,
				'option_name' => 'options_nojs',
				'option'     => 'map_language',
				'type'        => 'dropdown',
				'label'       => __( 'Map Language', 'store-locator-le' ),
				'description' =>
					__( 'Select the language to be used when sending and receiving data from the Google Maps API.', 'store-locator-le' ),
				'custom'      => $selections,

			) );
		}

		/**
		 * Add Experience / Map / Markers
		 *
		 * @param string[] $section_params
		 */
		private function add_group_map_markers( $section_params ) {
			$group_params[ 'section_slug' ] = $section_params[ 'slug' ];
			$group_params[ 'group_slug'   ] = 'markers';
			$group_params[ 'plugin'       ] = $this->slplus;

			$this->Settings->add_group( $group_params );

			$html =
				$this->slplus->data['iconNotice'] .

				"<div class='form_entry'>" .
				"<label for='map_home_icon'>" . __( 'Home Marker', 'store-locator-le' ) . "</label>" .
				"<input id='map_home_icon' name='map_home_icon' dir='rtl' size='45' " .
				"value='" . $this->slplus->options['map_home_icon'] . "' " .
				'onchange="document.getElementById(\'prev\').src=this.value">' .
				"<img id='home_icon_preview' alt='home_icon_preview' src='" . $this->slplus->options['map_home_icon'] . "'><br/>" .
				$this->slplus->data['homeIconPicker'] .
				"</div>" .

				"<div class='form_entry'>" .
				"<label for='map_end_icon'>" . __( 'Destination Marker', 'store-locator-le' ) . "</label>" .
				"<input id='map_end_icon' name='map_end_icon' dir='rtl' size='45' " .
				"value='" . $this->slplus->options['map_end_icon'] . "' " .
				'onchange="document.getElementById(\'prev2\').src=this.value">' .
				"<img id='end_icon_preview' alt='end_icon_preview' src='" . $this->slplus->options['map_end_icon'] . "'><br/>" .
				$this->slplus->data['endIconPicker'] .
				"</div>" .

				'<br/><p class="notice notice-success">' .
				__( 'Saved markers live here: <code>', 'store-locator-le' ) . SLPLUS_UPLOADDIR . "saved-icons/</code></p>";

			$this->Settings->add_ItemToGroup( array(
					'group_params' => $group_params,
					'label'      => '',
					'type'       => 'custom',
					'show_label' => false,
					'custom'     => $html,
				)
			);
		}

		/**
		 * Add Experience / Results / Appearance
		 *
		 * @param string[] $section_params
		 *
		 * TODO: Rip this out in SLP 5.0 when we drop legacy support
		 */
		private function add_group_results_appearance( $section_params ) {
			if (
				$this->slplus->is_AddonActive( 'slp-experience' ) ||
				$this->slplus->is_AddonActive( 'slp-enhanced-results' )
			) {
				$group_params[ 'section_slug' ] = $section_params[ 'slug' ];
				$group_params[ 'group_slug'   ] = 'appearance';
				$group_params[ 'plugin'       ] = $this->slplus;
				$this->Settings->add_group( $group_params );
			}
		}

		/**
		 * Generate the HTML for an input settings interface element.
		 *
		 * @param string $boxname
		 * @param string $label
		 * @param string $msg
		 * @param string $prefix
		 * @param string $default
		 * @param string $value - forced value
		 *
		 * @return string HTML for the div box.
		 *
		 * TODO: Deprecate when EM/ES stop using this method.
		 */
		function CreateInputDiv( $boxname, $label = '', $msg = '', $prefix = SLPLUS_PREFIX, $default = '', $value = null ) {
			$whichbox = $prefix . $boxname;
			if ( $value === null ) {
				$value = $this->get_experience_option( $whichbox, $default );
			}

			return
				"<div class='form_entry'>" .
				"<div class='input-group wpcsl-list'>" .
				"<label for='$whichbox'>$label:</label>" .
				"<input  name='$whichbox' value='$value'>" .
				"</div>" .
				"</div>";
		}

		/**
		 * Retrieves options and caches them in this->option_cache or slplus->data
		 *
		 * @param string $optionName - the option name
		 * @param mixed  $default    - what the default value should be
		 *
		 * @return mixed the value of the option as saved in the database
		 */
		private function get_experience_option( $optionName, $default = '' ) {
			$matches = array();

			// If the option name is <blah>[setting] then use option_cache
			//
			if ( preg_match( '/^(.*?)\[(.*?)\]/', $optionName, $matches ) === 1 ) {
				if ( ! isset( $this->option_cache[ $matches[1] ] ) ) {
					$this->option_cache[ $matches[1] ] = get_option( $matches[1], $default );
				}

				return
					isset( $this->option_cache[ $matches[1] ][ $matches[2] ] ) ?
						$this->option_cache[ $matches[1] ][ $matches[2] ] :
						'';

				// Otherwise use slplus->data
				//
			} else {
				if ( ! isset( $this->slplus->data[ $optionName ] ) ) {
					$this->slplus->data[ $optionName ] = get_option( $optionName, $default );
				}

				return esc_html( $this->slplus->data[ $optionName ] );

			}
		}

		/**
		 * Render the map settings admin page.
		 */
		function display() {

			/**
			 * @see http://goo.gl/UAXly - endIcon - the default map marker to be used for locations shown on the map
			 * @see http://goo.gl/UAXly - endIconPicker -  the icon selection HTML interface
			 * @see http://goo.gl/UAXly - homeIcon - the default map marker to be used for the starting location during a search
			 * @see http://goo.gl/UAXly - homeIconPicker -  the icon selection HTML interface
			 * @see http://goo.gl/UAXly - iconNotice - the admin panel message if there is a problem with the home or end icon
			 * @see http://goo.gl/UAXly - siteURL - get_site_url() WordPress call
			 */
			if ( ! isset( $this->slplus->data['homeIconPicker'] ) ) {
				$this->slplus->data['homeIconPicker'] = $this->slplus->AdminUI->CreateIconSelector( 'map_home_icon', 'home_icon_preview' );
			}
			if ( ! isset( $this->slplus->data['endIconPicker'] ) ) {
				$this->slplus->data['endIconPicker'] = $this->slplus->AdminUI->CreateIconSelector( 'map_end_icon', 'end_icon_preview' );
			}

			// Icon is the old path, notify them to re-select
			//
			$this->slplus->data['iconNotice'] = '';
			if ( ! isset( $this->slplus->data['siteURL'] ) ) {
				$this->slplus->data['siteURL'] = get_site_url();
			}
			if ( ! ( strpos( $this->slplus->options['map_home_icon'], 'http' ) === 0 ) ) {
				$this->slplus->options['map_home_icon'] = $this->slplus->data['siteURL'] . $this->slplus->options['map_home_icon'];
			}
			if ( ! ( strpos( $this->slplus->options['map_end_icon'], 'http' ) === 0 ) ) {
				$this->slplus->options['map_end_icon'] = $this->slplus->data['siteURL'] . $this->slplus->options['map_end_icon'];
			}
			if ( ! $this->slplus->helper->webItemExists( $this->slplus->options['map_home_icon'] ) ) {
				$this->slplus->data['iconNotice'] .=
					sprintf(
						__( 'Your home marker %s cannot be located, please select a new one.', 'store-locator-le' ),
						$this->slplus->options['map_home_icon']
					)
					.
					'<br/>';
			}
			if ( ! $this->slplus->helper->webItemExists( $this->slplus->options['map_end_icon'] ) ) {
				$this->slplus->data['iconNotice'] .=
					sprintf(
						__( 'Your destination marker %s cannot be located, please select a new one.', 'store-locator-le' ),
						$this->slplus->options['map_end_icon']
					)
					.
					'<br/>';
			}
			if ( $this->slplus->data['iconNotice'] != '' ) {
				$this->slplus->data['iconNotice'] =
					"<div class='highlight' style='background-color:LightYellow;color:red'><span style='color:red'>" .
					$this->slplus->data['iconNotice'] .
					"</span></div>";
			}

			// Navbar
			$this->Settings->add_section(
				array(
					'name'        => 'Navigation',
					'div_id'      => 'navbar_wrapper',
					'description' => $this->slplus->AdminUI->create_Navbar(),
					'innerdiv'    => false,
					'is_topmenu'  => true,
					'auto'        => false,
				)
			);

			// Subtabs
			add_action( 'slp_build_map_settings_panels', array( $this, 'add_search' ), 10 );
			add_action( 'slp_build_map_settings_panels', array( $this, 'add_map_subtab' ), 20 );
			add_action( 'slp_build_map_settings_panels', array( $this, 'add_results_subtab' ), 30 );
			add_action( 'slp_build_map_settings_panels', array( $this, 'add_view_subtab' ), 40 );

			// Render
			if ( count( $this->update_info ) > 0 ) {
				$update_msg = "<div class='highlight'>" . __( 'Successful Update', 'store-locator-le' );
				foreach ( $this->update_info as $info_msg ) {
					$update_msg .= '<br/>' . $info_msg;
				}
				$update_msg .= '</div>';
				$this->update_info = array();
				print $update_msg;
			}
			do_action( 'slp_build_map_settings_panels' , $this->Settings );
			$this->Settings->render_settings_page();
		}

		/**
		 * Save or update custom CSS
		 *
		 * Called when "Save Settings" button is clicked
		 *
		 */
		function save_custom_css() {
			if ( ! is_dir( SLPLUS_UPLOADDIR . "css/" ) ) {
				wp_mkdir_p( SLPLUS_UPLOADDIR . "css/" );
			}
			$this->slplus->createobject_Activation();
			$css_file = $this->slplus->options_nojs['theme'] . '.css';
			$this->slplus->Activation->copy_newer_files( SLPLUS_PLUGINDIR . "css/$css_file", SLPLUS_UPLOADDIR . "css/$css_file" );
		}

		/**
		 * Save the options to the WP database options table.
		 */
		function save_options() {
			if ( ! isset( $_REQUEST ) ) {
				return;
			}

			/**
			 * ACTION: slp_save_map_settings
			 *
			 * Runs before saving the map settings data.
			 *
			 * TODO: used by EM/ER/ES  , can this be done another way through the base class admin_startup() maybe?
			 */
			do_action( 'slp_save_map_settings' );

			// TODO: this can go away when all add-on packs convert to the parent::do_admin_startup() in base_class_admin.
			//
			do_action( 'slp_save_ux_settings' );

			// Set height unit to blank, if height is "auto !important"
			if ( ( strpos( $_POST['options_nojs']['map_height'], 'auto' ) !== false ) && ( $_POST['options_nojs']['map_height_units'] !== ' ' ) ) {
				$_REQUEST['map_height_units'] = ' ';
				array_push( $this->update_info, __( "Auto set height unit to blank when height is 'auto'", 'store-locator-le' ) );
			}
			// Set weight unit to blank, if height is "auto !important"
			if ( ( strpos( $_POST['options_nojs']['map_width'], 'auto' ) !== false ) && ( $_POST['options_nojs']['map_width_units'] !== ' ' ) ) {
				$_REQUEST['map_width_units'] = ' ';
				array_push( $this->update_info, __( "Auto set width unit to blank when width is 'auto'", 'store-locator-le' ) );
			}

			// Height, strip non-digits, if % set range 0..100
			if ( in_array( $_POST['options_nojs']['map_height_units'], array( '%', 'px', 'pt', 'em' ) ) ) {
				$_POST['options_nojs']['map_height'] = preg_replace( '/[^0-9]/', '', $_POST['options_nojs']['map_height'] );
				if ( $_POST['options_nojs']['map_height_units'] == '%' ) {
					$_REQUEST['map_height'] = max( 0, min( $_POST['options_nojs']['map_height'], 100 ) );
				}
			}

			// Width, strip non-digits, if % set range 0..100
			if ( in_array( $_POST['options_nojs']['map_width_units'], array( '%', 'px', 'pt', 'em' ) ) ) {
				$_POST['options_nojs']['map_width'] = preg_replace( '/[^0-9]/', '', $_POST['options_nojs']['map_width'] );
				if ( $_POST['options_nojs']['map_width_units'] == '%' ) {
					$_REQUEST['map_width'] = max( 0, min( $_POST['options_nojs']['map_width'], 100 ) );
				}
			}

			// Standard Input Saves
			//
			$BoxesToHit = apply_filters( 'slp_save_map_settings_inputs', array() );
			foreach ( $BoxesToHit as $JustAnotherBox ) {
				$this->slplus->helper->SavePostToOptionsTable( $JustAnotherBox );
			}

			// Register need translate text to WPML
			//
			$BoxesToHit = apply_filters( 'slp_regwpml_map_settings_inputs', array() );
			foreach ( $BoxesToHit as $JustAnotherBox ) {
				$this->slplus->WPML->register_post_options( $JustAnotherBox );
			}

			// Checkboxes
			//
			// TODO: Update ES / EM to remove slp_save_map_settings_checkboxes then this can go away.
			//
			$BoxesToHit = apply_filters( 'slp_save_map_settings_checkboxes', array() );
			foreach ( (array) $BoxesToHit as $JustAnotherBox ) {
				$this->slplus->helper->SaveCheckboxToDB( $JustAnotherBox, '', '' );
			}

			$this->slplus->smart_options->set_checkboxes();

			// Serialized Options Setting for stuff going into slp.js.
			// This should be used for ALL new JavaScript options.
			//
			array_walk( $_REQUEST, array( $this->slplus->smart_options, 'set_valid_options' ) );
			if ( isset( $_REQUEST['options'] ) ) {
				array_walk( $_REQUEST['options'], array( $this->slplus->smart_options, 'set_valid_options' ) );
			}

			// Save Map Domain and Center Map At Settings
			$this->save_the_country();

			$this->slplus->smart_options->execute_change_callbacks();

			$this->slplus->option_manager->update_wp_option( 'js' );

			// Serialized Options Setting for stuff NOT going to slp.js.
			// This should be used for ALL new options not going to slp.js.
			//
			$starting_theme = $this->slplus->options_nojs['theme'];
			array_walk( $_REQUEST, array( $this->slplus, 'set_ValidOptionsNoJS' ) );
			$ending_theme = $this->slplus->options_nojs['theme'];

			if ( isset( $_REQUEST['options_nojs'] ) ) {
				array_walk( $_REQUEST['options_nojs'], array( $this->slplus, 'set_ValidOptionsNoJS' ) );
			}
			$this->slplus->option_manager->update_wp_option( 'nojs' );

			// Save or Update a copy of the css file to the uploads\slp\css dir
			if ( $starting_theme !== $ending_theme ) {
				$this->save_custom_css();
			}
		}

		/**
		 * Set our object options.
		 */
		protected function set_default_object_options() {
			$this->objects[ 'Settings' ][ 'options' ] = array (
				'name'        => __( 'Experience', 'store-locator-le' ),
				'form_action' => ( is_network_admin() ? network_admin_url() : admin_url() ) . 'admin.php?page=' . $_REQUEST['page'],
			);
		}

		/**
		 * Change the Center Map settings if the Map Domain (country) has changed and those settings are blank.
		 *
		 * TODO: all the options here should be attached to smart options and THIS METHOD should fire via a callback there.
		 *
		 * @var     SLP_COUNTRY $selected_country
		 */
		private function save_the_country() {
			$this->slplus->create_object_CountryManager();
			$selected_country                    = $this->slplus->CountryManager->countries[ $_POST['options_nojs']['default_country'] ];
			$this->slplus->options['map_domain'] = $selected_country->google_domain;

			// Default Country (Map Domain) has changed, check map center settings.
			//
			if ( $_POST['options_nojs']['default_country'] !== $this->slplus->options_nojs['default_country'] ) {
				$this->slplus->options_nojs['default_country'] = $_POST['options_nojs']['default_country'];
			}

			if ( empty ( $_POST['options']['map_center'] ) ) {
				$this->slplus->options['map_center'] = null;
			} else {
				$this->slplus->options['map_center'] = $_POST['options']['map_center'];
			}
			if ( empty ( $_POST['options']['map_center_lat'] ) ) {
				$this->slplus->options['map_center_lat'] = null;
			} else {
				$this->slplus->options['map_center_lat'] = $_POST['options']['map_center_lat'];

			}
			if ( empty ( $_POST['options']['map_center_lng'] ) ) {
				$this->slplus->options['map_center_lng'] = null;
			} else {
				$this->slplus->options['map_center_lng'] = $_POST['options']['map_center_lng'];
			}

			$this->slplus->recenter_map();
		}

		/**
		 * Set the map languages when needed.
		 */
		private function set_map_languages() {
			if ( empty( $this->map_languages ) ) {
				$this->map_languages =
					array(
						__( 'English', 'store-locator-le' )                 => 'en',
						__( 'Arabic', 'store-locator-le' )                  => 'ar',
						__( 'Basque', 'store-locator-le' )                  => 'eu',
						__( 'Bulgarian', 'store-locator-le' )               => 'bg',
						__( 'Bengali', 'store-locator-le' )                 => 'bn',
						__( 'Catalan', 'store-locator-le' )                 => 'ca',
						__( 'Czech', 'store-locator-le' )                   => 'cs',
						__( 'Danish', 'store-locator-le' )                  => 'da',
						__( 'German', 'store-locator-le' )                  => 'de',
						__( 'Greek', 'store-locator-le' )                   => 'el',
						__( 'English (Australian)', 'store-locator-le' )    => 'en-AU',
						__( 'English (Great Britain)', 'store-locator-le' ) => 'en-GB',
						__( 'Spanish', 'store-locator-le' )                 => 'es',
						__( 'Farsi', 'store-locator-le' )                   => 'fa',
						__( 'Finnish', 'store-locator-le' )                 => 'fi',
						__( 'Filipino', 'store-locator-le' )                => 'fil',
						__( 'French', 'store-locator-le' )                  => 'fr',
						__( 'Galician', 'store-locator-le' )                => 'gl',
						__( 'Gujarati', 'store-locator-le' )                => 'gu',
						__( 'Hindi', 'store-locator-le' )                   => 'hi',
						__( 'Croatian', 'store-locator-le' )                => 'hr',
						__( 'Hungarian', 'store-locator-le' )               => 'hu',
						__( 'Indonesian', 'store-locator-le' )              => 'id',
						__( 'Italian', 'store-locator-le' )                 => 'it',
						__( 'Hebrew', 'store-locator-le' )                  => 'iw',
						__( 'Japanese', 'store-locator-le' )                => 'ja',
						__( 'Kannada', 'store-locator-le' )                 => 'kn',
						__( 'Korean', 'store-locator-le' )                  => 'ko',
						__( 'Lithuanian', 'store-locator-le' )              => 'lt',
						__( 'Latvian', 'store-locator-le' )                 => 'lv',
						__( 'Malayalam', 'store-locator-le' )               => 'ml',
						__( 'Marathi', 'store-locator-le' )                 => 'mr',
						__( 'Dutch', 'store-locator-le' )                   => 'nl',
						__( 'Norwegian', 'store-locator-le' )               => 'no',
						__( 'Polish', 'store-locator-le' )                  => 'pl',
						__( 'Portuguese', 'store-locator-le' )              => 'pt',
						__( 'Portuguese (Brazil)', 'store-locator-le' )     => 'pt-BR',
						__( 'Portuguese (Portugal)', 'store-locator-le' )   => 'pt-PT',
						__( 'Romanian', 'store-locator-le' )                => 'ro',
						__( 'Russian', 'store-locator-le' )                 => 'ru',
						__( 'Slovak', 'store-locator-le' )                  => 'sk',
						__( 'Slovenian', 'store-locator-le' )               => 'sl',
						__( 'Serbian', 'store-locator-le' )                 => 'sr',
						__( 'Swedish', 'store-locator-le' )                 => 'sv',
						__( 'Tagalog', 'store-locator-le' )                 => 'tl',
						__( 'Tamil', 'store-locator-le' )                   => 'ta',
						__( 'Telugu', 'store-locator-le' )                  => 'te',
						__( 'Thai', 'store-locator-le' )                    => 'th',
						__( 'Turkish', 'store-locator-le' )                 => 'tr',
						__( 'Ukrainian', 'store-locator-le' )               => 'uk',
						__( 'Vietnamese', 'store-locator-le' )              => 'vi',
						__( 'Chinese (Simplified)', 'store-locator-le' )    => 'zh-CN',
						__( 'Chinese (Traditional)', 'store-locator-le' )   => 'zh-TW',
					);
			}
		}
	}
}