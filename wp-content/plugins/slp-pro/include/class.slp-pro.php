<?php
if ( ! class_exists( 'SLPPro' ) ) {
    require_once(SLPLUS_PLUGINDIR . 'include/base_class.addon.php');

    /**
     * The Pro Pack Add-On Pack for Store Locator Plus.
     *
     * @package StoreLocatorPlus\ProPack
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014-2016 Charleston Software Associates, LLC
     *
     * @property    SLPPro_Admin        $admin
     * @property    SLPPro_Cron         $cron_job           If a cron job is running this points to the object handling it.
     * @property    CSVExportLocations  $csvExporter
     * @property    CSVImportLocations  $csvImporter
     * @property    SLPPro              $instance       A copy of the SLPPro object once instantiated.
     * @property    array               $options
     *                                      highlight_uncoded if on the non-geocoded locations are highlighted on the manage locations table.
     *                                      layout the overall locator page layout, uses special shortcodes, default set by base plugin \SLPlus class.
     *                                      tag_label text that appears before the form label
     *                                      tag_selector style of search form input 'none','hidden','dropdown','textinput'
     *                                      tag_selections list of drop down selections
     *                                      tag_output_processing how tag data should be pre-processed when sending JSONP response to front end UI.
     *                                      csv_duplicates_handling = how to handle incoming records that match name + add/add2/city/state/zip/country
     *                                           add = add duplicate records (default)
     *                                           skip = skip over duplicate records
     *                                           update = update duplicate records, requires id field in csv file to update name or address fields
     *                                      installed_version - set when the plugin is activated or updated
     *
     * @var mixed[] $options
     *
     *
     */
    class SLPPro extends SLP_BaseClass_Addon {
        public $admin;
        public $cron_job;
        public $csvExporter;
        public $csvImporter;
        public static $instance;
        public $options = array(
            'cron_import_timestamp'         => '',
            'cron_import_recurrence'        => 'none',
            'csv_file_url'                  => '',
            'csv_first_line_has_field_name' => '1',
            'csv_skip_first_line'           => '0',
            'csv_skip_geocoding'            => '0',
            'csv_duplicates_handling'       => 'update',
            'load_data'                     => '0',
            'custom_css'                    => '',
            'highlight_uncoded'             => '1',
            'installed_version'             => '',
            'layout'                        => '',
            'tag_autosubmit'                => '0',
            'tag_dropdown_first_entry'      => '',
            'tag_label'                     => '',
            'tag_selector'                  => 'none',
            'tag_selections'                => '',
            'tag_show_any'                  => '1',
            'tag_output_processing'         => 'as_entered',
            'reporting_enabled'             => '0',
            'use_sensor'                    => '0',
        );

        /**
         * Invoke the plugin.
         */
        public static function init() {
            static $instance = false;
            if (!$instance) {
                load_plugin_textdomain('slp-pro', false, SLPPRO_REL_DIR . '/languages/');
                $instance = new SLPPro( array(
                    'version'                   => '4.5.07',
                    'min_slp_version'           => '4.5.02',

                    'name'                      => __('Pro Pack', 'slp-pro'),
                    'option_name'               => 'csl-slplus-PRO-options',

                    'activation_class_name'     => 'SLPPro_Activation',
                    'admin_class_name'          => 'SLPPro_Admin',
                    'ajax_class_name'           => 'SLPPro_AJAX',
                    'userinterface_class_name'  => 'SLPPro_UI',

                    'file' => SLPPRO_FILE
                ));
            }

            return $instance;
        }

        /**
         * Convert an array to CSV.
         *
         * @param array[] $data
         * @return string
         */
        static function array_to_CSV($data) {
            $outstream = fopen("php://temp", 'r+');
            fputcsv($outstream, $data, ',', '"');
            rewind($outstream);
            $csv = fgets($outstream);
            fclose($outstream);
            return $csv;
        }


        /**
         * Create and attach the \CSVExportLocations object
         */
        function create_CSVLocationExporter() {
            if (!class_exists('CSVExportLocations')) {
                require_once( 'class.csvexport.locations.php' );
            }
            if (!isset($this->csvExporter)) {
                $this->csvExporter =
                    new CSVExportLocations(
                        array(
                            'addon' => $this,
                            'slplus' => $this->slplus,
                            'type' => 'Locations'
                        )
                    );
            }
        }

        /**
         * Create and attach the \CSVImportLocations object
         */
        function create_CSVLocationImporter() {
            if (!isset($this->csvImporter)) {
	            require_once( 'class.csvimport.locations.php' );
                $this->csvImporter = new CSVImportLocations( array( 'addon' => $this ) );
            }
        }

        /**
         * Set the admin menu items.
         *
         * @param mixed[] $menuItems
         * @return mixed[]
         */
        public function filter_AddMenuItems($menuItems) {
            $this->createobject_Admin();
            $this->admin_menu_entries = array(
                array(
                    'label' => __('Reports', 'slp-pro'),
                    'slug' => 'slp_reports',
                    'class' => $this->admin->reports,
                    'function' => 'render_ReportsTab'
                ),
            );
            return parent::filter_AddMenuItems($menuItems);
        }

        /**
         * Process a cron job.
         *
         * WordPress is not a "time perfect" cron processor.   It will fire the event the next time a visitor
         * comes to the site AFTER the specified cron job time.
         *
         * Action Parameter
         * - 'import_csv' : import the csv locations file, params needs to be a file_meta named array
         *
         * @param $action
         * @param $params
         */
        static function process_cron_job($action, $params) {
            if (class_exists('SLPPro_Cron') == false) {
                require_once( 'class.cron.php' );
            }

            $pro_instance = SLPPro::init();

            $pro_instance->cron_job = new SLPPro_Cron(
                array(
                    'action' => $action,
                    'action_params' => $params,
                    'addon' => $pro_instance,
                )
            );
        }

    }
}

