<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_darah_model extends CI_Model
{
    public $table = 'stok_darah';
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function select()
    {
        $query = $this->db->get($this->table);
        return $query->result();
    }
}


/* End of file Stok_darah_model.php and path \application\models\Stok_darah_model.php */
