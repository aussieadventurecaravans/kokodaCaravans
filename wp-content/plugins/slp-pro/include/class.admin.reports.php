<?php
if (!defined( 'ABSPATH'     )) { exit;   } // Exit if accessed directly, dang hackers

// Make sure the class is only defined once.
//
if (!class_exists('SLPPro_Admin_Reports')) {
    
    /**
     * Admin Report System for Pro Pack
     *
     * @property        SLPPro                      $plugin
     * @property        SLPPro_Admin_Reports_Data   $data
     * @property-read   string                      $end_date       The ending report range date.
     * @property-read   int                         $report_limit   Limit of records to report.
     * @property-read   SLP_Settings                $settings
     * @property-read   string                      $start_date     The starting report range date.
     *
     * @package StoreLocatorPlus\SLPPro\Admin\Reports
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014 - 2015 Charleston Software Associates, LLC
     */
    class SLPPro_Admin_Reports extends SLPlus_BaseClass_Object {
        public $addon;
        public $data;
        private $end_date;
        private $report_limit;
        private $settings;
        private $start_date;

        /**
         *
         * @param array $options
         */
        function __construct( $options = array() ) {
	        parent::__construct( $options );

            $this->settings = new SLP_Settings(array(
                'name'              => $this->slplus->name . __(' - Reporting','slp-pro'),
                'form_action'       => '',
                'save_text'         => __('Save Settings','slp-pro')
                ));

            // Start of date range to report on
            // default: 30 days ago
            //
            $this->start_date =
                isset($_POST['start_date'])                     ?
                    $_POST['start_date']                        :
                    date('Y-m-d',time() - (30 * 24 * 60 * 60))  ;

            // Start of date range to report on
            // default: today
            //
            $this->end_date =
                isset($_POST['end_date'])               ?
                    $_POST['end_date']                  :
                    date('Y-m-d',time()) . ' 23:59:59'  ;
            if ( ! preg_match( '/\d\d:\d\d$/' , $this->end_date ) ) {
                $this->end_date .= ' 23:59:59';
            }

            // Connect Data Object
            //
            $this->createobject_ReportData();

            // Prepare Data Summary
            //
            $this->data->summarize_data( $this->start_date , $this->end_date );
        }

        /**
         * Add Graph to reports.
         *
         * @param $section_name
         */
        private function add_Graph( $section_name ) {
            $panel_name = __('Report Summary', 'slp-pro');
            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'show_label'    => false                            ,
                    'type'          => 'custom'                         ,
                    'custom'        => '<div id="chart_div"></div>'
                )
            );
        }

        /**
         * Add the standard NavBar to the report page.
         */
        function add_NavBarToTab() {
            $this->settings->add_section(
                array(
                    'name'          => 'Navigation',
                    'div_id'        => 'navbar_wrapper',
                    'description'   => $this->slplus->AdminUI->create_Navbar(),
                    'innerdiv'      => false,
                    'is_topmenu'    => true,
                    'auto'          => false,
                    'headerbar'     => false
                )
            );
        }

        /**
         * Add the Report Section.
         */
        function add_ReportSection() {
            $section_name = __('Reports','slp-pro');
            $this->settings->add_section(
                array(
                    'name'          => $section_name,
                    'auto'          => true
                )
            );

            $this->add_Graph( $section_name );
            $this->add_ReportSection_ParametersPanel( $section_name );
            $this->add_ReportSection_SearchReportPanel( $section_name );
            $this->add_ReportSection_ResultsReportPanel( $section_name );
            $this->add_ReportSection_DownloadPanel( $section_name );
        }

        /**
         * Add the download panel to the reports section.
         *
         * @param $section_name
         */
        function add_ReportSection_DownloadPanel( $section_name ) {
            $panel_name = __('Export To CSV' , 'slp-pro');
            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'show_label'    => false                            ,
                    'type'          => 'custom'                         ,
                    'custom'        =>
                        '<div class="form_entry">'.
                            '<label for="export_all">'.__('Export all records','slp-pro').'</label>' .
                            '<input id="export_all" type="checkbox"  name="export_all" value="1">'.
                        '</div>'.
                        '<div class="form_entry">' .
                            '<input id="export_searches" class="button-secondary button-export" type="button" value="'.__('Top Searches','slp-pro').'"><br/>'.
                            '<input id="export_results"  class="button-secondary button-export" type="button" value="'.__('Top Results','slp-pro').'">'.
                        '</div>' .
                        '<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>'
                )
            );
        }

        /**
         * Add the Parameters Section to the Report Panel
         *
         * @param string $section_name
         */
        function add_ReportSection_ParametersPanel( $section_name ) {
            $panel_name = __('Report Parameters','slp-pro');

            // Start Date Entry
            //
            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'label'         => __('Start Date','slp-pro')   ,
                    'setting'       => 'start_date'                     ,
                    'use_prefix'    => false                            ,
                    'type'          => 'text'                           ,
                    'value'         => $this->start_date                ,
                    'description'   =>
                        __('Only show data on or after this date.  YYYY-MM-DD.', 'slp-pro')
                )
            );


            // End Date
            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'label'         => __('End Date','slp-pro')     ,
                    'setting'       => 'end_date'                       ,
                    'use_prefix'    => false                            ,
                    'type'          => 'text'                           ,
                    'value'         => $this->end_date                  ,
                    'description'   =>
                        __('Only show data on or before this date.  YYYY-MM-DD hh:mm:ss.', 'slp-pro')
                )
            );

            // How many detail records to report back
            // default: 10
            //
            $this->report_limit =
                isset( $_POST['report_limit'] ) ?
                    $_POST['report_limit']      :
                    '10'                        ;
            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'label'         => __('Report Limit','slp-pro') ,
                    'setting'       => 'report_limit'                   ,
                    'use_prefix'    => false                            ,
                    'type'          => 'text'                           ,
                    'value'         => $this->report_limit              ,
                    'description'   =>
                        __('Limit the report to this many detail records.  Default: 10.  Recommended maximum: 500.', 'slp-pro')
                )
            );

            // Counts
            //
            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'show_label'    => false                            ,
                    'type'          => 'custom'                         ,
                    'custom'   =>
                        sprintf(
                            '<div class="report_line total">' .
                            __('Total searches: <strong>%s</strong>', 'slp-pro'). "<br/>" .
                            __('Total results: <strong>%s</strong>', 'slp-pro').  "<br/>" .
                            __('Days with activity: <strong>%s</strong>', 'slp-pro'). "<br/>" .
                            '</div>',
                            $this->data->total_searches,
                            $this->data->total_results,
                            count( $this->data->counts_dataset )
                        )
                )
            );
        }


        /**
         * Add the Results Panel To The Report Section
         */
        private function add_ReportSection_ResultsReportPanel( $section_name ) {
            $panel_name = sprintf(__('Top %s Results Returned', 'slp-pro') , $this->report_limit);

            $slpSectionHeader = sprintf(__('Top %s Results Returned', 'slp-pro'),$this->report_limit);
            $slpColumnHeaders = array(
                __('Store'  ,'slp-pro'),
                __('City'   ,'slp-pro'),
                __('State'  ,'slp-pro'),
                __('Zip'    ,'slp-pro'),
                __('Tags'   ,'slp-pro'),
                __('Total'  ,'slp-pro')
            );
            $slpDataLines = array(
                array('columnName' => 'sl_store',   'columnClass'=> ''            ),
                array('columnName' => 'sl_city',    'columnClass'=> ''            ),
                array('columnName' => 'sl_state',   'columnClass'=> ''            ),
                array('columnName' => 'sl_zip',     'columnClass'=> ''            ),
                array('columnName' => 'sl_tags',    'columnClass'=> ''            ),
                array('columnName' => 'ResultCount','columnClass'=> 'alignright'  ),
            );

            $this->data->set_top_results( $this->start_date , $this->end_date , $this->report_limit );

            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'show_label'    => false                            ,
                    'type'          => 'custom'                         ,
                    'custom'   =>
                        $this->createstring_DataTable(
                            "top,,{$this->start_date},{$this->end_date},{$this->report_limit}",
                            $this->data->top_results,
                            $slpSectionHeader,
                            $slpColumnHeaders,
                            $slpDataLines,
                            __('topresults','slp-pro')
                        )
                )
            );

        }

        /**
         * Add the Search Report Panel To The Report Section
         */
        private function add_ReportSection_SearchReportPanel( $section_name ) {
            $panel_name = sprintf(__('Top %s Addresses Searched', 'slp-pro'),$this->report_limit);

            $slpSectionHeader = sprintf(__('Top %s Addresses Searched', 'slp-pro'),$this->report_limit);
            $slpColumnHeaders = array(
                __('Address','slp-pro'),
                __('Total','slp-pro')
            );
            $slpDataLines = array(
                array('columnName' => 'slp_repq_address', 'columnClass'=> ''            ),
                array('columnName' => 'QueryCount',       'columnClass'=> 'alignright'  ),
            );

            $this->data->set_top_searches( $this->start_date , $this->end_date , $this->report_limit );

            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                    ,
                    'group'         => $panel_name                      ,
                    'show_label'    => false                            ,
                    'type'          => 'custom'                         ,
                    'custom'   =>
                        $this->createstring_DataTable(
                            "addr,{$this->start_date},{$this->end_date},{$this->report_limit}",
                            $this->data->top_searches,
                            $slpSectionHeader,
                            $slpColumnHeaders,
                            $slpDataLines,
                            __('topsearches','slp-pro')
                        )
                )
            );
        }

        /**
         * Add the Settings Section.
         */
        private function add_SettingsSection() {
            $section_name = __('Settings','slp-pro');


            $this->settings->add_section(
                array(
                    'name'          => $section_name,
                    'auto'          => true ,
                    'description'   =>
                        ( ! $this->addon->options['reporting_enabled'] ) ?
                        __('Enable reporting to start recording location search data. ' , 'slp-pro') .
                        __('Once enabled the report section will appear on this tab.'   , 'slp-pro') :
                        ''
                )
            );

            $this->add_SettingsSection_SettingsPanel( $section_name );
        }

        /**
         * Add the Settings Panel to the Settings Section
         *
         * @param $section_name
         */
        private function add_SettingsSection_SettingsPanel( $section_name ) {
            $panel_name = __('Settings', 'slp-pro');
            $this->settings->add_ItemToGroup(
                array(
                    'section'       => $section_name                        ,
                    'group'         => $panel_name                          ,
                    'label'         => __('Enable Reporting','slp-pro') ,
                    'setting'       => 'reporting_enabled'                  ,
                    'use_prefix'    => false,
                    'type'          => 'checkbox'                           ,
                    'value'         => ( $this->slplus->is_CheckTrue($this->addon->options['reporting_enabled']) ? '1' : '0') ,
                    'description'   =>
                        __('Enables tracking of searches and returned results.  The added overhead can increase how long it takes to return location search results.', 'slp-pro')
                )
            );
        }

        /**
         * Create the report data interface and attaches it to ->data.
         */
        function createobject_ReportData() {
            if ( !isset( $this->data ) ) {
                require_once($this->addon->dir . 'include/class.admin.reports.data.php');
                $this->data = new SLPPro_Admin_Reports_Data(
                    array(
                        'addon'     => $this->addon,
                        'slplus'    => $this->slplus,
                    )
                );
            }
        }

        /**
         * Create a data table.
         *
         * @param $tag
         * @param $theQuery
         * @param $SectionHeader
         * @param $columnHeaders
         * @param $columnDataLines
         * @param $Qryname
         * @return string
         */
        function createstring_DataTable( $tag, $thisDataset, $SectionHeader, $columnHeaders, $columnDataLines, $Qryname) {

            $thisQryname = strtolower(preg_replace('/\s/','_',$Qryname));
            $thisQryvalue= htmlspecialchars($tag,ENT_QUOTES,'UTF-8');

            $thisSectionDesc =
                '<input type="hidden" name="'.$thisQryname.'" value="'.$thisQryvalue.'">' .
                '<table id="'.$Qryname.'_table" cellpadding="0" cellspacing="0">' .
                '<thead>' .
                '<tr>';

            foreach ($columnHeaders as $columnHeader) {
                $thisSectionDesc .= "<th>$columnHeader</th>";
            }

            $thisSectionDesc .=  '</tr>' .
                '</thead>' .
                '<tbody>';

            $slpReportRowClass = 'rowon';
            foreach ($thisDataset as $thisDatapoint) {
                $slpReportRowClass = ($slpReportRowClass === 'rowoff') ? 'rowon' : 'rowoff';
                $thisSectionDesc .= '<tr>';
                foreach ($columnDataLines as $columnDataLine) {
                    $columnName = $columnDataLine['columnName'];
                    $columnClass= $columnDataLine['columnClass'];
                    $thisSectionDesc .= sprintf(
                        '<td class="%s %s">%s</td>',
                        $columnClass,
                        $slpReportRowClass,
                        $thisDatapoint->$columnName
                    );
                }
                $thisSectionDesc .= '</tr>';
            }

            $thisSectionDesc .=
                '</tbody>' .
                '</table>'
            ;

            return $thisSectionDesc;
        }


        /**
         * Build the reports tab content.
         */
        function render_ReportsTab() {
            $this->save_Settings();
            $this->add_NavBarToTab();

            // If reporting is enabled put the report section first.
            //
            if ( $this->addon->options['reporting_enabled'] ) {
                $this->add_ReportSection();
            }

            $this->add_SettingsSection();

            $this->settings->render_settings_page();
        }

        /**
         * Save settings when appropriate.
         */
        function save_Settings() {
            if (
                ! isset( $_REQUEST['_wp_http_referer'] ) ||
                ( substr( $_REQUEST['_wp_http_referer'] , -strlen('page=slp_reports') ) !== 'page=slp_reports' ) ||

                ! isset( $_REQUEST['action'] ) ||
                ( $_REQUEST['action'] !== 'update' )
            ) {
                return;
            }

            $this->addon->options['reporting_enabled'] = (
                (
                    isset( $_REQUEST['reporting_enabled'] ) &&
                    $this->slplus->is_CheckTrue( $_REQUEST['reporting_enabled'] )
                )  ?
                    '1' :
                    '0'
            );

            update_option( $this->addon->option_name , $this->addon->options );
        }

    }
}
