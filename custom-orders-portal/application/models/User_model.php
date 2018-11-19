<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    const USERS_TABLE = "user";

    public function __construct()
    {
        parent::__construct();
    }

    public function register_user($user)
    {

        $this->db->insert(self::USERS_TABLE, $user);

    }

    public function login_user($user_login){

        $this->db->select('*');
        $this->db->from(self::USERS_TABLE);
        $this->db->where('user_email',$user_login['user_email']);
        $this->db->where('user_password',$user_login['user_password']);


       $query = $this->db->get();
       $this->db->close();

        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else{
            return false;
        }


    }
    public function email_check($email)
    {

        $this->db->select('*');
        $this->db->from(self::USERS_TABLE);
        $this->db->where('user_email',$email);
        $query=$this->db->get();

        if($query->num_rows()>0)
        {
            return false;
        }
        else
            {
            return true;
        }

    }

}