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
        $request_id = $this->input->get('request_id');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request') . '?request_id=' . $request_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();

        $quote_request = $this->quoterequest_model->get_quote_request($request_id);
        if($quote_request)
        {
            $data['quote_request'] = $quote_request;
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

        $request_id = $this->input->get('request_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request/edit') . '?request_id=' . $request_id );
            redirect( base_url('user/login'), 'refresh');
        }


        $data = array();


        $data['content'] = 'Pages/quote_request_edit';

        $quote_request = $this->quoterequest_model->get_quote_request($request_id);
        if($quote_request)
        {
            $data['quote_request'] = $quote_request;
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
        $request_id = $this->input->post('request_id');

        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request/edit') . '?request_id=' . $request_id );
            redirect( base_url('user/login'), 'refresh');
        }


        if($this->input->post('submitQuote'))
        {
            $result = $this->quoterequest_model->submit_to_quote($request_id);
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


        redirect( base_url('quote_request/edit') .'?request_id='. $request_id, 'refresh');

    }


    public function send_email_customer()
    {
        $user_id = $this->session->userdata('user_id');
        $request_id = $this->input->post('request_id');
        $custom_email = $this->input->post('custom_email');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request') . '?request_id=' . $request_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $request_object = $this->quoterequest_model->get_quote_request($request_id,'object');


        try
        {

            //call the quote object from wordpress plugin
            if ( ! class_exists( 'Quote' ) )
            {
                require_once(__DIR__.'/../../../wp-load.php');
                require_once(KOKODA_CUSTOM_ORDER_PLUGIN_URL.'includes/models/quote.php');
            }
            $wp_quote = new Quote();
            if(isset($custom_email) && !empty($custom_email))
            {
              $wp_quote->send_new_quote_email_to_customer($request_object,$custom_email);
            }
            else
            {
                $wp_quote->send_new_quote_email_to_customer($request_object);
            }

            echo true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            echo false;
        }

    }

    public function send_email_dealer()
    {
        $user_id = $this->session->userdata('user_id');
        $request_id = $this->input->post('request_id');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote_request') . '?request_id=' . $request_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $request_object = $this->quoterequest_model->get_quote_request($request_id,'object');


        try
        {
            //call the quote request object from wordpress plugin
            if ( ! class_exists( 'Quote' ) )
            {
                require_once(__DIR__.'/../../../wp-load.php');
                require_once(KOKODA_CUSTOM_ORDER_PLUGIN_URL.'includes/models/quote.php');
            }

            $wp_quote = new Quote();
            $wp_quote->send_new_quote_email_to_dealer($request_object);

            echo true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            echo false;
        }

    }
}