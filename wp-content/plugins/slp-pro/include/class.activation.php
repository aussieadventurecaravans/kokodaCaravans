<?php
if (! class_exists('SLPPro_Activation')) {
    require_once(SLPLUS_PLUGINDIR.'/include/base_class.activation.php');

    /**
     * Manage plugin activation.
     *
     * @package StoreLocatorPlus\Pro\Activation
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2013-2016 Charleston Software Associates, LLC
     *
     */
    class SLPPro_Activation  extends SLP_BaseClass_Activation {

        /**
         * Change these single get_option() settings into an slp-experience serialized option.
         * key = old setting key , value = new options array key
         * @var array
         */
        public $legacy_options = array(
            'csl-slplus-enhanced_search_auto_submit'    => array( 'key' => 'tag_autosubmit'           , 'since' => '4.4.01'  ),
            'csl-slplus-enhanced_search_show_tag_radio' => array( 'key' => 'tag_selector'             , 'since' => '4.0.019'  , 'callback' =>  'SLPPro_Activation::set_tag_selector'   ) ,
            'csl-slplus-custom_css'                     => array( 'key' => 'custom_css '              , 'since' => '4.2.00'  ),
            'csl-slplus-reporting_enabled'              => array( 'key' => 'reporting_enabled '       , 'since' => '4.1.00'  ),
            'csl-slplus_search_tag_label'               => array( 'key' => 'tag_label '               , 'since' => '4.4.01'  ),
            'csl-slplus_show_tag_any'                   => array( 'key' => 'tag_show_any'             , 'since' => '4.4.01'  ),
            'csl-slplus_show_tag_search'                => array( 'key' => 'tag_selector'             , 'since' => '4.1.02'   , 'callback' => 'SLPPro_Activation::set_tag_selector_search' ),
            'csl-slplus_show_tags'                      => array( 'key' => 'tag_output_processing'    , 'since' => '4.0.014' ),
            'csl-slplus_tag_pulldown_first'             => array( 'key' => 'tag_dropdown_first_entry' , 'since' => '4.4.01'  ),
            'csl-slplus_tag_search_selections'          => array( 'key' => 'tag_selections'           , 'since' => '4.0.014' ),
            'csl-slplus_use_location_sensor'            => array( 'key' => 'use_sensor'               , 'since' => '4.4.01'  ),
        );

        /**
         * Clean out duplicate report table indexes.
         */
        private function clean_duplicate_indexes() {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $this->clean_duplicate_index_from_table( 'slp_rep_query'         , 'slp_repq_time' );
            $this->clean_duplicate_index_from_table( 'slp_rep_query_results' , 'slp_repq_id'   );
        }


        /**
         * Clean the duplicate index for each table.
         *
         * @param string $table
         * @param string $column
         */
        private function clean_duplicate_index_from_table( $table , $column ) {
            global $wpdb;

            $table = $wpdb->prefix . $table;
            $tableindices = $wpdb->get_results("SHOW INDEX FROM {$table};");

            $the_list = wp_list_filter( $tableindices, array( 'Column_name' => $column ) );

            if ( count( $the_list ) > 1 ) {
                $first = true;
                foreach ( $the_list as $list_item ) {
                    if ( $first ) { $first = false; continue; }
                    drop_index( $list_item->Table , $list_item->Key_name );
                }
            }
        }

        /**
         * Set the tag selector value for the show_tag_radio from 4.0.019
         *
         * @param $legacy_value
         * @return string
         */
        static function set_tag_selector( $legacy_value ) {
            return ( $legacy_value === '1' ) ? 'radiobutton' : 'none';
        }

        /**
         * Set the tag selector value for show_tag_search from 4.1.02.
         *
         * @param $legacy_value
         * @return string
         */
        static function set_tag_selector_search( $legacy_value ) {
            if ( $legacy_value === false                            ) { return 'none';      }
            if ( ($legacy_value === '0'  )                          ) { return 'none';      }

            $options = get_option( 'csl-slplus-PRO-options' , array() );
            if ( isset( $options['tag_selections'] ) && ! empty( $options['tag_selections'] ) ) { return 'dropdown';  }

            return 'textinput';
        }

        /**
         * Update legacy settings, delete pre 3.12 license key checks, install reporting tables.
         */
        function update() {
            parent::update();

            if (version_compare($this->addon->options['installed_version'], '3.12', '<')) {
                delete_option('csl-slplus-SLPLUS-PRO-isenabled');
                delete_option('csl-slplus-SLPLUS-PRO-lk');
            }

            $this->install_reporting_tables();

            if ( version_compare( $this->addon->options['installed_version'] , '4.4.02' , '<' ) ) {
                $this->clean_duplicate_indexes();
            }
        }

        /*************************************
         * Install reporting tables
         *
         * Update the plugin version in config.php on every structure change.
         */
        private function install_reporting_tables() {
            global $wpdb;

            $charset_collate = '';
            if ( ! empty($wpdb->charset) )
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if ( ! empty($wpdb->collate) )
                $charset_collate .= " COLLATE $wpdb->collate";

            // Reporting: Queries
            //
            $table_name = $wpdb->prefix . "slp_rep_query";
            $sql = "CREATE TABLE $table_name (
                    slp_repq_id         bigint(20) unsigned NOT NULL auto_increment,
                    slp_repq_time       timestamp NOT NULL default current_timestamp,
                    slp_repq_query      varchar(255) NOT NULL,
                    slp_repq_tags       varchar(255),
                    slp_repq_address    varchar(255),
                    slp_repq_radius     varchar(5),
                    meta_value          longtext,
                    PRIMARY KEY  (slp_repq_id),
                    KEY slp_repq_time (slp_repq_time)
                    )
                    $charset_collate
                    ";
            $this->dbupdater($sql,$table_name);

            // Reporting: Query Results
            //
            $table_name = $wpdb->prefix . "slp_rep_query_results";
            $sql = "CREATE TABLE $table_name (
                    slp_repqr_id    bigint(20) unsigned NOT NULL auto_increment,
                    slp_repq_id     bigint(20) unsigned NOT NULL,
                    sl_id           mediumint(8) unsigned NOT NULL,
                    PRIMARY KEY  (slp_repqr_id),
                    KEY slp_repq_id (slp_repq_id)
                    )
                    $charset_collate
                    ";

            // Install or Update the slp_rep_query_results table
            //
            $this->dbupdater($sql,$table_name);
        }

        /**
         * Update the data structures on new db versions.
         *
         * @global object $wpdb
         * @param type $sql
         * @param type $table_name
         * @return string
         */
        private function dbupdater($sql,$table_name) {
            global $wpdb;
            $retval = ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) ? 'new' : 'updated';

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            global $EZSQL_ERROR;
            $EZSQL_ERROR = array();

            return $retval;
        }
    }
}