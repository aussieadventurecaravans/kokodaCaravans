<?php

if ( ! class_exists( 'SLP_Plugin_Style_Manager' ) ):

	/**
	 * class SLP_Plugin_Style_Manager
	 *
	 * @package StoreLocatorPlus\PluginStyle\Manager
	 * @author Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2012-2016 Charleston Software Associates, LLC	 *
	 *
	 * @var             SLP_Plugin_Style        $plugin_style
	 */
	class SLP_Plugin_Style_Manager extends SLPlus_BaseClass_Object {
	    private $plugin_style;

		/**
		 * Add the theme settings to the admin panel.
		 *
		 * @param   SLP_Settings    $settings
		 */
		public function add_settings( $settings ) {
			$group_params = array( 'plugin' => $this->slplus, 'section_slug' => 'view', 'group_slug' => 'appearance' );

			$this->create_object_plugin_style();
			$theme_list = $this->plugin_style->get_theme_list();

			$settings->add_ItemToGroup(array(
				'group_params'  => $group_params,
				'label'         => __( 'Plugin Style' , 'store-locator-le' ),
				'option_name'   => 'options_nojs',
				'option'       => 'theme'                               ,
				'type'          => 'list'                                       ,
				'custom'        => $theme_list                     ,
			));

			$settings->add_ItemToGroup(array(
				'group_params'  => $group_params,
				'type'        => 'subheader'                                ,
				'description' =>
					__( 'Select a plugin style to change the CSS styling and layout of the slplus shortcode elements. ' , 'store-locator-le' ) .
					__( 'This determines how the locator looks within your page. ' , 'store-locator-le' ) .
					'<span class="new_paragraph">'.
					__( 'Most plugin styles work best with the following add ons installed: ' , 'store-locator-le' ) .
					'<br/>' .
					$this->slplus->text_manager->get_web_link( 'shop_for_experience' )  .
					'<br/>' .
					$this->slplus->text_manager->get_web_link( 'shop_for_premier' ) .
					'</span>' .
					$this->slplus->text_manager->get_web_link( 'docs_for_plugin_style' )
			));

			// Add Style Details Divs
			//
			$settings->add_ItemToGroup(array(
				'group_params'  => $group_params,
				'label'         => '',
				'description'   => $this->plugin_style->setup_ThemeDetails( $theme_list ),
				'setting'       => 'themedesc'  ,
				'type'          => 'subheader'  ,
			));
		}

	    /**
	     * Attach plugin_style object to this->plugin_style.
	     */
	    private function create_object_plugin_style() {
	        if ( ! isset( $this->plugin_style ) ) {
		        require_once( SLPLUS_PLUGINDIR . 'include/unit/SLP_Plugin_Style.php' );
	            $this->plugin_style = new SLP_Plugin_Style();
	        }
	    }

	}

endif;