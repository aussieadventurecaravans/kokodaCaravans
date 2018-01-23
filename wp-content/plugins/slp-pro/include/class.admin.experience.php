<?php
if ( ! class_exists( 'SLPPro_Admin_ExperienceSettings' ) ) {

	/**
	 * Class SLPPro_Admin_ExperienceSettings
	 *
	 * The things that modify the Admin / Experience Settings UX.
	 *
	 *
	 * @package StoreLocatorPlus\SLPPremier\Admin\Experience
	 * @author Lance Cleveland <lance@storelocatorplus.com>
	 * @copyright 2015 - 2016 Charleston Software Associates, LLC
	 *
	 * Text Domain: slp-pro
	 *
	 * @property        SLPPro          $addon
	 */
	class SLPPro_Admin_ExperienceSettings  extends SLPlus_BaseClass_Object {
		public  $addon;

		/**
		 * Experience / Results
		 */
		function add_results_settings() {
            $this->results_appearance();
        }

        /**
         * Experience / Results / Appearance
         */
        private function results_appearance() {
            $this->addon->admin->current_group = __('Appearance' , 'slp-pro' );
			$this->addon->admin->add_sublabel( $this->addon->name );
            $this->addon->admin->add_dropdown(
                __('Show Tags In Output','slp-pro')      ,
                __('How should tags be output in the results below the map and the info bubble?','slp-pro') ,
					array(
						array(
							'label' => __( 'As Entered' , 'slp-pro' )       ,
							'value' => 'as_entered'
						) ,
						array(
							'label' => __( 'Hide Tags' , 'slp-pro' )        ,
							'value' => 'hide'
						) ,
						array(
							'label' => __( 'On Separate Lines' , 'slp-pro' ) ,
							'value' => 'replace_with_br'
						) ,
					),
                'tag_output_processing'
            );
		}

        /**
         * Experience / Search
         */
        function add_search_settings() {
            $this->search_features();
            $this->search_labels();
        }

        /**
         * Experience / Search / Features
         */
        private function search_features() {
            $this->addon->admin->current_group = __( 'Search Features', 'slp-pro' );
            $this->addon->admin->add_sublabel( $this->addon->name );

            $this->addon->admin->add_dropdown( __( 'Search Form Tag Input', 'slp-pro' ), __( 'Select the type of tag input that you would like to see on the search form.', 'slp-pro' ),
                array(
                    array(
                        'label' => __( 'None', 'slp-pro' ),
                        'value' => 'none'
                    ),
                    array(
                        'label' => __( 'Hidden', 'slp-pro' ),
                        'value' => 'hidden'
                    ),
                    array(
                        'label' => __( 'Drop Down', 'slp-pro' ),
                        'value' => 'dropdown'
                    ),
                    array(
                        'label' => __( 'Radio Button', 'slp-pro' ),
                        'value' => 'radiobutton'
                    ),
                    array(
                        'label' => __( 'Text Input', 'slp-pro' ),
                        'value' => 'textinput'
                    )
                ),
                'tag_selector' );
            $this->addon->admin->add_input(
                __( 'Default Tag Selections', 'slp-pro' ),
                __( 'For Hidden or Text tag input enter a default value to be used in the field, if any. ', 'slp-pro' ) .
                __( 'For Drop Down tag input enter a comma (,) separated list of tags to show in the search pulldown, mark the default selection with parenthesis (). ', 'slp-pro' ) .
                __( 'This is a default setting that can be overriden on each page within the shortcode.', 'slp-pro' ),
                'tag_selections'
            );
            $this->addon->admin->add_checkbox( __( 'Show Select All Option', 'slp-pro' ), __( 'Add an "any" selection on the tag drop down list thus allowing the user to show all locations in the area, not just those matching a selected tag.', 'slp-pro' ), 'tag_show_any' );
            $this->addon->admin->add_checkbox( __( 'Tag Autosubmit', 'slp-pro' ), __( 'Force the form to auto-submit when the tag is selected with a radio button.', 'slp-pro' ), 'tag_autosubmit' );
        }

        /**
         * Experience / Search / Labels
         */
        private function search_labels() {
            $this->addon->admin->current_group = __( 'Search Labels', 'slp-pro' );
            $this->addon->admin->add_sublabel( $this->addon->name );

            $this->addon->admin->add_input(  __('Tags Label', 'slp-pro'), __('Search form label to prefix the tag selector.','slp-pro')  , 'tag_label' , 'text' );
            $this->addon->admin->add_input(
                __('Tag Select All Text', 'slp-pro'),
                __('What should the "any" tag say? ','slp-pro').
                __('The first entry on the search by tag pulldown.','slp-pro'),
                'tag_dropdown_first_entry'
            );
        }

        /**
         * Experience / View Settings
         */
        public function add_view_settings() {
            $this->view_page_layout();
            $this->view_css_manipulation();
        }

        /**
         * Experience / View / Page Layout
         */
        private function view_page_layout() {
            $this->addon->admin->current_group = __( 'Page Layout', 'slp-pro' );
            $this->addon->admin->add_sublabel( $this->addon->name );

            $this->addon->admin->add_textarea(
                __('Locator Layout', 'slp-pro') ,
                __('Enter your custom page layout for the Store Locator Plus page. Set it to blank to reset to the default layout.', 'slp-pro') ,
                'layout'
            );	// layout
        }

        /**
         * Experience / View / CSS Manipulation
         */
        private function view_css_manipulation() {
            $this->addon->admin->current_group = __( 'CSS Manipulation', 'slp-pro' );
            $this->addon->admin->add_sublabel( $this->addon->name );

            $this->addon->admin->add_textarea(
                __( 'Custom CSS' , 'slp-pro' ) ,
                __('Enter your custom CSS, preferably for SLPLUS styling only but it can be used for any page element as this will go in your page header.','slp-pro') ,
                'custom_css'
            );	// custom_css
        }

	}
}