<?php

if ( ! class_exists('SLP_Web_Link_Manager') ) {

	/**
	 * Class SLP_Web_Link_Manager
	 *
	 *
	 * @package StoreLocatorPlus\Web_Link\Manager
	 * @author Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2016 Charleston Software Associates, LLC
	 *
	 * @since 4.6.2
     *
	 * @var    string           urls           important URLs
     * @var    SLP_Web_Link[]   web_links      the index key is the slug for the web link.
     *
	 */
	class SLP_Web_Link_Manager extends SLPlus_BaseClass_Object {
		private $urls;
		private $web_links;

		/**
		 * Build dynamic link.
		 *
		 * @param string    $slug
		 */
		private function build_dynamic_link( $slug ) {
			if ( strpos( $slug , 'docs_for_' ) === 0 ) {
				$this->docs_for( $slug );
				return;
			}
			if ( strpos( $slug , 'icon_for_' ) === 0 ) {
				$this->icon_for( $slug );
				return;
			}
			if ( strpos( $slug , 'shop_for_' ) === 0 ) {
				$this->shop_for( $slug );
				return;
			}

			// Default - assume we are full of slugs... like a zombie... ewww
			$this->web_links[ $slug ] = new SLP_Web_Link( array(
				'slug'      => $slug,
				'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
				'link_text' => $this->slplus->text_manager->get_text_string( array( 'link_text' , $slug ) ),
				'url'       => $this->get_url( $slug ),
			) );
		}

		/**
		 * Build a docs_for_<slug> link to the docs site.
		 *
		 * @param string $slug
		 */
		private function docs_for( $slug ) {
			$feature = substr( $slug , 9 );

			$link_text = $this->slplus->text_manager->get_text_string( array( 'label' , $feature ) );
			if ( empty( $link_text ) ) {
				$link_text =  $this->slplus->text_manager->get_text_string( array( 'admin' , 'this_setting' ));
			}

			$this->web_links[ $slug ] = new SLP_Web_Link( array(
				'slug'      => $slug,
				'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , 'docs_for_' ) ),
				'link_text' => $link_text,
				'url'       => $this->get_url( 'docs_tag' ) . str_replace( '_' , '-' , $feature ),
			) );
		}

		/**
		 * Get the specified web page HTML with embedded hyperlinks.
		 *
		 * docs_for_<slug> calls the docs_for() method
		 * icon_for_<slug> calls the icon_for() method
		 * shop_for_<slug> calls the shop_for() method
		 *
		 * any other web link not specified in the custom list below will link using..
		 *
		 * the "sentence" that matches the text manager 'printf' with the same slug
		 * the "link_text" taht matches the text manager 'link_text' with the same slug
		 * and link to the URL noted in the init_urls() list here with the same slug
		 *
		 *
		 * @param   string    $slug     web page link to fetch.
		 *
		 * @return   SLP_Web_Link
		 */
		public function get( $slug ) {
			if ( empty( $this->web_links[ $slug ] ) ) {
				require_once( SLPLUS_PLUGINDIR . 'include/unit/SLP_Web_Link.php' );

				switch ( $slug ) {

					case 'check_website_for_upgrades':
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => 'Store Locator Plus',
							'url'       => $this->get_url( 'slp' ),
						) );
						break;

					case 'import_provided_by' :
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => $this->slplus->text_manager->get_text_string( array( 'link_text', $slug ) ),
							'url'       => $this->get_url( 'slp_power' ),
						) );
						break;

					case 'import_suggestion':
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => $this->slplus->text_manager->get_text_string( array( 'link_text' , $slug ) ),
							'url'       => $this->get_url( 'power' ),
						) );
						break;

					case 'more_options_suggestion':
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => $this->slplus->text_manager->get_text_string( array( 'link_text' , $slug )  ),
							'url'       => $this->get_url( 'slp' ),
						) );
						break;

					case 'persistent_notice':
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => $this->slplus->text_manager->get_text_string( array( 'admin' , 'survey' ) ),
							'url'       => $this->get_url( 'survey' ),
						) );
						break;

					case 'premier_member_updates':
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => $this->slplus->text_manager->get_text_string( array( 'link_text', $slug  ) ),
							'url'       => $this->get_url( 'slp_premier' ),
						) );
						break;

					case 'premier_network_admin' :
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => $this->slplus->text_manager->get_text_string( array( 'link_text' , $slug ) ),
							'url'       => $this->get_url( 'slp_premier' ),
						) );
						break;

					case 'visit_site_for_addons':
						$this->web_links[ $slug ] = new SLP_Web_Link( array(
							'slug'      => $slug,
							'sentence'  => $this->slplus->text_manager->get_text_string( array( 'printf' , $slug ) ),
							'link_text' => 'Store Locator Plus',
							'url'       => $this->get_url( 'slp' ),
						) );
						break;


					default:
						$this->build_dynamic_link( $slug );
						if ( ! isset( $this->web_links[ $slug ] ) ) {
							return;
						}
						break;

				}
			}

			return $this->web_links[ $slug ];
		}

		/**
		 * Get the specified URL.
		 *
		 * @param string $slug
		 *
		 * @return string
		 */
		public function get_url( $slug ) {
			$this->init_urls();
			return ( ! empty ( $this->urls[ $slug ] ) ? $this->urls[ $slug ] : '' );
		}

		/**
		 * Build a icon_for_<slug> link.
		 *
		 * @param string    $slug
		 */
		private function icon_for( $slug ) {
			$icon_class = substr( $slug , 9 );
			$link_text = "<div class='sprite_48 {$icon_class}'></div>";

			$this->web_links[ $slug ] = new SLP_Web_Link( array(
				'slug'      => $slug,
				'sentence'  => '%s',
				'link_text' => $link_text,
				'url'       => $this->get_url( $icon_class ) ,
			) );
		}

		/**
		 * Load up the URLs array.
		 *
		 * TODO: rework this to accept the slug we are looking for & use switch/case so we only load up the URL we need vs. filling up this much RAM.
		 *
		 */
		private function init_urls() {
			if ( isset( $this->urls ) ) {
				return;
			}

			// Base URLs - these must come first
			$this->urls['csl']                 = 'http://www.cybersprocket.com/';
			$this->urls['google_js_api_docs'] = 'https://developers.google.com/maps/documentation/javascript/';
			$this->urls['myslp']              = 'http://my.storelocatorplus.com/';
			$this->urls['slp']                = $this->slplus->slp_store_url;
			$this->urls['slp_docs']           = $this->slplus->support_url . '/';
			$this->urls['slp_wp_dir']         = 'https://wordpress.org/support/plugin/store-locator-le/';
			$this->urls['survey']             = 'https://locatorlance.typeform.com/to/ybfpXh';
			$this->urls['twitter']            = 'https://twitter.com/locatorplus/';
			$this->urls['youtube']            = 'https://www.youtube.com/channel/UCJIMv63upz-qIaB5EcursyQ';

			// Extended URLs - second level, based on base URLs
			$this->urls['check_pro_version']        = $this->urls['slp' ] . 'support/slp-versions';
			$this->urls['documentation']            = $this->urls['slp_docs' ];
			$this->urls['docs_tag']                 = $this->urls['slp_docs' ] . 'blog/tag/';
			$this->urls['google_browser_key']       = $this->urls['google_js_api_docs' ] . 'get-api-key';
			$this->urls['get_started_google_key']   = $this->urls['slp_docs' ] . 'blog/getting-started/';
			$this->urls['new_addon_versions']       = $this->urls['slp' ] . 'support/slp-versions';
			$this->urls['rate_slp']                 = $this->urls['slp_wp_dir' ] . 'reviews/?filter=5';
			$this->urls['rss']                      = $this->urls['slp' ] . 'feed/';
			$this->urls['slp_contact_us']           = $this->urls['slp' ] . 'mindset/contact-us/';
			$this->urls['slp_docs_category']        = $this->urls['slp_docs' ] . 'blog/category/';
			$this->urls['slp_product_base']         = $this->urls['slp' ] . 'product/';

			// Tertiary URLs - require second level URLs to be set
			$this->urls['slp_experience'] = $this->urls[ 'slp_product_base' ] . 'experience/';
			$this->urls['slp_power']      = $this->urls[ 'slp_product_base' ] . 'power/';
			$this->urls['slp_premier']    = $this->urls[ 'slp_product_base' ] . 'premier/';

			/**
			 * FILTER: slp_urls
			 *
			 * @param string[] urls Array of defined URLs
			 *
			 * @return  string[]        Modified/augmented URL array.
			 */
			$this->urls = apply_filters( 'slp_urls', $this->urls );
		}

		/**
		 * Build a shop_for_<slug> link to the shopping site.
		 *
		 * @param string    $slug
		 */
		private function shop_for( $slug ) {
			$addon_slug = substr( $slug , 9 );

			if ( empty( $addon_slug ) ) {
				error_log( sprintf( '%s::%s shop_for_ web link slug %s generated and empty adddon_slug' , __CLASS__ , __FUNCTION__ , $slug ) );
				return;
			}

			$this->web_links[ $slug ] = new SLP_Web_Link( array(
				'slug'      => $slug,
				'sentence'  => '%s',
				'link_text' => $this->slplus->text_manager->get_text_string( array( 'link_text' , $addon_slug ) ),
				'url'       => $this->get_url( 'slp_product_base' ) . $addon_slug . '/' ,
			) );
		}

	}
}
