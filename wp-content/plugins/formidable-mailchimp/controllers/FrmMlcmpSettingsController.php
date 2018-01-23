<?php

class FrmMlcmpSettingsController{

    public static function add_settings_section($sections){
        $sections['mailchimp'] = array('class' => 'FrmMlcmpSettingsController', 'function' => 'route');
        return $sections;
    }
    
    public static function add_mailchimp_options($sections){
        $sections['mailchimp'] = array('class' => 'FrmMlcmpSettingsController', 'function' => 'mailchimp_options');
        return $sections;
    }
    
    public static function match_fields(){
        $form_id = isset($_POST['form_id']) ? (int) $_POST['form_id'] : false;
        $list_id = isset($_POST['list_id']) ? $_POST['list_id'] : false;
        if ( ! $form_id || ! $list_id ) {
            die();
        }
        
        $list_fields = FrmMlcmpAppController::decode_call('/lists/merge-vars', array( 'id' => array( $list_id ) ));

        $form_fields = FrmField::getAll('fi.form_id='. (int) $form_id ." and fi.type not in ('break', 'divider', 'html', 'captcha', 'form')", 'field_order');
        $groups = FrmMlcmpAppController::get_groups($list_id);

		$list_options = array( 'optin' => 0 );
        
        if ( isset($_POST['action_key']) ) {
            $action_control = FrmFormActionsController::get_form_actions( 'mailchimp' );
            $action_control->_set($_POST['action_key']);
            include(FrmMlcmpAppHelper::plugin_path() .'/views/action-settings/_match_fields.php');
        } else {
            $hide_mailchimp = '';
            include(FrmMlcmpAppHelper::plugin_path() .'/views/settings/_match_fields.php');
        }
        
        die();
    }
    
    public static function mailchimp_options($values){
        if(!empty($values['mlcmp_list'])){
            $lists = FrmMlcmpAppController::decode_call('/lists/list', array('limit' => 50));
            $form_fields = FrmField::getAll('fi.form_id='. (int) $values['id'] ." and fi.type not in ('break', 'divider', 'html', 'captcha', 'form')", 'field_order');
        }
        
        include(FrmMlcmpAppHelper::plugin_path() .'/views/settings/mailchimp_options.php');
    }
    
    public static function add_list($list_id=false, $active=true){
        $hide_mailchimp = $active ? '' : 'style="display:none;"';
        $die = ($list_id) ? false : true;
        if ( !$list_id && $_POST && isset($_POST['list_id']) ) {
            $list_id = $_POST['list_id'];
        }
        
        $lists = FrmMlcmpAppController::decode_call('/lists/list', array('limit' => 50));
            
        $list_options = array('optin' => 0);
        
        include(FrmMlcmpAppHelper::plugin_path() .'/views/settings/_list_options.php');
        
        if($die)
            die();
    }
    
    public static function add_logic_row(){
        if ( !$_POST || !isset($_POST['list_id']) ) {
            die();
        }
        
        $list_id = $_POST['list_id'];
        $form_id = (int) $_POST['form_id'];
        $meta_name = $_POST['meta_name'];
        $hide_field = '';
        $list_options = array('hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array());
        
        FrmMlcmpAppHelper::include_logic_row($meta_name, $form_id, $list_id, $list_options);
        
        die();
    }
    
    public static function get_group_values(){
		$list_id = $meta_name = sanitize_text_field( $_POST['list_id'] );
		$form_id = absint( $_POST['form_id'] );
        $group_id = $_POST['group_id'];
        
        $groups = FrmMlcmpAppController::get_groups($list_id);
        foreach ( $groups as $group ) {
            if ( isset($group['id']) && $group['id'] == $group_id ) {
                break;
            }
        }

		$new_field = FrmField::getOne( absint( $_POST['field_id'] ) );
        
        list( $form_options, $list_options ) = self::populate_options(compact('form_id', 'list_id'));
        
        $frm_version = is_callable('FrmAppHelper::plugin_version') ? FrmAppHelper::plugin_version() : 0;
        
        if ( version_compare($frm_version, '1.07.20', '>') ) {
            $action_control = FrmFormActionsController::get_form_actions( 'mailchimp' );
            $action_control->_set($_POST['action_key']);
            require(FrmMlcmpAppHelper::plugin_path() .'/views/action-settings/_group_values.php');
        } else {
            require(FrmMlcmpAppHelper::plugin_path() .'/views/settings/_group_values.php');
            
        }
        
		wp_die();
    }
    
    public static function check_apikey() {
        // Validate nonce
        if( !isset($_POST['wpnonce']) || !wp_verify_nonce( $_POST['wpnonce'], 'frm_mlcmp' ) ) {
            die( json_encode( array( 'error' => __('You do not have permission to access this page.', 'frmmlcmp') ) ) );
        }
        
        // Validate inputs
        if( !isset( $_POST['apikey'] ) ) 
            die( json_encode( array( 'error' => __('No api key code was sent', 'frmmlcmp') ) ) );

        die( FrmMlcmpAppController::call('/helper/ping', array(), $_POST['apikey']) );
    }
    
    public static function register_actions($actions) {
        $actions['mailchimp'] = 'FrmMlcmpAction';
        
        include_once(FrmMlcmpAppHelper::plugin_path() . '/models/FrmMlcmpAction.php');
        
        return $actions;
    }
    
    public static function load_form_settings_hooks() {
        $frm_version = is_callable('FrmAppHelper::plugin_version') ? FrmAppHelper::plugin_version() : 0;
        
        if ( version_compare($frm_version, '1.07.20', '>') ) {
            return;
        }
        
        // load hooks for < v2.0
        add_action('frm_add_form_settings_section', 'FrmMlcmpSettingsController::add_mailchimp_options', 10);
        add_filter('frm_setup_new_form_vars', 'FrmMlcmpSettingsController::setup_new_vars');
        add_filter('frm_setup_edit_form_vars', 'FrmMlcmpSettingsController::setup_edit_vars');
        add_filter('frm_form_options_before_update', 'FrmMlcmpSettingsController::update_options', 15, 2);
        add_action('wp_ajax_frm_mlcmp_add_list', 'FrmMlcmpSettingsController::add_list');
        add_action('wp_ajax_frm_mlcmp_add_logic_row', 'FrmMlcmpSettingsController::add_logic_row');
    }
    
    public static function setup_new_vars($values){
        $defaults = FrmMlcmpAppHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            $values[$opt] = FrmAppHelper::get_param($opt, $default);
            unset($default, $opt);
        }
        return $values;
    }
    
