<?php
if ( ! class_exists( 'SLPEnhancedSearch_Admin_UXSettings' ) ) {

	/**
	 * Class SLPEnhancedSearch_Admin_UXSettings
	 *
	 * The things that modify the Admin / General Settings UX.
	 *
	 * Text Domain: csa-slp-es
	 */
	class SLPEnhancedSearch_Admin_UXSettings {

		/**
		 * @var SLPPremier
		 */
		private $addon;

		/**
		 * @var SLPlus
		 */
		private $slplus;

		/**
		 * Instantiate the admin panel object.
		 *
		 * @param mixed[] $params
		 */
		function __construct( $params ) {
			foreach ( $params as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$this->$property = $value;
				}
			}
		}

		/**
		 * Add Premier settings to UX / Search / Features setting.
		 *
		 * @param $settings
		 * @param $section_name
		 */
		function add_ux_search_feature_settings( $settings , $section_name ) {

			// User Experience / Search / Search Features
			//
			$group_name = __('Search Features' , 'csa-slp-es' );

			$settings->add_ItemToGroup( array(
				'section'       => $section_name                        ,
				'group'         => $group_name                          ,
				'type'          => 'custom'                             ,
				'custom'        => $this->add_search_features_settings( )   ,
				'show_label'    => false
			) );

			// User Experience / Search / Search Labels
			//
			$group_name = __('Search Labels' , 'csa-slp-es' );

			$settings->add_ItemToGroup( array(
					'section'       => $section_name                        ,
					'group'         => $group_name                          ,
					'label'         => $this->addon->name                   ,
					'type'          => 'subheader'                          ,
					'show_label'    => false
			) );

			$settings->add_ItemToGroup( array(
				'section'       => $section_name                        ,
				'group'         => $group_name                          ,
				'type'          => 'custom'                             ,
				'custom'        => $this->add_search_label_settings( )   ,
				'show_label'    => false
			) );



			$settings->add_ItemToGroup( array(

				'label'         => __('Address Placeholder', 'csa-slp-es') ,

				'description'   =>__('Instructions to place in the address input.','csa-slp-es') ,

				'setting'   => SLPLUS_PREFIX.'-ES-options[address_placeholder]'               ,
				'value'     => $this->addon->options['address_placeholder']     ,

				'section'   => $section_name                                    ,
				'group'     => $group_name                                      ,
				'use_prefix'    => false                                        ,
			) );

			$settings->add_ItemToGroup( array(

				'label'         => __('Name Placholder', 'csa-slp-es') ,

				'description'   =>__('Instructions to place in the name input.','csa-slp-es') ,

				'setting'   => SLPLUS_PREFIX.'-ES-options[name_placeholder]'               ,
				'value'     => $this->addon->options['name_placeholder']     ,

				'section'   => $section_name                                    ,
				'group'     => $group_name                                      ,
				'use_prefix'    => false                                        ,
			) );
		}

		/**
		 * Add new settings for the search for to the map settings/search form section.
		 *
	 	 * TODO: convert this to add_ItemToGroup in add_ux_search_feature_settings
		 *
		 * @return null
		 */
		function add_search_features_settings() {

			$ignore_radius_hint = __('Consider setting Radius Behavior to "Use only when address is entered" when using discrete search mode.', 'csa-slp-es');


			$HTML = '';

			// Hide Search Form
			//
			$HTML .= $this->slplus->helper->CreateCheckboxDiv(
				'-ES-options[hide_search_form]',
				__('Hide Search Form','csa-slp-es'),
				__('Hide the user input on the search page, regardless of the SLP theme used.', 'csa-slp-es'),
				null,false,0,
				$this->addon->options['hide_search_form']
			);

			// Hide Address Input
			//
			$HTML .= $this->slplus->helper->CreateCheckboxDiv(
				'-ES-options[hide_address_entry]',
				__('Hide Address Entry','csa-slp-es'),
				__('Hide the address input box on the locator search form.', 'csa-slp-es'),
				null,false,0,
				$this->addon->options['hide_address_entry']
			);

			// Address Autocomplete
			//
			$HTML .= $this->slplus->helper->createstring_DropDownDiv(
				array(
					'id'			=> 'address_autocomplete',
					'name'			=> SLPLUS_PREFIX.'-ES-options[address_autocomplete]',
					'label'			=> __( 'Address Autocomplete' , 'csa-slp-es' ),
					'helptext'		=>
						__( 'When 2 or more characters are typed in the address input, show input suggestions based on location data. ' , 'csa-slp-es') .
						'<br/>' .
						__( 'None (default) - do not suggest address input. ', 'csa-slp-es' ) .
						'<br/>' .
						__( ' Zipcode - suggest matching zip codes. ' , 'csa-slp-es' )
				,
					'selectedVal'	=> $this->addon->options['address_autocomplete'],
					'items'			=> array(
						array(
							'label'	=> __( 'None' , 'csa-slp-es' ),
							'value'	=> 'none',
						),
						array(
							'label'	=> __( 'Zipcode' , 'csa-slp-es' ),
							'value'	=> 'zipcode',
						),
					)
				)
			);

			// Show Search By Name
			//
			$HTML .= $this->slplus->helper->CreateCheckboxDiv(
				'-ES-options[search_by_name]',
				__('Show Search By Name', 'csa-slp-es'),
				__('Shows the name search entry box to the user.', 'csa-slp-es'),
				null,false,0,
				$this->addon->options['search_by_name']
			);

			// Radius
			//
			$HTML .= $this->slplus->helper->CreateCheckboxDiv(
					'_hide_radius_selections',
					__('Hide Radius Selections','csa-slp-es'),
					__('Hide the radius selection drop down.', 'csa-slp-es')
				);

			$HTML .= $this->slplus->helper->createstring_DropDownDiv(
				         array(
					         'id'        => 'radius_behavior',
					         'name'      => SLPLUS_PREFIX.'-ES-options[radius_behavior]',
					         'label'     => __('Radius Behavior','csa-slp-es'),
					         'helptext'  =>
						         __('Show the city selector on the search form.', 'csa-slp-es') .
						         sprintf(__('View the <a href="%s" target="csa">documentation</a> for more info. ','csa-slp-es'),$this->slplus->support_url) .
						         __('If you want the option to search by address OR by city on the same form, select: Use radius only with address. If you choose not to use radius, you consider also hiding the radius selector.', 'csa-slp-es')
				         ,
					         'selectedVal' => $this->addon->options['radius_behavior'],
					         'items' => array (
						         array(
							         'label' => __('Do not use','csa-slp-es'),
							         'value' => 'always_ignore',
						         ),
						         array(
							         'label' => __('Use only when address is entered','csa-slp-es'),
							         'value' => 'ignore_with_blank_addr',
						         ),
						         array(
							         'label' => __('Always use','csa-slp-es'),
							         'value' => 'always_use',
						         ),

					         )
				         )
			         );


			 // City Selector
			 //
			$selector_label = ( ! empty( $this->addon->options['label_for_city_selector'] ) ) ? $this->addon->options['label_for_city_selector'] : __('City', 'csa-slp-es');
			$HTML .= $this->slplus->helper->createstring_DropDownDiv(
				         array(
					         'id'        => 'city_selector',
					         'name'      => SLPLUS_PREFIX.'-ES-options[city_selector]',
					         'label'     => sprintf( __('%s Selector','csa-slp-es') , $selector_label ) ,
					         'helptext'  =>
						         __('Show the city selector on the search form.', 'csa-slp-es') .
						         sprintf(__('View the <a href="%s" target="csa">documentation</a> for more info. ','csa-slp-es'),$this->slplus->support_url) .
						         $ignore_radius_hint
				         ,
					         'selectedVal' => $this->addon->options['city_selector'],
					         'items' => array (
						         array(
							         'label' => __('Hidden','csa-slp-es'),
							         'value' => 'hidden',
						         ),
						         array(
							         'label' => __('Dropdown, Address Input','csa-slp-es'),
							         'value' => 'dropdown_addressinput',
						         ),
						         array(
							         'label' => __('Dropdown, Discrete Filter','csa-slp-es'),
							         'value' => 'dropdown_discretefilter',
						         ),
						         array(
							         'label' => __('Dropdown, Discrete + Address Input','csa-slp-es'),
							         'value' => 'dropdown_discretefilteraddress',
						         ),
					         )
				         )
			         );

			 // State Selector
			 //
			$selector_label = ( ! empty( $this->addon->options['label_for_state_selector'] ) ) ? $this->addon->options['label_for_state_selector'] : __('State', 'csa-slp-es');
			$HTML .= $this->slplus->helper->createstring_DropDownDiv(
				         array(
					         'id'        => 'state_selector',
					         'name'      => SLPLUS_PREFIX.'-ES-options[state_selector]',
					         'label'     => sprintf( __('%s Selector','csa-slp-es') , $selector_label ) ,
					         'helptext'  =>
						         __('Show the state selector on the search form.', 'csa-slp-es') .
						         sprintf(__('View the <a href="%s" target="csa">documentation</a> for more info. ','csa-slp-es'),$this->slplus->support_url) .
						         $ignore_radius_hint
				         ,
					         'selectedVal' => $this->addon->options['state_selector'],
					         'items' => array (
						         array(
							         'label' => __('Hidden','csa-slp-es'),
							         'value' => 'hidden',
						         ),
						         array(
							         'label' => __('Dropdown, Address Input','csa-slp-es'),
							         'value' => 'dropdown_addressinput',
						         ),
						         array(
							         'label' => __('Dropdown, Discrete Filter','csa-slp-es'),
							         'value' => 'dropdown_discretefilter',
						         ),
						         array(
							         'label' => __('Dropdown, Discrete + Address Input','csa-slp-es'),
							         'value' => 'dropdown_discretefilteraddress',
						         ),

					         )
				         )
			         );

			 // Country Selector
			 //
			$selector_label = ( ! empty( $this->addon->options['label_for_country_selector'] ) ) ? $this->addon->options['label_for_country_selector'] : __('Country', 'csa-slp-es');
			$HTML .= $this->slplus->helper->createstring_DropDownDiv(
				         array(
					         'id'        => 'country_selector',
					         'name'      => SLPLUS_PREFIX.'-ES-options[country_selector]',
					         'label'     => sprintf( __('%s Selector','csa-slp-es') , $selector_label ) ,
					         'helptext'  =>
						         __('Show the country selector on the search form.', 'csa-slp-es') .
						         sprintf(__('View the <a href="%s" target="csa">documentation</a> for more info. ','csa-slp-es'),$this->slplus->support_url) .
						         $ignore_radius_hint
				         ,
					         'selectedVal' => $this->addon->options['country_selector'],
					         'items' => array (
						         array(
							         'label' => __('Hidden','csa-slp-es'),
							         'value' => 'hidden',
						         ),
						         array(
							         'label' => __('Dropdown, Address Input','csa-slp-es'),
							         'value' => 'dropdown_addressinput',
						         ),
						         array(
							         'label' => __('Dropdown, Discrete Filter','csa-slp-es'),
							         'value' => 'dropdown_discretefilter',
						         ),
						         array(
							         'label' => __('Dropdown, Discrete + Address Input','csa-slp-es'),
							         'value' => 'dropdown_discretefilteraddress',
						         ),

					         )
				         )
			         );

			// Selector Behavior
			//
			$HTML .= $this->slplus->helper->createstring_DropDownDiv(
				array(
					'id'			=> 'selector_behavior',
					'name'			=> SLPLUS_PREFIX.'-ES-options[selector_behavior]',
					'label'			=> __( 'Selector Behavior' , 'csa-slp-es' ),
					'helptext'		=>
						__( 'Should the address input be disabled when city, state, or country selectors are used? ', 'csa-slp-es' ) .
						__( 'Allow means address and city/state/zip selectors are both left active at all times. ' , 'csa-slp-es' ) .
						__( 'disable means users can interact with either the city/state/zip OR the address but not both at the same time. ', 'csa-slp-es')
				,
					'selectedVal'	=> $this->addon->options['selector_behavior'],
					'items'			=> array(
						array(
							'label'	=> __( 'Allow Selector and Address Input' , 'csa-slp-es' ),
							'value'	=> 'use_both',
						),
						array(
							'label'	=> __( 'Disable Address When Using Selector' , 'csa-slp-es' ),
							'value'	=> 'either_or',
						),
					)
				)
			);

			// Search Near Behavior
			//
			$HTML .= $this->slplus->helper->createstring_DropDownDiv(
				array(
					'id'        => 'searchnear',
					'name'      => SLPLUS_PREFIX.'-ES-options[searchnear]',
					'label'     => __('Search Address Nearest','csa-slp-es'),
					'helptext'  =>
						__('Worldwide is the default search, letting Google make the best guess which addres the user wants.','csa-slp-es') . ' '.
						__('Current Map will find the best matching address nearest the current area shown on the map.','csa-slp-es'),
					'selectedVal' => $this->addon->options['searchnear'],
					'items'     => array(
						array(
							'label' => __('Worldwide','csa-slp-es'),
							'value' => 'world'
						),
						array(
							'label' => __('Current Map','csa-slp-es'),
							'value' => 'currentmap'
						),
					)
				)
			);

			$settings = $this->slplus->AdminUI->Experience;
			$HTML .= $this->layout_textarea(
				'-ES-options[searchlayout]',
				__('Search Layout','csa-slp-es'),
				__('Enter your custom search form layout. ','csa-slp-es') .
				sprintf('<a href="%s" target="csa">%s</a> ',
					$this->slplus->support_url,
					sprintf(__('Uses HTML plus %s shortcodes.','csa-slp-es'),$this->addon->name)
				) .
				__('Set it to blank to reset to the default layout. ','csa-slp-es') .
				__('Overrides all other search form settings.','csa-slp-es')
				,
				SLPLUS_PREFIX,
				$this->addon->options['searchlayout'],
				true
			);

			// TODO: serialize this variable into options or options_nojs
			//
			$HTML .= $settings->CreateInputDiv(
				'-ES-options[append_to_search]',
				__('Append This To Searches','csa-slp-es'),
				__('Anything you enter in this box will automatically be appended to the address a user types into the locator search form address box on your site. ','csa-slp-es'),
				SLPLUS_PREFIX,
				$this->addon->options['append_to_search']
			)
			;


			return $HTML;
		}

		/**
		 * TODO: DEPRECATE when the above search layout call moves to an add_ItemToGroup mechanism.
		 * @param            $boxname
		 * @param string     $label
		 * @param string     $msg
		 * @param string     $prefix
		 * @param string     $default
		 * @param bool|false $usedefault
		 *
		 * @return string
		 */
		private function layout_textarea( $boxname, $label = '', $msg = '', $prefix = SLPLUS_PREFIX, $default = '', $usedefault = false ) {
			$whichbox = $prefix . $boxname;
			$value    =
				$usedefault ?
					$default :
					stripslashes( esc_textarea( get_option( $whichbox, $default ) ) );

			return
				"<div class='form_entry'>" .
				"<div class='wpcsl-input wpcsl-textarea'>" .
				"<label for='$whichbox'>$label:</label>" .
				"<textarea  name='$whichbox'>{$value}</textarea>" .
				"</div>" .
				$this->slplus->helper->CreateHelpDiv( $boxname, $msg ) .
				"</div>";

		}



		/**
		 * Add new custom labels.
		 *
		 * TODO: convert this to add_ItemToGroup in add_ux_search_feature_settings
		 *
		 */
		function add_search_label_settings(  ) {

			$settings = $this->slplus->AdminUI->Experience;

			$HTML =
				$settings->CreateInputDiv(
					'-ES-options[search_box_title]',
					__( 'Search Box Title' , 'csa-slp-es' ),
					__( 'The label that goes in the search form box header, for plugin themes that support it.' , 'csa-slp-es' ) .
					__( 'Put this in a search form using the [slp_option nojs="search_box_title"] shortcode.'   , 'csa-slp-es' )
					,
					SLPLUS_PREFIX,
					$this->addon->options['search_box_title'],
					$this->addon->options['search_box_title']
				) .

				$settings->CreateInputDiv(
                    '-ES-options[label_for_find_button]',
					__('Find Button', 'csa-slp-es'),
					__('The label on the find button, if text mode is selected.','csa-slp-es'),
					SLPLUS_PREFIX,
                    $this->addon->options['label_for_find_button'],
                    $this->addon->options['label_for_find_button']
				) .

				// TODO: serialize this variable into options or options_nojs
				//
				$settings->CreateInputDiv(
					'sl_name_label',
					__('Name', 'csa-slp-es'),
					__('The label that precedes the name input box.','csa-slp-es'),
					'',
					'Name'
				);

			// City
			//
			$selector_label = ( ! empty( $this->addon->options['label_for_city_selector'] ) ) ? $this->addon->options['label_for_city_selector'] : __('City', 'csa-slp-es');
			$HTML .=
				$settings->CreateInputDiv(
					'-ES-options[label_for_city_selector]',
					sprintf( __('%s Selector Label', 'csa-slp-es') , $selector_label ) ,
					__('The label that precedes the city selector.','csa-slp-es'),
					SLPLUS_PREFIX,
					$this->addon->options['label_for_city_selector'],
					$this->addon->options['label_for_city_selector']
				);

			// TODO: serialize this variable into options or options_nojs
			//
			$HTML .= $settings->CreateInputDiv(
					'_search_by_city_pd_label',
					sprintf( __('%s Selector First Entry', 'csa-slp-es') , $selector_label ),
					__('The first entry on the search by city selector.','csa-slp-es'),
					SLPLUS_PREFIX,
					__('--Search By City--','csa-slp-es')
				);

			// State
			//
			$selector_label = ( ! empty( $this->addon->options['label_for_state_selector'] ) ) ? $this->addon->options['label_for_state_selector'] : __('State', 'csa-slp-es');
			$HTML .= $settings->CreateInputDiv(
					'-ES-options[label_for_state_selector]',
					sprintf( __('%s Selector Label', 'csa-slp-es') , $selector_label ) ,
					__('The label that precedes the state selector.','csa-slp-es'),
					SLPLUS_PREFIX,
					$this->addon->options['label_for_state_selector'],
					$this->addon->options['label_for_state_selector']
				);

			// TODO: serialize this variable into options or options_nojs
			//
			$HTML .= $settings->CreateInputDiv(
				'_search_by_state_pd_label',
				sprintf( __('%s Selector First Entry', 'csa-slp-es') , $selector_label ),
				__('The first entry on the search by state selector.','csa-slp-es'),
				SLPLUS_PREFIX,
				__('--Search By State--','csa-slp-es')
				);

			// Country
			//
			$selector_label = ( ! empty( $this->addon->options['label_for_country_selector'] ) ) ? $this->addon->options['label_for_country_selector'] : __('Country', 'csa-slp-es');
			$HTML .= $settings->CreateInputDiv(
				'-ES-options[label_for_country_selector]',
				sprintf( __('%s Selector Label', 'csa-slp-es') , $selector_label ) ,
				__('The label that precedes the country selector.','csa-slp-es'),
				SLPLUS_PREFIX,
				$this->addon->options['label_for_country_selector'],
				$this->addon->options['label_for_country_selector']
				);

				// TODO: serialize this variable into options or options_nojs
				//
			$HTML .= $settings->CreateInputDiv(
					'_search_by_country_pd_label',
					sprintf( __('%s Selector First Entry', 'csa-slp-es') , $selector_label ),
					__('The first entry on the search by country selector.','csa-slp-es'),
					SLPLUS_PREFIX,
					__('--Search By Country--','csa-slp-es')
					);

			return $HTML;
		}

	}
}