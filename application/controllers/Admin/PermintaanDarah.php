<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PermintaanDarah extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/donor');
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('StockDarah_model', 'StokDarah');
        $this->load->model('StockDarahLog_model', 'StokDarahLog');
        $this->load->model('TransaksiDarah_model', 'TransaksiDarah');
        $this->load->model('PermintaanDarah_model', 'PermintaanDarah');
        $this->load->model('Pengguna_model', 'Pengguna');
    }

    public function index()
    {
        $data['title'] = 'Data Permintaan Darah';
        $data['content'] = $this->load->view('backend/admin/permintaan_darah/index', [], TRUE);
        $data['additional_css'] = $this->load->view('backend/admin/permintaan_darah/css', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/permintaan_darah/js', [], TRUE);
        $data['modal_costum'] = $this->load->view('templates/backend/modal', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }

    public function getPermintaanDarah()
    {
        $list = $this->PermintaanDarah->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $permintaan) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $permintaan->nama_pemohon;
            $row[] = $permintaan->email;
            $row[] = $permintaan->golongan_darah;
            $row[] = $permintaan->jumlah_dibutuhkan;
            $statusOptions = '
            <select class="form-control status-dropdown" data-id="' . $permintaan->id . '">
                <option value="Menunggu" ' . ($permintaan->status == 'Menunggu' ? 'selected' : '') . '>Menunggu</option>
                <option value="Disetujui" ' . ($permintaan->status == 'Disetujui' ? 'selected' : '') . '>Disetujui</option>
                <option value="Ditolak" ' . ($permintaan->status == 'Ditolak' ? 'selected' : '') . '>Ditolak</option>
            </select>';
            $row[] = $statusOptions;
            $row[] = '<button class="btn btn-info btn-sm" id="btnDetail" data-golongan="' . $permintaan->golongan_darah . '">Detail</button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->PermintaanDarah->count_all(),
            "recordsFiltered" => $this->PermintaanDarah->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function updateStatusPermintaan()
    {
        $id = $this->input->post('id');
        $new_status = $this->input->post('status');

        // Pastikan ID dan status dikirim
        if (!$id || !$new_status) {
            $response = array('status' => 'error', 'message' => 'Data tidak lengkap.');
            echo json_encode($response);
            return;
        }

        // Ambil status saat ini dari database
        $permintaan = $this->PermintaanDarah->select_where($id, 'status');

        // Cek apakah data ditemukan
        if (!$permintaan) {
            $response = array('status' => 'error', 'message' => 'Data tidak ditemukan.');
            echo json_encode($response);
            return;
        }

        // Cek apakah status saat ini bukan 'Menunggu'
        if ($permintaan->status != 'Menunggu') {
            $response = array('status' => 'error', 'message' => 'Status tidak bisa diubah setelah diperbarui.');
            echo json_encode($response);
            return;
        }

        // Update status jika status saat ini 'Menunggu'
        $update = $this->PermintaanDarah->update(['id' => $id], [
            'status' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($update) {
            $response = array('status' => 'success', 'message' => 'Status berhasil diperbarui.');
        } else {
            $response = array('status' => 'error', 'message' => 'Gagal memperbarui status.');
        }

        echo json_encode($response);
    }
}

/* End of file PermintaanDarah.php and path \application\controllers\admin\PermintaanDarah.php */
