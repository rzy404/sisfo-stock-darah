<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LandingPage extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('form_validation');
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('LandingPage_model', 'LandingPage');
    }

    public function index()
    {
        $data['title'] = 'Sistem Informasi Stok Darah';
        $data['golongan_darah'] = $this->GolonganDarah->select();
        $data['blood_stocks'] = $this->get_blood_stocks();
        $data['monthly_donations'] = $this->get_monthly_donations();
        $this->load->view('frontend/index', $data);
    }

    public function pengajuan_darah()
    {
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('nomor_telepon', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required');
        $this->form_validation->set_rules('jml_kantong', 'Jumlah Kantong Darah', 'required|integer');
        $this->form_validation->set_rules('alasan', 'Alasan Pengajuan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $response = [
                'status' => 'error',
                'message' => validation_errors()
            ];
            echo json_encode($response);
            return;
        }

        if ($this->db->get_where('permintaan_darah', ['email' => $this->input->post('email'), 'status' => 'Menunggu'])->num_rows() > 0) {
            $response = [
                'status' => 'error',
                'message' => 'Permintaan anda sedang diproses'
            ];
            echo json_encode($response);
            return;
        }

        $data = [
            'nama_pemohon' => $this->input->post('nama_lengkap'),
            'email' => $this->input->post('email'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'jumlah_dibutuhkan' => $this->input->post('jml_kantong'),
            'nomor_telepon' => $this->input->post('nomor_telepon'),
            'catatan' => $this->input->post('alasan'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert_id = $this->db->insert('permintaan_darah', $data);

        if ($insert_id) {
            $response = [
                'status' => 'success',
                'message' => 'Permintaan anda sedang diproses'
            ];
            echo json_encode($response);
            return;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan permintaan'
            ];
            echo json_encode($response);
            return;
        }
    }

    private function get_blood_stocks()
    {
        return $this->LandingPage->get_blood_stocks();
    }

    private function get_monthly_donations()
    {
        $monthly_donations = $this->LandingPage->get_monthly_donations();
        $result = array_fill(1, 12, 0); // Initialize all months with 0

        foreach ($monthly_donations as $donation) {
            $result[$donation->month] = $donation->count;
        }

        return $result;
    }
}

/* End of file LandingPage.php and path \application\controllers\LandingPage.php */
