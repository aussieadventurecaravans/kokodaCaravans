<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote_model extends CI_Model
{

    const QUOTE_TABLE = "quotes";

    const WP_QUOTE_TABLE = "wp_custom_order_quote";


    public function __construct()
    {
        parent::__construct();
    }


    public function get_quotes()
    {
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        //Load  database to load quote table
        $custom_order_db = $this->db;

        $custom_order_db->select('*');
        $custom_order_db->order_by('quote_id', 'DESC');
        if($user_role == 'admin')
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE);
        }
        else
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE, array('dealer_email' => $user_email));
        }

        $custom_order_db->close();

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

        //Load  database to load quote table
        $custom_order_db = $this->db;

        if($user_role == 'admin')
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE, array('quote_id'=>$id));
        }
        else
        {
            $query = $custom_order_db->get_where(self::QUOTE_TABLE, array('quote_id'=>$id,'dealer_email' => $user_email));
        }


        if($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {

            return false;
        }
    }


    public function delete_quote($id)
    {
        $user_email = $this->session->userdata('user_email');
        $user_role = $this->session->userdata('user_role');

        //Load  database to load quote table
        $custom_order_db = $this->db;

        if($user_role == 'admin')
        {
            $query = $custom_order_db->delete(self::QUOTE_TABLE, array('quote_id'=>$id));
        }
        else
        {
            $query = $custom_order_db->delete(self::QUOTE_TABLE, array('quote_id'=>$id,'dealer_email' => $user_email));
        }

        $custom_order_db->close();

        return $query;
    }

    public function update_quote($quote_id,$data)
    {
        if(!is_array($data) || !isset($quote_id))
        {
            return false;
        }

        //Load  database to load quote table
        $custom_order_db = $this->db;

        $custom_order_db->trans_start();

        $custom_order_db->update(self::QUOTE_TABLE, $data,  array('quote_id' => $quote_id));

        $custom_order_db->trans_complete();

        $result =  $custom_order_db->trans_status();

        $custom_order_db->close();

        return $result;
    }


    public function place_order($quote_id)
    {

        $status = array('status' => 'In Order');

        $result = $this->setQuoteStatus($status,$quote_id);

        $wp_result = $this->setWPQuoteStatus($status,$quote_id);

        if($wp_result != true)
        {
            return $wp_result;
        }
        if($result === true )
        {
            //copy the quote detail and add it to the custom_order table
            $quote = $this->get_quote($quote_id);

            $result = $this->db->insert('custom_orders',$quote,true);

        }

        $this->db->close();

        return $result;
    }


    public function setQuoteStatus($status,$quote_id)
    {
        if(is_array($status) && isset($quote_id))
        {
            //Load  database to load quote table
            $custom_order_db = $this->db;

            $custom_order_db->trans_start();

            $custom_order_db->update(self::QUOTE_TABLE, $status, array('quote_id' => $quote_id));

            $custom_order_db->trans_complete();

            $result =  $custom_order_db->trans_status();

            return $result;
        }
        else
        {
            return false;
        }

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
