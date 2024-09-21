<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockDarahLog_model extends CI_Model
{
    private $table = 'stok_darah_log';
    private $table2 = 'stok_darah';

    public function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('stok_darah', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function simpan($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($stok_darah_id, $data)
    {
        $this->db->where('stok_darah', $stok_darah_id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }
}


/* End of file StockDarahLog_model.php and path \application\models\StockDarahLog_model.php */
