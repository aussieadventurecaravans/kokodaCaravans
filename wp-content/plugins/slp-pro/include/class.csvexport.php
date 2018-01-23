<?php
if (!defined( 'ABSPATH'     )) { exit;   } // Exit if accessed directly, dang hackers

// Make sure the class is only defined once.
//
if (!class_exists('CSVExport')) {

    /**
     * CSV Export for Pro Pack
     *
     * @package StoreLocatorPlus\ProPack\CSVExport
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2013-2014 Charleston Software Associates, LLC
     */
    class CSVExport {

        /**
         * The plugin object
         *
         * @var \SLPPro $plugin
         */
        protected $addon;

        /**
         * The base plugin object.
         *
         * @var \SLPlus $slplus
         */
        protected $slplus;               

        /**
         * What type of export are we running?
         * 
         * @var string $type
         */
        protected $type;

	    /**
	     * @param $params
	     */
        function __construct($params) {
            foreach ($params as $name => $value) {
                $this->$name = $value;
            }
        }

        /**
         * AJAX handler to send the data to a download file for the user.
         */
        function do_SendFile() {
            $this->send_Header();
            $fileaction = "do_Send".$this->type;
            $this->$fileaction();
            die();
        }

        /**
         * Write a file without sending an HTML header, this is for local disk writes.
         *
	     * @param string $output_file the output location php://output for download files
	     */
        function do_WriteFile( $output_file ) {
            $fileaction = "do_Send".$this->type;
            $this->$fileaction( $output_file );
        }

        /**
         * Send the CSV Header
         */
        function send_Header() {
            header( 'Content-Description: File Transfer' );
            header( 'Content-Disposition: attachment; filename=slplus_' . $_REQUEST['filename'] . '.csv' );
            header( 'Content-Type: application/csv;');
            header( 'Pragma: no-cache');
            header( 'Expires: 0');
        }
    }
}