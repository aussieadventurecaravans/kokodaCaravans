<?php
if (! class_exists('SLPPro_AJAX')) {
    require_once(SLPLUS_PLUGINDIR.'/include/base_class.ajax.php');


    /**
     * Holds the ajax-only code.
     *
     * This allows the main plugin to only include this file in AJAX mode
     * via the slp_init when DOING_AJAX is true.
     *
     * @property        SLPPro  $addon
     *
     * @package StoreLocatorPlus\SLPPro\AJAX
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014 Charleston Software Associates, LLC
     */
    class SLPPro_AJAX extends SLP_BaseClass_AJAX {

        /**
         * Things we do to latch onto an AJAX processing environment.
         */
        public function do_ajax_startup() {

            // Check AJAX action is valid
            //
            $this->valid_actions[] = 'csl_ajax_onload';
            $this->valid_actions[] = 'csl_ajax_search';
            $this->valid_actions[] = 'slp_background_location_download';
            $this->valid_actions[] = 'slp_download_report_csv';
            $this->valid_actions[] = 'slp_download_locations_csv';
            if ( ! $this->is_valid_ajax_action() ) { return; }

            // Custom AJAX handlers
            //
            add_action('wp_ajax_slp_download_report_csv'            , array( $this , 'download_report_csv'              )   );
            add_action('wp_ajax_slp_download_locations_csv'         , array( $this , 'download_locations_csv'           )   );
            add_action('wp_ajax_slp_background_location_download'   , array( $this , 'location_download_in_background'  )   );

            // SLP.js load and search filters
            //
            add_filter( 'slp_location_filters_for_AJAX' , array( $this, 'createstring_TagSelectionWhereClause'  ) );
            add_filter( 'slp_results_marker_data'       , array( $this, 'modify_AJAXResponseMarker'             ) );


            // Reporting Hooks
            //
            if( $this->slplus->is_CheckTrue( $this->addon->options['reporting_enabled'] ) ) {
                add_action( 'slp_report_query_result' , array( $this, 'log_search_queries_and_results' ), 10, 2);
            }
        }

	    /**
	     * Clean the $_REQUEST array of some things we don't want to track.
	     *
	     * @return array[string]string
	     */
	    private function clean_request() {
		    $clean_request= $_REQUEST;

		    unset( $clean_request['options']['bubblelayout']);

		    // Remove all label* options
		    foreach ( $clean_request['options'] as $key => $value ) {
			    if ( strpos( $key , 'label' ) === 0 ) { unset( $clean_request['options'][$key]); }
		    }

		    return $clean_request;
	    }

        /**
         * Add the tags condition to the MySQL statement used to fetch locations with JSONP.
         *
         * @param string[] $currentFilters
         * @return string[]
         */
        public function createstring_TagSelectionWhereClause( $currentFilters ) {
            if (!isset($_POST['tags']) || ($_POST['tags'] == '')) {
                return $currentFilters;
            }

            $posted_tag = preg_replace('/^\s+(.*?)/', '$1', $_POST['tags']);
            $posted_tag = preg_replace('/(.*?)\s+$/', '$1', $posted_tag);
            return array_merge(
                $currentFilters, array(" AND ( sl_tags LIKE '%%" . $posted_tag . "%%') ")
            );
        }


        /**
         * Process incoming AJAX request to download the CSV file.
         * TODO: use locations extended class
         */
        function download_locations_csv( ) {
            $this->slplus->set_php_timeout();


            $this->addon->create_CSVLocationExporter();
            $this->addon->csvExporter->do_SendFile();
        }


        /**
         * Process incoming AJAX request to download the CSV file.
         */
        function download_report_csv() {
            $this->slplus->set_php_timeout();


            // CSV Header
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=slplus_' . $_REQUEST['filename'] . '.csv');
            header('Content-Type: application/csv;');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Setup our processing vars
            //
            global $wpdb;
            $query = $_REQUEST['query'];

            // All records - revise query
            //
            if (isset($_REQUEST['all']) && ($_REQUEST['all'] == 'true')) {
                $query = preg_replace('/\s+LIMIT \d+(\s+|$)/', '', $query);
            }

            $slpQueryTable = $wpdb->prefix . 'slp_rep_query';
            $slpResultsTable = $wpdb->prefix . 'slp_rep_query_results';
            $slpLocationsTable = $wpdb->prefix . 'store_locator';

            $expr = "/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/";
            $parts = preg_split($expr, trim(html_entity_decode($query, ENT_QUOTES)));
            $parts = preg_replace("/^\"(.*)\"$/", "$1", $parts);

            // Return the address in CSV format from the reports
            //
            if ($parts[0] === 'addr') {
                $slpReportStartDate = $parts[1];
                $slpReportEndDate = $parts[2];

                // Only Digits Here Please
                //
                $slpReportLimit = preg_replace('/[^0-9]/', '', $parts[3]);

                $query = "SELECT slp_repq_address, count(*)  as QueryCount FROM $slpQueryTable " .
                    "WHERE slp_repq_time > %s AND " .
                    "      slp_repq_time <= %s " .
                    "GROUP BY slp_repq_address " .
                    "ORDER BY QueryCount DESC " .
                    "LIMIT %d"
                ;
                $queryParms = array(
                    $slpReportStartDate,
                    $slpReportEndDate,
                    $slpReportLimit
                );

                // Return the locations searches in CSV format from the reports
                //
            } else if ($parts[0] === 'top') {
                $slpReportStartDate = $parts[1];
                $slpReportEndDate = $parts[2];

                // Only Digits Here Please
                //
                $slpReportLimit = preg_replace('/[^0-9]/', '', $parts[3]);

                $query = "SELECT sl_store,sl_city,sl_state, sl_zip, sl_tags, count(*) as ResultCount " .
                    "FROM $slpResultsTable res " .
                    "LEFT JOIN $slpLocationsTable sl " .
                    "ON (res.sl_id = sl.sl_id) " .
                    "LEFT JOIN $slpQueryTable qry " .
                    "ON (res.slp_repq_id = qry.slp_repq_id) " .
                    "WHERE slp_repq_time > %s AND slp_repq_time <= %s " .
                    "GROUP BY sl_store,sl_city,sl_state,sl_zip,sl_tags " .
                    "ORDER BY ResultCount DESC " .
                    "LIMIT %d"
                ;
                $queryParms = array(
                    $slpReportStartDate,
                    $slpReportEndDate,
                    $slpReportLimit
                );

                // Not Locations (top) or addresses entered in search
                // short circuit...
                //
            } else {
                die(__("Cheatin' huh!", 'slp-pro'));
            }

            // No parms array?  GTFO
            //
            if (!is_array($queryParms)) {
                die(__("Cheatin' huh!", 'slp-pro'));
            }


            // Run the query & output the data in a CSV
            $thisDataset = $wpdb->get_results($wpdb->prepare($query, $queryParms), ARRAY_N);


            // Sorting
            // The sort comes in based on the display table column order which
            // matches the query output column order listed here.
            //
            // It is a paired array, first number is the column number (zero offset)
            // second number is the sort order [0=ascending, 1=descending]
            //
            // The sort needs to happen AFTER the select.
            //

            // Get our sort array
            //
            $thisSort = explode(',', $_REQUEST['sort']);

            // Build our array_multisort command and our sort index/sort order arrays
            // we will need this later for helping do a multi-dimensional sort
            //
            $sob = 'sort';
            $amsstring = '';
            $sortarrayindex = 0;
            foreach ($thisSort as $sl_value) {
                if ($sob == 'sort') {
                    $sort[] = $sl_value;
                    $amsstring .= '$s[' . $sortarrayindex++ . '], ';
                    $sob = 'order';
                } else {
                    $order[] = $sl_value;
                    $amsstring .= ($sl_value == 0) ? 'SORT_ASC, ' : 'SORT_DESC, ';
                    $sob = 'sort';
                }
            }
            $amsstring .= '$thisDataset';

            // Now that we have our sort arrays and commands,
            // build the indexes that will be used to do the
            // multi-dimensional sort
            //
            foreach ($thisDataset as $key => $row) {
                $sortarrayindex = 0;
                foreach ($sort as $column) {
                    $s[$sortarrayindex++][$key] = $row[$column];
                }
            }

            // Now do the multidimensional sort
            //
            // This will sort using the first array ($s[0] we built in the above 2 steps)
            // to determine what order to put the "records" (the outter array $thisDataSet)
            // into.
            //
            // If there are secondary arrays ($s[1..n] as built above) we then further
            // refine the sort using these secondary arrays.  Think of them as the 2nd
            // through nth columns in a multi-column sort on a spreadsheet.
            //
            // This exactly mimics the jQuery sorts that manage our tables on the HTML
            // page.
            //

            //array_multisort($amsstring);
            // Output the sorted CSV strings
            // This simply iterates through our newly sorted array of records we
            // got from the DB and writes them out in CSV format for download.
            //
            foreach ($thisDataset as $thisDatapoint) {
                print SLPPro::array_to_CSV($thisDatapoint);
            }

            // Get outta here
            die();
        }

        /**
         * Log the search query and results into the reporting tables.
         *
         * <code>
         * $query_params['QUERY_STRING'] => 'the query string';
         * $query_params['tags'] => 'tags,used,for,this,query';
         * $query_params['address'] => 'address for, the search';
         * $query_params['radius'] => 'radius_of_search';
         * </code>
         *
         * @param array[string]string Contain query sql, tags, address and radius
         * @param string[] Query result row id (integers) array.
         */
        function log_search_queries_and_results( $query_params, $results ) {
	        $inserted_query_id = $this->log_search_query( $query_params );
	        $this->log_search_results( $results , $inserted_query_id );
        }

	    /**
	     * Log the search query that was used.
	     *
	     * <code>
	     * $query_params['QUERY_STRING'] => 'the query string';
	     * $query_params['tags'] => 'tags,used,for,this,query';
	     * $query_params['address'] => 'address for, the search';
	     * $query_params['radius'] => 'radius_of_search';
	     * </code>
	     *
	     * @param array[string]string Contain query sql, tags, address and radius
	     *
	     * @return int the insert ID for this record.
	     */
	    private function log_search_query( $query_params ) {

		    $this->slplus->db->insert(
			    "{$this->slplus->db->prefix}slp_rep_query",
			    array(
				    'slp_repq_query'    => $query_params['QUERY_STRING'  ],
				    'slp_repq_tags'     => $query_params['tags'          ],
				    'slp_repq_address'  => $query_params['address'       ],
				    'slp_repq_radius'   => $query_params['radius'        ],
				    'meta_value'        => serialize( array( 'REQUEST' => $this->clean_request() , 'SERVER' => $_SERVER ) )
			    ),
			    '%s'
		    );
		    return $this->slplus->db->insert_id;
	    }

	    /**
	     * Log the search results that were returned.
	     *
	     * @param string[] $results
	     * @param int $inserted_query_id
	     */
	    private function log_search_results( $results , $inserted_query_id ) {
		    foreach ($results as $row_id) {
			    $this->slplus->db->insert(
				    "{$this->slplus->db->prefix}slp_rep_query_results",
				    array(
					    'slp_repq_id' => $inserted_query_id,
					    'sl_id'       => $row_id
				    ),
				    '%d'
			    );
		    }
	    }

        /**
         * Start the process to download the locations in the background.
         */
        function location_download_in_background() {

            // TODO: Fire off the process to get the CSV written to disk.
            // Do this is an AJAX post from here?
            //

            // Tell the user the CSV creation process has started.
            //
            die(
                json_encode(
                    array(
                        'message'       => __( 'Creating the location export CSV file.' , 'slp-pro' )
                    )
                )
            );
        }

        /**
         * Modify the marker data.
         *
         * @param mixed[] $marker the current marker data
         * @return mixed[]
         */
        function modify_AJAXResponseMarker( $marker ) {

            // Tag output processing
            //
            switch ($this->addon->options['tag_output_processing']) {
                case 'hide':
                    $marker['tags'] = '';
                    break;

                case 'replace_with_br':
                    $marker['tags'] = str_replace(',', '<br/>', $marker['tags']);
                    $marker['tags'] = str_replace('&#044;', '<br/>', $marker['tags']);
                    break;

                case 'as_entered':
                default:
                    break;
            }

            return $marker;
        }

   }
}