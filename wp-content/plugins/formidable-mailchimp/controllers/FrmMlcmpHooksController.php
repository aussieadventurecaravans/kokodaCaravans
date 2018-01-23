<?php

class FrmMlcmpHooksController{

    public static function load_hooks() {
        add_action('plugins_loaded', 'FrmMlcmpAppController::load_lang' );

        add_action('frm_entry_form', 'FrmMlcmpAppController::hidden_form_fields', 10, 2);

        // 2.0 hooks
        add_action('frm_trigger_mailchimp_action', 'FrmMlcmpAppController::trigger_mailchimp', 10, 3);
        add_action('frm_registered_form_actions', 'FrmMlcmpSettingsController::register_actions');

        // < 2.0 hooks
        add_action('frm_after_create_entry', 'FrmMlcmpAppController::maybe_send_to_mailchimp', 25, 2);
        add_action('frm_after_update_entry', 'FrmMlcmpAppController::maybe_send_to_mailchimp', 25, 2);
        //add_action('frm_before_destroy_entry', 'FrmMlcmpAppController::unsubscribe');

        // < 2.0 fallback
        add_action('init', 'FrmMlcmpSettingsController::load_form_settings_hooks');

        self::load_admin_hooks();
    }

    public static function load_admin_hooks() {
        if ( ! is_admin() ) {
            return;
        }

		add_action( 'admin_init', 'FrmMlcmpAppController::include_updater', 0 );
        add_action('after_plugin_row_formidable-mailchimp/formidable-mailchimp.php', 'FrmMlcmpAppController::min_version_notice');

        add_action('frm_add_settings_section', 'FrmMlcmpSettingsController::add_settings_section');
        add_action('wp_ajax_frm_mlcmp_match_fields', 'FrmMlcmpSettingsController::match_fields');
        add_action('wp_ajax_frm_mlcmp_get_group_values', 'FrmMlcmpSettingsController::get_group_values');
        add_action('wp_ajax_frm_mlcmp_check_apikey', 'FrmMlcmpSettingsController::check_apikey');

        // 2.0 hooks
        add_action('frm_before_list_actions', 'FrmMlcmpSettingsController::migrate_to_2');
    }

}