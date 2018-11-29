<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote_request extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');

        $this->load->model("quoterequest_model");

    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $quote_id = $this->input->get('quote_id');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();

        $quote = $this->quoterequest_model->get_quote($quote_id);
        if($quote)
        {
            $data['quote'] = $quote;
        }
        else
        {
            redirect( base_url(''), 'refresh');
        }

        $data['content'] = 'Pages/quote_request_detail';
        $data['menu'] = 'quote_request';
        $this->load->view('Layouts/master', $data);

    }

    public function edit()
    {
        $user_id = $this->session->userdata('user_id');

        $quote_id = $this->input->get('quote_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request/edit') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }


        $data = array();


        $data['content'] = 'Pages/quote_request_edit';

        $quote = $this->quoterequest_model->get_quote($quote_id);
        if($quote)
        {
            $data['quote'] = $quote;
        }
        else
        {
            redirect( base_url(''), 'refresh');
        }

        $data['menu'] = 'quote_request';

        $this->load->view('Layouts/master', $data);

    }


    public function update()
    {
        $quote_id = $this->input->post('quote_id');

        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request/edit') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }


        if($this->input->post('submitQuote'))
        {
            $result = $this->quoterequest_model->submit_quote($quote_id);
            if(!$result)
            {
                $this->session->set_flashdata('error_msg', 'Update failed, please check the input or contact to our IT Support.' . $result );
            }
            else
            {
                $this->session->set_flashdata('success_msg', 'The quote is created successfully !!!' );

            }
        }
        else
        {
            $this->session->set_flashdata('error_msg', 'Update failed, please try again or contact to our Admin at Kokoda Caravans.' );
        }


        redirect( base_url('quote_request/edit') .'?quote_id='. $quote_id, 'refresh');

    }


    public function send_email_customer()
    {
        $user_id = $this->session->userdata('user_id');
        $quote_id = $this->input->post('quote_id');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $quote_obj = $this->quoterequest_model->get_quote($quote_id,'object');


        try
        {

            //call the quote object from wordpress plugin
            if ( ! class_exists( 'Quote' ) )
            {
                require_once(__DIR__.'/../../../wp-load.php');
                require_once(KOKODA_CUSTOM_ORDER_PLUGIN_URL.'includes/models/quote.php');
            }
            $wp_quote = new Quote();

            $wp_quote->send_new_quote_email_to_customer($quote_obj);

            return true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }

    }

    public function send_email_dealer()
    {
        $user_id = $this->session->userdata('user_id');
        $quote_id = $this->input->post('quote_id');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $quote_obj = $this->quoterequest_model->get_quote($quote_id,'object');


        try
        {
            //call the quote object from wordpress plugin
            if ( ! class_exists( 'Quote' ) )
            {
                require_once(__DIR__.'/../../../wp-load.php');
                require_once(KOKODA_CUSTOM_ORDER_PLUGIN_URL.'includes/models/quote.php');
            }

            $wp_quote = new Quote();
            $wp_quote->send_new_quote_email_to_dealer($quote_obj);

            return true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }

    }
}