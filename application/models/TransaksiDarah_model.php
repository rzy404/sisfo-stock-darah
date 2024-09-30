<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiDarah_model extends CI_Model
{
    private $table = 'transaksi_darah';
    private $column_order = array(null, 'tanggal_transaksi', 'jumlah', 'jenis_transaksi', 'catatan'); // Kolom yang bisa diurutkan
    private $column_search = array('tanggal_transaksi', 'jumlah', 'catatan', 'jenis_transaksi'); // Kolom yang bisa dicari
    private $order = array('id' => 'asc'); // Default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        // Pencarian
        $search = $this->input->post('search')['value'];
        if ($search) {
            foreach ($this->column_search as $item) {
                if ($item) {
                    $this->db->or_like($item, $search);
                }
            }
        }

        // Urutan
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
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

    public function get_filtered_transaksi($filters)
    {
        $this->db->select('*');
        $this->db->from('transaksi_darah');

        if (!empty($filters['tanggal_dari'])) {
            $this->db->where('tanggal_transaksi >=', $filters['tanggal_dari']);
        }
        if (!empty($filters['tanggal_sampai'])) {
            $this->db->where('tanggal_transaksi <=', $filters['tanggal_sampai']);
        }
        if (!empty($filters['jenis_transaksi'])) {
            $this->db->where('jenis_transaksi', $filters['jenis_transaksi']);
        }

        $query = $this->db->get();
        return $query->result();
    }
}


/* End of file TransaksiDarah_model.php and path \application\models\TransaksiDarah_model.php */
