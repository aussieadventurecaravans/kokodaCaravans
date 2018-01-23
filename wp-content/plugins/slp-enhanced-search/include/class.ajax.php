<?php
if (! class_exists('SLPEnhancedSearch_AJAX')) {
    require_once(SLPLUS_PLUGINDIR.'/include/base_class.ajax.php');


    /**
     * Holds the ajax-only code.
     *
     * This allows the main plugin to only include this file in AJAX mode
     * via the slp_init when DOING_AJAX is true.
     *
     * @property        SLPEnhancedSearch       $addon
     *
     * @package StoreLocatorPlus\EnhancedSearch\AJAX
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014-2015 Charleston Software Associates, LLC
     */
    class SLPEnhancedSearch_AJAX extends SLP_BaseClass_AJAX {

        /**
         * Things we do to latch onto an AJAX processing environment.
         *
         * Add WordPress and SLP hooks and filters only if in AJAX mode.
         *
         * WP syntax reminder: add_filter( <filter_name> , <function> , <priority> , # of params )
         *
         * Remember: <function> can be a simple function name as a string
         *  - or - array( <object> , 'method_name_as_string' ) for a class method
         * In either case the <function> or <class method> needs to be declared public.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_filter
         *
         */
        public function do_ajax_startup() {
			$this->valid_actions[] = 'slp_list_location_zips';
	        if ( ! $this->is_valid_ajax_action() ) { return; }

	        add_filter( 'slp_location_filters_for_AJAX'         , array( $this , 'filter_JSONP_SearchByStore'       )       );
	        add_filter( 'slp_location_having_filters_for_AJAX'  , array( $this , 'filter_AJAX_AddHavingClause'      ) , 55  );
	        add_filter( 'slp_ajaxsql_fullquery'                 , array( $this , 'filter_JSONP_ModifyFullSQL'       )       );
	        add_filter( 'slp_location_filters_for_AJAX'         , array( $this , 'filter_JSONP_SearchByStore'       )       );

	        add_filter( 'slp_ajaxsql_where'                     , array( $this , 'filter_JSONP_SearchFilters'       ) , 20  );

			// Custom Actions
			//
			add_action('wp_ajax_slp_list_location_zips'    , array( $this , 'list_location_zips' )   );
        }

        //-------------------------------------
        // Methods : Custom
        //-------------------------------------

	    /**
	     * Add to the AJAX having clause
	     *
	     * @param mixed[] having clause array
	     * @return mixed[]
	     */
	    function filter_AJAX_AddHavingClause( $clauseArray ) {

            // Only do this on search
            //
            // MOVE init_OptionsViaAJAX and set_QueryParams outside of here
            // if code is added to perform other AJAX query updates.
            //
            if ( $_REQUEST['action'] === 'csl_ajax_search' ) {
                $this->init_OptionsViaAJAX();
                $this->set_QueryParams();

                // Ignore Radius Is On
                // Or address is blank and ignore if blank is set... strip out all sl_distance clauses
                // this does not check for AddressInputCity, AddressInputState, or AddressInputCountry fields, only the address field on the form.
                //
                if (isset($this->addon->options['radius_behavior'])) {
                    if (
                        ($this->addon->options['radius_behavior'] == "always_ignore") ||
                        ($this->addon->options['radius_behavior'] == "ignore_with_blank_addr" && empty($this->formdata['addressInput']))
                    ) {
                        $clauseArray = array_filter($clauseArray, array($this, 'remove_distance_clauses'));
                    }
                }
            }

		    return $clauseArray;
	    }

		/**
		 * List the zip codes for the locations in the system for autocomplete.
		 *
		 * @return string
		 */
		function list_location_zips() {
			add_filter( 'slp_extend_get_SQL' , array( $this , 'select_location_zips')	 );
			$this->set_autocomplete_vars();
			$sql_parameters = array( $this->formdata['address'] );
			$zips = $this->slplus->database->get_Record( 'select_location_zips', $sql_parameters, 0, ARRAY_A , 'get_col' );
			die( json_encode( $zips ) );
		}

		/**
		 * For autocomplete entries just jam the $_REQUEST variables into the formdata property.
		 */
		function set_autocomplete_vars() {
			$this->formdata = wp_parse_args( $_REQUEST , $this->formdata_defaults );
		}

		/**
		 * Remove any having clauses with sl_distance in them from the DB Query.
		 *
		 * @param $array_value
		 * @return bool
		 */
		function remove_distance_clauses( $array_value ) {
			return ( stripos( $array_value , 'sl_distance' ) === false );
		}

		/**
		 * Database statement to select the location zipcodes.
		 *
		 * @param $command
		 * @return string
		 */
		function select_location_zips( $command ) {
			if ( $command !== 'select_location_zips'  ) { return ''; }
			return
				'SELECT sl_zip ' .
				"FROM {$this->slplus->database->info['table']} " .
				"WHERE sl_zip LIKE '%%%s%%' " .
				'GROUP BY sl_zip ' .
				'ORDER BY sl_zip ASC '
				;
		}

		/**
		 * Add the selected filters to the search results.
		 *
		 * @param $where
		 * @return string
		 */
	    function filter_JSONP_SearchFilters($where) {
		    if ( !isset( $this->slplus->AjaxHandler ) ) { return $where; }

            $this->set_QueryParams();

		    $ajax_options       = $this->addon->options;
		    $discrete_settings  = array('hidden', 'discrete' , 'dropdown_discretefilter' , 'dropdown_discretefilteraddress' );

		    // Discrete City Output
		    //
		    if (
			    ! empty( $this->formdata['addressInputCity'] )                   &&
			    in_array( $ajax_options['city_selector'] , $discrete_settings )
		    ){
			    $sql_city_expression =
				    (preg_match('/, /',$this->slplus->AjaxHandler->formdata['addressInputCity']) === 1) ?
					    'CONCAT_WS(", ",sl_city,sl_state)=%s'   :
					    'sl_city=%s'                            ;

			    $where =
				    $this->slplus->database->extend_Where(
					    $where,
					    $this->slplus->db->prepare(
						    $sql_city_expression,
						    sanitize_text_field($this->slplus->AjaxHandler->formdata['addressInputCity'])
					    )
				    );
		    }

		    // Discrete State Output
		    //
		    if (
			    ! empty( $this->formdata['addressInputState'] )                   &&
			    in_array( $ajax_options['state_selector'] , $discrete_settings )
		    ){
			    $where = $this->slplus->database->extend_WhereFieldMatches( $where , 'trim(sl_state)' , $this->formdata['addressInputState']);
		    }

		    // Discrete Country Output
		    //
		    if (
			    ! empty( $this->formdata['addressInputCountry'] )                   &&
			    in_array( $ajax_options['country_selector'] , $discrete_settings )
		    ) {
			    $where = $this->slplus->database->extend_WhereFieldMatches( $where , 'trim(sl_country)' , $this->formdata['addressInputCountry']);
		    }

		    return $where;
	    }

	    /**
	     * Modify the AJAX processor SQL statement.
	     *
	     * Remove the distance clause (having distance) if the ignore radius option is set.
	     *
	     * @param string $sqlStatement full SQL statement.
	     * @return string modified SQL statement
	     */
	    function filter_JSONP_ModifyFullSQL($sqlStatement) {
            $this->set_QueryParams();

		    // radius_behavior
		    // if always ignore radius
		    // or, if ignore on blank address, and address is indeed blank
			if ( isset( $this->addon->options['radius_behavior'] ) ) {
				if (
                    ( $this->addon->options['radius_behavior'] == "always_ignore" ) ||
                    ( $this->addon->options['radius_behavior'] == "ignore_with_blank_addr"  && empty( $this->formdata['addressInput'] ) )
				) {

					$sqlStatement = str_replace('HAVING (sl_distance < %f)', 'HAVING (sl_distance >= 0)', $sqlStatement);
				}
			}

		    return $sqlStatement;
	    }

	    /**
	     * Add the store name condition to the MySQL statement used to fetch locations with JSONP.
	     *
	     * @param string $currentFilters
	     * @return string the modified where clause
	     */
	    function filter_JSONP_SearchByStore($currentFilters) {
		    if (empty($_POST['name'])) { return $currentFilters; }
		    $posted_name = preg_replace('/^\s+(.*?)/','$1',$_POST['name']);
		    $posted_name = preg_replace('/(.*?)\s+$/','$1',$posted_name);
		    return array_merge(
			    $currentFilters,
			    array(" AND (sl_store LIKE '%%".$posted_name."%%')")
		    );
	    }

	    /**
	     * Set options based on the AJAX formdata properties.
	     *
	     * This will allow AJAX entries to take precedence over local options.
	     * Typically these are passed via slp.js by using hidden fields with the name attribute.
	     * The name must match the options available to this add-on pack for jQuery to pass them along.
	     */
	    function init_OptionsViaAJAX() {
            $this->set_QueryParams();
		    if ( !empty( $this->formdata ) ) {
                array_walk($this->formdata , array($this,'set_ValidOptions') );
		    }
	    }

	    /**
	     * Set valid options from the incoming REQUEST
	     *
	     * @param mixed $val - the value of a form var
	     * @param string $key - the key for that form var
	     */
	    function set_ValidOptions($val,$key) {
		    $simpleKey = str_replace($this->slplus->prefix.'-','',$key);
		    if (array_key_exists($simpleKey, $this->addon->options)) {
			    $this->addon->options[$simpleKey] = stripslashes_deep($val);
		    }
	    }
   }
}