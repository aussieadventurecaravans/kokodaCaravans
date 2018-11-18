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


    public function get_quotes()
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

    public function get_quote($id)
    {

        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        $wordpress_db = $this->load->database('wordpress', TRUE);

        if($user_role == 'admin')
        {
            $query = $wordpress_db->get_where(self::WP_QUOTE_TABLE, array('quote_id'=>$id));
        }
        else
        {
            $query = $wordpress_db->get_where(self::WP_QUOTE_TABLE, array('quote_id'=>$id,'dealer_email' => $user_email));
        }

        $wordpress_db->close();


        if($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {

            return false;
        }
    }


    public function submit_quote($id)
    {

        $status = array('status' => 'In Review');

        $result = $this->setWPQuoteStatus($status,$id);

        if ($result ===  true)
        {
            //copy the quote detail and add it to the custom_order table
            $quote = $this->get_quote($id);

            //if this quote request was added from website
            if(isset($quote['quote_id']))
            {
                $quote['web_quote_id']  = $quote['quote_id'];
                unset($quote['quote_id']);
            }

            $custom_order_db = $this->db;

            $result = $custom_order_db->insert(self::QUOTES_TABLE,$quote);

            return $result;
        }

        return $result;
    }

    public function setWPQuoteStatus($status,$quote_id)
    {
        if(is_array($status) && isset($quote_id))
        {
            $wordpress_db = $this->load->database('wordpress', TRUE);

            $wordpress_db->trans_start();

            $wordpress_db->update(self::WP_QUOTE_TABLE, $status,  array('quote_id' => $quote_id));

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
