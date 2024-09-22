<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendonor_model extends CI_Model
{
    private $column_order = array(null, 'golongan_darah', 'total_pendonor');
    private $column_search = array('golongan_darah', 'total_pendonor');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_pendonor_datatables()
    {
        $this->_get_custom_query_by_pendonor();

        // Search filter
        if ($this->input->post('search')['value']) {
            $search_value = $this->input->post('search')['value'];
            foreach ($this->column_search as $item) {
                $this->db->or_like($item, $search_value);
            }
        }

        // Ordering
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        }

        // Pagination
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        // Execute the main query
        $query = $this->db->get('(' . $this->_get_custom_query_by_pendonor()->get_compiled_select() . ') AS subquery');
        return $query->result();
    }

    public function count_all()
    {
        $this->db->reset_query();
        $this->db->select('COUNT(*) AS total');
        $this->db->from('golongan_darah gd');
        $this->db->join('stok_darah sd', 'gd.id = sd.golongan_darah', 'left');
        $this->db->join('transaksi_darah td', 'sd.id = td.stok_darah AND td.jenis_transaksi = "Donasi"', 'left');

        $query = $this->db->get();
        return $query->row()->total; // Return the total count
    }

    public function count_filtered()
    {
        $this->_get_custom_query_by_pendonor(); // Build the custom query for filtering
        return $this->db->get()->num_rows(); // Return the number of rows in the result set
    }

    public function get_detail_datatables($golongan)
    {
        $this->get_detail_pendonor_datatables($golongan);

        // Check if 'length' and 'start' are set in $_POST before using them
        $length = isset($_POST['length']) ? $_POST['length'] : -1;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_detail_pendonor_datatables($golongan)
    {
        $this->db->select('u.nama AS nama_pendonor, u.email AS email, p.tanggal_donor_terakhir AS tanggal_donor');
        $this->db->from('donor p');
        $this->db->join('pengguna u', 'p.pengguna = u.id', 'left');
        $this->db->join('golongan_darah gd', 'p.golongan_darah = gd.id', 'left');
        $this->db->where('gd.golongan_darah', $golongan);

        // Search
        if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
            $this->db->group_start(); // Open bracket
            $this->db->like('u.nama', $_POST['search']['value']);
            $this->db->or_like('u.email', $_POST['search']['value']);
            $this->db->group_end(); // Close bracket
        }

        // Ordering
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('u.nama', 'ASC');
        }
    }

    public function count_filtered_detail($golongan)
    {
        $this->get_detail_pendonor_datatables($golongan);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_detail()
    {
        $this->db->from('donor');
        return $this->db->count_all_results();
    }

    private function _get_custom_query_by_pendonor()
    {
        $this->db->reset_query();

        $this->db->select('gd.id AS idGolonganDarah,
                        gd.golongan_darah AS golongan_darah, 
                        COALESCE(SUM(td.jumlah), 0) AS total_pendonor');
        $this->db->from('golongan_darah gd');
        $this->db->join('stok_darah sd', 'gd.id = sd.golongan_darah', 'left');
        $this->db->join('transaksi_darah td', 'sd.id = td.stok_darah AND td.jenis_transaksi = "Donasi"', 'left');
        $this->db->group_by('gd.golongan_darah');

        return $this->db;
    }
}

/* End of file Pendonor_model.php and path \application\models\Pendonor_model.php */
