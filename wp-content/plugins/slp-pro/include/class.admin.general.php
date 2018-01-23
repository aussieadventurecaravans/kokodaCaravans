<?php
if ( ! class_exists( 'SLPPro_Admin_GeneralSettings' ) ) {

	/**
	 * Class SLPPro_Admin_GeneralSettings
	 *
	 * The things that modify the Admin / General Settings UX.
	 *
	 *
	 * @package StoreLocatorPlus\SLPPremier\Admin\Experience
	 * @author Lance Cleveland <lance@storelocatorplus.com>
	 * @copyright 2016 Charleston Software Associates, LLC
	 *
	 * Text Domain: slp-pro
	 *
	 * @property        SLPPro          $addon
	 */
	class SLPPro_Admin_GeneralSettings  extends SLPlus_BaseClass_Object {
		public  $addon;

        /**
         * Things we do at the start.
         */
        public function initialize() {
            add_action( 'slp_build_general_settings_panels' , array( $this , 'extend_admin_subtab'    ), 90 );
            add_action( 'slp_build_general_settings_panels' , array( $this , 'extend_user_subtab'     ), 90 );
        }

        /**
         * Add General / Admin
         *
         * @param SLP_Settings  $settings
         */
        function extend_admin_subtab( $settings ) {
            $group_params = array(
                'plugin'       => $this->addon,
                'section_slug' => 'admin',

                'header'       =>  __( 'Locations' , 'slp-pro' ) ,
                'group_slug'   => 'locations',
            );

            $settings->add_ItemToGroup(array(
                'group_params'  => $group_params,
                'type'          => 'checkbox'  ,
                'option'        => 'highlight_uncoded',
                'label'         => __('Highlight Uncoded' ,'slp-pro'),
                'description'   => __('Highlight the uncoded locations in the Manage Locations panel.','slp-pro')
            ));

        }

        /**
         * Extend the General / User tab with new settings.
         *
         * @param SLP_Settings  $settings
         */
        function extend_user_subtab( $settings ) {
            $group_params = array(
                'plugin'       => $this->addon,
                'section_slug' => 'user_interface',
                'group_slug'   => 'javascript',
            );

            // JavaScript
            $settings->add_ItemToGroup(array(
                'group_params'  => $group_params,
                'type'          => 'checkbox'  ,
                'option'        => 'use_sensor',
                'label'         => __('Use location sensor' , 'slp-pro' ),
                'description'   => __('This can be slow to load and customers are prompted whether or not to allow location sensing.', 'slp-pro')
            ));
        }
    }
}