<?php
/**
 * Plugin Name: Custom Order and Quote for Kokoda
 * Description: Custom order/quote  pluggin for kokoda website
 * Version: 1.0
 * Author: Son Nguyen
 */
if( !function_exists( 'add_action' ) ){
    die( "Hi there! I'm just a plugin, not much I can do when called directly." );
}

// Setup
define( 'KOKODA_CUSTOM_ORDER_PLUGIN_URL', plugin_dir_path( __FILE__ )  );


// Includes
include('includes/admin/quote_list.php');
include('activate.php');



// Hooks
register_activation_hook(__FILE__, 'install_custom_order_page');
register_activation_hook(__FILE__, 'install_custom_order_tables');
add_action( 'template_include', 'uploadr_redirect' );
//add_action( 'init', 'custom_order_init' );
//add_action( 'admin_init', 'custom_order_admin_init' );
add_action( 'plugins_loaded', function ()
{
    Kokoda_Custom_Order_Plugin::get_instance();
} );

class Kokoda_Custom_Order_Plugin {

    // class instance
    static $instance;

    // @var $quote_obj WP_List_Table
    public $quote_obj;

    // class constructor
    public function __construct() {
        add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
        //render quote list page and quote list menu at main menu admin
        add_action( 'admin_menu', array( $this, 'quote_list_init' ) );
        //render edit quote page
        add_action( 'admin_menu', array( $this, 'quote_edit_init' ) );


        add_action('admin_init', array($this, 'custom_order_register_settings'));
        add_action( 'admin_menu', array( $this,'add_custom_order_settings_page') ); // add page to setting menu
    }


    public static function set_screen( $status, $option, $value ) {
        return $value;
    }

    public function quote_list_init() {

        $hook = add_menu_page(
            'Quote List',
            'Quote List',
            'manage_options',
            'quotes',
            [ $this, 'admin_quote_route' ],
            'dashicons-feedback',
            6
        );

        add_action( "load-$hook", [ $this, 'screen_option' ] );

    }


    public function quote_edit_init()
    {
        add_submenu_page(
            'quotes',
            'Edit Quote',
            'Edit Quote',
            'manage_options',
            'quotes',
            [$this,'admin_quote_route']
        );
    }


    public function admin_quote_route()
    {
        if(isset($_REQUEST['action']))
        {
            $action = $_REQUEST['action'];
            switch ($action)
            {
                case 'edit':
                    $this->edit_quote_page();
                    return;
                default:
                    $this->quote_list_page();
            }

        }
        else
        {
            //default display quote list if no action take page
            $this->quote_list_page();
        }
    }

    /**
     * render the quote edit page function
     */
    public function edit_quote_page()
    {
        //get the request var
        $action = isset($_REQUEST['action']) ? 'edit' : '';
        if ( $action)
        {
            require( KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'includes/admin/view/quote_edit_view.php' );
        }

    }

    /**
     * render the quote list page function
     */
    public function quote_list_page()
    {

        require( KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'includes/admin/view/quote_list_view.php' );

    }

    /**
     * Screen options
     */
    public function screen_option() {

        $option = 'per_page';
        $args   = [
            'label'   => 'Quote',
            'default' => 10,
            'option'  => 'quotes_per_page'
        ];

        add_screen_option( $option, $args );

        $this->quote_obj = new Quote_List();
    }


    /** Singleton instance */
    public static function get_instance()
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * adds plugin options page to setting menu
     */
    public function add_custom_order_settings_page() {

        $page_hook_suffix= add_options_page(
            'Custom Orders Settings',
            'Custom Orders Settings',
            'manage_options',
            'custom-orders-settings', //page slug
            array($this,'custom_orders_create_settings_page')
        );
    }

    /**
     * prints html for plugin options page
     */
    public function custom_orders_create_settings_page()
    {

        echo '<div class="wrap bpwpcleaner-wrap">';
        echo '<h2>Kokoda Custom Option Settings</h2>';
        echo '<form method="post" action="options.php">';

        settings_fields( 'custom_orders_settings' );
        do_settings_sections( 'custom-orders-settings' );

        submit_button();
        echo '</form>';
        echo '</div>';

    }

    public function custom_order_register_settings()
    {
        register_setting(
            'custom_orders_settings',    // $option_group
            'custom_order_development-mode',   // $option_name
         'esc_attr'
        );


        add_settings_section(
            'custom-orders-general-section', //id
          'General Settings', //title
            array($this, 'custom_order_setting_section_cb'),  //callback function
            'custom-orders-settings' //page slug
        );


        add_settings_field(
            'custom_order_development-mode',
            'Development Mode',
            array($this,'custom_order_development_setting_callback_function'),
            'custom-orders-settings',
            'custom-orders-general-section',
            array( 'label_for' => 'custom_order_development-mode' )
        );


    }
    public function custom_order_development_setting_callback_function()
    {
        $value = get_option( 'custom_order_development-mode');
        if ( $value === 0 ) // Nothing yet saved
        ?>
            <select name="custom_order_development-mode" id="custom_order_development-mode">
                <option value='0' <?= ($value == false) ?  'selected' : '' ?> >Disable</option>
                <option value='1' <?= ($value == true || $value == '' ) ?  'selected' : '' ?> >Enable</option>
            </select>

        <?php
    }

