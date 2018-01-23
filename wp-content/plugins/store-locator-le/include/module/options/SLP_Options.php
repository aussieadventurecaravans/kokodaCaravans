<?php

if ( ! class_exists( 'SLP_Options' ) ):

	/**
	 * Class SLP_Options
	 *
	 * The options management for the base plugin (replaces SLPlus->options / SLPlus->options_nojs)
	 *
	 * @package   StoreLocatorPlus\Options
	 * @author    Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2016 Charleston Software Associates, LLC
	 *
	 * @since     4.6
	 *
	 * @property SLP_Option      $admin_notice_dismissed                 ;
	 * @property SLP_Option      $distance_unit
	 * @property SLP_Option      $google_server_key
	 * @property SLP_Option      $initial_radius
	 * @property SLP_Option      $invalid_query_message
	 * @property SLP_Option      $instructions
	 * @property SLP_Option      $label_directions
	 * @property SLP_Option      $label_email
	 * @property SLP_Option      $label_fax
	 * @property SLP_Option      $label_hours
	 * @property SLP_Option      $label_image
	 * @property SLP_Option      $label_phone
	 * @property SLP_Option      $label_radius
	 * @property SLP_Option      $label_search
	 * @property SLP_Option      $label_website
	 * @property SLP_Option      $map_center
	 * @property SLP_Option      $map_center_lat
	 * @property SLP_Option      $map_center_lng
	 * @property SLP_Option      $map_height
	 * @property SLP_Option      $map_width
	 * @property SLP_Option      $message_no_results
	 * @property SLP_Option      $message_no_api_key
	 * @property SLP_Option      $remove_credits
	 * @property SLP_Option      $zoom_level
	 * @property SLP_Option      $zoom_tweak
	 *
	 * @property        array    $change_callbacks                       Stack (array) of callbacks in array ( _func_ , _params_ ) format.
	 *
	 * @property-read   string[] $current_checkboxes                     Array of smart option checkbox slugs for the current admin screen
	 *
	 * @property-read   array    $page_layout                            The page layout array( pages slugs => array( section slugs => array( group_slugs => property slugs ) ) )
	 *
	 * @property-read   string[] $smart_properties                       Array of property names that are smart options.
	 *
	 * @property        string[] $text_options                           An array of smart option slugs that are text options.
	 *
	 * @property-read  boolean   $initial_distance_already_calculated    only do this once per change set.
	 *
	 *
	 * TODO: Options for drop downs needs to be hooked to a load_dropdowns method - to offload this overhead so we don't carry around huge arrays for every SLPlus instantiation
	 * note: should be called only when rendering the admin page, the option values should go in the include/module/admin_tabs directory in an SLP_Admin_Experience_Dropdown class
	 *
	 */
	class SLP_Options extends SLPlus_BaseClass_Object {

		// The SLP User-Set Options
		public $admin_notice_dismissed;
		public $distance_unit;
		public $google_server_key;
		public $initial_radius;
		public $initial_results_returned;
		public $invalid_query_message;
		public $instructions;
		public $label_directions;
		public $label_email;
		public $label_fax;
		public $label_hours;
		public $label_image;
		public $label_phone;
		public $label_radius;
		public $label_search;
		public $label_website;
		public $map_center;
		public $map_center_lat;
		public $map_center_lng;
		public $message_no_results;
		public $message_no_api_key;
		public $remove_credits;
		public $zoom_tweak;

		// Things that help us manage the options.
		private $current_checkboxes;

		protected $change_callbacks = array();

		private $initial_distance_already_calculated = false;

		private $initialized = false;

		public $page_layout;

		private $smart_properties;

		public $text_options;

		/**
		 * Things we do at the start.
		 */
		public function initialize() {
			if ( $this->initialized ) {
				return;
			}

			$smart_options['admin_notice_dismissed'] = array( 'type' => 'checkbox', 'default' => '0' );

			$smart_options['google_server_key'] = array();

			$smart_options['invalid_query_message'] = array( 'is_text' => true, );

			$smart_options['message_no_api_key'] = array( 'is_text' => true, 'use_in_javascript' => true, );

			// Experience : Search : Functionality
			$smart_options['distance_unit'] = array(
				'page'    => 'slp_experience',
				'section' => 'search',
				'group'   => 'functionality',
				'default'           => 'miles',
				'call_when_changed' => array( $this, 'recalculate_initial_distance' ),
				'use_in_javascript' => true,
				'type'              => 'dropdown',
				'options'           => array(
					array( 'label' => __( 'Kilometers', 'store-locator-le' ), 'value' => 'km' ),
					array( 'label' => __( 'Miles', 'store-locator-le' ), 'value' => 'miles' ),
				),
			);
			$smart_options['radii'] = array(
				'page'    => 'slp_experience',
				'section' => 'search',
				'group'   => 'functionality',
				'default'           => '10,25,50,100,(200),500',
				'use_in_javascript' => true,
			);

			// Experience : Search : Labels
			$smart_options['label_search'] = array(
				'page'    => 'slp_experience',
				'section' => 'search',
				'group'   => 'labels',
				'is_text' => true,
			);
			$smart_options['label_radius'] = array(
				'page'    => 'slp_experience',
				'section' => 'search',
				'group'   => 'labels',
				'is_text' => true,
			);

			// Experience : Map : At Startup
			$smart_options['map_center']     = array(
				'page'              => 'slp_experience',
				'section'           => 'map',
				'group'             => 'at_startup',
				'type'              => 'textarea',
				'use_in_javascript' => true,
				'call_when_changed' => array( $this, 'map_center_change' ),
			);
			$smart_options['map_center_lat'] = array(
				'page'              => 'slp_experience',
				'section'           => 'map',
				'group'             => 'at_startup',
				'use_in_javascript' => true,
				'call_when_changed' => array( $this, 'recalculate_initial_distance' ),
			);
			$smart_options['map_center_lng'] = array(
				'page'              => 'slp_experience',
				'section'           => 'map',
				'group'             => 'at_startup',
				'use_in_javascript' => true,
				'call_when_changed' => array( $this, 'recalculate_initial_distance' ),
			);

			// Experience : Map : Functionality
			$smart_options['zoom_level'] = array(
				'page'              => 'slp_experience',
				'section'           => 'map',
				'group'             => 'functionality',
				'type'              => 'dropdown',
				'default'           => '12',
				'use_in_javascript' => true,
				'options'           => array(
					array( 'label' => '0' ),
					array( 'label' => '1' ),
					array( 'label' => '2' ),
					array( 'label' => '3' ),
					array( 'label' => '4' ),
					array( 'label' => '5' ),
					array( 'label' => '6' ),
					array( 'label' => '7' ),
					array( 'label' => '8' ),
					array( 'label' => '9' ),
					array( 'label' => '10' ),
					array( 'label' => '11' ),
					array( 'label' => '12' ),
					array( 'label' => '13' ),
					array( 'label' => '14' ),
					array( 'label' => '15' ),
					array( 'label' => '16' ),
					array( 'label' => '17' ),
					array( 'label' => '18' ),
					array( 'label' => '19' ),
				),
			);
			$smart_options['zoom_tweak'] = array(
				'page'              => 'slp_experience',
				'section'           => 'map',
				'group'             => 'functionality',
				'type'              => 'dropdown',
				'default'           => '0',
				'use_in_javascript' => true,
				'options'           => array(
					array( 'label' => '-10' ),
					array( 'label' => '-9' ),
					array( 'label' => '-8' ),
					array( 'label' => '-7' ),
					array( 'label' => '-6' ),
					array( 'label' => '-5' ),
					array( 'label' => '-4' ),
					array( 'label' => '-3' ),
					array( 'label' => '-2' ),
					array( 'label' => '-1' ),
					array( 'label' => '0' ),
					array( 'label' => '1' ),
					array( 'label' => '2' ),
					array( 'label' => '3' ),
					array( 'label' => '4' ),
					array( 'label' => '5' ),
					array( 'label' => '6' ),
					array( 'label' => '7' ),
					array( 'label' => '8' ),
					array( 'label' => '9' ),
					array( 'label' => '10' ),
					array( 'label' => '11' ),
					array( 'label' => '12' ),
					array( 'label' => '13' ),
					array( 'label' => '14' ),
					array( 'label' => '15' ),
					array( 'label' => '16' ),
					array( 'label' => '17' ),
					array( 'label' => '18' ),
					array( 'label' => '19' ),
				),
			);

			// Experience : Map : Appearance
			$smart_options['map_height']       = array(
				'page'    => 'slp_experience',
				'section' => 'map',
				'group'   => 'appearance',
				'default' => '480',
			);
			$smart_options['map_height_units'] = array(
				'page'    => 'slp_experience',
				'section' => 'map',
				'group'   => 'appearance',
				'default' => 'px',
				'type'    => 'dropdown',
				'options' =>
					array(
						array( 'label' => '%' ),
						array( 'label' => 'px' ),
						array( 'label' => 'em' ),
						array( 'label' => 'pt' ),
						array( 'label' => __( 'CSS / inherit', 'store-locator-le' ), 'value' => ' ' ),
					),
			);
			$smart_options['map_width']        = array(
				'page'    => 'slp_experience',
				'section' => 'map',
				'group'   => 'appearance',
				'default' => '100',
			);
			$smart_options['map_width_units']  = array(
				'page'    => 'slp_experience',
				'section' => 'map',
				'group'   => 'appearance',
				'default' => '%',
				'type'    => 'dropdown',
				'options' => array(
					array( 'label' => '%' ),
					array( 'label' => 'px' ),
					array( 'label' => 'em' ),
					array( 'label' => 'pt' ),
					array( 'label' => __( 'CSS / inherit', 'store-locator-le' ), 'value' => ' ' ),
				),
			);
			$smart_options['map_type']         = array(
				'page'              => 'slp_experience',
				'section'           => 'map',
				'group'             => 'appearance',
				'default'           => 'roadmap',
				'type'              => 'dropdown',
				'use_in_javascript' => true,
				'options'           => array(
					array( 'label' => 'Roadmap', 'value' => 'roadmap' ),
					array( 'label' => 'Hybrid', 'value' => 'hybrid' ),
					array( 'label' => 'Satellite', 'value' => 'satellite' ),
					array( 'label' => 'Terrain', 'value' => 'terrain' ),
				),
			);
			$smart_options['remove_credits']   = array(
				'page'    => 'slp_experience',
				'section' => 'map',
				'group'   => 'appearance',
				'type'    => 'checkbox',
				'default' => '0',
			);

			// Experience : Results : At Startup
			$smart_options['immediately_show_locations'] = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'at_startup',
				'type'              => 'checkbox',
				'default'           => '1',
				'use_in_javascript' => true,
			);
			$smart_options['initial_radius']             = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'at_startup',
				'default'           => '',
				'use_in_javascript' => true,
			);
			$smart_options['initial_results_returned']   = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'at_startup',
				'default'           => '25',
				'use_in_javascript' => true,
			);

			// Experience : Results : After Search
			$smart_options['max_results_returned'] = array(
				'page'    => 'slp_experience',
				'section' => 'results',
				'group'   => 'after_search',
				'default' => '25',
			);

			// Experience : Results : Appearance
			$smart_options['message_no_results'] = array(
				'is_text' => true,
				'use_in_javascript' => true,
				);

			// Experience : Results : Labels
			$smart_options['instructions']     = array(
				'page'    => 'slp_experience',
				'section' => 'results',
				'group'   => 'labels',
				'is_text' => true,
			);
			$smart_options['label_website']    = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'labels',
				'is_text'           => true,
				'use_in_javascript' => true,
			);
			$smart_options['label_directions'] = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'labels',
				'is_text'           => true,
				'use_in_javascript' => true,
			);
			$smart_options['label_hours']      = array(
				'page'    => 'slp_experience',
				'section' => 'results',
				'group'   => 'labels',
				'is_text' => true,
			);
			$smart_options['label_email']      = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'labels',
				'is_text'           => true,
				'use_in_javascript' => true,
			);
			$smart_options['label_phone']      = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'labels',
				'is_text'           => true,
				'use_in_javascript' => true,
			);
			$smart_options['label_fax']        = array(
				'page'              => 'slp_experience',
				'section'           => 'results',
				'group'             => 'labels',
				'is_text'           => true,
				'use_in_javascript' => true,
			);
			$smart_options['label_image']      = array(
				'page'    => 'slp_experience',
				'section' => 'results',
				'group'   => 'labels',
				'is_text' => true,
			);

			require_once( SLPLUS_PLUGINDIR . 'include/unit/SLP_Option.php' );
			$this->create_smart_options( $smart_options, $this->slplus->options, $this->slplus->options_nojs );

			$this->initialized = true;
		}

		/**
		 * Create smart option objects and set to default_if_empty values.
		 *
		 * @param array $smart_option_params array of options arrays = array ( 'slug' , ... )
		 * @param array $options             the options array for this plugin
		 * @param array $options_nojs        the nojs options for this plugin
		 */
		public function create_smart_options( $smart_option_params, &$options, &$options_nojs ) {

			foreach ( $smart_option_params as $slug => $option_params ) {
				$property = $slug;

				$option_params['slug'] = $slug;
				$this->{$property}       = new SLP_Option( $option_params );

				if ( $this->$property->use_in_javascript ) {
					$options[ $property ] = $this->$property->default;
				} else {
					$options_nojs[ $property ] = $this->$property->default;
				}

				// Text Option
				if ( $this->$property->is_text ) {
					$this->text_options[] = $property;
				}

				// List of Smart Option Slugs
				//
				$this->smart_properties[] = $slug;

				// Page Layout
				//
				if ( ! empty( $this->$property->page ) ) {
					if ( empty( $this->$property->section ) ) {
						$this->$property->section = 'default';
					}
					if ( empty ( $this->$property->group ) ) {
						$this->$property->group = 'default';
					}

					$this->page_layout[ $this->$property->page ][ $this->$property->section ][ $this->$property->group ][] = $slug;
				}

			}
		}

		/**
		 * Execute the stack of change callbacks.
		 *
		 * Use this to run callbacks after all options have been updated.
		 */
		public function execute_change_callbacks() {
			if ( ! empty( $this->change_callbacks ) ) {
				foreach ( $this->change_callbacks as $callback_info ) {
					call_user_func_array( $callback_info[0], $callback_info[1] );
					$this->change_callbacks = array();
				}
			}
		}

		/**
		 * Does the specified slug exist as a smart option?
		 *
		 * @param string $property
		 *
		 * @return boolean
		 */
		public function exists( $property ) {
			return property_exists( $this, $property );
		}

		/**
		 * Return the property formatted option name.
		 *
		 * @param $property
		 *
		 * @return string
		 */
		public function get_option_name( $property ) {
			if ( property_exists( $this, $property ) ) {
				$base_setting = $this->$property->use_in_javascript ? 'options' : 'options_nojs';

				return "${base_setting}[{$property}]";
			}

			return $property;
		}

		/**
		 * Get string defaults.
		 *
		 * @param string $key key name for string to translate
		 *
		 * @return string
		 */
		private function get_string_default( $key ) {
			$this->slplus->create_object_text_manager();
			$text_to_return = $this->slplus->text_manager->get_text_string( array( 'option_default', $key ) );
			if ( empty( $text_to_return ) ) {
				$text_to_return = apply_filters( 'slp_string_default', '', $key );
			}

			return $text_to_return;
		}

		/**
		 * Return a list of option slugs that are text options.
		 *
		 * @return string[]
		 */
		public function get_text_options() {
			if ( ! isset( $this->text_options ) ) {
				$smart_options = get_object_vars( $this );
				foreach ( $smart_options as $slug => $option ) {
					if ( $option->is_text ) {
						$this->text_options[] = $option->slug;
					}
				}
			}

			return $this->text_options;
		}

		/**
		 * Things we do once after the plugins are loaded.
		 */
		public function initialize_after_plugins_loaded() {
			$this->set_text_string_defaults();
			$this->slp_specific_setup();
		}

		/**
		 * Things we do when a new map center is set.
		 *
		 * TODO: look up the address and set the lat/long.
		 *
		 * @param $key
		 * @param $old_val
		 * @param $new_val
		 */
		public function map_center_change( $key, $old_val, $new_val ) {
			$this->map_center_lng->value             = null;
			$this->slplus->options['map_center_lng'] = null;

			$this->map_center_lat->value             = null;
			$this->slplus->options['map_center_lat'] = null;

			$this->slplus->recenter_map();

			$this->recalculate_initial_distance( $key, $old_val, $new_val );
		}

		/**
		 * Recalculate the initial distance for a location from the map center.
		 *
		 * Called if 'distance_unit' changes.
		 */
		public function recalculate_initial_distance( $key, $old_val, $new_val ) {
			if ( ! $this->initial_distance_already_calculated ) {
				$this->slplus->create_object_location_manager();
				$this->slplus->location_manager->recalculate_initial_distance();
				$this->initial_distance_already_calculated = true;
			}
		}

		/**
		 * Set the smart option value and the legacy options/options_nojs
		 *
		 * @param $property
		 * @param $value
		 */
		public function set( $property, $value ) {
			if ( property_exists( $this, $property ) ) {
				$this->$property->value = $value;

				if ( $this->$property->use_in_javascript ) {
					if ( method_exists( $this, 'set_valid_options ' ) ) {
						$this->set_valid_options( $value, $property );
					}
				} else {
					if ( method_exists( $this, 'set_valid_options_nojs ' ) ) {
						$this->set_valid_options_nojs( $value, $property );
					}
				}
			}
		}

		/**
		 * Get the parameters needed for the SLP_Settings entry.
		 *
		 * @param array $params
		 *
		 * @return array
		 */
		public function get_setting_params( $params ) {
			$option                = $this->slplus->smart_options->{$params['option']};
			$params['option_name'] = 'smart_option';
			$params['use_prefix']  = false;

			$params['type'] = $option->type;

			$params['value']       = $option->value;
			$params['selectedVal'] = $params['value'];

			$params['setting'] = $this->slplus->smart_options->get_option_name( $params['option'] );
			$params['name']    = $params['setting'];

			$params['show_label'] = $option->show_label;
			if ( $params['show_label'] ) {
				$params['label'] = $option->label;
			}

			$params['description'] = $option->description;

			$params['custom'] = $option->options;

			$params['empty_ok'] = true;

			unset( $params['option'] );
			unset( $params['plugin'] );

			return $params;
		}

		/**
		 * Set text string defaults.
		 */
		private function set_text_string_defaults() {
			foreach ( $this->get_text_options() as $key ) {

				if ( array_key_exists( $key, $this->slplus->options ) ) {
					$this->slplus->options[ $key ] = $this->get_string_default( $key );

				} elseif ( array_key_exists( $key, $this->slplus->options_nojs ) ) {
					$this->slplus->options_nojs[ $key ] = $this->get_string_default( $key );

				}
			}
		}

		/**
		 * Initialize the options properties from the WordPress database.
		 *
		 * Called by MySLP Dashboard.
		 */
		public function slp_specific_setup() {
			do_action( 'start_slp_specific_setup' );

			// Set options defaults to values set in property definition above.
			//
			$this->slplus->options['map_home_icon'] = SLPLUS_ICONURL . 'box_yellow_home.png';
			$this->slplus->options['map_end_icon']  = SLPLUS_ICONURL . 'bulb_azure.png';

			// Serialized Options from DB for JS parameters
			//
			$this->slplus->options_default = $this->slplus->options;
			$dbOptions                     = $this->slplus->option_manager->get_wp_option( 'js' );
			if ( is_array( $dbOptions ) ) {
				array_walk( $dbOptions, array( $this, 'set_valid_options' ) );
			}

			// Map Center Fallback
			//
			$this->slplus->recenter_map();

			// Load serialized options for noJS parameters
			//
			$this->slplus->options_nojs_default = $this->slplus->options_nojs;
			$dbOptions                          = $this->slplus->option_manager->get_wp_option( 'nojs' );
			if ( is_array( $dbOptions ) ) {
				array_walk( $dbOptions, array( $this, 'set_valid_options_nojs' ) );
			}
			$this->slplus->javascript_is_forced = $this->slplus->is_CheckTrue( $this->slplus->options_nojs['force_load_js'] );

			$this->set_to_default_if_empty();
		}

		/**
		 * Set incoming REQUEST checkboxes for the current admin page.
		 */
		public function set_checkboxes() {
			$this->set_current_checkboxes();
			if ( is_array( $this->current_checkboxes ) ) {
				foreach ( $this->current_checkboxes as $property ) {
					$which_option = $this->$property->use_in_javascript ? 'options' : 'options_nojs';
					if ( isset( $_REQUEST[ $which_option ][ $this->$property->slug ] ) ) {
						continue;
					}
					$_REQUEST[ $which_option ][ $this->$property->slug ] = '0';
				}
			}
		}

		/**
		 * Builds a list of checkboxes for the current admin settings page.
		 */
		private function set_current_checkboxes() {
			if ( empty( $this->current_checkboxes ) ) {
				if ( empty( $this->page_layout[ $this->slplus->current_admin_page ] ) ) {
					return;
				}
				foreach ( $this->page_layout[ $this->slplus->current_admin_page ] as $sections ) {
					foreach ( $sections as $groups ) {
						foreach ( $groups as $property ) {
							if ( $this->$property->type === 'checkbox' ) {
								$this->current_checkboxes[] = $property;
							}
						}
					}
				}
			}
		}

		/**
		 * Set an option in an array only if the key already exists, for empty values set to default.
		 *
		 * @param mixed  $val - the value of a form var
		 * @param string $key - the key for that form var
		 * @param        $option_array
		 * @param        $default_array
		 */
		public function set_valid_option( $val, $key, &$option_array, $default_array ) {

			// A smart option - get initial settings from that
			//
			if ( property_exists( $this, $key ) && ! array_key_exists( $key, $option_array ) ) {
				$option_array[ $key ] = $this->$key->default;
			}

			// Old Option Mapping (slplus->options or slplus->options_nojs by reference
			// determined by how this method is called
			if ( array_key_exists( $key, $option_array ) ) {
				$original_value = $option_array[ $key ];

				// Value specified
				//
				if ( is_numeric( $val ) || is_bool( $val ) || ! empty( $val ) ) {
					$option_array[ $key ] = is_string( $val ) ? stripslashes( $val ) : $val;

					// No value or empty - set to default from smart options first then default array
					//
				} else {
					if ( property_exists( $this, $key ) ) {
						$option_array[ $key ] = $this->$key->default;
					} else {
						$option_array[ $key ] = $default_array[ $key ];
					}
				}

				// Set smart option value to our options array value
				//
				if ( property_exists( $this, $key ) ) {

					// Value Changed
					//
					if ( $original_value !== $val ) {

						if ( isset( $this->$key->value ) ) {
							$original_value = $this->$key->value;
						} else {
							$original_value = null;
						}

						$this->$key->value = $option_array[ $key ];

						if ( ! is_null( $original_value ) ) {
							// Callback on change
							//
							if ( ! is_null( $this->$key->call_when_changed ) ) {
								$this->change_callbacks[] = array(
									$this->$key->call_when_changed,
									array( $key, $original_value, $this->$key->value ),
								);
							}
						}
					}
				}
			}
		}

		/**
		 * Set valid slplus->options and copy to smart_options
		 *
		 * @param $val
		 * @param $key
		 */
		public function set_valid_options( $val, $key ) {
			$this->set_valid_option( $val, $key, $this->slplus->options, $this->slplus->options_default );
		}

		/**
		 * Set valid slplus->options_nojs and copy to smart_options
		 *
		 * @param $val
		 * @param $key
		 */
		public function set_valid_options_nojs( $val, $key ) {
			$this->set_valid_option( $val, $key, $this->slplus->options_nojs, $this->slplus->options_nojs_default );
		}

		/**
		 * If option is empty then set it to the default.
		 *
		 * Defaults are in the slplus->defaults property.
		 *
		 * options              defaults
		 * bubblelayout         bubblelayout
		 *                      layout
		 *                      maplayout
		 * results_layout       resultslayout
		 *                      searchlayout
		 */
		private function set_to_default_if_empty() {
			foreach ( $this->slplus->defaults as $name => $value ) {
				if ( empty( $this->slplus->options[ $name ] ) && ( ! empty( $value ) ) ) {
					$this->slplus->options[ $name ] = $value;
				}
			}
		}
	}

endif;