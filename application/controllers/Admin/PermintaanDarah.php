<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PermintaanDarah extends CI_Controller
{
    public $email;
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('email');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/permintaan-darah');
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('StockDarah_model', 'StokDarah');
        $this->load->model('StockDarahLog_model', 'StokDarahLog');
        $this->load->model('TransaksiDarah_model', 'TransaksiDarah');
        $this->load->model('PermintaanDarah_model', 'PermintaanDarah');
        $this->load->model('Pengguna_model', 'Pengguna');
        $this->email->initialize();
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
            $row[] = '<button class="btn btn-info btn-sm" id="btnDetail" data-golongan="' . $permintaan->golongan_darah . '" data-id="' . $permintaan->id . '">Detail</button>';
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
        $permintaan = $this->PermintaanDarah->select_where($id, 'status, email, golongan_darah, jumlah_dibutuhkan');

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

        // Mulai transaksi database
        $this->db->trans_start();

        // Update status jika status saat ini 'Menunggu'
        $update = $this->PermintaanDarah->update(['id' => $id], [
            'status' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($update) {
            // Proses pengurangan stok darah hanya jika status baru adalah 'Disetujui'
            if ($new_status == 'Disetujui') {
                $stok_darah = $this->db->get_where('stok_darah', ['golongan_darah' => $permintaan->golongan_darah])->row();

                if ($stok_darah && $stok_darah->jumlah >= $permintaan->jumlah_dibutuhkan) {
                    $jumlah_dibutuhkan = (int)$permintaan->jumlah_dibutuhkan;

                    // Kurangi stok darah
                    $this->db->set('jumlah', 'jumlah - ' . $jumlah_dibutuhkan, FALSE);
                    $this->db->where('id', $stok_darah->id);
                    $this->db->update('stok_darah');

                    // Masukkan data ke tabel transaksi_darah
                    $this->db->insert('transaksi_darah', [
                        'stok_darah' => $stok_darah->id,
                        'permintaan_darah' => $id,
                        'jenis_transaksi' => 'Penggunaan',
                        'jumlah' => $jumlah_dibutuhkan,
                        'tanggal_transaksi' => date('Y-m-d'),
                        'catatan' => 'Penggunaan darah untuk permintaan darah #' . uniqid(),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    // Masukkan data ke tabel stok_darah_log
                    $this->db->insert('stok_darah_log', [
                        'stok_darah' => $stok_darah->id,
                        'jumlah' => $jumlah_dibutuhkan,
                        'jenis_transaksi' => 'Kurang',
                        'tanggal_log' => date('Y-m-d'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Rollback jika stok tidak mencukupi
                    $this->db->trans_rollback();
                    $this->sendResponse('error', 'Stok darah tidak mencukupi.');
                    return;
                }
            }

            // Selesaikan transaksi
            $this->db->trans_complete();

            // Kirim email notifikasi
            $email_status = $this->sendStatusEmail($permintaan->email, $new_status);

            if ($email_status) {
                $this->sendResponse('success', 'Status berhasil diperbarui dan email dikirim.');
            } else {
                $this->sendResponse('error', 'Status diperbarui, tapi gagal mengirim email.');
            }
        } else {
            $this->db->trans_rollback();
            $this->sendResponse('error', 'Gagal memperbarui status.');
        }
    }

    public function detail()
    {
        $id = $this->input->get('id');

        if (!$id) {
            $this->sendResponse('error', 'ID tidak ditemukan.');
            return;
        }

        // Ambil data detail permintaan darah berdasarkan ID
        $permintaan = $this->PermintaanDarah->getDetailPermintaan($id);

        // Cek apakah data ditemukan
        if ($permintaan) {
            $permintaan->catatan = $permintaan->catatan ? $permintaan->catatan : 'Tidak ada catatan';

            // Mengirim data ke view
            $data['permintaan'] = $permintaan;

            // Render view ke dalam HTML
            $html = $this->load->view('backend/admin/permintaan_darah/detail', $data, TRUE);

            // Kirim response
            $this->sendResponse(true, 'Data ditemukan.', ['data' => $permintaan, 'html' => $html]);
        } else {
            $this->sendResponse(false, 'Data tidak ditemukan.');
        }
    }


    private function sendStatusEmail($to_email, $new_status)
    {
        // Pastikan email penerima tersedia
        if (!$to_email) {
            return false;
        }

        // Tentukan isi email berdasarkan status
        $subject = 'Pembaruan Status Permintaan Darah';
        $message = ($new_status == 'Disetujui')
            ? 'Selamat, permintaan darah Anda telah disetujui untuk lebih lanjut silahkan menghubungi kami.'
            : 'Mohon maaf, permintaan darah Anda telah ditolak.';

        // Atur pengirim dan konten email
        $this->email->from('rsud-bulukumba@sidarah.com', 'SiDarah');
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($message);

        // Kirim email dan kembalikan status
        return $this->email->send();
    }

    // Method untuk mengirim response
    private function sendResponse($status, $message, $data = null)
    {
        $response = array('status' => $status, 'message' => $message);

        if ($data) {
            $response['data'] = $data;
        }
        echo json_encode($response);
    }
}

/* End of file PermintaanDarah.php and path \application\controllers\admin\PermintaanDarah.php */
