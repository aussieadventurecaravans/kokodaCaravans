<?php


//Add Events page on activation:

function install_custom_order_page(){
    $new_page_title = 'Custom Order';
    $new_page_content = 'This is your page content that automatically gets inserted into the custom order page!';
    $new_page_template =  'custom-order-template.php'; //ex. template-custom.php. Leave blank if you don't want a custom page template.
    //don't change the code below, unless you know what you're doing
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
        'post_type' => 'page',
        'post_title' => $new_page_title,
        'post_content' => $new_page_content,
        'post_status' => 'publish',
        'post_author' => 1,
    );
    if(!isset($page_check->ID)){
        $new_page_id = wp_insert_post($new_page);
        if(!empty($new_page_template)){
            update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
        }
    }
}//end install_custom_order_page function to add page to wp on plugin activation


function uploadr_redirect( $template ) {

    $plugindir = dirname( __FILE__ );

    if ( is_page_template( 'custom-order-template.php' )) {

        $template = $plugindir . '/template/custom-order-template.php';
    }

    return $template;

}


function install_custom_order_tables() {

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'custom_order_quote';


    $sql = "CREATE TABLE $table_name (
      quote_id mediumint NOT NULL AUTO_INCREMENT,
      customer_first_name varchar(255) NOT NULL  DEFAULT '',
      customer_last_name varchar(255) NOT NULL  DEFAULT '',
      product_id varchar(255)  NOT NULL DEFAULT '',
      product_name varchar(255)  NOT NULL DEFAULT '',
      custom_options longtext default NULL,
      add_on_accessories longtext default NULL,
      other_options longtext default NULL,
      product_cost double NOT NULL DEFAULT '0',
      orc_cost double NOT NULL DEFAULT '0',
      add_on_cost double NOT NULL DEFAULT '0',
      total_cost double NOT NULL DEFAULT '0',
      payment_method varchar(80) NOT NULL  DEFAULT '',
      customer_address varchar(255) NOT NULL  DEFAULT '',
      customer_city varchar(255) NOT NULL  DEFAULT '',
      customer_postcode varchar(255) NOT NULL  DEFAULT '',
      customer_state varchar(255) NOT NULL  DEFAULT '',
      customer_email varchar(255) NOT NULL  DEFAULT '',
      customer_phone varchar(15) NOT NULL  DEFAULT '',
      dealer_id mediumint NOT NULL DEFAULT 0,
      dealer_name varchar(255)  NOT NULL DEFAULT '',
      dealer_email varchar(255)  NOT NULL DEFAULT '',
      dealer_phone varchar(15)  NOT NULL DEFAULT '',
      dealer_address varchar(255)  NOT NULL DEFAULT '',
      dealer_city varchar(255)  NOT NULL DEFAULT '',
      dealer_state varchar(255)  NOT NULL DEFAULT '',
      dealer_postcode varchar(255)  NOT NULL DEFAULT '',
      status varchar(255)  NOT NULL DEFAULT 'In Progress',
      has_loan tinyint(1)  NOT NULL DEFAULT 0,
      apply_loan_option varchar(55) NOT NULL DEFAULT 'None',
      loan_status varchar(50)  NOT NULL DEFAULT 'Pending',
      loan_detail longtext default NULL,
      date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      date_modified datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (quote_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta( $sql );
}
