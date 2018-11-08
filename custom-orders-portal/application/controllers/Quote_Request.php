<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote_Request extends CI_Controller {

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
}