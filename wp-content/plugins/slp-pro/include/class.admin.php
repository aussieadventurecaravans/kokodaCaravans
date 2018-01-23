<?php
if (! class_exists('SLPro_Admin')) {
    require_once(SLPLUS_PLUGINDIR.'/include/base_class.admin.php');


    /**
     * Holds the admin-only code.
     *
     * This allows the main plugin to only include this file in admin mode
     * via the admin_menu call.   Reduces the front-end footprint.
     *
     * Text Domain: slp-pro
     *
     * @package StoreLocatorPlus\SLPPro\Admin
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014 - 2016 Charleston Software Associates, LLC
     *
     * @property        SLPPro                              $addon
     * @property        SLPProAdminLocationFilters          $admin_location_filters     Admin Location Filters UI Object
     * @property        string[]                            $cb_options_uxtab           List of option keys that are checkboxes show in User Experience.
     * @property-read   string                              $export_filename            The export filename for CSV location export.
     * @property        string                              $export_fileurl             The export fileurl for CSV location export.
     * @property-read   SLPPro_Admin_GeneralSettings        $general
     * @property        SLPPro_Admin_Reports                $reports                    The reports interface.
     * @property-read   SLPPro_Admin_ExperienceSettings     $experience
     */
    class SLPPro_Admin extends SLP_BaseClass_Admin {
        public $addon;
        public $admin_location_filters;
        private $export_filename;
        private $export_fileurl;
        private $general;
        public $reports;
        private $experience;
        public  $settings_pages = array(
            'slp_experience' => array(
                'tag_autosubmit' ,
                'tag_show_any'
            ),
            'slp_general'    => array(
                'highlight_uncoded',
                'use_sensor',
            ),
            'slp_manage_locations'  => array (
                'csv_first_line_has_field_name' ,
                'csv_skip_first_line'           ,
                'csv_skip_geocoding'            ,
                'load_data'                     ,
            )
        ) ;

        /**
         * Admin specific hooks and filters.
         *
         */
        function add_hooks_and_filters() {
            parent::add_hooks_and_filters();
            // Load objects based on which admin page we are on.
            //
            if ( isset( $_REQUEST['page'] ) ) {
                switch ( $_REQUEST['page'] ) {
                    case 'slp_general':
                        $this->create_object_general();
                        break;
                }
            }


            // Experience Tab
            //
            add_action( 'slp_ux_modify_adminpanel_search'       , array( $this , 'add_experience_search_settings'      ) , 10 , 2 );
            add_action( 'slp_ux_modify_adminpanel_results'      , array( $this , 'add_experience_results_settings'     ) , 10 , 2 );
            if ( ! $this->slplus->is_AddonActive( 'slp-experience' ) ) {
                add_action('slp_uxsettings_modify_viewpanel'    , array( $this , 'add_experience_view_settings'        ) , 11 , 2 );
            }

            // Admin skinning and scripts
            //
            add_action('admin_enqueue_scripts'                  , array($this, 'action_EnqueueAdminScripts' )    );

            // Manage Locations UI
            //
            add_filter('slp_build_locations_panels'             ,array($this,'action_AddLocationsPanels'                    ) ,30       );
            add_filter('slp_locations_manage_bulkactions'       ,array($this,'filter_LocationsBulkAction'                   )           );
            add_filter('slp_locations_manage_filters'           ,array($this,'filter_LocationsFilters'                      )           );
            add_filter( 'slp_invalid_highlight'                 , array( $this , 'filter_HighlightUncoded'                  ), 10 , 1   );

            // Manage Location Fields
            // - tweak the add/edit form
            // - tweak the manage locations column headers
            // - tweak the manage locations column data
            //
            add_filter('slp_edit_location_right_column'         ,array($this,'filter_AddFieldsToEditForm'                   ),11   );
            add_filter('slp_manage_expanded_location_columns'   ,array($this,'filter_AddFieldHeadersToManageLocations'      )      );
            add_filter('slp_column_data'                        ,array($this,'filter_AddFieldDataToManageLocations'         ),90, 3);

            // Manage Locations Processing
            //
            add_action('slp_manage_locations_action'            ,array($this,'action_ManageLocationsProcessing'));
        }


        /**
         * Execute some admin startup things for this add-on pack.
         */
        function do_admin_startup() {
	        parent::do_admin_startup();

            if (
                isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] === 'slp_manage_locations' ) &&
                isset( $_REQUEST['act' ] ) && ( $_REQUEST['act' ] === 'import' )
            ) {
                $this->save_my_settings();
            }

            $this->createobject_Reports();
            $this->export_filename = SLPLUS_UPLOADDIR .'csv/exported_slp_locations.csv';
            $this->export_fileurl = SLPLUS_UPLOADURL .'csv/exported_slp_locations.csv';
        }

        /**
         * Enqueue the admin scripts.
         *
         * @param string $hook
         */
        function action_EnqueueAdminScripts($hook) {
            $styleHandle = '';
            $scriptData = array(
                'plugin_url' => SLPLUS_PLUGINURL
            );

            switch ($hook) {
                case SLP_ADMIN_PAGEPRE . 'slp_reports':
                    $scriptData['message_nodata'] =
                        __('No data recorded yet. ','slp-pro');
                    $scriptData['message_chartaftersearch'] =
                        __('Chart will be available after a Store Locator Plus search has been performed.','slp-pro');
                    $scriptData['total_searches'] = $this->reports->data->total_searches;
                    $scriptData['count_dataset']  = $this->reports->data->counts_dataset;
                    $scriptData['chart_type']     = $this->reports->data->google_chart_type;

                    wp_enqueue_script('google_jsapi'        , 'https://www.google.com/jsapi'                                );
                    wp_enqueue_script('jquery_tablesorter'  , $this->addon->url . '/jquery.tablesorter.js', array('jquery') );

                    wp_enqueue_script ( 'slp_reporting' , $this->addon->url . '/reporting.js' );
                    wp_localize_script( 'slp_reporting' , 'slp_pro' , $scriptData );

                    $styleHandle = 'slp_pro_style';

                    break;

                case SLP_ADMIN_PAGEPRE . 'slp_manage_locations':
                    $styleHandle = 'slp_pro_style';

                    $manage_locations_vars = array_merge(
                        $_REQUEST,
                        array(
                            'download_file_message' =>
                                __('Download your ','slp-pro') .
                                sprintf(
                                    '<a href="%s">%s</a>',
                                    $this->export_fileurl ,
                                    __('locations CSV file.','slp-pro')
                                )
                        )
                    );
                    wp_register_script( 'slp_pro_manage_locations' , $this->addon->url . '/js/manage_locations.js' , array('jquery') );
                    wp_localize_script( 'slp_pro_manage_locations' , 'request_vars' , $manage_locations_vars );
                    wp_enqueue_script(  'slp_pro_manage_locations' );

                    break;


                default:
                    break;
            }

            // Load up the admin.css style sheet for Pro Pack
            //
            if ($styleHandle !== '') {
                wp_register_style('slp_pro_style', $this->addon->url . '/css/pro_admin.css');
                wp_enqueue_style('slp_pro_style');
                wp_enqueue_style($this->slplus->AdminUI->styleHandle);
            }
        }

        /**
         * Add the import/export panels to the Locations tab.
         */
        function action_AddLocationsPanels() {

            // Do not allow users to import/export unless they have the manage_slp role.
            //
            if ( ! current_user_can('manage_slp_admin') ) { return; }

            $this->create_AdminLocationFilters();

            $this->addon->create_CSVLocationImporter();
            $this->addon->csvImporter->create_BulkUploadForm();
        }

        /**
         * Add Experience / View
         *
         * @param SLP_Settings  $settings
         * @param string        $section_name
         */
        function add_experience_view_settings( $settings , $section_name ) {
            $this->create_object_experience();
            $this->settings_interface = $settings;
            $this->current_section = $section_name;
            $this->experience->add_view_settings();
        }

        /**
         * Additional location processing on manage locations admin page.
         *
         */
        function action_ManageLocationsProcessing() {

            switch ($_REQUEST['act']) {

                // Export Locations To CSV
                // Also filter by property
                //
                case 'export':
                    $this->slplus->notifications->enabled = false;
                    $this->create_AdminLocationFilters();
                    add_action('slp_manage_location_where', array($this,'action_ManageLocations_ByProperty') );
                    $this->slplus->notifications->enabled = true;
                    break;

                case 'export_local':
                    $this->slplus->notifications->enabled = false;
                    $this->export_locations_to_local_csv_file();
                    $this->create_AdminLocationFilters();
                    add_action('slp_manage_location_where', array($this,'action_ManageLocations_ByProperty') );
                    $this->slplus->notifications->enabled = true;
                    break;

                // Import a CSV File
                //
                case 'import':

                    // Save the option check boxes in permanent store.
                    //
                    $this->addon->options =
                        $this->slplus->AdminUI->save_SerializedOption(
                            $this->addon->option_name,
                            $this->addon->options,
                            array()
                            );

                    // Setup the CSV Import Object
                    //
                    $this->addon->create_CSVLocationImporter();

                    // Process the CSV File
                    //
                    $mode = $this->addon->csvImporter->prepare_for_import();
                    $this->addon->csvImporter->process_File( $this->addon->csvImporter->file_meta , $mode );

                    break;

                // Add tags to locations
                case 'add_tag':
                    if (isset($_REQUEST['sl_id'])) { $this->tag_Locations('add'); }
                    break;

                // Remove tags from locations
                case 'remove_tag':
                    if (isset($_REQUEST['sl_id'])) { $this->tag_Locations('remove'); }
                    break;


                // Recode The Selected Locations
                case 'recode_all':
                    $this->recode_all_uncoded_locations();
                    break;

                // Recode The Address
                case 'recode':
                    $this->slplus->notifications->delete_all_notices();
                    if (isset($_REQUEST['sl_id'])) {
                        $theLocations =
                            (!is_array($_REQUEST['sl_id']))         ?
                                array($_REQUEST['sl_id'])           :
                                $theLocations = $_REQUEST['sl_id']  ;

                        // Process SL_ID Array
                        // TODO: use where clause in database property
                        //
                        //
                        foreach ($theLocations as $locationID) {
                            $this->slplus->currentLocation->set_PropertiesViaDB($locationID);
                            $this->slplus->currentLocation->do_geocoding();
                            if ($this->slplus->currentLocation->dataChanged) {
                                $this->slplus->currentLocation->MakePersistent();
                            }
                        }
                        $this->slplus->notifications->display();
                    }
                    break;

                // Filter to show uncoded locations only.
                case 'show_uncoded':
                    add_action('slp_manage_location_where', array($this,'action_ManageLocations_WhereUncoded') );
                    break;

                // Filter with specific location properties
                case 'add':
                case 'save':
                case 'filter_by_property':
                    $this->create_AdminLocationFilters();
                    add_action('slp_manage_location_where', array($this,'action_ManageLocations_ByProperty') );
                    add_filter( 'slp_manage_locations_actionbar_ui' , array( $this->admin_location_filters , 'createstring_FilterDisplay' ) );
                    break;

                // Reset on show_all
                case 'show_all':
                    $this->create_AdminLocationFilters();
                    $this->admin_location_filters->reset();
                    break;

                default:
                    break;
            }
        }

        /**
         * Set the NOT where valid lat long clause.
         *
         * @param $where
         * @return string
         */
        function set_where_not_valid_lat_long( $where ) {
            $where_valid_lat_long = $this->slplus->database->filter_SetWhereValidLatLong('');
            return 'NOT ( ' . $where_valid_lat_long . ' ) OR sl_latitude IS NULL OR sl_longitude IS NULL';
        }

        /**
         * Setup the show uncoded fitler for manage locations.
         *
         * @param string $where
         * @return string
         */
        function action_ManageLocations_WhereUncoded($where) {
            return
                $where .
                ( empty($where) ? '' : ' AND ' ) .
                ' (NOT (' . $this->slplus->database->filter_SetWhereValidLatLong('') . ') ' .
                "or sl_latitude IS NULL or sl_longitude IS NULL)"
                ;
        }

        /**
         * Filter the manage locations list by property matches in the filter form.
         *
         * @param string $where
         * @return string
         */
        function action_ManageLocations_ByProperty($where) {
            // Get the SQL command and where params
            //
            list($sqlCommands, $sqlParams) = $this->admin_location_filters->create_LocationSQLCommand( $this->create_LocationFilterInputArray() );
            $whereclause = $this->slplus->database->get_SQL('where_default');
            if ( strpos( $whereclause, '%' ) !== false ) {
                $whereclause = $this->slplus->database->db->prepare( $whereclause , $sqlParams );
            }
            $whereclause = preg_replace('/^\s+WHERE /i','',$whereclause);

            return $whereclause;
        }

        /**
         * Create and attach the \CSVExportLocations object
         */
        function create_AdminLocationFilters() {
            if (!class_exists('SLPProAdminLocationFilters')) {
                require_once('class.admin.location.filters.php');
            }
            if (!isset($this->admin_location_filters)) {
                $this->admin_location_filters =
                    new SLPProAdminLocationFilters( array(
                        'addon'     =>  $this->addon,
                        'slplus'    =>  $this->slplus
                    ));
            }
        }

        /**
         * Create an array of location filter inputs from $_REQUEST.
         *
         * @return string[] named array
         */
        function create_LocationFilterInputArray() {
            $formdata_array = array();
            $location_filter_inputs =
                array(
                'name'              ,
                'state_filter'      ,
                'state_joiner'      ,
                'zip_filter'        ,
                'zip_filter_joiner' ,
                'country_joiner'    ,
                'country_filter'
                );
            foreach ($location_filter_inputs as $input) {
                if ( ! empty( $_REQUEST[$input] ) ) {
                    $formdata_array[$input] = $_REQUEST[$input];
                }
            }
            return $formdata_array;
        }

        /**
         * Create the reports interface object and attach to this->reports
         */
        function createobject_Reports() {
            if ( !isset( $this->reports ) ) {
                require_once($this->addon->dir . 'include/class.admin.reports.php');
                $this->reports = new SLPPro_Admin_Reports( array( 'addon' => $this->addon ) );
            }
        }

        /**
         * Create the filter by properties div.
         *
         * @return string
         */
        function createstring_FilterByPropertiesDiv() {
            $this->create_AdminLocationFilters();
            $HTML =
                "<iframe id='pro_csv_download' src='' style='display:none; visibility:hidden;'></iframe>" .
                '<div id="extra_filter_by_property" class="filter_extras">'.
                    $this->admin_location_filters->createstring_LocationFilterForm().
                '</div>' .
                '<div id="slp-pro_message_board" class="popup_message_div ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable">' .
                    '<div class="ui-dialog-titlebar ui-widget-header">' .
                        __( 'Location Processing Info' , 'slp-pro' ).
                    '</div>' .
                    '<div id="slp-pro_messages" class="ui-dialog-content ui-widget-content"></div>' .
                '</div>'
                ;

            return $HTML;
        }

        /**
		 * Save the Pro Pack options to the database in serialized format.
		 *
		 * Make sure the options are valid first.
         *
         * @param string[] $cbArray The checkbox array need to handle for the null to false operation
         */
        function data_SaveOptions($cbArray) {
            $_POST[$this->addon->option_name] = array();
            array_walk($_REQUEST,array($this,'set_ValidOptions'));

            // AdminUI->save_SerializedOption stores the $_POST[<option_name>] values plus the checkbox options (3rd param)
            // in the persistent store of wp_options
            //
            $this->options =
                $this->slplus->AdminUI->save_SerializedOption(
                    $this->addon->option_name,
                    $this->addon->options,
                    $cbArray
                    );

            $this->addon->init_options();
        }

        /**
         * Export locations to a local CSV file that to be fetched manually.
         */
        function export_locations_to_local_csv_file() {

            $this->slplus->set_php_timeout();

            $this->addon->create_CSVLocationExporter();

            $csv_dir = SLPLUS_UPLOADDIR .'csv';
            if ( ! is_dir( $csv_dir ) ) {   mkdir( $csv_dir ,0755 ); }

            $this->addon->csvExporter->do_WriteFile( 'file://' . $this->export_filename );
        }

        /**
         * Render the extra fields on the manage location table.
         *
         * SLP Filter: slp_column_data
         *
         * @param string $theData  - the option_value field data from the database
         * @param string $theField - the name of the field from the database (should be sl_option_value)
         * @param string $theLabel - the column label for this column (should be 'Categories')
         * @return type
         */
        function filter_AddFieldDataToManageLocations($theData,$theField,$theLabel) {
            if (
                ($theField === 'sl_tags') &&
                ($theLabel === __('Tags'        ,'slp-pro'))
               ) {
                $theData =($this->slplus->currentLocation->tags!='')?
                    $this->slplus->currentLocation->tags :
                    "" ;
            }
            return $theData;
        }

        /**
         * Add the images column header to the manage locations table.
         *
         * SLP Filter: slp_manage_location_columns
         *
         * @param mixed[] $currentCols column name + column label for existing items
         * @return mixed[] column name + column labels, extended with our extra fields data
         */
        function filter_AddFieldHeadersToManageLocations($currentCols) {
            return array_merge($currentCols,
                    array(
                        'sl_tags'       => __('Tags'     ,'slp-pro'),
                    )
                );
        }

        /**
         * Add extra fields that show in results output to the edit form.
         *
         * @param string    $html
         * @return string
         */
        function filter_AddFieldsToEditForm( $html ) {
	        $current_action = $this->slplus->AdminUI->ManageLocations->current_action;
	        $section_name =
		        (  $current_action === 'edit' ) ?
			        __( 'Edit' , 'slp-pro' ) :
			        __( 'Add'  , 'slp-pro' ) ;
	        $group_name = $this->addon->name;

	        $this->slplus->AdminUI->ManageLocations->settings->add_ItemToGroup(array(
		        'label'         => __('Tags' , 'slp-pro' ),
		        'description'   =>
		            __('List of tags assigned to location. ', 'slp-pro' ) .
	                __('Separate with commas.' , 'slp-pro' )
	                ,
		        'value'         => $this->slplus->currentLocation->tags,
		        'setting'       => 'tags-' . $this->slplus->currentLocation->id,
                'data_field'    => 'tags',
		        'use_prefix'    => false,
			    'section'       => $section_name,
		        'group'         => $group_name,
		        ));

	        // Submit
	        $this->slplus->AdminUI->ManageLocations->settings->add_ItemToGroup(array(
		        'type'         => 'details'  ,
		        'custom'       => $this->slplus->AdminUI->ManageLocations->filter_EditLocationLeft_Submit(''),
		        'show_label'   => false,
		        'section'      => $section_name,
		        'group'        => $group_name
	        ));

            return $html;
        }

        /**
         * Add our admin pages to the valid admin page slugs.
         *
         * @param string[] $slugs admin page slugs
         * @return string[] modified list of admin page slugs
         */
        function filter_AddOurAdminSlug($slugs) {
            return array_merge($slugs,
                    array(
	                    'slp_propack',
	                    'slp_reports',
                        SLP_ADMIN_PAGEPRE.'slp_propack',
                        SLP_ADMIN_PAGEPRE.'slp_reports'
                        )
                    );
        }

        /**
         * Add more actions to the Bulk Action drop down on the admin Locations/Manage Locations interface.
         *
         * @param mixed[] $items
         * @return mixed[]
         */
        function filter_LocationsBulkAction($items) {
            return
                array_merge(
                    $items,
                    array(
                        array(
                            'label'     =>  __('Export, Download CSV','slp-pro')   ,
                            'value'     => 'export'                             ,
                        ),
                        array(
                            'label'     =>  __('Export, Hosted CSV','slp-pro')   ,
                            'value'     => 'export_local'                             ,
                        ),
                        array(
                            'label'     =>  __('Geocode Selected','slp-pro'),
                            'value'     => 'recode'                             ,
                        ),
                        array(
                            'label'     =>  __('Geocode All Uncoded','slp-pro')         ,
                            'value'     => 'recode_all'                             ,
                        ),
                        array(
                            'label'     =>  __('Tag','slp-pro')             ,
                            'value'     => 'add_tag'                            ,
                            'extras'    =>
                                '<div id="extra_add_tag" class="bulk_extras">'.
                                    '<label for="sl_tags">'.__('Enter your comma-separated tags:','slp-pro').'</label>'.
                                    '<input name="sl_tags">'.
                                '</div>'
                        ),
                        array(
                            'label'     =>  __('Remove Tags','slp-pro') ,
                            'value'     => 'remove_tag'                     ,
                        ),
                    )
                );
        }

        /**
         * Add more actions to the Bulk Action drop down on the admin Locations/Manage Locations interface.
         *
         * @param mixed[] $items
         * @return mixed[]
         */
        function filter_LocationsFilters($items) {
            return
                array_merge(
                    $items,
                    array(
                        array(
                            'label'     =>  __('Show Uncoded','slp-pro')  ,
                            'value'     => 'show_uncoded'                     ,
                        ),
                        array(
                            'label'     => __('With These Properties', 'slp-pro'),
                            'value'     => 'filter_by_property'                      ,
                            'extras'    => $this->createstring_FilterByPropertiesDiv()
                        )
                    )
                );
        }

        /**
         * Add settings to User Experience / Results / Results Features
         *
         * @param SLP_Settings $settings
         * @param string $section_name
         */
        function add_experience_results_settings( $settings , $section_name ) {
            $this->create_object_experience();
            $this->settings_interface = $settings;
            $this->current_section = $section_name;
            $this->experience->add_results_settings( );
        }

        /**
         * Add tags box to the search form section of map settings.
         *
         * @param SLP_Settings $settings
         * @param string $section_name
         */
        function add_experience_search_settings( $settings , $section_name ) {
            $this->create_object_experience();
            $this->settings_interface = $settings;
            $this->current_section = $section_name;
            $this->experience->add_search_settings( );
        }

        /**
         * Create and attach the user experience object.
         */
        private function create_object_experience() {
            if ( ! isset( $this->experience ) ) {
                require_once('class.admin.experience.php');
                $this->experience = new SLPPro_Admin_ExperienceSettings(array( 'addon' => $this->addon ) );
            }
        }

        /**
         * Create and attach the user general object.
         */
        private function create_object_general() {
            if ( ! isset( $this->general ) ) {
                require_once('class.admin.general.php');
                $this->general = new SLPPro_Admin_GeneralSettings(array( 'addon' => $this->addon ) );
            }
        }

        /**
         * Recode all the uncoded locations.
         */
        function recode_all_uncoded_locations() {
            $this->slplus->notifications->delete_all_notices();

            add_filter( 'slp_location_where' , array( $this , 'set_where_not_valid_lat_long' ) );

            $offset = 0;
            do {
                $location = $this->slplus->database->get_Record(array('selectslid', 'where_default'), array(), $offset++);
                if ( ! empty ( $location['sl_id'] ) ) {
                    $this->slplus->currentLocation->set_PropertiesViaDB( $location['sl_id'] );
                    $this->slplus->currentLocation->do_geocoding();
                    if ( $this->slplus->currentLocation->dataChanged ) {
                        $this->slplus->currentLocation->MakePersistent();
                    }
                }
            } while ( ! empty ( $location['sl_id'] ) );
            remove_filter( 'slp_location_where' , array( $this , 'set_where_not_valid_lat_long') );
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
                $_POST[$this->addon->option_name][$simpleKey] = stripslashes_deep($val);
            }
         }

        /**
         * Remove the invalid class from non-geocoded locations if highlight uncoded is off.
         *
         * @param $class_string
         * @return mixed
         */
        function filter_HighlightUncoded( $class_string ) {
            if ( ! $this->slplus->is_CheckTrue( $this->addon->options['highlight_uncoded'] ) ) {
                $class_string = str_replace(' invalid','',$class_string);
            }
            return $class_string;
        }

    /**
     * Tag a location
     *
     * @param string $action = add or remove
     */
    function tag_Locations($action) {
        global $wpdb;

        //adding or removing tags for specified a locations
        //
        if (is_array($_REQUEST['sl_id'])) {
            $id_string='';
            foreach ($_REQUEST['sl_id'] as $sl_value) {
                $id_string.="$sl_value,";
            }
            $id_string=substr($id_string, 0, strlen($id_string)-1);
        } else {
            $id_string=$_REQUEST['sl_id'];
        }

        // If we have some store IDs
        //
        if ($id_string != '') {

            //adding tags
            if ( $action === 'add' ) {
                $wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET ".
                        "sl_tags=CONCAT_WS(',',sl_tags,'".$_REQUEST['sl_tags']."') ".
                        "WHERE sl_id IN ($id_string)"
                        );

            //removing tags
            } elseif ( $action === 'remove' ) {
                if (empty($_REQUEST['sl_tags'])) {
                    //if no tag is specified, all tags will be removed from selected locations
                    $wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags='' WHERE sl_id IN ($id_string)");
                } else {
                    $wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags=REPLACE(sl_tags, ',{$_REQUEST['sl_tags']},', '') WHERE sl_id IN ($id_string)");
                }
            }
        }
    }
  }
}