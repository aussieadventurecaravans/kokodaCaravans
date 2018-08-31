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
add_action( 'plugins_loaded', function () {
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
        add_action( 'admin_menu', [ $this, 'quote_list_init' ] );
        //render edit quote page
        add_action( 'admin_menu', [ $this, 'quote_edit_init' ] );
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

}

add_action('wp_ajax_submit_customorder', 'submit_customorder');
add_action('wp_ajax_nopriv_submit_customorder', 'submit_customorder');

add_action('wp_ajax_get_caravan', 'get_caravan');
add_action('wp_ajax_nopriv_get_caravan', 'get_caravan');


function submit_customorder()
{

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


    die();

}


function get_caravan()
{


    if( isset( $_POST['caravan_id'] ))
    {

        set_query_var('caravan_id', $_POST['caravan_id']);

        require( KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'template/caravan-specs-part.php' );

    }

    die();
}