<?php

require_once( SLPLUS_PLUGINDIR . 'include/base_class.object.php' );

if ( ! class_exists( 'SLP_Settings' ) ) {

    /**
     * The UI and management interface for a page full of settings for a WordPress admin page.
     *
     * @package   StoreLocatorPlus\Settings
     * @author    Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2012-2016 Charleston Software Associates, LLC
     *
     * @property        string                    $form_action    what action to take with the form.
     * @property        string                    $form_enctype   form encryption type  default: '' , often 'multipart/form-data'
     * @property        string                    $form_name
     * @property        string                    $name
     * @property        string                    $save_text      optional: The text for the save button. (default: blank, don't show save button)
     * @property        SLPlus_Settings_Section[] $sections
     * @var             boolean                   $show_help_sidebar
     *
     */
    class SLP_Settings extends SLPlus_BaseClass_Object {
        public $form_action = 'options.php';
        public $form_enctype = '';
        public $form_name = '';
        public $name = '';
        public $save_text = '';
        public $sections;
	    public $show_help_sidebar = true;

        /**
         * Set some pre-load defaults.
         *
         * @param array $options
         */
        function __construct( $options = array() ) {
            $this->save_text = __( 'Save Changes', 'store-locator-le' );
            $this->name      = __( 'Store Locator Plus', 'store-locator-le' );
            parent::__construct( $options );

        }

        /**
         * Create a settings page panel.
         *
         * Does not render the panel, it simply creates the container to add stuff to for later rendering.
         *
         * @param array $params named array of the section properties, name is required.
         */
        function add_section( $params ) {
            if ( ! isset( $params['name'] ) ) {
                if ( ! isset( $params['slug'] ) ) {
                    return;
                } else {
                    $params['name'] = $this->slplus->text_manager->get_text_string( array( 'settings_section' , $params['slug'] ) );
                }
            }

            $params['slug'] = isset( $params['slug'] ) ? $params['slug'] : $this->set_section_slug( $params['name'] );

            if ( empty( $params[ 'slug' ] ) ) {
                return;
            }

            if ( ! $this->has_section( $params[ 'slug' ] ) ) {
                $this->sections[ $params['slug'] ] = new SLPlus_Settings_Section( $params );
            }

            $this->add_smart_options_to_section( $params['slug'] );
        }

	    /**
	     * Add smart option groups and settings to this section.
	     *
	     * @param string $section   the section slug
	     */
        private function add_smart_options_to_section( $section ) {
        	if ( empty( $this->slplus->smart_options->page_layout[ $this->slplus->current_admin_page ] ) ) {
        		return;
	        }
	        if ( empty( $this->slplus->smart_options->page_layout[ $this->slplus->current_admin_page ][ $section ] ) ) {
		        return;
	        }

	        $group_params['section_slug'] = $section;
	        $group_params['plugin']       = $this->slplus;

	        foreach ( $this->slplus->smart_options->page_layout[ $this->slplus->current_admin_page ][ $section ] as $group => $properties ) {
		        $group_params['group_slug']   = $group;
		        $this->add_group( $group_params );

		        foreach ($properties as $property ) {
		        	$this->add_ItemToGroup( $group_params , $property );
		        }
	        }

        }

        /**
         * Add a group to the specified section.
         *
         * Params requires:
         *  'section_slug'  => slug for a section to add the group to
         *  'group_slug'    => slug for this group
         *
         * Suggested:
         *  'group' || 'header' => the text to name the group
         *
         * @param $params
         */
        function add_group( $params ) {
            if ( empty( $params[ 'section_slug'] ) || ! $this->has_section( $params['section_slug'] ) ) {
                return;
            }
            if ( ! isset( $params['group_slug'] ) ) {
                return;
            }
            if ( ! isset( $params['header'] ) ) {
                $params['header'] = $this->slplus->text_manager->get_text_string( array( 'settings_group' , $params['group_slug'] ) );
            }

            $this->sections[ $params['section_slug'] ]->add_group( $params );
        }

	    /**
	     * Add the help sidebar.
	     *
	     * @return string
	     */
	    private function add_help_section() {
	    	if ( ! $this->show_help_sidebar ) {
	    		return '';
		    }

		    $this->enqueue_help_script();

		    return
			    '<section class="dashboard-aside-secondary">' .
				    '<h3 class="panel-content aside-heading">' . $this->slplus->text_manager->get_text_string( array( 'admin' , 'help_header' ) ) . '</h3>' .
				    '<div class="panel-content">' .
					    '<h4 class="description-heading">' . $this->slplus->text_manager->get_text_string( array( 'admin' , 'help_subheader' ) ) . '</h4>' .
					    '<div class="settings-description"></div>' .
				    '</div>' .
			    '</section>';
	    }

        /**
         * Same as add_item but uses named params.
         *
         * 'type' => textarea, text, checkbox, dropdown, slider, list, submit_button, ..custom..
         *
         * NOTE: If use_prefix is false the automatic option saving in SLP 4.2 add-on framework will be disabled.
         * This can be useful for admin settings you do not want saved/restored between sessions.
         * It can suck if you do want that to happen though and will likely find this comment after spending
         * the past 30 minutes tearing your hair out wondering WTF is going on.
         *
         *
         *
         * @param array $params optional parameters
         * @param mixed $option if set this is the name of the option to add
         *
         * @var 'section'   => string   text for section heading to put the setting in
         * @var 'label'     => string   text that precedes the input
         * @var 'setting'   => string   name of the setting (input ID)
         * @var 'type'      => string   type of interface element ('checkbox'|'custom'|'details'|'dropdown'|'sidelabel'|'subheader'|'submit'|'text'|'textarea')
         * @var 'show_label'=> boolean  set to true to show the label (default: true)
         * @var 'custom'        => string   the custom HTML output to render
         *      'section_slug'  => string   the section slug, if set uses SLP 4.5 defaults.
         */
        function add_ItemToGroup( $params , $option = null ) {

	        // Smart Option - do not add
	        //
	        if ( ! is_null( $option ) && $this->slplus->smart_options->exists( $option ) && ! $this->slplus->smart_options->$option->add_to_settings_tab ) {
		        return;
	        }

        	// Handle positional parameter passing.
	        //
        	if ( empty( $params['group_params'] ) && ! is_null( $option ) ) {
		        $params['group_params'] = $params;
		        $params['option'] = $option;
	        }

            // Use new SLP 4.4.28+ Defaults
            if (
                isset( $params['group_params'] ) ||
                isset( $params['group_slug'] ) ||
                isset( $params['section_slug'] ) ||
                isset( $params['plugin'] ) ||
                isset( $params['option'] )
            ) {
                $new_defaults = true;

                if ( isset( $params['group_params'] ) ) {
                    if ( isset( $params['group_params']['section_slug'] ) ) {
                        $params['section_slug'] = $params['group_params']['section_slug'];

                        if ( ! $this->has_section( $params[ 'section_slug' ] ) ) {
                          $this->add_section( array( 'slug' =>  $params['section_slug'] )  );
                        }
                    }
                    if ( isset( $params['group_params']['group_slug'] ) ) {
                        $params['group_slug'] = $params['group_params']['group_slug'];
                    }
                    if ( isset( $params['group_params']['plugin'] ) ) {
                        $params['plugin'] = $params['group_params']['plugin'];
                    }

                    if ( ! isset( $params['header'] ) ) {
                        if ( isset( $params['group_params']['header'] ) ) {
                            $params['header'] = $params['group_params']['header'];
                        }  else {
                            if ( isset( $params['group_slug'] ) ) {
                                $params['header'] = $this->slplus->text_manager->get_text_string( array( 'settings_group_header' , $params['group_slug'] ) );
                            }
                        }
                    }
                }

            } else {
                $new_defaults = false;
            }

            if ( ! isset( $params['section_slug'] ) ) {
                $params['section']      = isset( $params['section'] ) ? $params['section'] : __( 'Settings', 'store-locator-le' );
                $params['section_slug'] = $this->set_section_slug( $params['section'] );
            }

            if ( ! $this->has_section( $params['section_slug'] ) ) {
                return;
            }

            $section_slug = $params['section_slug'];
            unset( $params['section_slug'] );

            $params = $this->set_item_params( $new_defaults, $params );

            $this->sections[ $section_slug ]->add_item( $params );
        }

	    /**
	     * Enqueue help text manager script.
	     */
        private function enqueue_help_script() {
	        if ( file_exists( SLPLUS_PLUGINDIR . 'js/admin-settings-help.js' ) ) {
		        wp_enqueue_script( 'slp_admin_settings-help', SLPLUS_PLUGINURL . '/js/admin-settings-help.js', array() );
	        }
        }

	    /**
	     * Return true if the section identified by the slug exists.
	     * @param string $slug
	     *
	     * @return bool
	     */
	    public function has_section ($slug ) {
		    return ! empty( $this->sections[ $slug ] );
	    }

        /**
         * Put the header div on the top of every settings page.
         */
        private function render_settings_header() {
        	?>
	        <div class="dashboard-header-height"><!--empty--></div>
                <header id="dashboard-header" class="dashboard-header">
                    <section class="dashboard-navigation">
	                    <div class="panel-content">
	                        <div class="searchform">
	                            <?php echo $this->render_settings_header_searchform(); ?>
	                        </div>
	                        <div class="user-area">
	                            <?php echo $this->render_settings_header_userarea(); ?>
	                        </div>
	                    </div>
                    </section>
                </header>
	        <?php
        }

        /**
         *  Render the settings area search form.
         */
        private function render_settings_header_searchform() {
            return '';
        }

        /**
         *  Render the settings area user area.
         */
        private function render_settings_header_userarea() {
            $documentation_label = $this->slplus->text_manager->get_text_string( array( 'admin', 'Documentation' ) );
            $html                =
                '<div class="user-support">' .
                sprintf( "<a href='%s' target='store_locator_plus' title='%s'>" , $this->slplus->text_manager->get_url( 'slp_docs' ) , $documentation_label ) .
                '<span class="dashicons dashicons-sos"></span>' .
                $documentation_label .
                '</a>' .
                '</div>';

            return $html;
        }

        /**
         * Create the HTML for the plugin settings page on the admin panel.
         */
        function render_settings_page() {

            $selectedNav = isset( $_REQUEST['selected_nav_element'] ) ? $_REQUEST['selected_nav_element'] : '';

            print
                "<div class='dashboard-wrapper'>" .
                $this->render_settings_header() .
                "<div class='dashboard-main'>" .
                "<section class='dashboard-content'>" .
                "<div id='wpcsl_container' class='settings_page page_{$this->slplus->current_admin_page}'>" .
                "<form method='post' " .
                "action='{$this->form_action}' " .
                ( ( $this->form_name !== '' ) ? "id='{$this->form_name}' " : '' ) .
                ( ( $this->form_name !== '' ) ? "name='{$this->form_name}' " : '' ) .
                ( ( $this->form_enctype !== '' ) ? "enctype='{$this->form_enctype}' " : '' ) .
                'class ="slplus_settings_form" ' .
                ">" .
                "<input type='hidden' " .
                "id='selected_nav_element' " .
                "name='selected_nav_element' " .
                "value='{$selectedNav}' " .
                "/>";

            settings_fields( SLPLUS_PREFIX . '-settings' );

            /**
             * Render all top menus first.
             * @var SLPlus_Settings_Section $section
             */
            foreach ( $this->sections as $section ) {
                if ( isset( $section->is_topmenu ) && ( $section->is_topmenu ) ) {
                    $section->display();
                }
            }

            // Main area under tabs
            //
            print '<div id="main" class="dashboard-content-inner">';

            // Menu Area
            //
            $selectedNav = isset( $_REQUEST['selected_nav_element'] ) ?
                $_REQUEST['selected_nav_element'] :
                '';
            $firstOne    = true;
	        ?>
                                <div id="wpcsl-nav" class="sub_navigation" style="display: block;">
                                    <ul>
			<?php
            foreach ( $this->sections as $section ) {
                if ( $section->auto ) {
                    $friendlyName = strtolower( strtr( $section->name, ' ', '_' ) );
                    $friendlyDiv  = ( isset( $section->div_id ) ? $section->div_id : $friendlyName );
                    $firstClass   = (
                            ( "#wpcsl-option-{$friendlyDiv}" == $selectedNav ) ||
                            ( $firstOne && ( $selectedNav == '' ) )
                        ) ?
                        ' first current open' :
                        '';
                    $firstOne     = false;

                    $link_id = "wpcsl-option-{$friendlyDiv}";
                    print "<li class='top-level general {$firstClass}'>" .
                            sprintf ( "<a id='%s_sidemenu' class='subtab_link' data-slug='%s' href='#%s' title='%s' >%s</a>" , $link_id, $friendlyDiv, $link_id, $section->name, $section->name ) .
                          '</li>';
                }
            }

            echo $this->save_button();

            ?>
                                    </ul>
                                </div> <!-- wpcsl-nav -->
	                            <div id="content" class="content js settings-content">
			<?php



            // Draw each settings section as defined in the plugin config file
            //
            $firstClass = true;
            foreach ( $this->sections as $section ) {
                if ( $section->auto ) {
                    if ( ! empty( $firstClass ) ) {
                        $section->first = true;
                        $firstClass     = false;
                    }
                    $section->display();
                }
            }

            ?>
	                            </div> <!-- settings-content -->
	                        </div>
                        </form>
	                </div> <!-- WPCSL Container SLP settings -->
	            </section> <!-- dashboard content SLP settings -->
                <?php echo $this->add_help_section(); ?>
	            </div> <!-- dashboard-main SLP settings -->
	        </div> <!-- dashboard-wrapper SLP settings -->
            <?php
        }

        /**
         * Create the save button text.
         *
         * Set save_text to '' to prevent the save button.
         *
         * @return string
         */
        private function save_button() {
            if ( empty( $this->save_text ) ) {
                return '';
            }

	        return "<li class='top-level general save-button'>" .
	               '<div class="navsave">' .
	               sprintf( '<input type="submit" class="button-primary" value="%s" />', $this->save_text ) .
	               '</div>' .
	              '</li>';
        }

        /**
         * Set Add Item To Group Params
         *
         * @param boolean $new_defaults If true use the new-style SLP 4.5 defaults
         * @param array   $params
         *
         * @return array
         */
        private function set_item_params( $new_defaults, $params ) {
        	if ( ! empty ( $params[ 'option' ] ) ) {
		        if ( property_exists( $this->slplus->smart_options , $params[ 'option' ] ) ) {
			        return $this->slplus->smart_options->get_setting_params( $params );
		        }
	        }

            if ( $new_defaults ) {
                return $this->set_item_params_new( $params );
            } else {
                return $this->set_item_params_old( $params );
            }
        }

        /**
         * Set Add Item To Group Params to new defaults.
         *
         * use_prefix = false
         * show_label = true
         * type       = text
         * name       = params['setting']
         *
         * If 'plugin' param is set also use 'options' param to set the name and value for the setting.
         *
         * @param array $params
         *
         * @return array
         */
        private function set_item_params_new( $params ) {
            if ( isset( $params['plugin'] ) ) {

                if ( isset( $params['plugin'] ) && isset( $params['option'] ) ) {
                    $params['option_name'] = isset( $params['option_name'] ) ? $params['option_name'] : 'options';
                    $params['value']       = isset( $params['value'] ) ? $params['value'] : $this->get_value_for_setting( $params['plugin'], $params['option_name'], $params['option'] );
                    $params['selectedVal'] = $params['value'];
                    $params['setting']     = $this->get_option_setting( $params );
	                $params['name']        = isset( $params['name'] ) ? $params['name'] : $params['setting'];
                } else {
                    $params['value']   = isset( $params['value'] ) ? $params['value'] : '';
	                if ( isset( $params['setting'] ) ) {
		                $params['name'] = isset( $params['name'] ) ? $params['name'] : $params['setting'];
	                }
                }

                unset( $params['option'] );
                unset( $params['plugin'] );
            }

            $params['use_prefix'] = false;
            $params['show_label'] = isset( $params['show_label'] ) ? $params['show_label'] : true;
            $params['type']       = isset( $params['type'] ) ? $params['type'] : 'text';

            return $params;
        }

        /**
         * Get the option setting (name)
         *
         * @param array $params
         *
         * @return string
         */
        private function get_option_setting( $params ) {

            // Add On
            if ( isset( $params['plugin']->option_name ) ) {
                return $params['plugin']->option_name . '[' . $params['option'] . ']';

                // Base Plugin
            } elseif ( is_a( $params['plugin'], 'SLPlus' ) && isset( $params['option_name'] ) ) {
            	if ( $params['option_name'] === 'smart_option' ) {
            		return $this->slplus->smart_options->get_option_name( $params['option'] );
	            }

                return $params['option_name'] . '[' . $params['option'] . ']';

                // Explicit
            } else {
                return $params['option'];

            }
        }

        /**
         * Get the value for a specific add-on option.  If empty use add-on option_defaults.   If still empty use slplus defaults.
         *
         * @param   mixed   $plugin      An instantiated SLP Plugin object.
         * @param   string  $option_name Name of the options property to fetch from (default: 'options')
         * @param    string $setting     The key name for the setting to retrieve.
         *
         * @return    mixed                    The value of the add-on options[<setting>], add-on option_defaults[<setting>], or slplus defaults[<setting>]
         */
        private function get_value_for_setting( $plugin, $option_name, $setting ) {
        	if ( $option_name === 'smart_option' ) {
        		return $this->slplus->smart_options->$setting->value;
	        }

            // Default: add-on options value
            //
            if ( ! empty( $plugin->{$option_name}[ $setting ] ) ) {
	            $value = $plugin->{$option_name}[ $setting ];

            } else {

                // First Alternative: add-on option_defaults value.
                //
                if ( isset( $plugin->option_defaults ) && isset( $plugin->option_defaults[ $setting ] ) ) {
                    $value = $plugin->option_defaults[ $setting ];
                }

                // Second Alternative: slplus defaults value.
                //
                if ( isset( $this->slplus->defaults[ $setting ] ) ) {
                    $value = $this->slplus->defaults[ $setting ];
                } else {
	                $value = '';
                }
            }

            return $value;
        }

        /**
         * Set Add Item To Group Params to new defaults.
         *
         * @param array $params
         *
         * @return array
         */
        private function set_item_params_old( $params ) {
            if ( ! isset( $params['name'] ) && isset( $params['setting'] ) ) {

                // use_prefix is on by default
                //
                if ( ! isset( $params['use_prefix'] ) ) {
                    $params['use_prefix'] = true;
                }

                // Using a prefix? Craft the name with that attached...
                //
                if ( $params['use_prefix'] ) {

                    // If we have a prefix, set the separator to '-' by default
                    //
                    if ( ! isset( $params['separator'] ) ) {
                        $params['separator'] = '-';
                    }

                    $params['name'] = SLPLUS_PREFIX . $params['separator'] . $params['setting'];

                    // No prefix?  Use the name without one.
                    //
                } else {
                    $params['name'] = $params['setting'];
                }
            }

            if ( ! isset( $params['show_label'] ) ) {
                $params['show_label'] = true;
            }

            $defaultSettingName = '';
            if (
                ( $params['show_label'] && ! isset( $params['label'] ) ) ||
                ( ! isset( $params['setting'] ) )
            ) {
                $defaultSettingName = wp_generate_password( 8, false );
            }

            if ( $params['show_label'] && ! isset( $params['label'] ) ) {
                $params['label'] = __( 'Setting ', 'store-locator-le' ) . $defaultSettingName;
            }

            if ( ! isset( $params['setting'] ) ) {
                $params['setting'] = $defaultSettingName;
            }

            if ( ! isset( $params['type'] ) ) {
                $params['type'] = 'text';
            }
            if ( ! isset( $params['show_label'] ) ) {
                $params['show_label'] = true;
            }

            return $params;
        }

        /**
         * Set section slug by name if not provided.
         *
         * @param $name
         *
         * @return string
         */
        private function set_section_slug( $name ) {
            return strtolower( str_replace( ' ', '_', $name ) );
        }
    }

}

