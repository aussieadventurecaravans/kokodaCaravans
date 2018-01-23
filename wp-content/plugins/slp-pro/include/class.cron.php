<?php
if (! class_exists('SLPPro_Cron')) {

    /**
     * The cron job processing class.
     *
     * @package StoreLocatorPlus\SLPPro\Cron
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014 Charleston Software Associates, LLC
     */
    class SLPPro_Cron {
        /**
         * The cron action to be performed.
         *
         * @var string
         */
        private $action;

        /**
         * The params received during the cron job call.
         *
         * @var mixed[]
         */
        private $action_params;

        /**
         * This addon pack.
         *
         * @var \SLPPro $addon
         */
        public $addon;

        /**
         * The cron status stack written to the wp_option table.
         *
         * @var string[]
         */
        private $cron_status = array();

        /**
         * @var /SLPlus $slplus
         */
        public $slplus;

        function __construct( $params ) {

            // Set properties based on constructor params,
            // if the property named in the params array is well defined.
            //
            if ($params !== null) {
                foreach ($params as $property=>$value) {
                    if (property_exists($this,$property)) { $this->$property = $value; }
                }
            }


            $this->addon->cron_job = $this;
            $this->add_cron_status( __('Pro Pack cron import underway.','slp-pro') );
            $this->process_action();
        }

        /**
         * Update the cron status message stack and make persistent in wp_options.
         *
         * @param $message
         */
        function add_cron_status( $message ) {
            $timestamped_message = current_time( 'mysql' , true ) .' :: ' . $message;
            $this->cron_status[] = $timestamped_message;
            error_log($timestamped_message);
            update_option('slp-pro-cron' , $this->cron_status );
        }

        /**
         * Process the cron action.
         */
        function process_action() {

            switch ( $this->action ) {
                case 'import_csv':
                    $this->import_csv();
                    break;

                default:
                    $this->add_cron_status( sprintf(__('Action %s is unsupported','slp-pro') , $this->action ) );
                    break;
            }
        }

        /**
         * Import a location CSV file.
         */
        function import_csv() {
            $this->addon->create_CSVLocationImporter();
            $this->addon->csvImporter->prepare_for_import();
            $this->addon->csvImporter->process_File( $this->addon->csvImporter->file_meta , 'immediate' );
            if ( count($this->addon->csvImporter->processing_report) > 0 ) {
                foreach ( $this->addon->csvImporter->processing_report as $message ) {
                    $this->add_cron_status( $message );
                }
            }
            $this->addon->slplus->notifications->delete_all_notices();
        }
  }
}