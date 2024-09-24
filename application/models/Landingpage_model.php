<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landingpage_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_blood_stocks()
    {
        $this->db->select('golongan_darah.id, golongan_darah.golongan_darah, COALESCE(SUM(stok_darah.jumlah), 0) as total');
        $this->db->from('golongan_darah');
        $this->db->join('stok_darah', 'golongan_darah.id = stok_darah.golongan_darah AND stok_darah.tanggal_exp > CURDATE()', 'left');
        $this->db->group_by('golongan_darah.id, golongan_darah.golongan_darah');
        $this->db->order_by('golongan_darah.id');

        return $this->db->get()->result();
    }

    public function get_monthly_donations()
    {
        $this->db->select('MONTH(tanggal_transaksi) as month, COUNT(*) as count');
        $this->db->from('transaksi_darah');
        $this->db->where('jenis_transaksi', 'Donasi');
        $this->db->where('YEAR(tanggal_transaksi)', date('Y'));
        $this->db->group_by('MONTH(tanggal_transaksi)');
        $this->db->order_by('MONTH(tanggal_transaksi)');

        return $this->db->get()->result();
    }
}


/* End of file Landingpage_model.php and path \application\models\Landingpage_model.php */