if ( ! class_exists( 'SLPlus_Settings_Section' ) ) {

    /**
     * Sections are collections of groups.
     *
     * @package   SLPlus\Settings\Section
     * @author    Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2013 - 2016 Charleston Software Associates, LLC
     *
     * @property boolean                 $auto
     * @property string                  $closing_html
     * @property string                  $description
     * @property string                  $div_id
     * @property string                  $first          True if the first rendered section on the panel.
     * @property SLPlus_Settings_Group[] $groups
     * @property boolean                 $innerdiv
     * @property boolean                 $is_topmenu
     * @property string                  $name
     * @property string                  $opening_html
     * @property string                  $slug           The normalized section slug.
     */
    class SLPlus_Settings_Section extends SLPlus_BaseClass_Object {
        public $auto = true;
        public $closing_html = '';
        private $current_div_group = '';
        public $description = '';
        public $div_id;
        public $first = false;
        public $groups;
        public $group_slug;
        public $innerdiv = true;
        public $is_topmenu = false;
        public $name;
        public $opening_html = '';
        public $slug;

        /**
         * Add an item to a section.
         *
         * @param array $params
         */
        function add_item( $params ) {
            if ( empty( $params['group'] ) ) {
                $params['group'] = 'Settings';
            }
            $params['group_slug'] = isset( $params['group_slug'] ) ? $params['group_slug'] : strtolower( str_replace( ' ', '_', $params['group'] ) );

            $this->add_group( $params );

            $group_slug = $params['group_slug'];
            unset( $params['group_slug'] );

            $this->groups[ $group_slug ]->add_item( $params );
        }

        /**
         * Add a group to the section.
         *
         * @param   array $params
         */
        function add_group( $params ) {
            if ( ! isset( $this->groups[ $params['group_slug'] ] ) ) {
                $params['slug']   = $params['group_slug'];
                $params['header'] = isset( $params['header'] ) ? $params['header'] : ( isset( $params['group'] ) ? $params['group'] : '' );
                $params['intro']  = isset( $params['intro'] ) ? $params['intro'] : ( isset( $this->description ) ? $this->description : '' );

                $this->groups[ $params['group_slug'] ] = new SLPlus_Settings_Group( $params );

                $this->description = '';
            }
        }

        /**
         * Render a section panel.
         *
         * Panels are rendered in the order they are put in the stack, FIFO.
         */
        function display() {
            $this->header();
            if ( isset( $this->groups ) ) {
                foreach ( $this->groups as $group ) {
                    if ( ! empty( $group->div_group ) && ( $group->div_group != $this->current_div_group ) ) {
                        if ( ! empty( $this->current_div_group ) ) {
                            echo '</div>';
                        }
                        echo "\n<div class='{$group->div_group}'>";
                        $this->current_div_group = $group->div_group;
                    }
                    $group->render_Group();
                }
                if ( ! empty( $this->current_div_group ) ) {
                    echo '</div>';
                }
            }
            $this->footer();
        }

        /**
         * Render a section header.
         */
        function header() {
            $friendlyName = strtolower( strtr( $this->name, ' ', '_' ) );
            $friendlyDiv  = ( isset( $this->div_id ) ? $this->div_id : $friendlyName );
            $groupClass   = $this->is_topmenu ? '' : 'group';

            echo '<div ' .
                 "id='wpcsl-option-{$friendlyDiv}' " .
                 "class='{$groupClass} subtab_{$friendlyDiv} subtab settings' " .
                 ">";

            print $this->opening_html;

            if ( $this->innerdiv ) {
                echo "<div class='inside section meta-box-sortables'>";
                if ( ! empty( $this->description ) ) {
                    print "<div class='section_description'>";
                }
            }

            if ( ! empty( $this->description ) ) {
                echo $this->description;
            }

            if ( $this->innerdiv ) {
                if ( ! empty( $this->description ) ) {
                    echo '</div>';
                }
            }
        }

        /**
         * Should the section be show (display:block) now?
         *
         * @return boolean
         */
        function show_now() {
            return ( $this->first || $this->is_topmenu );
        }

        /**
         * Render a section footer.
         */
        function footer() {
            if ( $this->innerdiv ) {
                echo '</div>';
            }
            print $this->closing_html;
            echo '</div>';
        }

    }

}

