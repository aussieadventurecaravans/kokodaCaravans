<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Quote_List extends WP_List_Table
{

    /** Class constructor */
    public function __construct()
    {

        parent::__construct( [
            'singular' => __('quote'), //singular name of the listed records
            'plural'   =>  __('quote'), //plural name of the listed records
            'ajax'     => true //does this table support ajax?
        ] );

    }


    /**
     * Retrieve quote data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_quotes( $per_page = 5, $page_number = 1 ) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_order_quote';

        $sql = "SELECT * FROM {$table_name}";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }



    /**
     * Delete a quote record.
     *
     * @param int $id Quote ID
     */
    public static function delete_quote( $id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_order_quote';
        $wpdb->delete(
            "{$table_name}",
            [ 'quote_id' => $id ],
            [ '%d' ]
        );
    }


    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_order_quote';

        $sql = "SELECT COUNT(*) FROM {$table_name}";

        return $wpdb->get_var( $sql );
    }


    /** Text displayed when no Quote data is available */
    public function no_items() {
        _e( 'No quotes avaliable.');
    }


    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'cb':
            case 'quote_id' :
            case 'product_name' :
            case 'total_cost':
            case 'payment_method' :
            case 'status':
            case 'loan_status':
            case 'customer_first_name':
            case 'customer_last_name':
            case 'customer_address' :
            case 'customer_email' :
            case 'customer_phone' :
            case 'date_created' :
            case 'date_modified' :
                return $item[ $column_name ];
            case 'has_loan':
                return  ($item[ $column_name ] == 1) ? 'true' : 'false';
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['quote_id']
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_customer_first_name( $item ) {

        $delete_nonce = wp_create_nonce( 'delete_quote_action' );

        $edit_nonce =  wp_create_nonce( 'edit_quote_action' );

        $title = '<strong>' . $item['customer_first_name'] . '</strong>';
        $actions = array();


        $actions ['delete'] = sprintf( '<a href="?page=%s&action=%s&quote=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['quote_id'] ), $delete_nonce );

        $actions ['edit'] = sprintf( '<a href="?page=%s&action=%s&quote_id=%s">Edit</a>', esc_attr( $_REQUEST['page'] ),'edit', absint( $item['quote_id'] ));


        return $title . $this->row_actions( $actions );
    }


    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'quote_id'    => __( 'ID' ),
            'customer_first_name' => __( 'Customer Name'),
            'customer_last_name' => __( 'Customer Name'),
            'product_name' => __( 'Product Name'),
            'total_cost'  => __( 'Quote Price'),
            'payment_method'  => __( 'Payment'),
            'status'=>__('status'),
            'has_loan'=>__('has_loan'),
            'loan_status'=>__('loan_status'),
            'customer_address' => __( 'Customer Address'),
            'customer_email' => __( 'Email'),
            'customer_phone' => __( 'Phone'),
            'date_created' => __( 'Date Created'),
            'date_modified' => __( 'Date Modified'),
        ];

        return $columns;
    }


    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [
            'bulk-delete' => 'Delete'
        ];

        return $actions;
    }



    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'quote_id' => array( 'quote_id', true ),
            'date_created' => array( 'date_created', true ),
            'date_modified' => array( 'date_modified', true )
        );

        return $sortable_columns;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'quotes_per_page', 25 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ] );

        $this->items = self::get_quotes( $per_page, $current_page );
    }

    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, 'delete_quote_action' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_quote( absint( $_GET['quote'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                $arr_params = array('action','quote','_wpnonce');
                wp_redirect( esc_url_raw(remove_query_arg( $arr_params)) );
                exit;
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_quote( $id );

            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            wp_redirect( esc_url_raw(add_query_arg()) );
            exit;
        }
    }
}