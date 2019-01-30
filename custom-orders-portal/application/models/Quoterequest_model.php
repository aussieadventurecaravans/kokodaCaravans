<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quoterequest_model extends CI_Model
{

    const QUOTES_TABLE = "quotes";

    const WP_QUOTE_TABLE = "wp_custom_order_quote";

    public function __construct()
    {
        parent::__construct();
    }


    public function get_quote_requests()
    {
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        //Load Wordpress database to load wp_custom_order_quote table
        $wordpress_db = $this->load->database('wordpress', TRUE);

        $wordpress_db->select('*');
        $wordpress_db->order_by('quote_id', 'DESC');
        if($user_role == 'admin')
        {
            $query = $wordpress_db->get_where(self::WP_QUOTE_TABLE);
        }
        else
        {
            $query = $wordpress_db->get_where(self::WP_QUOTE_TABLE, array('dealer_email' => $user_email));
        }

        $wordpress_db->close();

        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else
         {

            return false;
        }
    }

    public function get_quote_request($request_id,$type = 'array')
    {

        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        $wordpress_db = $this->load->database('wordpress', TRUE);

        if($user_role == 'admin')
        {
            $query = $wordpress_db->get_where(self::WP_QUOTE_TABLE, array('quote_id'=>$request_id));
        }
        else
        {
            $query = $wordpress_db->get_where(self::WP_QUOTE_TABLE, array('quote_id'=>$request_id,'dealer_email' => $user_email));
        }

        $wordpress_db->close();


        if($query->num_rows() > 0)
        {
            if($type == 'object')
            {
                return $query->row_object();
            }
            else
            {
                return $query->row_array();
            }

        }
        else
        {

            return false;
        }
    }


    public function submit_quote($request_id)
    {

        $status = array('status' => 'In Review');

        $result = $this->setWPQuoteStatus($status,$request_id);

        if ($result ===  true)
        {
            //copy the Web quote detail and add it to the table quote of custom order database
            $quote_request = $this->get_quote_request($request_id);

            //if this quote request was added from website
            if(isset($quote_request['quote_id']))
            {
                $quote_request['web_quote_id']  = $quote_request['quote_id'];
                unset($quote_request['quote_id']);
            }

            $custom_order_db = $this->db;

            $result = $custom_order_db->insert(self::QUOTES_TABLE,$quote_request);

            return $result;
        }

        return $result;
    }

    public function setWPQuoteStatus($status,$request_id)
    {
        if(is_array($status) && isset($request_id))
        {
            $wordpress_db = $this->load->database('wordpress', TRUE);

            $wordpress_db->trans_start();

            $wordpress_db->update(self::WP_QUOTE_TABLE, $status,  array('quote_id' => $request_id));

            $wordpress_db->trans_complete();

            $result = $wordpress_db->trans_status();

            $wordpress_db->close();

            return $result;

        }
        else
        {
            return false;
        }

    }


}
