<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockDarah_model extends CI_Model
{
    private $table = 'stok_darah';
    private $table2 = 'golongan_darah';
    private $table3 = 'stok_darah_log';
    private $column_order = array(null, 'golongan_darah', 'jumlah', 'tanggal_exp');
    private $column_search = array('gd', 'jumlah_stok');
    private $order = array('id' => 'asc');
    private $orderLog = array('idGol' => 'asc');

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

    public function get_stok_darah_log_datatables()
    {
        $this->_get_custom_query();

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
        } else if (isset($this->orderLog)) {
            $orderLog = $this->orderLog;
            $this->db->order_by(key($orderLog), $orderLog[key($orderLog)]);
        }

        // Pagination
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_stok_darah_by_golongan_datatables($nama_golongan)
    {
        $this->_get_custom_query_by_golongan($nama_golongan);

        $searchColumn = array($this->table2 . '.golongan_darah', $this->table . '.jumlah', $this->table . '.tanggal_exp');

        // Search
        $search_value = $this->input->post('search')['value'] ?? null; // Cek apakah ada nilai search
        if (!empty($search_value)) {
            foreach ($searchColumn as $item) {
                $this->db->or_like($item, $search_value);
            }
        }

        // Ordering
        if (isset($_POST['order']) && is_array($_POST['order']) && isset($_POST['order'][0]['column']) && isset($_POST['order'][0]['dir'])) {
            $column_index = $_POST['order'][0]['column']; // Index kolom
            $order_direction = $_POST['order'][0]['dir']; // Arah (asc/desc)

            if (isset($this->column_order[$column_index])) {
                $this->db->order_by($this->column_order[$column_index], $order_direction);
            }
        } else {
            // Default order
            $order = array($this->table . '.id' => 'asc');
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // Pagination (Limit)
        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start'] ?? 0);
        }

        $query = $this->db->get();
        return $query->result();
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

    public function count_all_log()
    {
        $this->_get_custom_query();
        return $this->db->count_all_results();
    }

    public function count_filtered_log()
    {
        $this->_get_custom_query();

        // Search filter
        if ($this->input->post('search')['value']) {
            $search_value = $this->input->post('search')['value'];
            foreach ($this->column_search as $item) {
                $this->db->or_like($item, $search_value);
            }
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_by_golongan()
    {
        $this->_get_custom_query_by_golongan(''); // Memanggil query dengan nama_golongan kosong
        return $this->db->count_all_results();
    }

    public function count_filtered_by_golongan($nama_golongan)
    {
        $this->_get_custom_query_by_golongan($nama_golongan);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_by_golongan_darah($golongan_darah_id)
    {
        $this->db->from($this->table);
        $this->db->where('golongan_darah', $golongan_darah_id);
        return $this->db->get()->row();
    }

    public function get_latest_by_golongan_darah($golongan_darah_id)
    {
        $this->db->from($this->table);
        $this->db->where('golongan_darah', $golongan_darah_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function count_by_golongan($golongan_darah_id)
    {
        $this->db->from($this->table);
        $this->db->where('golongan_darah', $golongan_darah_id);
        return $this->db->count_all_results();
    }

    public function get_stok_darah_by_golongan($golongan_darah_id)
    {
        $this->db->from($this->table);
        $this->db->where('golongan_darah', $golongan_darah_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return !empty($result) ? $result : null;
    }

    public function get_stok_darah_by_nama_golongan($golongan_darah_nama)
    {
        $this->db->from($this->table);
        $this->db->join($this->table2, $this->table2 . '.id = ' . $this->table . '.golongan_darah');
        $this->db->where($this->table2 . '.golongan_darah', $golongan_darah_nama);
        $query = $this->db->get();
        $result = $query->result_array();
        return !empty($result) ? $result : null;
    }

    public function hitung_jumlah_berdasarkan_golongan($golongan_darah_id)
    {
        $this->db->select_sum('jumlah');
        $this->db->where('golongan_darah', $golongan_darah_id);
        $query = $this->db->get($this->table);
        $result = $query->row();
        return $result->jumlah ?? 0;
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

    private function _get_custom_query()
    {
        $this->db->select('g.id AS idGol, g.golongan_darah AS gd, COALESCE(SUM(s.jumlah), 0) AS jml_stok');
        $this->db->from($this->table2 . ' g');
        $this->db->join($this->table . ' s', 'g.id = s.golongan_darah', 'left');
        $this->db->group_by('g.golongan_darah');
    }


    private function _get_custom_query_by_golongan($nama_golongan)
    {
        $this->db->select($this->table . '.*', $this->table2 . '.golongan_darah AS namaGol');
        $this->db->from($this->table);
        $this->db->join($this->table2, $this->table2 . '.id' . '=' . $this->table . '.golongan_darah');
        $this->db->where($this->table2 . '.golongan_darah', $nama_golongan);
    }
}
/* End of file StockDarah_model.php and path \application\models\StockDarah_model.php */
