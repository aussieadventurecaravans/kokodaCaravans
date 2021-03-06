<?php
if (! class_exists('SLP_Helper')) {

	/**
	 * Helper, non-critical methods to make WordPress plugins easier to manage.
	 *
	 * TODO: Separate ADMIN helpers (put in Settings class??)
	 * Ubiquitious (stay here): createstring_DropDownMenu, create_string_wp_setting_error_box
	 * ADMIN: all others
	 *
	 *
	 * @package StoreLocatorPlus\Helper
	 * @author Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2014-2016 Charleston Software Associates, LLC
	 */
	class SLP_Helper extends SLPlus_BaseClass_Object {

		/**
		 * Add a notification to the WP admin pages.
		 *
		 * @param $text
		 */
		function add_wp_admin_notification( $text, $class = 'error' ) {
			if ( empty( $text ) ) { return; }
			add_action( 'admin_notices', create_function( '',  "echo '<div class=\"{$class}\"><p>{$text}</p></div>';" ) );
		}

		/**
		 * Force the checkbox post on or off even if not sent in from form post.
		 *
		 * @param $checkbox_name
         *
         * @used-by SLP_Helper.SaveCheckboxToDB
		 */
		function create_checkbox_post( $checkbox_name ) {
			$_POST[$checkbox_name] = ( isset( $_POST[$checkbox_name] ) && ! empty( $_POST[$checkbox_name] ) ) ? 1 : 0 ;
		}

		/**
		 * Return the icon selector HTML for the icon images in saved markers and default icon directories.
		 *
		 * @param string|null $field_id
		 * @param string|null $image_id
		 * @return string
		 */
		function create_string_icon_selector($field_id = null, $image_id = null) {
			if (($field_id == null) || ($image_id == null)) { return ''; }

			$htmlStr = '';
			$files=array();
			$fqURL=array();

			// If we already got a list of icons and URLS, just use those
			//
			if (
				isset($this->slplus->data['iconselector_files']) &&
				isset($this->slplus->data['iconselector_urls'] )
			) {
				$files = $this->slplus->data['iconselector_files'];
				$fqURL = $this->slplus->data['iconselector_urls'];

				// If not, build the icon info but remember it for later
				// this helps cut down looping directory info twice (time consuming)
				// for things like home and end icon processing.
				//
			} else {

				// Load the file list from our directories
				//
				// using the same array for all allows us to collapse files by
				// same name, last directory in is highest precedence.
				$iconAssets = apply_filters('slp_icon_directories',
					array(
						array('dir'=>SLPLUS_UPLOADDIR.'saved-icons/',
							'url'=>SLPLUS_UPLOADURL.'saved-icons/'
						),
						array('dir'=>SLPLUS_ICONDIR,
							'url'=>SLPLUS_ICONURL
						)
					)
				);
				$fqURLIndex = 0;
				foreach ($iconAssets as $icon) {
					if (is_dir($icon['dir'])) {
						if ($iconDir=opendir($icon['dir'])) {
							$fqURL[] = $icon['url'];
							while ($filename = readdir($iconDir)) {
								if (strpos($filename,'.')===0) { continue; }
								$files[$filename] = $fqURLIndex;
							};
							closedir($iconDir);
							$fqURLIndex++;
						} else {
							$this->slplus->notifications->add_notice(
								9,
								sprintf(
									__('Could not read icon directory %s','store-locator-le'),
									$icon['dir']
								)
							);
						}
					}
				}
				ksort($files);
				$this->slplus->data['iconselector_files'] = $files;
				$this->slplus->data['iconselector_urls']  = $fqURL;
			}

			// Build our icon array now that we have a full file list.
			//
			foreach ($files as $filename => $fqURLIndex) {
				if (
					(preg_match('/\.(png|gif|jpg)/i', $filename) > 0) &&
					(preg_match('/shadow\.(png|gif|jpg)/i', $filename) <= 0)
				) {
					$htmlStr .=
						"<div class='slp_icon_selector_box'>".
						"<img
	                         	 data-filename='$filename'
	                        	 class='slp_icon_selector'
	                             src='".$fqURL[$fqURLIndex].$filename."'
	                             onclick='".
						"document.getElementById(\"".$field_id."\").value=this.src;".
						"document.getElementById(\"".$image_id."\").src=this.src;".
						"'>".
						"</div>"
					;
				}
			}

			// Wrap it in a div
			//
			if ($htmlStr != '') {
				$htmlStr = '<div id="'.$field_id.'_icon_row" class="slp_icon_row">'.$htmlStr.'</div>';

			}


			return $htmlStr;
		}

		/**
		 * Create a WordPress-like settings message error box.
		 *
		 * Uses same class as that for the built-in "settings saved" message.
		 *
		 * @param        $message
		 * @param string $message_detail
		 *
		 * @return string
		 */
		function create_string_wp_setting_error_box( $message, $message_detail = '' ) {
			if ( empty( $message ) && empty( $message_detail ) ) { return ''; }

			if ( ! empty( $message ) ) {
                if ( is_array( $message ) ) { $message = '<pre>' . print_r($message,true) . '</pre>'; }
				$message .= '<br/>';
			}

			return
				'<div id="setting-error-settings_updated" class="updated settings-error">' .
				"<p><strong>{$message}</strong>${message_detail}</p>" .
				'</div>';
		}

		/**
		 * Generate the HTML for a drop down settings interface element.
		 *
         * @deprecated 4.4.24
         *
		 * TODO: Deprecate when removed from ES
		 *
		 * @params mixed[] $params
		 *
		 * @return string HTML
		 */
		function createstring_DropDownDiv( $params ) {
			return
				"<div class='form_entry'>" .
				"<div class='" . SLPLUS_PREFIX . "-input'>" .
				"<label  for='{$params['name']}'>{$params['label']}:</label>" .
				$this->createstring_DropDownMenu( $params ) .
				"</div>" .
				"</div>";
		}

		/**
		 * Create the bulk actions block for the top-of-table navigation.
		 *
		 * $params is a named array:
		 *
		 * The drop down components:
		 *
		 * string  $params['id'] the ID that goes in the select tag, defaults to 'actionType'
		 *
		 * string  $params['name'] the name that goes in the select tag, defaults to 'action'
		 *
		 * string  $params['onchange'] JavaScript to run on select change.
		 *
		 * string  $params['selectedVal'] if the item value matches this param, mark it as selected
		 *
		 * mixed[] $params['items'] the named array of drop down elements
		 *
		 *     $params['items'] is an array of named arrays:
		 *
		 *         string  $params['items'][0]['label'] the label to put in the drop down selection
		 *
		 *         string  $params['items'][0]['value'] the value of the option
		 *
		 *         boolean $params['items'][0]['selected] true of selected
		 *
		 * @param mixed[] $params a named array of the drivers for this method.
		 *
		 * @return string the HTML for the drop down with a button beside it
		 *
		 */
		function createstring_DropDownMenu( $params ) {
			if ( ! isset( $params['items'] ) || ! is_array( $params['items'] ) ) {
				return '';
			}

			if ( ! isset( $params['id'] ) ) {
				$params['id'] = 'actionType';
			}
			if ( ! isset( $params['name'] ) ) {
				$params['name'] = 'action';
			}
			if ( ! isset( $params['selectedVal'] ) ) {
				$params['selectedVal'] = '';
			}
			if ( ! isset( $params['empty_ok'] ) ) {
				$params['empty_ok'] = false;
			}

			$params['disabled'] =
				( isset( $params['disabled'] ) && $params['disabled'] ) ?
					' disabled ' :
					'';

			if ( ! isset( $params['onchange'] ) || empty( $params['onchange'] ) )  {
				$params['onchange'] = '';
			} else {
				if ( stripos( $params['onchange'] , 'onchange=' ) === false ) {
					$params['onchange'] = " onChange=\"{$params['onchange']}\" ";
				}
			}

			// Drop down menu
			//
			$dropdownHTML = '';
			foreach ( $params['items'] as $item ) {
				if ( ! isset( $item['label'] ) ) {
					continue;
				}
				if ( ! $params['empty_ok'] && empty( $item['label'] ) ) {
					continue;
				}

				if ( ! isset( $item['value'] ) ) {
					$item['value'] = $item['label'];
				}
				if ( $item['value'] === $params['selectedVal'] ) {
					$item['selected'] = true;
				}
				$selected = ( isset( $item['selected'] ) && $item['selected'] ) ? 'selected="selected" ' : '';
				$dropdownHTML .= "<option {$selected} value='{$item['value']}'>{$item['label']}</option>";
			}

			return
				'<select ' .
				"id='{$params['id']}' " .
				"name='{$params['name']}' " .
				$params['disabled'] .
				$params['onchange'] .
				'>' .
				$dropdownHTML .
				'</select>';
		}

		/**
		 * Create the bulk actions block for the top-of-table navigation.
		 *
		 * $params is a named array:
		 *
		 * The drop down components:
		 *
		 * string  $params['id'] the ID that goes in the select tag, defaults to 'actionType'
		 * string  $params['name'] the name that goes in the select tag, defaults to 'action'
		 * string  $params['onchange'] JavaScript to run on select change.
		 * mixed[] $params['items'] the named array of drop down elements
		 *     $params['items'] is an array of named arrays:
		 *         string  $params['items'][0]['label'] the label to put in the drop down selection
		 *         string  $params['items'][0]['value'] the value of the option
		 *         boolean $params['items'][0]['selected] true of selected
		 *
		 * string  $params['buttonLabel'] the text that goes on the accompanying button, defaults to 'Apply'
		 * string  $params['onclick'] JavaScript to run on button click.
		 *
		 * @param mixed[] $params a named array of the drivers for this method.
		 *
		 * @return string the HTML for the drop down with a button beside it
		 *
		 */
		function createstring_DropDownMenuWithButton( $params ) {
			if ( ! isset( $params['items'] ) || ! is_array( $params['items'] ) ) {
				return '';
			}

			if ( ! isset( $params['id'] ) ) {
				$params['id'] = 'actionType';
			}
			if ( ! isset( $params['name'] ) ) {
				$params['name'] = 'action';
			}
			if ( ! isset( $params['buttonlabel'] ) ) {
				$params['buttonlabel'] = __( 'Apply', 'store-locator-le' );
			}
			if ( ! isset( $params['onchange'] ) ) {
				$params['onchange'] = '';
			}
			if ( ! isset( $params['onclick'] ) ) {
				$params['onclick'] = '';
			}

			// Drop down menu
			//
			$dropdownHTML = $this->createstring_DropDownMenu( $params );

			// Button
			//
			$submitButton =
				'<input id="doaction_' . $params['id'] . '" class="button action" type="submit" ' .
				'value="' . $params['buttonlabel'] . '" name="" ' .
				( ! empty( $params['onclick'] ) ? 'onClick="' . $params['onclick'] . '"' : '' ) .
				'/>';

			// Render The Div
			//
			return
				'<div class="alignleft actions">' .
				$dropdownHTML .
				$submitButton .
				'</div>';
		}

		/**
		 * Create a help div next to a settings entry.
		 *
		 * @param string $divname - name of the div
		 * @param string $msg     - the message to dislpay
		 *
		 * @return string - the HTML
         *
         * TODO: Remove when SLP SLPlus_Settings_Item.display() call to this is gone and ES is updated to 4.5
		 */
		function CreateHelpDiv( $divname, $msg ) {
			$jqDivName    = str_replace( ']', '\\\\]', str_replace( '[', '\\\\[', $divname ) );
			$moreInfoText = esc_html( $msg );

			return
				 "<a class='dashicons dashicons-editor-help slp-no-box-shadow' " .
				 "onclick=\"jQuery('div#" . SLPLUS_PREFIX . "-help{$jqDivName}').toggle('slow');\" " .
				 "href=\"javascript:;\" " .
				 "alt='{$moreInfoText}' title='{$moreInfoText}'" .
				 '>' .
				 "</a>" .
				"<div id='" . SLPLUS_PREFIX . "-help{$divname}' class='input_note wpcsl_helptext''>" .
				$msg .
				"</div>";

		}

		/**
		 * Generate the HTML for a sub-heading label in a settings panel.
		 *
		 * @param string $label
		 * @param boolean $use_h3  - use h3 tag like an add_ItemToGroup subheader.
		 *
		 * @return string HTML
		 */
		function create_SubheadingLabel( $label , $use_h3 = false ) {
			if ( $use_h3 ) {
				$output = "<h3>$label</h3>";
			} else {
				$output = "<p class='slp_admin_info'><strong>$label</strong></p>";
			}
			return $output;
		}

		/**
		 * Generate the HTML for a checkbox settings interface element.
         *
         * @deprecated 4.4.24
         *
         * TODO: Deprecate when dropped from EM/ES
         *
		 *
		 * @param string  $boxname     - the name of the checkbox (db option name)
		 * @param string  $label       - default '', the label to go in front of the checkbox
		 * @param string  $msg         - default '', the help message
		 * @param string  $prefix      - defaults to SLPLUS_PREFIX, can be ''
		 * @param boolean $disabled    - defaults to false
		 * @param mixed   $default
		 * @param mixed   $checkOption - if present, test this variable == 1 to mark as checked otherwise get the boxname option.
		 *
		 * @return string
		 */
		function CreateCheckboxDiv( $boxname, $label = '', $msg = '', $prefix = null, $disabled = false, $default = 0, $checkOption = null ) {
            if ( $prefix === null ) {
                $prefix = SLPLUS_PREFIX;
            }
            $whichbox = $prefix . $boxname;
            if ( $checkOption === null ) {
                $checkOption = $this->slplus->option_manager->get_wp_option( $whichbox, $default );
            }

			return
				"<div class='form_entry'>" .
				"<div class='" . SLPLUS_PREFIX . "-input'>" .
				"<label  for='$whichbox' " .
				( $disabled ? "class='disabled '" : ' ' ) .
				">$label:</label>" .
				"<input name='$whichbox' value='1' " .
				"type='checkbox' " .
				( ( $checkOption == 1 ) ? ' checked ' : ' ' ) .
				( $disabled ? "disabled='disabled'" : ' ' ) .
				'>' .
				"</div>" .
				"</div>";
		}

		/**************************************
		 ** function: SaveCheckboxToDB
		 **
		 ** Update the checkbox setting in the database.
		 **
		 ** Parameters:
		 **  $boxname (string, required) - the name of the checkbox (db option name)
		 **  $prefix (string, optional) - defaults to SLPLUS_PREFIX, can be ''
		 **/
		function SaveCheckboxToDB($boxname,$prefix = null, $separator='-') {
			if ($prefix === null) { $prefix = $this->slplus->prefix; }
			$whichbox = $prefix.$separator.$boxname;
			$this->create_checkbox_post( $whichbox );
			$this->SavePostToOptionsTable($whichbox,0);
		}

		/**
		 * @param string $optionname
		 * @param mixed  $default
		 * @param array  $cboptions
         *
         * TODO: Move this to SLP admin.experience once ES removes this call.
		 */
		function SavePostToOptionsTable( $optionname, $default = null, $cboptions = null ) {
			if ( $default != null ) {
				if ( ! isset( $_POST[ $optionname ] ) ) {
					$_POST[ $optionname ] = $default;
				}
			}

			// Save the option
			//
			if ( isset( $_POST[ $optionname ] ) ) {
				$optionValue = $_POST[ $optionname ];

				// Checkbox Pre-processor
				//
				if ( $cboptions !== null ) {
					foreach ( $cboptions as $cbname ) {
						if ( ! isset( $optionValue[ $cbname ] ) ) {
							$optionValue[ $cbname ] = '0';
						}
					}
				}

				$optionValue = stripslashes_deep( $optionValue );
				$this->slplus->option_manager->update_wp_option( $optionname, $optionValue );
			}
		}

		/**
		 * Check if an item exists out there in the "ether".
		 *
		 * @param string $url - preferably a fully qualified URL
		 *
		 * @return boolean - true if it is out there somewhere
		 */
		function webItemExists( $url ) {
			if ( ( $url == '' ) || ( $url === null ) ) {
				return false;
			}
			$response              = wp_remote_head( $url, array( 'timeout' => 5 ) );
			$accepted_status_codes = array( 200, 301, 302 );
			if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
				return true;
			}

			return false;
		}

		//------------------------------------------------------------------------
		// DEPRECATED
		//------------------------------------------------------------------------

		/**
		 * Do not use, deprecated.
		 *
		 * @deprecated 4.0
		 */
		function load_checkboxes_into_options( $a = null, $b = null, $c = null, $d = null, $e = null, $f = null ) {
			$this->create_string_wp_setting_error_box( $this->slplus->createstring_Deprecated( __FUNCTION__ ) );
		}

		/**
		 * Do not use, deprecated.
		 *
		 * @deprecated 4.0
		 */
		function create_SimpleMessage( $a = null, $b = null, $c = null, $d = null, $e = null, $f = null ) {
			$this->create_string_wp_setting_error_box( $this->slplus->createstring_Deprecated( __FUNCTION__ ) );
		}

		/**
		 * Do not use, deprecated.
		 *
		 * @deprecated 4.0
		 */
		function getData( $a = null, $b = null, $c = null, $d = null, $e = null, $f = null ) {
			$this->create_string_wp_setting_error_box( $this->slplus->createstring_Deprecated( __FUNCTION__ ) );
		}

		/**
		 * Do not use, deprecated.
		 *
		 * @deprecated 4.2.63
		 */
		function loadPluginData( $a = null, $b = null, $c = null, $d = null, $e = null, $f = null ) {
			$this->create_string_wp_setting_error_box( $this->slplus->createstring_Deprecated( __FUNCTION__ ) );
		}
	}
}