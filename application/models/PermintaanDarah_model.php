<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PermintaanDarah_model extends CI_Model
{
    private $column_order = array(null, 'nama_pemohon', 'email', 'jumlah_dibutuhkan', 'nomor_telepon', 'status');
    private $column_search = array('nama_pemohon', 'email', 'jumlah_dibutuhkan', 'nomor_telepon', 'status');
    private $order = array('id' => 'asc'); // Default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->select('pd.id, pd.nama_pemohon, pd.email, pd.jumlah_dibutuhkan, pd.nomor_telepon, pd.status, pd.created_at, gd.golongan_darah');
        $this->db->from('permintaan_darah pd');
        $this->db->join('golongan_darah gd', 'pd.golongan_darah = gd.id');

        $i = 0;
        foreach ($this->column_search as $item) { // Loop over search columns
            if ($_POST['search']['value']) { // If there's a search input
                if ($i === 0) {
                    $this->db->group_start(); // Start a grouped where clause for multiple columns
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) { // Last iteration, close group
                    $this->db->group_end();
                }
            }
            $i++;
        }

        // Handle column ordering
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) { // Apply pagination
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('permintaan_darah');
        return $this->db->count_all_results();
    }

    public function select_where($id, $kolom)
    {
        $this->db->select($kolom);
        $this->db->where('id', $id); // Menggunakan 'id' sebagai kondisi
        $query = $this->db->get('permintaan_darah');

        // Mengembalikan satu baris data
        return $query->row();
    }


    public function update($where, $data)
    {
        $this->db->update('permintaan_darah', $data, $where);
        return $this->db->affected_rows() > 0;
    }
}


/* End of file PermintaanDarah_model.php and path \application\models\PermintaanDarah_model.php */
