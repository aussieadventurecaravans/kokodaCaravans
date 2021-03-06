<?php

if ( ! class_exists( 'SLP_Option_Manager' ) ):

	require_once( SLPLUS_PLUGINDIR . 'include/base_class.object.php' );

	/**
	 * Class SLP_Option_Manager
	 *
	 * Generic option manager to interface with delette/get/update wp_options for base plugin and add ons.
	 *
	 * @package   StoreLocatorPlus\Options\Manager
	 * @author    Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2016 Charleston Software Associates, LLC
	 *
	 * @since     4.3.20
	 *
	 * @property        string       $option_slug        Used by the get_wp_option('js') call.
	 * @property        string       $option_nojs_slug   Used by the get_wp_option('nojs') call.
	 *
	 */
	class SLP_Option_Manager extends SLPlus_BaseClass_Object {
		public  $option_nojs_slug;
		public  $option_slug;

		/**
		 * Delete the Store Locator Plus options from the WordPress options table.
		 *
		 * Default option name is csl-slplus-options per $this->option_slug.
		 *
		 * @param string $which_option 'default' , 'js' , 'nojs' or the option name.
		 *
		 * @return mixed|void
		 */
		public function delete_wp_option( $which_option = 'default' ) {
			return delete_option( $this->set_the_slug( $which_option, 'delete' ) );
		}

		/**
		 * Fetch the Store Locator Plus options from the WordPress options table.
		 *
		 * Default option name is csl-slplus-options per $this->option_slug.
		 *
		 * @param string $which_option 'default' , 'js' , 'nojs' or the option name.
		 * @param mixed  $default
		 *
		 * @return mixed|void
		 */
		public function get_wp_option( $which_option = 'default', $default = false ) {
			return get_option( $this->set_the_slug( $which_option, 'get' ), $default );
		}

		/**
		 * Things we do at startup.
		 */
		public function initialize() {

			// Option slug is set for add ons but not the base plugin.
			//
			if ( ! isset( $this->option_slug ) ) {
				$this->option_slug      = SLPLUS_PREFIX . '-options';
				$this->option_nojs_slug = SLPLUS_PREFIX . '-options_nojs';
			}
		}

		/**
		 * Set the option slug.
		 *
		 * @param string $which_option 'js', 'nojs', or the option name
		 * @param string $mode         'get' or 'update'
		 *
		 * @return mixed|null|void
		 */
		private function set_the_slug( $which_option, $mode ) {
			switch ( $which_option ) {
				case 'default':
				case 'js':
					$slug_to_fetch = $this->option_slug;
					break;
				case 'nojs':
					$slug_to_fetch = $this->option_nojs_slug;
					break;
				default:
					$slug_to_fetch = $which_option;
					break;
			}

			/**
			 * FILTER: slp_option_slug
			 *
			 * @param   string $slug_to_fetch the name of the wp_options table key to fetch with get_option().
			 * @param   string $get_or_update 'get' or 'update
			 *
			 * @return  string      a modified slug
			 */
			return apply_filters( 'slp_option_slug', $slug_to_fetch, $mode );

		}

		/**
		 * Update the WordPress option.
		 *
		 * @param string $which_option  'default' , 'js' , 'nojs' or the option name.
		 * @param mixed  $option_values values to be stored
		 *
		 * @return mixed
		 */
		public function update_wp_option( $which_option = 'default', $option_values = null ) {
			if ( is_null( $option_values ) ) {
				switch ( $which_option ) {
					case 'default':
					case 'js':
						$option_values = $this->slplus->options;
						break;
					case 'nojs':
						$option_values = $this->slplus->options_nojs;
						break;
					default:
						break;
				}
			}

			$option_name = $this->set_the_slug( $which_option, 'update' );

			return update_option( $option_name, $option_values );
		}
	}

endif;