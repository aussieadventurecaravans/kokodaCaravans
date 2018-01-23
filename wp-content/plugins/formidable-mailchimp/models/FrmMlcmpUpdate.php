<?php

class FrmMlcmpUpdate extends FrmAddon {

	public $plugin_file;
	public $plugin_name = 'MailChimp';
	public $version = '1.04.02';

	public function __construct() {
		$this->plugin_file = dirname( dirname( __FILE__ ) ) . '/formidable-mailchimp.php';
		parent::__construct();
	}

	public static function load_hooks() {
		add_filter( 'frm_include_addon_page', '__return_true' );
		new FrmMlcmpUpdate();
	}

}