    public static function setup_edit_vars($values){
        $defaults = FrmMlcmpAppHelper::get_default_options();
        foreach ( $defaults as $opt => $default ) {
            if ( !isset($values[$opt]) ) {
                $values[$opt] = ($_POST && isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;
            }
            unset($default, $opt);
        }
        
        if ( $_POST && isset($_POST['options']['mlcmp_list']) ) {
            $values['mlcmp_list'] = $_POST['options']['mlcmp_list'];
        }

        return $values;
    }
    
    public static function update_options($options, $values){
        $defaults = FrmMlcmpAppHelper::get_default_options();
        
        foreach ( $defaults as $opt => $default ) {
            $options[$opt] = (isset($values['options'][$opt])) ? $values['options'][$opt] : $default;
            unset($default, $opt);
        }

        unset($defaults);
        
        return $options;
    }
    
    public static function display_form(){
        $frm_mlcmp_settings = new FrmMlcmpSettings();
        
        if(method_exists('FrmAppHelper', 'plugin_version'))
            $frm_version = FrmAppHelper::plugin_version();
        else
            global $frm_version; //version fallback < v1.07.02

        require_once(FrmMlcmpAppHelper::plugin_path() . '/views/settings/form.php');
    }

    public static function process_form(){
        $frm_mlcmp_settings = new FrmMlcmpSettings();

        //$errors = $frm_mlcmp_settings->validate($_POST,array());
        $errors = array();
        
        $frm_mlcmp_settings->update($_POST);

        if( empty($errors) ){
            $frm_mlcmp_settings->store();
            $message = __('Settings Saved', 'frmmlcmp');
        }

        require_once(FrmMlcmpAppHelper::plugin_path() . '/views/settings/form.php');
    }

    public static function route(){
        $action = FrmAppHelper::get_param('action');
        if($action == 'process-form')
            return self::process_form();
        else
            return self::display_form();
    }
    
    public static function populate_options($atts) {
        // $atts includes $form_id, $meta_name, $list_id
        extract($atts);
        
        global $wpdb;
        $form_options = $wpdb->get_var($wpdb->prepare("SELECT options FROM {$wpdb->prefix}frm_forms WHERE id=%d", $form_id));
        $form_options = maybe_unserialize($form_options);
        
        if ( isset($form_options['mlcmp_list'][$list_id]) ) {
            $list_options = $form_options['mlcmp_list'][$list_id];
        } else {
            $list_options = array('hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array());
        }
        
        if ( isset($meta_name) && !isset($list_options['hide_field_cond'][$meta_name]) ) {
            $list_options['hide_field_cond'][$meta_name] = '==';
        }
        
        return array($form_options, $list_options);
    }
    
    public static function migrate_to_2($form) {
        if ( ! isset($form->options['mailchimp']) || ! $form->options['mailchimp'] || empty($form->options['mlcmp_list']) ) {
            return;
        }
        
        $frm_version = is_callable('FrmAppHelper::plugin_version') ? FrmAppHelper::plugin_version() : 0;
        
        if ( version_compare($frm_version, '1.07.20', '<=') ) {
            return;
        }
        
        $action_control = FrmFormActionsController::get_form_actions( 'mailchimp' );
        $orginal_options = $form->options;
        
        foreach ( (array) $form->options['mlcmp_list'] as $list_id => $list_options ) {
            $form->options['list_id'] = $list_id;
            $form->options = array_merge($form->options, $list_options);
            
            $post_id = $action_control->migrate_to_2($form, 'skip');
            $form->options = $orginal_options;
        }
        
        if ( $post_id ) {
            global $wpdb;
            
            // update form options
            unset($form->options['mailchimp']);
            unset($form->options['mlcmp_list']);
            
            $wpdb->update($wpdb->prefix .'frm_forms', array('options' => $form->options), array('id' => $form->id));
            wp_cache_delete( $form->id, 'frm_form');
        }
        
        return $post_id;
    }
}