    public function custom_order_setting_section_cb($arg){
        //print_r($arg);
    }

}


//ajax actions
add_action('wp_ajax_submit_customorder', 'submit_customorder');
add_action('wp_ajax_nopriv_submit_customorder', 'submit_customorder');

add_action('wp_ajax_get_caravan', 'get_caravan');
add_action('wp_ajax_nopriv_get_caravan', 'get_caravan');


add_action('wp_ajax_export_pdf', 'export_pdf');
add_action('wp_ajax_nopriv_export_pdf', 'export_pdf');

add_action('wp_ajax_list_accessories', 'list_accessories');
add_action('wp_ajax_nopriv_list_accessories', 'list_accessories');


// Hooking up our functions to  filter wordpress email send header
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );

//add custom order page template
add_filter( 'page_template', 'custom_order_page_template',99 );
add_filter ('theme_page_templates', 'add_custom_order_page_template');

function custom_order_page_template($page_template)
{

    if ('custom-order-template.php' == basename ($page_template))
    {
        $page_template = WP_PLUGIN_DIR . '/kokoda-custom-order/template/custom-order-template.php';
    }
    return $page_template;
}

function add_custom_order_page_template ($templates)
{
    $templates['custom-order-template.php'] = 'Custom Order Template';
    return $templates;
}



function submit_customorder()
{

    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

    if (! isset( $_POST['kokoda_wpnonce'] ) || ! wp_verify_nonce($_POST['kokoda_wpnonce'], 'submit_new_quote' ) )
    {
        echo false;
        wp_die();
    }

    $custom_order = $_POST['custom_order'];

    require( KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'includes/models/quote.php' );

    try
    {

        $quote_object =  Quote::new_quote($custom_order);

        if($quote_object != false)
        {
            echo true;
        }
        else
        {
            echo false;
        }
    }
    catch (Exception $e)
    {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        echo false;
    }
    wp_die();
}


function get_caravan()
{
    if(isset($_POST['custom_order']))
    {
        Track_user_export_pdf($_POST['custom_order']);
    }
    if( isset( $_POST['caravan_id'] ))
    {

        set_query_var('caravan_id', $_POST['caravan_id']);
        require( KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'template/caravan-specs-part.php' );
    }
    wp_die();
}


function export_pdf()
{
    if( isset( $_POST['caravan_id'] ))
    {
        set_query_var('caravan_id', $_POST['caravan_id']);
        set_query_var('custom_order', $_POST['custom_order']);
        require( KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'template/summary-report-template.php' );
    }
    wp_die();
}


function list_accessories()
{
    try
    {
        if( isset( $_POST['accessories_file'] ))
        {
            $lines = explode( "\n", file_get_contents( $_POST['accessories_file']  ) );
            $headers = str_getcsv( array_shift( $lines ) );
            $data = array();
            foreach ( $lines as $line )
            {
                $row = array();
                foreach ( str_getcsv( $line ) as $key => $field )
                    $row[ $headers[ $key ] ] = $field;
                $row = array_filter( $row );
                $data[] = $row;
            }
            echo json_encode($data);
        }
        else
        {
            echo false;
        }
    }
    catch(Exception $e)
    {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        echo false;
    }
    wp_die();

}


// Function to change sender name
function wpb_sender_name()
{
    return 'Kokoda Caravans Site';
}

// Function to change email address
function wpb_sender_email()
{
    //Make sure the email is from the same domain
    //as your website to avoid being marked as spam.
    $host = $_SERVER['HTTP_HOST'];
    return "noreply@" .str_replace('www.','',$host);
}

/**
 * this function will track user input
 * at the custom order page so we will know their behavior and interest at our model
 * @param array $user_input
 */
function Track_user_export_pdf($user_input)
{
    if(isset($user_input))
    {
        try {
            $list = array(
                array(
                    GetClientIP(),
                    $user_input['caravan'],
                    $user_input['caravan_options']['panel']['value'],
                    $user_input['caravan_options']['checker_plate']['value']
                )
            );

            $file_name = WP_CONTENT_DIR . '/uploads/custom_order/track_user_choice.csv';

            $fp = fopen($file_name, 'a+');

            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";

        }
    }
}

/**
 * this function will track user ip
 *
 * @return string $ip
 */

function GetClientIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}