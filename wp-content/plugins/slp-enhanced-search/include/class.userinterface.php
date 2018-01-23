<?php
if (! class_exists('SLPEnhancedSearch_UI')) {
	require_once(SLPLUS_PLUGINDIR.'/include/base_class.userinterface.php');

    /**
     * Holds the UI-only code.
     *
     * This allows the main plugin to only include this file in the front end
     * via the wp_enqueue_scripts call.   Reduces the back-end footprint.
	 *
	 * @property		SLPEnhancedSearch		$addon
	 * @property-read	boolean					$attribute_allow_addy_is_set		True if the allow_addy_in_url is present in the slplus shortcode.
	 * @property-read	boolean					$is_address_in_url					Used when testing if address was passed succesfully in URL and is valid to process.
	 * @property-read	boolean 				$shortcode_attributes_processed		Have shortcode attributes been processed?
	 * @property-read	array					$shortcode_options_combo			Shortcode attributes we accept that must be set to a value of A|B\C
	 * @property-read	array					$shortcode_options_unrestricted		Shortcode attributes we accept that are set to a user-defined value
     *
     * @package StoreLocatorPlus\EnhancedSearch\UI
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014 - 2015 Charleston Software Associates, LLC
     */
    class SLPEnhancedSearch_UI extends SLP_BaseClass_UI {
        public $addon;
	    private $attribute_allow_addy_is_set = false;
	    private $is_address_in_url = null;
	    private $shortcode_attributes_processed = false;
	    private $shortcode_options_combo = array (
		    'city_selector'         => array ('discrete' => 'dropdown_discretefilter', 'input' => 'dropdown_addressinput', 'hidden' => 'hidden'),
		    'country_selector'      => array ('discrete' => 'dropdown_discretefilter', 'input' => 'dropdown_addressinput', 'hidden' => 'hidden'),
		    'state_selector'        => array ('discrete' => 'dropdown_discretefilter', 'input' => 'dropdown_addressinput', 'hidden' => 'hidden'),
		    'radius_behavior'       => array ('use' => 'always_use', 'ignore_with_blank_addr' => 'ignore_with_blank_addr', 'hidden' => 'always_ignore'),
	   		);
	    private $shortcode_options_unrestricted = array (
		    'append_to_search'          ,
		    'city'                      ,
		    'country'                   ,
		    'initial_results_returned'  ,
		    'state'                     ,
	    );

        /**
         * Add WordPress and SLP hooks and filters.
         */
        public function add_hooks_and_filters() {
			parent::add_hooks_and_filters();

	        add_filter( 'slp_searchlayout'              , array( $this , 'filter_ModifySearchLayout'        ) , 5   ); // This needs to run first to load the custom search layout.
	        add_filter( 'shortcode_slp_searchelement'   , array( $this , 'filter_ProcessSearchElement'      )       );
	        add_filter( 'slp_search_form_html'          , array( $this , 'modify_SearchForm'                )       );
	        add_filter( 'slp_search_default_address'    , array( $this , 'set_SearchAddressFromRequest'     )       );
	        add_filter( 'slp_js_options'                , array( $this , 'filter_ModifyJSOptions'           )       );
	        add_filter( 'slp_shortcode_atts'            , array( $this , 'filter_SetAllowedShortcodeAtts'   ),90, 3 );
	        add_filter( 'slp_map_html'                  , array( $this , 'filter_ModifyMapOutput'           ),90    );
	        add_filter( 'slp_change_ui_radius_selector' , array( $this , 'filter_ui_radius_selector'        )       );
            add_filter( 'shortcode_slp_option'          , array( $this , 'augment_slp_option_shortcode'     )       );

            add_filter( 'slp_find_button_text'              , array( $this , 'change_find_button_text'          )           );

        }

        /**
         * Change the find button label to what we have set in this plugin.
         *
         * @param  string $find_label
         * @return string
         */
        public function change_find_button_text( $find_label ) {
            return $this->addon->options['label_for_find_button'];
        }

	    /**
	     * Modify the slplus.options object going into SLP.js
	     *
	     * @param mixed[] $options
	     * @return mixed[]
	     */
	    function filter_ModifyJSOptions($options) {
		    $es_options = array(
				'address_autocomplete' 	=> $this->addon->options['address_autocomplete'] 	,
				'address_autocomplete_min' 	=> $this->addon->options['address_autocomplete_min'] 	,
			    'append_to_search'  	=> $this->addon->options['append_to_search']		,
				'searchnear'        	=> $this->addon->options['searchnear']				,
				'selector_behavior' 	=> $this->addon->options['selector_behavior']
		    );

		    if (
			    ! empty( $_REQUEST['address'] ) &&
			    $this->is_address_passed_by_URL()
		    ) {
			    $es_options['immediately_show_locations'] = '1';
			    $es_options['use_sensor']                 = false;
		    }

		    return array_merge( $options, $es_options );
	    }

	    /**
	     * Modify the map layout.
	     *
	     * @param string $HTML
	     *
	     * @return string
	     */
	    function filter_ModifyMapOutput( $HTML ) {
		    if ( $this->is_address_passed_by_URL() ) {
			    $HTML =
				    str_replace(
					    '<div id="map_box_map">',
					    '<div id="map_box_map" style="display:block;">',
					    $HTML
				    );
		    }
		    return $HTML;
	    }

	    /**
	     * Change the search form layout, hide it, etc.
	     *
	     * Shortcode attribute takes precedence, then check the map settings hide search form.
	     *
	     * @param $layout
	     *
	     * @return string
	     */
	    function filter_ModifySearchLayout($layout) {
		    $alwaysOutput = '';

		    // Ignore Radius Set, possibly in shortcode attribute, make sure it is on the form.
		    // If radius behavior is set to always ignore
		    // Or if it is set to ignore on blank address and the address is indeed blank
		    // note: this exact logic is used in 2 places, here and sql distance
		    //
		    $ignore_radius_value = ( $this->addon->options['radius_behavior'] == "always_ignore" ? '1':'0');
		    $alwaysOutput .= "<input type='hidden' name='ignore_radius' id='ignore_radius' value='{$ignore_radius_value}' />" ; // Needs documentation

            // Always retain hidden fields.
            //
            $type_hidden = '(?=[^>]*type=["\']hidden)';
            $pattern = "/(<input\b\s+{$type_hidden}[^>]*>)/im";
            preg_match_all($pattern,$layout,$matches);
            foreach ($matches[0] as $match) {
                $alwaysOutput .= $match;
            }

		    // Hide Search Form
		    //
		    $hide_search_form = isset( $this->slplus->options['hide_search_form'] )         ?
			    $this->slplus->is_CheckTrue( $this->slplus->options['hide_search_form'] )   :
			    $this->slplus->is_CheckTrue( $this->addon->options['hide_search_form'] )    ;
		    if ( $hide_search_form ) { return $alwaysOutput; }

		    // Custom Layout
		    //
		    if ( ! empty( $this->addon->options['searchlayout'] )) {
			    $layout = $this->addon->options['searchlayout'];
		    }

		    // Hide Address Input
		    //
		    if ( $this->slplus->is_CheckTrue( $this->addon->options['hide_address_entry'] ) ) {
			    $layout = preg_replace('/\[slp_search_element\s+.*input_with_label="address".*\]/i' , '' , $layout);
		    }

		    // Add Name Search
		    //
		    if (
			    ( $this->slplus->is_CheckTrue( $this->addon->options['search_by_name'] ) )
			    &&
			    (!preg_match('/\[slp_search_element\s+.*input_with_label="name".*\]/i',$layout))
		    ){
			    $layout .= '[slp_search_element input_with_label="name"]';
		    }

		    // Add City Dropdown
		    //
		    if ( ! preg_match( '/\[slp_search_element\s+.*dropdown_with_label="city".*\]/i' , $layout ) ) {
			    $layout .= '[slp_search_element dropdown_with_label="city"]';
		    }

		    // Add State Dropdown
		    //
		    if (
			    ($this->addon->options['state_selector'] !== 'hidden')
			    &&
			    (!preg_match('/\[slp_search_element\s+.*dropdown_with_label="state".*\]/i',$layout))
		    ){
			    $layout .= '[slp_search_element dropdown_with_label="state"]';
		    }


		    // Add Country Dropdown
		    //
		    if (
			    ($this->addon->options['country_selector'] !== 'hidden')
			    &&
			    (!preg_match('/\[slp_search_element\s+.*dropdown_with_label="country".*\]/i',$layout))
		    ){
			    $layout .= '[slp_search_element dropdown_with_label="country"]';
		    }

		    return $layout.$alwaysOutput;
	    }

	    /**
	     * Perform extra search form element processing.
	     *
	     * @param mixed[] $attributes
	     *
	     * @return array
	     */
	    function filter_ProcessSearchElement($attributes) {
		    foreach ($attributes as $name=>$value) {

			    switch (strtolower($name)) {

				    case 'dropdown_with_label':
					    switch ($value) {

						    case 'city':
							    return array(
								    'hard_coded_value' =>
									    ($this->addon->options['city_selector']!=='hidden') ?
										    $this->createstring_CitySelector()        :
										    "<input type='hidden' id='addressInputCity' name='addressInputCity' value='{$this->addon->options['city']}' />"
							        );

						    case 'country':
							    return array(
								    'hard_coded_value' =>
									    ($this->addon->options['country_selector']!=='hidden') ?
										    $this->createstring_CountrySelector()     :
										    "<input type='hidden' id='addressInputCountry' name='addressInputCountry' value='{$this->addon->options['country']}' />"
							        );

                            case 'radius':
                                return array(
                                    'hard_coded_value' => $this->create_string_radius_selector()
                                    );

                            case 'state':
							    return array(
								    'hard_coded_value' =>
									    ($this->addon->options['state_selector']!=='hidden') ?
										    $this->createstring_StateSelector()         :
										    "<input type='hidden' id='addressInputState' name='addressInputState' value='{$this->addon->options['state']}' />"
							        );

						    default:
							    break;
					    }
					    break;

				    case 'input_with_label':
					    switch ($value) {
						    case 'address':
							    return array(
								    'hard_coded_value'  =>
									    $this->slplus->UI->createstring_DefaultSearchDiv_Address($this->addon->options['address_placeholder'])
							    );

						    case 'name':
							    return array(
								    'hard_coded_value' =>
									    $this->slplus->UI->createstring_InputDiv(
										    'nameSearch',
										    get_option('sl_name_label',__('Name of Store','csa-slp-es')),
										    $this->addon->options['name_placeholder'],
										    ($this->addon->options['search_by_name'] === '0'),
										    'div_nameSearch'
									    )
							    );
							    break;

						    default:
							    break;
					    }
					    break;

					/**
					 * Output the value of an Enhanced Search option value.
					 * [search_element label_value="label_for_city_selector"]
					 */
					case 'option_value':
						return array(
							'hard_coded_value' => $this->addon->options[$value]
						);
						break;

				    default:
					    break;
			    }
		    }
		    return $attributes;
	    }

	    /**
	     * Extends the main SLP shortcode approved attributes list, setting defaults.
	     *
	     * This will extend the approved shortcode attributes to include the items listed.
	     * The array key is the attribute name, the value is the default if the attribute is not set.
	     *
	     * @param mixed[] $attArray current list of approved attributes
	     * @param mixed[] $attributes the shortcode attributes as entered by the user
	     * @param string $content
	     *
	     * @return array
	     */
	    function filter_SetAllowedShortcodeAtts($attArray,$attributes,$content) {
		    $allowed_atts = array();

		    // Shortcode settable on/off switches
		    //
		    $attribute_names = array(
			    'allow_addy_in_url' ,
			    'hide_address_entry',
			    'hide_search_form'  ,
			    'hide_search_form',
		    );
		    foreach ( $attribute_names as $attname ) {
			    if ( isset( $attributes[$attname] ) ) {
				    $this->addon->options[$attname] = $this->slplus->is_CheckTrue( $attributes[$attname] , 'string' );
				    if ( $attname === 'allow_addy_in_url' ) { $this->attribute_allow_addy_is_set = true; }
			    }
			    $allowed_atts[ $attname ] = $this->addon->options[ $attname ];
		    }

		    // Shortcode Atts with Values (not on/off)
		    // Set option value base on shorcode attr, value is the attribute value
		    //
		    foreach ( $this->shortcode_options_unrestricted as $attname ) {
			    if ( isset( $attributes[$attname] ) ) {
				    $this->addon->options[$attname] = $attributes[$attname];
			    }
			    $allowed_atts[$attname] = $this->addon->options[$attname];

			    // Make an exception for this setting that is in the base plugin options.
			    if ($attname === 'initial_results_returned' ) {
				    $allowed_atts[$attname] = $this->slplus->options[$attname];
			    }
		    }

		    // Shortcode Atts with combo values
		    // Set option value base on shorcode attr, find the value in $valuePairs
		    //
		    foreach ($this->shortcode_options_combo as $attname => $valuePairs) {
			    if (isset($attributes[$attname]) && isset($valuePairs[$attributes[$attname]])) {
				    $this->addon->options[$attname] = $valuePairs[$attributes[$attname]];
			    }
			    $allowed_atts[$attname] = $this->addon->options[$attname];
		    }

		    // Return the allowed attributes merged with our updated array.
		    // note: array_merge later values take precedence if there is a key match.
		    //
		    $this->shortcode_attributes_processed = true;
		    return array_merge($attArray, $allowed_atts);
	    }

	    /**
	     * Modify the UI radius selector according to the selected behavior.
	     *
	     * behavior 'always_ignore' hides the radius.
	     *
	     * @param $radius_HTML
	     *
	     * @return mixed
	     */
	    function filter_ui_radius_selector($radius_HTML ) {
		    if ($this->addon->options['radius_behavior'] === 'always_ignore') {
				return '';	    // hide the radius options selector

		    }
			return $radius_HTML;
	    }

	    /**
	     * Make inline changes to the search form.
	     *
	     * @param string $currentHTML
	     *
	     * @return string
	     */
	    function modify_SearchForm($currentHTML) {

		    // Address Placeholder
		    //
		    // <input type='text' id='addressInput' placeholder='' size='50' value='' />
		    //
		    $pattern = "/<input(.*?)id='addressInput'(.*?)placeholder=''(.*?)>/";
		    $placeholder = $this->addon->options['address_placeholder'];
		    $replacement = '<input${1}id="addressInput"${2}placeholder="'.$placeholder.'"${3}>';
		    $currentHTML = preg_replace($pattern,$replacement,$currentHTML);

		    // Name Placeholder
		    //
		    $pattern = "/<input(.*?)id='nameSearch'(.*?)placeholder=''(.*?)>/";
		    $placeholder = $this->addon->options['name_placeholder'];
		    $replacement = '<input${1}id="nameSearch"${2}placeholder="'.$placeholder.'"${3}>';
		    $currentHTML = preg_replace($pattern,$replacement,$currentHTML);

		    return $currentHTML;
	    }

	    /**
	     * Sets the search form address input on the UI to a post/get var.
	     *
	     * The option "allow address in URL" needs to be on.
	     *
	     * @param string $currentVal
	     *
	     * @return string the default input value
	     */
	    function set_SearchAddressFromRequest($currentVal) {
		    if ($this->is_address_passed_by_URL() && empty($currentVal)) {
			    return stripslashes_deep($_REQUEST['address']);
		    }
		    return $currentVal;
	    }
	    
	    /**
	     * Create the city selector HTML.
	     *
	     * @return string
	     */
	    function createstring_CitySelector() {
		    $onChange =
			    ($this->addon->options['city_selector'] === 'dropdown_discretefilter') ?
				    ''                                                              :
				    'aI=document.getElementById("searchForm").addressInput;if(this.value!=""){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}';
		    return
			    "<div id='addy_in_city' class='search_item es_category_selector'>".
			    "<label for='addressInputCity'>".
			    $this->addon->options['label_for_city_selector'] .
			    '</label>'.
			    "<select id='addressInputCity' name='addressInputCity' onchange='$onChange'>".
			    "<option value=''>".
			    get_option(SLPLUS_PREFIX.'_search_by_city_pd_label',__('--Search By City--','csa-slp-es')).
			    '</option>'.
			    $this->createstring_CityPD().
			    '</select>'.
			    '</div>'
			    ;
	    }
	    
	    /**
	     * Create the city pulldown list, mark the checked item.
	     *
	     * @return string
	     */
	    private function createstring_CityPD() {
		    $myOptions = '';
		    $cs_array=$this->slplus->db->get_results(
			    "SELECT CONCAT(TRIM(sl_city), ', ', TRIM(sl_state)) as city_state " .
			    "FROM ".$this->slplus->db->prefix."store_locator " .
			    "WHERE sl_city<>'' AND sl_latitude<>'' AND sl_longitude<>'' " .
			    "GROUP BY city_state " .
			    "ORDER BY city_state ASC",
			    ARRAY_A);

		    if ($cs_array) {
			    foreach($cs_array as $sl_value) {
				    $sl_value['city_state'] = preg_replace('/, $/','',$sl_value['city_state']);
				    $myOptions.="<option value='$sl_value[city_state]'>$sl_value[city_state]</option>";
			    }
		    }
		    return $myOptions;
	    }

	    /**
	     * Create the country pulldown list, mark the checked item.
	     *
	     * @return string
	     */
	    private function createstring_CountryPD() {
		    $myOptions = '';

		    add_filter( 'slp_location_where' , array( $this->slplus->database , 'filter_SetWhereValidLatLong' ));
		    $cs_array=$this->slplus->database->get_Record(
			    array( 'select_country' , 'where_valid_country' , 'group_by_country' , 'order_by_country' ) ,
			    array(),
			    0,
			    ARRAY_A,
			    'get_col'
		    );

		    // If we have state data show it in the pulldown
		    //
		    if ($cs_array) {
			    foreach($cs_array as $sl_value) {
				    $myOptions.=
					    "<option value='$sl_value'>" .
					    $sl_value."</option>";
			    }
		    }
		    return $myOptions;
	    }

	    /**
	     * Create the country drop down input for the search form.
	     */
	    function createstring_CountrySelector() {
		    $onChange =
			    ($this->addon->options['country_selector'] === 'dropdown_discretefilter') ?
				    ''                                                                 :
				    'aI=document.getElementById("searchForm").addressInput;if(this.value!=""){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}' ;
		    return
			    "<div id='addy_in_country' class='search_item  es_category_selector'>".
			    "<label for='addressInputCountry'>".
			    $this->addon->options['label_for_country_selector'] .
			    '</label>'.
			    "<select id='addressInputCountry' name='addressInputCountry' onchange='$onChange'>".
			    "<option value=''>".
			    get_option(SLPLUS_PREFIX.'_search_by_country_pd_label',__('--Search By Country--','csa-slp-es')).
			    '</option>'.
			    $this->createstring_CountryPD().
			    '</select>'.
			    '</div>'
			    ;
	    }


        /**
         * Create the radius selector string.
         */
        private function create_string_radius_selector() {
            if ( $this->slplus->is_CheckTrue( get_option(SLPLUS_PREFIX.'_hide_radius_selections',0) ) ) {
                $HTML = $this->slplus->data['radius_options'];
            } else {
                $HTML =
                    "<div id='addy_in_radius' class='search_item  es_category_selector'>".
                    "<label for='radiusSelect'>".
                    $this->slplus->WPML->get_text('sl_radius_label', get_option('sl_radius_label',__('Within','csa-slp-es'))).
                    '</label>'.
                    "<select id='radiusSelect'>".$this->slplus->data['radius_options'].'</select>'.
                    "</div>"
                ;
            }
            return apply_filters( 'slp_change_ui_radius_selector' , $HTML ) ;
        }
	    
	    /**
	     * Create the state pulldown list, mark the checked item.
	     *
	     * @return string
	     */
	    private function createstring_StatePD() {
		    $myOptions = '';

            add_filter( 'slp_location_where' , array( $this->slplus->database , 'filter_SetWhereValidLatLong' ));
		    $cs_array=$this->slplus->database->get_Record(
                array( 'select_states' , 'where_valid_state' , 'group_by_state' , 'order_by_state' ) ,
                array(),
                0,
                ARRAY_A,
                'get_col'
            );

		    // If we have state data show it in the pulldown
		    //
		    if ($cs_array) {
			    foreach($cs_array as $sl_value) {
				    $myOptions.=
					    "<option value='$sl_value'>" .
					    $sl_value."</option>";
			    }
		    }
		    return $myOptions;
	    }

	    /**
	     * Add State pulldown to search form.
	     *
	     * @return string
	     */
	    function createstring_StateSelector() {
		    $onChange =
			    ($this->addon->options['state_selector'] === 'dropdown_discretefilter') ?
				    ''                                                              :
				    'aI=document.getElementById("searchForm").addressInput;if(this.value!=""){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}';
		    return
			    "<div id='addy_in_state' class='search_item es_category_selector'>".
			    "<label for='addressInputState'>".
			    $this->addon->options['label_for_state_selector'] .
			    '</label>'.
			    "<select id='addressInputState' name='addressInputState' onchange='$onChange'>".
			    "<option value=''>".
			    get_option(SLPLUS_PREFIX.'_search_by_state_pd_label',__('--Search By State--','csa-slp-es')).
			    '</option>'.
			    $this->createstring_StatePD().
			    '</select>'.
			    (($this->addon->options['state_selector'] === 'dropdown_discretefilter' || $this->addon->options['state_selector'] === 'dropdown_discretefilteraddress')?
				    '<input type="hidden" name="state_selector_discrete" value="on" />':'').
			    '</div>'
			    ;
	    }

		/**
		 * Only enqueue userinterface.js if custom selector behavior is used.
		 */
		function enqueue_ui_javascript() {
			if (
				($this->addon->options['selector_behavior'] 	!== 'use_both') ||
				($this->addon->options['address_autocomplete']	!== 'none'    )
			) {

				// TODO: if address_autocomplete
				if ( true ) {
					$this->js_requirements = array( 'jquery-ui-autocomplete' );
				}

				parent::enqueue_ui_javascript();
			}
		}

	    /**
	     * Return true if the address was passed in via the URL and that option is enabled.
	     *
	     * @return boolean
	     */
	    function is_address_passed_by_URL() {

		    // This will not work if shortcode attributes have not been processed.
		    // Return false as the default case.
		    //
		    if (! $this->shortcode_attributes_processed ) { return false; }

		    if ($this->is_address_in_url === null) {

			    // Shortcode allow_addy_in_url is used and set to true.
			    //
			    $shortcode_is_on = $this->attribute_allow_addy_is_set && $this->slplus->is_CheckTrue($this->slplus->data['allow_addy_in_url']);

			    // Option is turned on
			    //
			    $option_enabled = $this->slplus->is_CheckTrue(get_option('csl-slplus-es_allow_addy_in_url',0));

			    // Check Address In URL
			    //
			    $this->is_address_in_url = (($shortcode_is_on || ($option_enabled && !$this->attribute_allow_addy_is_set)) && isset($_REQUEST['address']));
		    }

		    return $this->is_address_in_url;
	    }	    
    }
}