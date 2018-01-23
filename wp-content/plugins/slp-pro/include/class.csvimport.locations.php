<?php

// Make sure the class is only defined once.
//
if (!class_exists('CSVImport')) {
    require_once( 'class.csvimport.php');
}

/**
 * CSV Import of Locations
 *
 * @package StoreLocatorPlus\ProPack\CSVImportLocations
 * @author Lance Cleveland <lance@charlestonsw.com>
 * @copyright 2013 - 2015 Charleston Software Associates, LLC
 *
 * @property-read       mixed           $action_params              The params received during the cron job call.
 * @property            SLPPro          $addon                      This addon pack.
 * @property            _FILES          $file_meta
 * @property-read       bool            $reported_too_many_fields   Did we already report a too many fields problem?
 * @property-read       boolean         $skip_geocoding             Should goecoding be skipped?
 * @property-read       SLP_Settings    $settings                   The manage locations settings interface object.
 */
class CSVImportLocations extends CSVImport_Pro {

    private $action_params;
    public  $addon;
    public  $file_meta;
    private $reported_too_many_fields   = false;
    private $skip_geocoding             = false;
    private $settings;

    /**
     * Setup a standard CSV Import object and attach the processing method and data filters.
     */
    function initialize() {
	    parent::initialize();

        if ( ! defined('DOING_CRON') ) {
            $this->settings = $this->slplus->AdminUI->ManageLocations->settings;
        }

        // Set private properties that are only for this class.
        //
        $this->skip_geocoding= $this->slplus->is_CheckTrue( $this->addon->options['csv_skip_geocoding' ] );

        // Set inherited properties specific to this class.
        //
        $this->strip_prefix = 'sl_';
        $this->firstline_has_fieldname  = $this->slplus->is_CheckTrue( $this->addon->options['csv_first_line_has_field_name'] );
        $this->skip_firstline           = $this->firstline_has_fieldname || $this->slplus->is_CheckTrue( $this->addon->options['csv_skip_first_line'] );
        $this->load_data                = $this->slplus->is_CheckTrue( $this->addon->options['load_data'] );

        // TODO: Make this a checkbox option when loading locations.
        //
        $this->initialize_message_stack();

        // Add filters and hooks for this class.
        //
        add_filter( 'slp_csv_processing_messages', array( $this , 'filter_SetMessages'           ) );
        add_filter( 'slp_csv_default_fieldnames' , array( $this , 'filter_SetDefaultFieldNames'  ) );
        add_action( 'slp_csv_processing'         , array( $this, 'action_ProcessCSVFile'         ) );
        add_action( 'slp_csv_processing_complete', array( $this, 'save_import_message_stack'     ) );
    }

    /**
     * Process the lines of the CSV file.
     */
    function action_ProcessCSVFile() {
        $num = count($this->data);
        $locationData = array();
        if ($num <= $this->maxcols) {
            for ($fldno=0; $fldno < $num; $fldno++) {
                $locationData[$this->fieldnames[$fldno]] = $this->data[$fldno];
            }

            // Record Add/Update
            //
            add_filter( 'slp_csv_locationdata' , array( $this , 'strip_extra_spaces_from_csv_location_data' ) );

            // FILTER: slp_csv_locationdata
            // Pre-location import processing.
            //
            $locationData = apply_filters('slp_csv_locationdata',$locationData);

	        if ( ! isset( $locationData['sl_latitude' ] ) ) { $locationData['sl_latitude'] = ''; }
	        if ( ! isset( $locationData['sl_longitude'] ) ) { $locationData['sl_longitude'] = ''; }

	        // Go add the CSV Data to the locations table.
            //
            $resultOfAdd = $this->slplus->currentLocation->add_to_database(
                    $locationData,
                    $this->addon->options['csv_duplicates_handling'],
                    $this->skip_geocoding ||
                        (
                        $this->slplus->currentLocation->is_valid_lat( $locationData['sl_latitude']  ) &&
                        $this->slplus->currentLocation->is_valid_lng( $locationData['sl_longitude'] )
                        )
                    );

            // Add the results of this location to the detailed message stack.
            //
            if ( isset( $this->message_stack ) ) {
                $this->message_stack->add_message(
                        "{$this->slplus->currentLocation->id} : {$this->slplus->currentLocation->store}  {$resultOfAdd}"
                );
            }

            // FILTER: slp_csv_locationdata_added
            // Post-location import processing.
            //
            apply_filters('slp_csv_locationdata_added',$locationData, $resultOfAdd);

            // Update processing counts.
            //
            $this->processing_counts[$resultOfAdd]++;

        } else {
            $this->processing_counts['malformed']++;
            if ( ! $this->reported_too_many_fields ) {
                $this->processing_report[] =
                    __('Some CSV Records have too many fields.', 'slp-pro')                               . '<br/>' .
                    sprintf(__('Got %d expected %d or fewer fields.', 'slp-pro'), $num, $this->maxcols)   . '<br/>' .
                    __('Defined field names are: ' , 'slp-pro')                                           . '<br/>' .
                    join( ',' , $this->fieldnames )
                    ;
                $this->processing_report[] =
                    __('Are you defining fileds as the first line of a CSV file? ' , 'slp-pro' )          . ' <br/>' .
                    __('If so, did you check the First Line Has Field Name box? '  , 'slp-pro' )
                    ;
                $this->reported_too_many_fields = true;
            }
        }
    }

