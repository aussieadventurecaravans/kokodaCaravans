<?php
class Quote
{

    /*Quote Attributes */
    public $quote_id;
    /**
     * @var string
     */
    public $product_name = '';
    /**
     * @var string
     */
    public $custom_options = '';
    /**
     * @var string
     */
    public $add_on_options = '';

    /**
     * @var double
     */
    public $product_cost = 0;

    /**
     * @var double
     */
    public $orc_cost = 0;

    /**
     * @var double
     */
    public $total_cost = 0;
    /**
     * @var double
     */
    public $add_on_cost = 0;
    /**
     * @var string
     */
    public $payment_method = '';
    /**
     * @var string
     */
    public $customer_name = '';
    /**
     * @var string
     */
    public $customer_address = '';
    /**
     * @var string
     */
    public $customer_postcode = '';

    /**
     * @var string
     */
    public $customer_state='';
    /**
     * @var string
     */
    public $customer_email= '';
    /**
     * @var string
     */
    public $customer_phone = '';
    /**
     * @var string
     */
    public $date_created =  '0000-00-00 00:00:00';
    /**
     * @var string
     */
    public $date_modified =  '0000-00-00 00:00:00';

    /**
     * Stores the quote object's sanitization level.
     *
     * Does not correspond to a DB field.
     *
     * @var string
     */
    public $filter;

    /**
     * Constructor.
     *
     * @param Quote|object $quote quote object.
     */
    public function __construct( $quote ) {
        foreach ( get_object_vars( $quote ) as $key => $value )
            $this->$key = $value;
    }

    /**
     * Retrieve Quote instance.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int $quote_id Quote ID.
     * @return Quote|false Quote object, false otherwise.
     */
    public static function get_instance( $quote_id )
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_order_quote';

        $quote_id = (int) $quote_id;
        if ( ! $quote_id ) {
            return false;
        }
        $_quote = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE quote_id = %d LIMIT 1", $quote_id ) );
        if ( ! $_quote )
            return false;


        return new Quote( $_quote );
    }




    /**
     * Update Quote instance.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int $quote_id Quote ID.
     * @param $data data for update.
     * @return Quote|false Quote object, false otherwise.
     */
    public static function update_quote( $quote_id, $data )
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_order_quote';



        $quote_id = (int) $quote_id;
        if ( ! $quote_id || !is_array($data))
        {
            return false;
        }
        $data = self::prepareDataForUpdate($data);
        $wpdb->update(
            $table_name,
            $data,
            array( 'quote_id' => $quote_id )
        );


        $_quote = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE quote_id = %d LIMIT 1", $quote_id ) );
        if ( ! $_quote )
            return false;

        return new Quote( $_quote );
    }

    /**
     * Update Quote instance.
     *
     *
     * @param $data data for update.
     * @return array $quote_data.
     */
    public function prepareDataForUpdate($data)
    {
        $quote_data =  array();
        $columns= array('product_name','custom_options','add_on_options','product_cost','orc_cost','total_cost',
                        'add_on_cost','payment_method','customer_name','customer_address','customer_postcode','customer_state',
                        'customer_email','customer_phone','date_created','date_modified');
        foreach ($data as $key => $value)
        {
            if(in_array($key,$columns))
            {
                $quote_data[$key] = $value;
            }
        }

        //update the date modified

        $quote_data['date_modified'] = date("Y-m-d H:i:s");

        return $quote_data;
    }

    /**
     * Delete Quote at database.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int $quote_id Quote ID.
     * @return boolean true or false .
     */
    public function delete_quote($quote_id)
    {
        try
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'custom_order_quote';
            $wpdb->delete(
                "{$table_name}",
                [ 'quote_id' => $quote_id ],
                [ '%d' ]
            );
            return true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }

    }

}