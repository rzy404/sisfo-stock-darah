<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    protected $users = 'pengguna';
    protected $biodata = 'biodata_pengguna';

    public function __construct()
    {
        parent::__construct();
    }

    public function login($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get($this->users, 1);
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function register($data)
    {
        $this->db->insert($this->users, $data);
        return $this->db->insert_id() ? true : false;
    }

    public function insert_biodata($data)
    {
        return $this->db->insert($this->biodata, $data);
    }

    public function email_exists($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get($this->users);
        return $query->num_rows() > 0;
    }

    public function nik_exists($nik)
    {
        $this->db->where('nik', $nik);
        $query = $this->db->get($this->biodata);
        return $query->num_rows() > 0;
    }
}


/* End of file Auth_model.php and path \application\models\Auth\Auth_model.php */
