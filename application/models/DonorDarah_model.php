<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DonorDarah_model extends CI_Model
{
    private $table = 'donor';
    private $column_order = array(null, 'tanggal_donor_terakhir');
    private $column_search = array('tanggal_donor_terakhir');
    private $order = array('id' => 'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        // Search
        if ($this->input->post('search')['value']) {
            $search_value = $this->input->post('search')['value'];
            foreach ($this->column_search as $item) {
                $this->db->or_like($item, $search_value);
            }
        }

        // Ordering
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
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


    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
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

    public function isExistDonorDarahByTanggal($tanggal)
    {
        $this->db->from($this->table);
        $this->db->where('DATE(tanggal_donor_terakhir) =', $tanggal);
        $this->db->where('pengguna', $this->session->userdata('user_id'));
        $query = $this->db->get();
        return $query->num_rows();
    }
}


/* End of file DonorDarah_model.php and path \application\models\DonorDarah_model.php */