if ( ! class_exists( 'SLPlus_Settings_Group' ) ) {

    /**
     * Groups are collections of individual settings (items).
     *
     * @package   SLPlus\Settings\Group
     * @author    Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2013 - 2016 Charleston Software Associates, LLC
     *
     * @property        string                 $div_group    the div group we belong in
     * @property        string                 $header       the header
     * @property        string                 $intro        the starting text
     * @property-read   SLPlus_Settings_Item[] $items
     * @property        string                 $slug         the slug
     */
    class SLPlus_Settings_Group extends SLPlus_BaseClass_Object {
        public $div_group;
        public $intro;
        private $items;
        public $header;
        public $slug;

        /**
         * Add an item to the group.
         *
         * @param mixed[] $params
         */
        function add_item( $params ) {
            $this->items[] = new SLPlus_Settings_Item( $params );
        }

        /**
         * Does this group have any items?
         *
         * @return bool
         */
        function has_items() {
            return ! empty( $this->items );
        }

        /**
         * Render a group.
         */
        function render_Group() {
            if ( ! $this->has_items() && empty( $this->intro )) { return; }
            $this->render_Header();
            if ( isset( $this->items ) ) {
                foreach ( $this->items as $item ) {
                    $item->display();
                }
            }
            echo '</div></div>';
        }

        /**
         * Output the group header.
         */
        function render_Header() {
            echo
                "\n<div class='settings-group' id='wpcsl_settings_group-{$this->slug}' >" .
                "<h4 class='settings-header'>{$this->header}</h4>" .
                "<div class='inside'>" .
                (
                ( $this->intro != '' ) ?
                    "<div class='section_column_intro' id='wpcsl_settings_group_intro-{$this->slug}'>{$this->intro}</div>" :
                    ''
                );
        }

    }

}

