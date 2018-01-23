<?php
if (! class_exists('SLPEnhancedSearch_Admin')) {
	require_once(SLPLUS_PLUGINDIR.'/include/base_class.admin.php');

    /**
     * Holds the admin-only code.
     *
     * This allows the main plugin to only include this file in admin mode
     * via the admin_menu call.   Reduces the front-end footprint.
     *
     * @package StoreLocatorPlus\EnhancedSearch\Admin
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2014 - 2015 Charleston Software Associates, LLC
     */
    class SLPEnhancedSearch_Admin extends SLP_BaseClass_Admin {

        /**
         * This addon pack.
         *
         * @var \SLPEnhancedSearch $addon
         */
        public $addon;

	    /**
	     * List of option keys that are checkboxes.
	     *
	     * Helps with processing during save of form posts.
	     *
	     * @var string[] $cb_options
	     */
	    private $cb_options = array (
		    'hide_address_entry',
		    'hide_search_form'  ,
		    'search_by_name'    ,
		    'csl-slplus_hide_radius_selections'
	    );

	    /**
	     * @var SLPEnhancedSearch_Admin_UXSettings
	     */
	    private $userexperience;

	    /**
	     * Add our specific hooks and filters.
	     */
	    function add_hooks_and_filters() {
		    parent::add_hooks_and_filters();
		    add_action( 'slp_ux_modify_adminpanel_search'      , array( $this , 'add_ux_search_feature_settings'      ) , 10 , 2 );

		    // General Settings tab : Google section
		    //
		    add_action('slp_generalsettings_modify_userpanel'   ,array($this,'action_AddUserSettings'               ), 9 , 2);
		    add_filter('slp_save_general_settings_checkboxes'   ,array($this,'filter_SaveGeneralCBSettings'         )       );


		    add_action( 'slp_save_map_settings'                 , array( $this , 'save_uxtab_settings'      ) , 9   );
		    add_filter( 'slp_save_map_settings_checkboxes'      , array( $this , 'save_uxtab_checkboxes'    )       );
	    }

	    /**
	     * Add ES settings to UX / Map / Features setting.
	     *
	     * @param $settings
	     * @param $section_name
	     */
	    function add_ux_search_feature_settings( $settings, $section_name ) {
		    $this->create_object_admin_ux();
		    $this->userexperience->add_ux_search_feature_settings( $settings, $section_name );
	    }

	    /**
	     * Add items to the General Settings tab : Google section
	     *
	     * @param SLP_Settings $settings
	     * @param string $sectName
	     */
	    function action_AddUserSettings($settings,$sectName) {
		    $groupName = __('Program Interface','csa-slp-es');
		    $settings->add_ItemToGroup(
			    array(
				    'section'       => $sectName        ,
				    'group'         => $groupName       ,
				    'type'          => 'subheader'      ,
				    'label'         => $this->addon->name      ,
				    'show_label'    => false
			    ));
		    $settings->add_ItemToGroup(
			    array(
				    'section'       => $sectName    ,
				    'group'         => $groupName   ,
				    'type'          => 'checkbox'     ,
				    'setting'       => 'es_allow_addy_in_url',
				    'label'         => __('Allow Address In URL','csa-slp-es'),
				    'description'   =>
					    __('If checked an address can be pre-loaded via a URL string ?address=blah.', 'csa-slp-es') .
					    ' ' .
					    __('This will disable the Pro Pack location sensor whenever the address is used in the URL.', 'csa-slp-es')
			    ));
	    }

	    /**
	     * Create and attach the user experience object.
	     */
	    private function create_object_admin_ux() {
		    if ( ! isset( $this->userexperience ) ) {
			    require_once('class.admin.userexperience.php');
			    $this->userexperience = new SLPEnhancedSearch_Admin_UXSettings(array( 'addon' => $this->addon , 'slplus' => $this->slplus ) );
		    }
	    }

	    /**
	     * Save the map settings via SLP Action slp_save_map_settings.
         *
         * TODO: Convert all of these to serialized options.
	     *
	     * @return string
	     */
	    function save_uxtab_settings() {
		    $BoxesToHit = array(
			    'sl_name_label'                                 ,
			    SLPLUS_PREFIX.'_state_pd_label'                 ,
			    SLPLUS_PREFIX.'_search_by_city_pd_label'        ,
			    SLPLUS_PREFIX.'_search_by_state_pd_label'       ,
			    SLPLUS_PREFIX.'_search_by_country_pd_label'     ,
		    );
		    foreach ($BoxesToHit as $JustAnotherBox) {
			    $this->slplus->helper->SavePostToOptionsTable($JustAnotherBox);
		    }

		    // Serialized : Compound Options
		    //
		    $this->addon->options =
			    $this->slplus->AdminUI->save_SerializedOption(
				    $this->addon->option_name,
				    $this->addon->options,
				    $this->cb_options
			    );
	    }

	    /**
	     * Save the General Settings tab checkboxes.
	     *
	     * @param mixed[] $cbArray
	     * @return mixed[]
	     */
	    function filter_SaveGeneralCBSettings($cbArray) {
		    return array_merge($cbArray,
			    array(
				    SLPLUS_PREFIX.'-es_allow_addy_in_url',
			    )
		    );
	    }

	    /**
	     * Save admin UX tab checkboxes to the options table in WP.
	     *
	     * @param string[] $cbArray array of checkbox names to be saved
	     *
	     * @return string[] augmented list of inputs to save
	     */
	    function save_uxtab_checkboxes( $cbArray ) {
		    return array_merge( $cbArray, $this->cb_options );
	    }

  }
}
