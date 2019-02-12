<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model("quote_model");
        $this->load->model("user_model");
        $this->load->model("dealer_model");


    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $quote_id = $this->input->get('quote_id');
        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $data = array();

        $quote = $this->quote_model->get_quote($quote_id);
        if($quote)
        {
            $data['quote'] = $quote;
        }
        else
        {
            redirect( base_url(''), 'refresh');
        }

        $data['content'] = 'Pages/quote_detail';

        $data['menu'] = 'quotes';

        $this->load->view('Layouts/master', $data);

    }


    public function edit()
    {
        $user_id = $this->session->userdata('user_id');

        $quote_id = $this->input->get('quote_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote/edit') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }


        $data = array();


        $data['content'] = 'Pages/quote_edit';

        $quote = $this->quote_model->get_quote($quote_id);
        if($quote)
        {
            $data['quote'] = $quote;
        }
        else
        {
            redirect( base_url(''), 'refresh');
        }
        $data['menu'] = 'quotes';

        $this->load->view('Layouts/master', $data);

    }

    public function update()
    {
        $quote_id = $this->input->post('quote_id');

        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote/edit') . '?quote_id=' . $quote_id );
            redirect( base_url('user/login'), 'refresh');
        }

        $data=array(
            'status'=>$this->input->post('status'),
            'customer_first_name'=>$this->input->post('customer_first_name'),
            'customer_last_name'=>$this->input->post('customer_last_name'),
            'customer_address'=>$this->input->post('customer_address'),
            'customer_city'=>$this->input->post('customer_city'),
            'customer_postcode'=>$this->input->post('customer_postcode'),
            'customer_state'=>strtolower($this->input->post('customer_state')),
            'customer_email'=>$this->input->post('customer_email'),
            'customer_phone'=>$this->input->post('customer_phone'),
            'product_name'=>$this->input->post('product_name')
        );

        if($this->input->post('updateQuote'))
        {
            $result = $this->quote_model->update_quote($quote_id,$data);
            if(!$result)
            {
                $this->session->set_flashdata('error_msg', 'Update failed, please try again or contact to our Admin at Kokoda Caravans.' );
            }
            else
            {
                $this->session->set_flashdata('success_msg', 'Quote update successfully !!!' );

            }
        }

        if($this->input->post('placeOrder'))
        {
            $result = $this->quote_model->place_order($quote_id);
            if(!$result)
            {
                $this->session->set_flashdata('error_msg', 'Update failed, please try again or contact to our Admin at Kokoda Caravans.' );
            }
            else
            {
                $this->session->set_flashdata('success_msg', 'The order is created successfully !!!' );

            }
        }


        redirect( base_url('quote/edit') .'?quote_id='. $quote_id, 'refresh');

    }


    public function new_quote()
    {
        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote/new'));
            redirect( base_url('user/login'), 'refresh');
        }


        //load products model from Wordpress website
        $this->load->model('wp_product_model');


        $caravans =  $this->wp_product_model->get_products();

        //get model title
        $products_title = array();
        if(isset($caravans) && $caravans !== false)
        {
            foreach ($caravans as $caravan )
            {
                $products_title[$caravan->ID] =  $caravan->post_title;
            }
        }



        //Exterior Custom Options  of all Models
        $exterior_custom_options= array();
        foreach ($caravans as $caravan)
        {
            $exterior_custom_options[$caravan->ID] = get_field('custom_exterior',$caravan->ID);
        }


        //Exterior Custom Options  of all Models
        $interior_custom_options= array();
        foreach ($caravans as $caravan)
        {
            $interior_custom_options[$caravan->ID] = get_field('custom_interior',$caravan->ID);
        }

        $add_on_accessories = array();

        $data = array();

        $data['content'] = 'Pages/quote_new';

        $data['products_title'] = $products_title;

        $data['exterior_custom_options'] = $exterior_custom_options;

        $data['interior_custom_options'] = $interior_custom_options;

        $data['add_on_accessories'] = $add_on_accessories;

        $this->load->view('Layouts/master', $data);
    }


    public function insert()
    {
        $user_id = $this->session->userdata('user_id');

        if(!$user_id)
        {
            $this->session->set_userdata('referer_url',  base_url('quote/new'));
            redirect( base_url('user/login'), 'refresh');
        }

        //$quote_data = $this->input->post('quote_data');
        $user = $this->user_model->getUser();
        $user_email = $user->user_email;

        //check if user is the dealer.
        if($this->dealer_model->email_check($user_email))
        {

            // get dealer for detail
            $dealer_detail = $this->dealer_model->getDealerByEmail($user_email);

            //prepare custom quote detail for insert new quote
            if($this->input->post('quote_data'))
            {

               $data =[];

               $post_data = $this->input->post('quote_data');


                $data['customer_first_name'] = $post_data['customer']['customer_first_name'];
                $data['customer_last_name'] = $post_data['customer']['customer_last_name'];
                $data['customer_address'] = $post_data['customer']['customer_address'];
                $data['customer_city'] = $post_data['customer']['customer_city'];
                $data['customer_state'] = $post_data['customer']['customer_state'];
                $data['customer_email'] = $post_data['customer']['customer_email'];
                $data['customer_phone'] = $post_data['customer']['customer_phone'];



                $data['dealer_name'] = $dealer_detail->dealer_name;
                $data['dealer_email'] = $dealer_detail->dealer_email;
                $data['dealer_phone'] = $dealer_detail->dealer_phone;
                $data['dealer_city'] = $dealer_detail->dealer_city;
                $data['dealer_address'] = $dealer_detail->dealer_address;
                $data['dealer_postcode'] = $dealer_detail->dealer_postcode;
                $data['dealer_state'] = $dealer_detail->dealer_state;


                $data['product_name'] = $post_data['product'];

               $custom_options = [
                   'upholstery_options' => $post_data['upholstery_options'],
                   'interior_options' => $post_data['interior_options'],
                    'exterior_options' => $post_data['exterior_options']
               ];

               $data['custom_options'] = serialize($custom_options) ;

               if($post_data['add_on_accessories'])
               {
                   $data['add_on_accessories'] = serialize($post_data['add_on_accessories']);
               }
               else
               {
                   $data['add_on_accessories'] = '';
               }



                echo json_encode($data);
/*
                if($this->quote_model->addNewQuote($data))
                {
                    echo 'true';
                }
                else
                {

                    echo 'false';
                }*/

            }

        }
        else
        {
            echo 'false';
        }


    }

}