if ( ! class_exists( 'SLPlus_Settings_Item' ) ) {

    /**
     * Individual settings (options).
     *
     * @package         SLPlus\Settings\Item
     * @author          Lance Cleveland <lance@charlestonsw.com>
     * @copyright       2013 - 2016 Charleston Software Associates, LLC
     *
     * @propery         string             $button_label       Text for hyperlink buttons.
     * @property        mixed             $custom
     * @property        string            $data_field         The data field.
     * @property        string            $description
     * @property        boolean           $disabled
     * @property        boolean           $empty_ok           Empty drop down menu items OK?
     * @property        string            $id                 ID for field, defaults to name ('setting') if not set.
     * @property        string            $label
     * @property        string            $name
     * @property        string            $onChange           onChange JavaScript for an input item.
     * @property        string            $onClick            onClick JavaScript for an input item.
     * @property        string            $placeholder        The placeholder text.
     * @property        boolean           $show_label
     * @property        string            $selectedVal        Value of item to be selected for a drop down object.
     * @property        string            $type               'checkbox'|'custom'|'dropdown'|'sidelabel'|'subheader'|'submit'|'text'|'textarea'
     * @property        string            $value              Value of item for text boxes, etc.   For checkboxes, evaluated for 'on'|'1' to check the box.
     *
     * @property-read    SLP_Admin_Helper $helper
     * @property        SLPlus            $slplus
     */
    class SLPlus_Settings_Item extends SLPlus_BaseClass_Object {

        public $button_label = '';
        public $custom;
        public $data_field;
        public $description;
        public $disabled = false;
        public $empty_ok = false;
        private $helper;
        public $id;
        public $label;
        public $name;
        public $onChange = '';
        public $onClick = '';
        public $placeholder = '';
        public $show_label = true;
        public $selectedVal = '';
        public $type = 'custom';
        public $value;

        /**
         * Things we do at startup.
         */
        function initialize() {
            $this->set_defaults();
        }

	    /**
	     * Create the help text div.
	     */
        private function create_help_text() {
        	?>
			<div class="input-description">
				<span class="input-description-text"><?php echo $this->description; ?></span>
			</div>
			<?php
        }

        /**
         * Create helper object.
         */
        private function create_object_helper() {
            if ( ! isset( $this->helper ) ) {
                require_once( SLPLUS_PLUGINDIR . 'include/class.admin.helper.php' );
                $this->helper = new SLP_Admin_Helper();
            }
        }

        /**
         * Create the HTML for the drop down selectors.
         *
         * @return string
         */
        private function create_string_dropdown() {
            return $this->slplus->helper->createstring_DropDownMenu(
                array(
                    'id'          => $this->name,
                    'name'        => $this->name,
                    'items'       => $this->custom,
                    'onchange'    => $this->onChange,
                    'disabled'    => $this->disabled,
                    'selectedVal' => $this->selectedVal,
                    'empty_ok'    => $this->empty_ok,
                )
            );
        }

        /**
         * Create the drop down for the 'list' input types.
         *
         * If $type is 'list' then $custom is a hash used to make a <select>
         * drop-down representing the setting.  This function returns a
         * string with the markup for that list.
         *
         * The selected value will use the get_wp_option() on the name of the drop down,
         * with a default being allowed in the $value parameter.
         *
         * @return string
         */
        private function create_string_list() {
            $content     = "<select id='{$this->id}'  name='{$this->name}' class='csl_select' data-field='{$this->data_field}' {$this->onChange} >";
            $selectMatch = $this->value;

            foreach ( $this->custom as $key => $value ) {
                if ( $selectMatch === $value ) {
                    $content .= "<option class='csl_option' value='{$value}' selected='selected'>{$key}</option>\n";
                } else {
	                $content .= "<option class='csl_option' value='{$value}'>{$key}</option>\n";
                }
            }

            $content .= "</select>\n";

            return $content;
        }

        /**
         * Render the item to the page.
         *
         */
        function display() {

            // No value provided?  Find one in the options table.
            //
            if ( ! isset( $this->value ) ) {
                $this->value = $this->slplus->option_manager->get_wp_option( $this->name );

                if ( is_array( $this->value ) ) {
                    $this->value = print_r( $this->value, true );
                }

                $this->value = htmlspecialchars( $this->value );
            }
            $value_to_show = ( $this->type === 'textarea' ) ? esc_textarea( $this->value ) : esc_html( $this->value );

            if ( ! isset( $this->id ) ) {
                $this->id = $this->name;
            }
            if ( ! isset( $this->data_field ) ) {
                $this->data_field = $this->id;
            }

            if ( ! empty( $this->onChange ) ) {
                $this->onChange = "onchange='{$this->onChange}'";
            }

            if ( isset( $this->placeholder ) && ! empty ( $this->placeholder ) ) {
                $placeholder = "placeholder='{$this->placeholder}'";
            } else {
                $placeholder = '';
            }

            $disabled_class    = $this->disabled ? 'disabled' : '';
            $disabled_property = $this->disabled ? 'disabled="disabled"' : '';

            // The input fields wrapper must be one for all rows.
            // echo '<div class="form_entry input-fields-wrapper">';
            // Field label + input wrapper
            echo "<div class='input-group wpcsl-{$this->type} {$disabled_class}' id='input-group-{$this->id}' name='input-group-{$this->id}'>";

            // Show label wrapper.
            //
            if ( $this->show_label ) {
                echo
                    '<div class="label input-label">' .
                    "<label for='{$this->name}'>{$this->label}</label>" .
                    '</div>';
            }

            // Type Processing
            //
            echo '<div class="input input-field">';
            switch ( $this->type ) {

                case 'input':
                case 'text':
                    echo "<input type='text' id='{$this->id}' name='{$this->name}' data-field='{$this->data_field}' value='{$value_to_show}' {$placeholder} {$disabled_property} {$this->onChange}>";
                    break;

                case 'password':
                    echo "<input type='password' id='{$this->id}'  name='{$this->name}' data-field='{$this->data_field}' value='{$value_to_show}' {$placeholder} {$disabled_property} {$this->onChange} />";
                    break;

                case 'textarea':
                    echo
                        "<textarea  name='{$this->name}' id='{$this->id}' data-field='{$this->data_field}' " .
                        'cols="50" ' .
                        'rows="5" ' .
                        $placeholder .
                        $disabled_property .
                        '>' . $value_to_show . '</textarea>';
                    break;

                case 'checkbox':
                    $checked = $this->slplus->is_CheckTrue( $value_to_show ) ? 'checked' : '';
                    echo "<input type='checkbox' id='{$this->id}'  name='{$this->name}' data-field='{$this->data_field}' value='1' {$disabled_property} {$checked} {$this->onChange}/>";
                    break;

                case 'subheader':
                    if ( ! empty( $this->label ) ) {
                        echo "<h3>{$this->label}</h3>";
                    }
                    if ( ! empty( $this->description ) ) {
                        echo "<p class='slp_subheader_description' id='{$this->id}_p'>{$this->description}</p>";
                    }
                    $this->description = null;
                    break;

                case 'dropdown':
                    echo $this->create_string_dropdown();
                    break;

                case 'hyperbutton':
                    if ( isset( $this->onClick ) ) {
                        $onclick = empty( $this->onClick ) ? '' : " onclick='{$this->onClick}' ";
                    } else {
                        $onclick = " onclick='SLP_ADMIN.options.change_option(this);' ";
                    }
                    echo "<a href='javascript:void(0);' {$onclick}  class='hyper_button button_{$this->id}' " .
                         "id='{$this->id}' value='{$value_to_show}' " .
                         "data-field='{$this->data_field}' " .
                         ">{$this->button_label}</a>";
                    break;

                case 'icon':
                    $this->create_object_helper();
                    echo "<input type='text' id='{$this->id}' name='{$this->name}' data-field='{$this->data_field}' value='{$value_to_show}' {$disabled_property} {$this->onChange} />";
                    if ( ! is_null( $this->description ) ) {
                        echo $this->slplus->helper->CreateHelpDiv( $this->name, $this->description );
                        $this->description = null;
                    }
                    $icon_src = ! empty( $value_to_show ) ? $value_to_show : $this->slplus->options['map_end_icon'];
                    echo "<img id='{$this->id}_icon' alt='{$this->id}' src='{$icon_src}' class='slp_settings_icon'>";
                    echo $this->helper->create_string_icon_selector( $this->id, $this->id . '_icon' );
                    break;

                case 'list':
                    echo $this->create_string_list();
                    break;

                case 'submit':
                case 'submit_button':
                    echo "<input  name='{$this->name}' " .
                         'class="button-primary" type="submit" ' .
                         "id='{$this->id}' value='{$value_to_show}' " .
                         "data-field='{$this->data_field}' " .
                         ( ! empty( $this->onClick ) ? 'onClick="' . $this->onClick . '" ' : '' ) .
                         '>';
                    break;

                case 'custom':
                case 'details':
                default:
                    if ( ! empty( $this->custom ) ) {
                        echo $this->custom;
                    }
                    break;
            }

            echo '</div>'; // Wrap all input types with a class="input" div.

	        // Help text via description.
	        //
	        if ( ! is_null( $this->description ) && ! empty( $this->description ) ) {
		        $this->create_help_text();
	        }

            echo '</div>'; // Close the label + input field wrapper.
        }

        /**
         * Set defaults for various item types.
         */
        function set_defaults() {
            switch ( $this->type ) {

                // DETAILS
                //
                // The text/HTML can go in 'custom' or 'description' when calling add_ItemToGroup
                // No other settings are required.
                //
                case 'details':
                    if ( empty( $this->custom ) ) {
                        $this->custom = $this->description;
                    }
                    $this->description = null;
                    $this->show_label  = false;
                    $this->value       = '';
                    break;

                // SIDELABEL
                //
                case 'sidelabel':
                    $this->show_label = false;
                    break;


                // SUBHEADER
                //
                // Place the subheader text in label.   Set the section and group name.
                // All else can be unset.
                //
                case 'subheader':
                    $this->show_label = false;
                    break;
            }
        }

    }
}
