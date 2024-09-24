<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ZyAuth');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/dashboard');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data["additional_css"] = $this->load->view('backend/admin/dashboard/css', [], TRUE);
        $data['content'] = $this->load->view('backend/admin/dashboard/index', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/dashboard/js', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }

    public function get_data()
    {
        $data = [
            'totalBloodStock' => $this->get_total_stock(),
            'donorsThisMonth' => $this->get_donors_this_month(),
            'pendingRequests' => $this->get_pending_requests(),
            'transactionsThisMonth' => $this->get_transactions_this_month(),
            'bloodStockByGroup' => $this->get_stock_by_group(),
            'donationUsageTrend' => $this->get_donation_usage_trend()
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_total_stock()
    {
        $this->db->select_sum('jumlah');
        $query = $this->db->get('stok_darah');
        return $query->row()->jumlah;
    }

    public function get_stock_by_group()
    {
        $this->db->select('golongan_darah.golongan_darah, SUM(stok_darah.jumlah) as jumlah');
        $this->db->from('stok_darah');
        $this->db->join('golongan_darah', 'golongan_darah.id = stok_darah.golongan_darah');
        $this->db->group_by('golongan_darah.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_donors_this_month()
    {
        $this->db->where('MONTH(tanggal_donor_terakhir)', date('m'));
        $this->db->where('YEAR(tanggal_donor_terakhir)', date('Y'));
        return $this->db->count_all_results('donor');
    }

    public function get_pending_requests()
    {
        $this->db->where('status', 'Menunggu');
        return $this->db->count_all_results('permintaan_darah');
    }

    public function get_transactions_this_month()
    {
        $this->db->where('MONTH(tanggal_transaksi)', date('m'));
        $this->db->where('YEAR(tanggal_transaksi)', date('Y'));
        return $this->db->count_all_results('transaksi_darah');
    }

    public function get_donation_usage_trend()
    {
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(tanggal_transaksi, '%b') as bulan,
                SUM(CASE WHEN jenis_transaksi = 'Donasi' THEN jumlah ELSE 0 END) as donasi,
                SUM(CASE WHEN jenis_transaksi = 'Penggunaan' THEN jumlah ELSE 0 END) as penggunaan
            FROM transaksi_darah
            WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY YEAR(tanggal_transaksi), MONTH(tanggal_transaksi)
            ORDER BY YEAR(tanggal_transaksi), MONTH(tanggal_transaksi)
        ");
        return $query->result();
    }
}

/* End of file Dashboard.php and path \application\controllers\Admin\Dashboard.php */
