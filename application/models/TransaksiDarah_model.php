<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiDarah_model extends CI_Model
{
    private $table = 'transaksi_darah';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select()
    {
        return $this->db->get($this->table)->result();
    }

    public function simpan($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }
}


/* End of file TransaksiDarah_model.php and path \application\models\TransaksiDarah_model.php */
