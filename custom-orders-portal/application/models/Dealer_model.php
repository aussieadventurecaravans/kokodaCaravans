<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dealer_model extends CI_Model
{
    const DEALERS_TABLE = "dealer";

    public function __construct()
    {
        parent::__construct();
    }

    public function register_dealer($user)
    {

        $this->db->insert(self::DEALERS_TABLE, $user);

    }

    public function getDealerByEmail($email)
    {

        if(isset($email))
        {
            $sql = "SELECT * FROM " . self::DEALERS_TABLE . " WHERE dealer_email = ? ";

            $query = $this->db->query($sql,array($email));

            $dealer_obj = $query->row();

            if(isset($dealer_obj))
            {
                return $dealer_obj;
            }
            else
            {
                return false;
            }
        }
    }

    public function getDealerByID($dealer_id)
    {

        if(isset($dealer_id))
        {
            $sql = "SELECT * FROM " . self::DEALERS_TABLE . " WHERE dealer_id = ? ";

            $query = $this->db->query($sql,array($dealer_id));

            $dealer_obj = $query->row();

            if(isset($dealer_obj))
            {
                return $dealer_obj;
            }
            else
            {
                return false;
            }
        }
    }


    public function email_check($email)
    {

        $this->db->select('*');
        $this->db->from(self::DEALERS_TABLE);
        $this->db->where('dealer_email',$email);
        $query = $this->db->get();

        if($query->num_rows()>0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

}