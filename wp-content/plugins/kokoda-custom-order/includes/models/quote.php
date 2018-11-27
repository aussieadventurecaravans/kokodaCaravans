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
    public $add_on_accessories = '';

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
    public $customer_first_name = '';
    /**
     * @var string
     */
    public $customer_last_name = '';
    /**
     * @var string
     */
    public $customer_address = '';
    /**
     * @var string
     */
    public $customer_city = '';
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
    public function __construct( $quote = NULL )
    {
        if($quote != NULL)
        {
            foreach ( get_object_vars( $quote ) as $key => $value )
                $this->$key = $value;
        }

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
     * Insert New Quote to.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param $data data for insert.
     * @return Quote|false Quote object, false otherwise.
     */
    public static function new_quote( $data )
    {

        global $wpdb;
        try
        {
            $table_name = $wpdb->prefix . 'custom_order_quote';


            $quote_data = self::prepareDataForInsert($data);

            $wpdb->insert(
                $table_name,
                $quote_data
            );

            $quote_id = $wpdb->insert_id;

            $_quote = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE quote_id = %d LIMIT 1", $quote_id ) );
            if ( ! $_quote )
                return false;


            self::send_new_quote_email_to_dealer($_quote);
            self::send_new_quote_email_to_customer($_quote);

            return new Quote( $_quote );
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
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
     * @param array $data  for insert.
     * @return array $quote_data.
     */
    public function prepareDataForInsert($data)
    {

        $quote_data =  array();
        $columns= array('product_name','custom_options','add_on_options','payment_method',
            'customer_first_name','customer_last_name','customer_address','customer_city','customer_postcode','customer_state','customer_email','customer_phone',
            'dealer_id','dealer_name','dealer_phone','dealer_email','dealer_address','dealer_city','dealer_state','dealer_postcode',
            'date_created','date_modified');

        //prepare the custom options(panel/checkerplate) from custom order
        $custom_options = array();
        $custom_options_price = 0;
        if(isset($data['caravan_options']['panel']))
        {
            $custom_options['panel']['value'] = $data['caravan_options']['panel']['value'];
            if(isset($data['caravan_options']['panel']['price']))
            {
                $custom_options['panel']['price'] = $data['caravan_options']['panel']['price'];
                $custom_options_price +=  $custom_options['panel']['price'];

            }
        }
        if(isset($data['caravan_options']['checker_plate']))
        {
            $custom_options['checker_plate']['value'] = $data['caravan_options']['checker_plate']['value'];
            if(isset($data['caravan_options']['checker_plate']['price']))
            {
                $custom_options['checker_plate']['price'] = $data['caravan_options']['checker_plate']['price'];
                $custom_options_price +=  $custom_options['checker_plate']['price'];
            }
        }
        $quote_data['custom_options'] = serialize($custom_options);


        //prepare custom accessories for quote
         $quote_data['add_on_accessories'] = serialize($data['accessories']);



        //prepare for product  details
        $caravan = get_post($data['caravan']);
        $quote_data['product_id'] = $data['caravan'];
        $quote_data['product_name'] = $caravan->post_title;

        //prepare for customer details
        $customer = $data['customer'];
        foreach ($customer as $key => $value)
        {
            if(in_array($key,$columns))
            {
                $quote_data[$key] = $value;
            }
        }

        //prepare for dealer details
        $dealer = $data["dealer"];
        foreach ($dealer as $key => $value)
        {
            if(in_array($key,$columns))
            {
                $quote_data[$key] = $value;
            }
        }

        //prepare data for finance detail
        if($data['customer']['payment_method'] == 'loan')
        {
            $quote_data['apply_loan_option'] = $data['finance']['apply_loan_option'];
            $quote_data['has_loan'] = TRUE;

            if($quote_data['apply_loan_option'] == 'apply later')
            {
                $quote_data['loan_status'] = 'none';
            }
            if($quote_data['apply_loan_option'] == 'self arrange')
            {
                $quote_data['loan_status'] = 'review';
            }


            $quote_data['loan_detail'] = serialize($data['finance']);

        }
        else
        {
            $quote_data['has_loan'] = FALSE;
            $quote_data['apply_loan_option'] = $data['finance']['apply_loan_option'];
            $quote_data['loan_status'] = 'none';
            $quote_data['loan_detail'] = '';

        }
        //prepare data for price detail
        $quote_data['product_cost'] = $data['product_price'];
        $quote_data['orc_cost'] = $data['orc_price'];
        $quote_data['add_on_cost'] = $data['accessories_price'];
        $quote_data['total_cost'] = $quote_data['product_cost'] + $quote_data['orc_cost'] + $quote_data['add_on_cost'] + $custom_options_price;


        //add the date modified and date created of quote
        date_default_timezone_set('Australia/Melbourne');
        $quote_data['date_modified'] = date("Y-m-d H:i:s");
        $quote_data['date_created'] = date("Y-m-d H:i:s");


        return $quote_data;
    }

    /**
     * Update Quote instance.
     *
     *
     * @param  array $data update.
     * @return array $quote_data.
     */
    public function prepareDataForUpdate($data)
    {
        $quote_data =  array();
        $columns= array('product_name','custom_options','add_on_accessories','product_cost','orc_cost','total_cost','status',
                        'add_on_cost','payment_method','customer_first_name','customer_last_name','customer_address','customer_city','customer_postcode','customer_state',
                        'apply_loan_option','loan_status',
                        'customer_email','customer_phone','date_created','date_modified');


        foreach ($data as $key => $value)
        {
            if(in_array($key,$columns))
            {
                $quote_data[$key] = $value;
            }
        }

        //update total price
        $quote_data['total_cost'] =  $quote_data['orc_cost'] + $quote_data['add_on_cost'] + $quote_data['product_cost'];


        //update the date modified
        date_default_timezone_set('Australia/Melbourne');
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


    /**
     * send new quote email to dealers/admin.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param Object $_quote query row result.
     * @param string $custom_email custom email address
     * @return boolean true or false.
     */
    public function send_new_quote_email_to_dealer($_quote,$custom_email = Null)
    {
        try
        {
            require_once(KOKODA_CUSTOM_ORDER_PLUGIN_URL.'/assets/Mail/WP_Mail.php');


            $subject = "You have new Quote #" . $_quote->quote_id ;
            if($custom_email == Null)
            {
                if(get_option('custom_order_development-mode') == false)
                {

                    $receiver = array(
                        $_quote->dealer_email,
                        get_option('admin_email')
                    );
                }
                else
                {
                    $receiver = array(
                        get_option('admin_email')
                    );
                }
            }
            else
            {
                $receiver = array(
                    $custom_email
                );
            }

            $pdf_file = self::generate_pdf_summary_file($_quote);

            return $email = WP_Mail::init()
                ->to($receiver)
                ->subject($subject)
                ->attach(array($pdf_file))
                ->template(KOKODA_CUSTOM_ORDER_PLUGIN_URL .'/template/email/new_quote_to_dealer_email.php',
                    ['_quote' => $_quote]
                )
                ->send();

        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }

    }



    /**
     * send new quote email to dealers/admin.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param Object _quote query row result.
     * @param string $custom_email custom email address
     * @return boolean true or false.
     */
    public function send_new_quote_email_to_customer($_quote,$custom_email = Null)
    {
        try
        {
            require_once(KOKODA_CUSTOM_ORDER_PLUGIN_URL.'/assets/Mail/WP_Mail.php');

            $subject = "Your quote is submited successfully";
            if($custom_email == Null)
            {
                $receiver = array(
                    $_quote->customer_email
                );
            }
            else
            {
                $receiver = array(
                    $custom_email
                );
            }

            $pdf_file = self::generate_pdf_summary_file($_quote);

            return $email = WP_Mail::init()
                ->to( $receiver)
                ->subject($subject)
                ->attach(array($pdf_file))
                ->template(KOKODA_CUSTOM_ORDER_PLUGIN_URL .'/template/email/new_quote_to_customer_email.php',
                    ['_quote' => $_quote]
                )
                ->send();

        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }

    }



    /**
     * send new quote email to dealers/admin and customer.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int $quote_id Quote ID.
     * @return boolean true or false.
     */
    public function send_update_quote_email($quote_id)
    {
        try
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'custom_order_quote';

            $quote_id = (int) $quote_id;
            if ( ! $quote_id )
            {
                return false;
            }

            $_quote = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE quote_id = %d LIMIT 1", $quote_id ) );
            if ( ! $_quote )
                return false;


            return true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }

    }


    /**
     * Genrate pdf quote summary file
     *
     *
     * @param Object  query row result object.
     * @return boolean true or false.
     */
    public function generate_pdf_summary_file($_quote)
    {

        try
        {
            if (!file_exists(WP_CONTENT_DIR. '/uploads/custom_order/email_attach'))
            {
                mkdir(WP_CONTENT_DIR. '/uploads/custom_order/email_attach', 0777, true);
            }

            set_query_var('quote', $_quote);

            require( KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'template/email/attach/summary-report-template.php' );

            return WP_CONTENT_DIR. '/uploads/custom_order/email_attach/quote_summary_' . $_quote->quote_id .'.pdf';

        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
    }


}