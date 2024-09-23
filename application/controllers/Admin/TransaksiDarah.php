<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiDarah extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('email');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/transaksi-darah');
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('StockDarah_model', 'StokDarah');
        $this->load->model('StockDarahLog_model', 'StokDarahLog');
        $this->load->model('TransaksiDarah_model', 'TransaksiDarah');
        $this->load->model('PermintaanDarah_model', 'PermintaanDarah');
        $this->load->model('Pengguna_model', 'Pengguna');
    }

    public function index()
    {
        $data['title'] = 'Data Transaksi Darah';
        $data['content'] = $this->load->view('backend/admin/transaksi_darah/index', [], TRUE);
        $data['additional_css'] = $this->load->view('backend/admin/transaksi_darah/css', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/transaksi_darah/js', [], TRUE);
        $data['modal_costum'] = $this->load->view('templates/backend/modal', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }

    public function getTransaksiDarah()
    {
        $list = $this->TransaksiDarah->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $transaksi) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d-m-Y', strtotime($transaksi->tanggal_transaksi));
            $row[] = $transaksi->jumlah . ' Kantong';
            $row[] = $transaksi->jenis_transaksi;
            $row[] = $transaksi->catatan ? $transaksi->catatan : 'Tidak ada catatan';
            $row[] = '<button class="btn btn-danger btn-sm" id="btnDelete" data-id="' . $transaksi->id . '">Hapus</button>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->TransaksiDarah->count_all(),
            "recordsFiltered" => $this->TransaksiDarah->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function deleteTransaksiDarah()
    {
        $id = $this->input->post('id');
        $deleted = $this->TransaksiDarah->delete($id);

        if ($deleted) {
            $response = array('status' => true, 'message' => 'Data transaksi darah telah dihapus.');
        } else {
            $response = array('status' => false, 'message' => 'Data transaksi darah gagal dihapus.');
        }

        echo json_encode($response);
    }
}

/* End of file TransaksiDarah.php and path \application\controllers\admin\TransaksiDarah.php */