    /**
     * Add the File Settings panel to the Import section of Locations.
     *
     * @param $section_name
     */
    private function  add_FileSettingsPanel( $section_name ) {
        $panel_name = __( 'File Settings', 'slp-pro' );

        // Skip First Line
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name     ,
                'group'         => $panel_name       ,
                'type'          => 'checkbox'        ,
                'setting'       => 'PRO-options[csv_skip_first_line]',
                'value'         => $this->addon->options['csv_skip_first_line'],
                'label'         => __('Skip First Line','slp-pro'),
                'description'   => __('Skip the first line of the import file.','slp-pro')
            )
        );

        // First Line Has Field Name
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name     ,
                'group'         => $panel_name       ,
                'type'          => 'checkbox'       ,
                'setting'       => 'PRO-options[csv_first_line_has_field_name]',
                'label'         => __('First Line Has Field Name','slp-pro'),
                'value'         => $this->addon->options['csv_first_line_has_field_name'],
                'description'   =>
                    __('Check this if the first line contains the field names.','slp-pro') . ' ' .
                    sprintf(__('Text must match the <a href="%s">approved field name list</a>.','slp-pro'),$this->slplus->support_url)
            )
        );

        // Skip Geocoding
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name     ,
                'group'         => $panel_name       ,
                'type'          => 'checkbox'       ,
                'setting'       => 'PRO-options[csv_skip_geocoding]',
                'value'         => $this->addon->options['csv_skip_geocoding'],
                'label'         => __('Skip Geocoding','slp-pro'),
                'description'   =>
                    __('Do not check with the Geocoding service to get latitude/longitude.  Locations without a latitude/longitude will NOT appear on map base searches.','slp-pro')
            )
        );


        // Direct Load
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name     ,
                'group'         => $panel_name       ,
                'type'          => 'checkbox'       ,
                'setting'       => 'PRO-options[load_data]',
                'label'         => __('Load Data','slp-pro'),
                'value'         => $this->addon->options['load_data'],
                'description'   =>
                    __('If checked use the faster MySQL Load Data method of file processing.','slp-pro') . ' ' .
                    sprintf(__('Only base plugin data can be loaded, see the <a href="%s">approved field name list</a>.','slp-pro'),$this->slplus->support_url)
            )
        );

        // Duplicates Handling
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name     ,
                'group'         => $panel_name       ,
                'type'          => 'dropdown'       ,
                'setting'       => 'PRO-options[csv_duplicates_handling]',
                'selectedVal'   => $this->addon->options['csv_duplicates_handling'],
                'label'         => __('Duplicates Handling','slp-pro'),
                'description'   =>
                    __('How should duplicates be handled? ','slp-pro') .
                    __('Duplicates are records that match on name and complete address with country. ','slp-pro') .
                    __('Add (default) will add new records when duplicates are encountered. ','slp-pro') . '<br/>' .
                    __('Skip will not process duplicate records. ','slp-pro') . '<br/>' .
                    __('Update will update duplicate records. ','slp-pro') .
                    __('To update name and address fields the CSV must have the ID column with the ID of the existing location.','slp-pro')
            ,
                'custom'    =>
                    array(
                        array(
                            'label'     => __('Add','slp-pro'),
                            'value'     =>'add',
                        ),
                        array(
                            'label'     => __('Skip','slp-pro'),
                            'value'     =>'skip',
                        ),
                        array(
                            'label'     => __('Update','slp-pro'),
                            'value'     =>'update',
                        ),
                    )
            )
        );

        // Add help text
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name    ,
                'group'         => $panel_name      ,
                'type'          => 'subheader'      ,
                'label'         => '',
                'description'   =>
                    sprintf(__('See the %s for more details on the import format.','slp-pro'),
                        sprintf('<a href="%slocations/bulk-data-import/">',$this->slplus->support_url) .
                        __('online documentation','slp-pro') .
                        '</a>'
                    ),
                'show_label'    => false
            ));
    }

    /**
     * Local File Import Panel.
     *
     * @param $section_name
     */
    function add_UploadCSVPanel( $section_name ) {
        $panel_name = __('Upload CSV File', 'slp-pro');

        // Form Start with Media Input
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name    ,
                'group'         => $panel_name      ,
                'type'          => 'custom'         ,
                'show_label'    => false            ,
                'custom'         =>
                        '<input type="file" name="csvfile" value="" id="bulk_file" size="40">'              .
                        "<input type='submit' value='".__('Upload Locations', 'slp-pro')."' "           .
                        "class='button-primary'>"
            )
        );
    }


    /**
     * Remote File Import Panel.
     *
     * @param $section_name
     */
    function add_RemoteFileImportPanel( $section_name ) {
        $panel_name = __('File Import', 'slp-pro');

        // Remove File URL
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name                            ,
                'group'         => $panel_name                              ,
                'label'         => __('CSV File URL','slp-pro')         ,
                'setting'       => 'PRO-options[csv_file_url]'              ,
                'type'          => 'text'                                   ,
                'value'         => $this->addon->options['csv_file_url']    ,
                'description'   =>
                    __('Enter a full URL for a CSV file you wish to import', 'slp-pro')
            )
        );

        // Cron Import Recurrence
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name     ,
                'group'         => $panel_name       ,
                'type'          => 'dropdown'       ,
                'setting'       => 'PRO-options[cron_import_recurrence]',
                'selectedVal'   => $this->addon->options['cron_import_recurrence'],
                'label'         => __('Import Recurrence','slp-pro'),
                'description'   =>
                    __('How often to fetch the file from the URL. ','slp-pro') .
                    __('None loads the remote file immediately with no background processing. ','slp-pro') .
                    __('At loads the file one time on or after the time specified. ','slp-pro')
                    ,
                'custom'    =>
                    array(
                        array(
                            'label'     => __('None','slp-pro'),
                            'value'     =>'none',
                        ),
                        array(
                            'label'     => __('At','slp-pro'),
                            'value'     =>'at',
                        ),
                        array(
                            'label'     => __('Hourly','slp-pro'),
                            'value'     =>'hourly',
                        ),
                        array(
                            'label'     => __('Twice Daily','slp-pro'),
                            'value'     =>'twicedaily',
                        ),
                        array(
                            'label'     => __('Daily','slp-pro'),
                            'value'     =>'daily',
                        ),
                    )
            )
        );

        // Cron Import Time
        //
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name     ,
                'group'         => $panel_name       ,
                'type'          => 'text'            ,
                'setting'       => 'PRO-options[cron_import_timestamp]'             ,
                'value'         => $this->addon->options['cron_import_timestamp']   ,
                'label'         => __('Daily Import Time','slp-pro')            ,
                'description'   =>
                    __('How many seconds after midnight to run the recurring import from this URL.  '               , 'slp-pro') .
                    __('Set to 3600 to run at the top of the hour (hourly) or at 1 AM 9 (daily).  '                 , 'slp-pro') .
                    __('WordPress cron is not exact, it executes the next time a visitor comes to your site.  '     , 'slp-pro') .
                    __('Set to recurrence to none and leave the URL blank to clear the cron job.  '                 , 'slp-pro')
            )
        );

        // Show Cron Info
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name    ,
                'group'         => $panel_name      ,
                'type'          => 'subheader'      ,
                'label'         => '',
                'description'   => $this->createstring_CronInfo(),
                'show_label'    => false
            ));


        // Form Start with Media Input
        //
        $this->settings->add_ItemToGroup(
            array(
                'section'       => $section_name    ,
                'group'         => $panel_name      ,
                'type'          => 'custom'         ,
                'show_label'    => false            ,
                'custom'         =>
                    "<input type='submit' value='".__('Import Locations', 'slp-pro')."' "           .
                    "class='button-primary'>"
            )
        );

    }

    /**
     * Add the bulk upload form to add locations.
     */
    function create_BulkUploadForm() {
        $section_name = __('Import','slp-pro');

        $this->settings->add_section(
            array(
                'name'          => $section_name ,

                'opening_html'  =>
                    "<form id='importForm' name='importForm' method='post' enctype='multipart/form-data'>"  .
                    "<input type='hidden' name='act' id='act' value='import' />" ,

                'closing_html' =>
                    '</form>'
            )
        );

        // File Settings Panel
        //
        $this->add_FileSettingsPanel( $section_name );

        // Upload CSV Local
        //
        $this->add_UploadCSVPanel( $section_name );

        // File Import Remoete
        //
        $this->add_RemoteFileImportPanel( $section_name );

    }

    /**
     * Create string for cron info.
     *
     * @return string
     */
    function createstring_CronInfo() {
        $box_title = __('Scheduled Import Activity', 'slp-pro');

        // Opening Divs
        //
        $html =
            '<div class="metabox-holder">' .
            '<div class="meta-box-sortables">' .
            '<div id="location_import_cron_messages" class="postbox">'                      .
                '<div class="handlediv" title="Click to toggle"><br></br></div>'    .
                "<h3 class='hndle'><span>{$box_title}</span></h3>"                              .
                '<div class="inside">'
        ;

        $html .= $this->createstring_CronSchedule();

        $html .= $this->createstring_CronMessages();

        // Closing Divs
        //
        $html .=
               '</div>' .
               '</div>' .
               '</div>' .
            '</div>'
            ;


        return $html;
    }

    /**
     * Create cron messages box.
     */
    function createstring_CronMessages() {
        $title= __('Location Import Cron Messages','slp-pro');

        $this->cron_status  = get_option('slp-pro-cron',array());

        if ( count( $this->cron_status ) ) {
            $html =
                '<div class="activity-block">' .
                "<h4>{$title}</h4>";
            foreach ($this->cron_status as $message) {
                $html .= sprintf('<span class="cron_message">%s</span>', $message);
            }

            $html .= '</div>';
        } else {
            $html = '';
        }

        return $html;
    }

    /**
     * Get the cron schedule as a formatted HTML string.
     *
     * @return string
     */
    function createstring_CronSchedule() {
        $html = '';
        $schedule = wp_get_schedule('cron_csv_import', array('import_csv', $this->file_meta ) );
        if ( !empty($schedule) ) {
            $html =
                sprintf( __('CSV file imports are currently scheduled to run %s.', 'slp-pro') , $schedule ) .
                '<br/><br/>'
                ;
        }
        $html .= sprintf( __('The current WordPress time (GMT) is %s.','slp-pro') , current_time( 'mysql' , true ) );
        return $html;
    }

    /**
     * Set the process count output strings the users sees after an upload.
     *
     * @param string[] $message_array
     * @return mixed[]
     */
    function filter_SetMessages($message_array) {
        return array_merge(
                $message_array,
                array(
                    'added'             => __(' new locations added.'                                                   ,'slp-pro'),
                    'location_exists'   => __(' pre-existing locations skipped.'                                        ,'slp-pro'),
                    'malformed'         => __(' locations skipped due to malformed CSV data.'                           ,'slp-pro'),
                    'not_updated'       => __(' locations did not need to be updated.'                                  ,'slp-pro'),
                    'skipped'           => __(' locations were skipped due to duplicate address information.'           ,'slp-pro'),
                    'updated'           => __(' locations were updated.'                                                ,'slp-pro'),
                )
            );
    }

    /**
     * Check that the location file has the address fields.
     */
    function file_has_address_fields() {
        $address_field_count =
            count(
                array_intersect(
                    $this->fieldnames ,
                    array(
                        'sl_address',
                        'sl_address2',
                        'sl_city',
                        'sl_state',
                        'sl_zip',
                        'sl_country'
                    )
                )
            );

        return ( $address_field_count > 0 );
    }

    /**
     * Set the default field names if the CSV Import header is not provided.
     *
     * Default:
     * 'sl_store'   [ 0],'sl_address'  [ 1],'sl_address2'[ 2],'sl_city'       [ 3],'sl_state'[ 4],
     * 'sl_zip'     [ 5],'sl_country'  [ 6],'sl_tags'    [ 7],'sl_description'[ 8],'sl_url'  [ 9],
     * 'sl_hours'   [10],'sl_phone'    [11],'sl_email'   [12],'sl_image'      [13],'sl_fax'  [14],
     * 'sl_latitude'[15],'sl_longitude'[16],'sl_private' [17],'sl_neat_title' [18]
     *
     * @param string[] $name_array
     * @return string[]
     */
    function filter_SetDefaultFieldNames($name_array) {
        return array_merge(
                $name_array,
                array(
                    'sl_store','sl_address','sl_address2','sl_city','sl_state',
                    'sl_zip','sl_country','sl_tags','sl_description','sl_url',
                    'sl_hours','sl_phone','sl_email','sl_image','sl_fax',
                    'sl_latitude','sl_longitude','sl_private','sl_neat_title'
                )
            );
    }

	/**
	 * Things we do to prepare for an import.
	 */
	public function prepare_for_import() {
		do_action('slp_prepare_location_import');
		return $this->set_FileMeta();
	}

    /**
     * Set file meta for the import.
     *
     * Use the standard browser file upload objects $_FILES if set.
     *
     * If not set check for a remote file URL and use that.
     *
     */
    private function set_FileMeta( ) {

        // Browser File Upload
        //
        if ( isset( $_FILES ) && ( ! empty( $_FILES['csvfile']['name'] ) ) ) {
            $this->file_meta = $_FILES;
            return 'immediate';
        }

        $remote_file =  $this->addon->options['csv_file_url'];
        if ( defined('DOING_CRON') ) {
            $remote_file = $this->addon->options['csv_file_url'];
            include_once( ABSPATH . 'wp-admin/includes/file.php');
        }


        // Remote File URL
        //
        if ( ! empty( $remote_file ) ) {
            if ( $this->slplus->helper->webItemExists( $remote_file ) ) {
                $response = wp_remote_get( $remote_file , array( 'timeout' => 300 ) );

                // File opened without any issues.
                //
                if ( is_array( $response ) &&  isset( $response['body'] ) &&  ! empty( $response['body'] )  ) {
                    $ftp_file = $response['body'];
                    $local_file = wp_tempnam();

                    file_put_contents($local_file, $ftp_file);

                    $this->file_meta['csvfile'] = array(
                        'name' => 'slp_locations.csv',
                        'type' => 'text/csv',
                        'tmp_name' => $local_file,
                        'error' => (is_bool($ftp_file) ? '4' : '0'),
                        'size' => strlen($ftp_file),
                        'source' => 'direct_url',
                    );

                    // Houston, we have a problem...
                    //
                } else {
                    $this->addon->cron_job->add_cron_status(__('Could not fetch the remote file.', 'slp-pro'));

                }

            // Remote File does not exist.
            //
            } else {
                $this->addon->cron_job->add_cron_status(
                    sprintf(
                        __('%s does not exist.', 'slp-pro') ,
                        $remote_file
                    )
                );
            }
        }
	    return NULL;
    }

    /**
     * Return true if it is OK to process this file.
     *
     * @return bool
     */
    function ok_to_process_file() {
        $is_ok = $this->file_has_address_fields();

        if ( ! $is_ok  ) {
            $this->processing_report[] = __('The location CSV import file is missing the address fields.', 'slp-pro');
            $this->processing_report[] =
                sprintf( __('Fields received were: <pre>%s</pre>', 'slp-pro'), print_r( $this->fieldnames,true) );
        }

        return $is_ok;
    }

    /**
     * Process the file being imported.
     *
     * cron_csv_import action takes 2 parameters:
     * param 1: the action to perform
     * param 2: the params to be sent to the action processor
     *
     * @param mixed[] $file_meta the details about the file being processed.
     */
    public function process_File( $file_meta = NULL , $mode = NULL ) {

        if ( $mode === NULL ) {
            $mode = $this->addon->options['cron_import_recurrence'];
        }

        // Cron Job CSV Import Processing
        //
        switch ( $mode ) {

            // Immediate Processing
            //
            case 'immediate':
            case 'none'     :
                wp_clear_scheduled_hook('cron_csv_import',  array('import_csv', $this->file_meta ));

                if ( $this->addon->options['load_data'] ) {
                    $this->load_directly_into_mysql();
                } else {
                    parent::process_File($file_meta);
                }
                break;

            // ASAP
            //
            case 'at'  :
                wp_schedule_single_event(
                    $this->addon->options['cron_import_timestamp'],
                    'cron_csv_import',
                    array('import_csv', $this->file_meta )
                );
                break;

            // Hourly, Twice Daily, Daily
            //
            default     :
                wp_schedule_event(
                    $this->addon->options['cron_import_timestamp'],
                    $this->addon->options['cron_import_recurrence'],
                    'cron_csv_import',
                    array('import_csv', $this->file_meta )
                );
                break;
        }
    }

    /**
     * Strip extra spaces from location data.
     *
     * @param $location_data
     * @return string[] $location_data
     */
    function strip_extra_spaces_from_csv_location_data( $location_data ) {
        return array_map( 'trim' , $location_data );
    }

    /**
     * Load basic CSV files directly into MYSQL with LOAD DATA.
     *
     * This is MUCH faster than CSV parsing but requires the CSV has no extended data
     * such as Tagalong categories, etc.
     */
    function load_directly_into_mysql() {

        if ( ! $this->is_valid_csv_file( $this->file_meta ) ) { return; }

        $new_file = $this->move_csv_to_slpdir( $this->file_meta );
        if ( empty( $new_file ) ) { return; }

        if ( ! $this->open_csv_file( $new_file ) ) { return; }

        $this->set_FieldNames();
        fclose( $this->filehandle );


        $core_fieldnames = array_filter( $this->fieldnames , array( $this , 'return_base_fields_only' ) );
        $field_list = join(',',$core_fieldnames);

        global $wpdb;
        $table_name = $wpdb->prefix . "store_locator";

        $ignore_first_line =
            ($this->skip_firstline || $this->firstline_has_fieldname) ?
                'IGNORE 1 LINES':
                '';

            $load_data_sql =
            sprintf(
                "LOAD DATA LOCAL INFILE '%s' INTO TABLE %s  " .
                "FIELDS TERMINATED BY ','   " .
                "ENCLOSED BY '\"'           " .
                "ESCAPED BY '\\\\'           " .
                "%s " .
                "( %s )"
                ,
                $new_file ,
                $table_name ,
                $ignore_first_line,
                $field_list
            );
        $this->slplus->db->query( $load_data_sql );

        // Now geocode them.
        //
        if ( ! $this->skip_geocoding ) {
            $this->addon->admin->recode_all_uncoded_locations();
        }
    }

    /**
     * Only use the base table fields.
     *
     * @param $var
     * @return bool
     */
    function return_base_fields_only( $var ) {
        $var = preg_replace('/^sl_/', '', $var );
        return ( array_search( $var , $this->slplus->currentLocation->dbFields , true ) !== false );
    }

    /**
     * Save the import message stack to persistent storage.
     */
    function save_import_message_stack() {
        if ( isset( $this->message_stack ) ) {
            $this->message_stack->save_messages();
        }
    }

}
