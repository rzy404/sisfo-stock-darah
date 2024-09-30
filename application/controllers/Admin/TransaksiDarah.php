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

        // Start transaction
        $this->db->trans_begin();

        // Get transaksi_darah data for potential rollback of stok_darah
        $transaksi = $this->TransaksiDarah->getById($id);

        if ($transaksi) {
            // If transaction type is 'Donasi', decrease stok_darah
            // If transaction type is 'Penggunaan', increase stok_darah
            if ($transaksi->jenis_transaksi == 'Donasi') {
                $this->db->set('jumlah', 'jumlah - ' . (int)$transaksi->jumlah, FALSE);
                $this->db->where('id', $transaksi->stok_darah);
                $this->db->update('stok_darah');

                if (!empty($transaksi->donor)) {
                    // Delete the donor record
                    $this->db->where('id', $transaksi->donor);
                    $this->db->delete('donor');
                }
            } else if ($transaksi->jenis_transaksi == 'Penggunaan') {
                $this->db->set('jumlah', 'jumlah + ' . (int)$transaksi->jumlah, FALSE);
                $this->db->where('id', $transaksi->stok_darah);
                $this->db->update('stok_darah');

                if (!empty($transaksi->permintaan_darah)) {
                    // Delete the permintaan_darah record
                    $this->db->where('id', $transaksi->permintaan_darah);
                    $this->db->delete('permintaan_darah');
                }
            }

            // Delete the transaksi_darah record
            $this->db->where('stok_darah', $transaksi->stok_darah);
            $this->db->delete('transaksi_darah');  // Hapus semua transaksi terkait stok darah

            // Log stok_darah change
            $data_log = array(
                'stok_darah' => $transaksi->stok_darah,
                'jumlah' => $transaksi->jumlah,
                'jenis_transaksi' => ($transaksi->jenis_transaksi == 'Donasi') ? 'Kurang' : 'Tambah',
                'tanggal_log' => date('Y-m-d')
            );
            $this->db->insert('stok_darah_log', $data_log);

            // Check if stok_darah is less than or equal to 0, then delete the stok_darah record
            $this->db->where('id', $transaksi->stok_darah);
            $stok_darah = $this->db->get('stok_darah')->row();
            if ($stok_darah && $stok_darah->jumlah <= 0) {
                // Delete stok_darah after all transactions are deleted
                $this->db->where('id', $stok_darah->id);
                $this->db->delete('stok_darah');
            }

            // Check if all queries were successful
            if ($this->db->trans_status() === FALSE) {
                // Rollback transaction if there was an error
                $this->db->trans_rollback();
                $response = array('status' => false, 'message' => 'Gagal menghapus data transaksi darah.');
            } else {
                // Commit transaction if no errors
                $this->db->trans_commit();
                $response = array('status' => true, 'message' => 'Data transaksi darah berhasil dihapus.');
            }
        } else {
            // If transaction not found
            $this->db->trans_rollback();
            $response = array('status' => false, 'message' => 'Data transaksi darah tidak ditemukan.');
        }

        echo json_encode($response);
    }
}

/* End of file TransaksiDarah.php and path \application\controllers\admin\TransaksiDarah.php */
