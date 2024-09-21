<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('ZyAuth');
        $this->load->library('form_validation');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('dashboard');
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('Pengguna_model', 'Pengguna');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->select('pengguna.nama, 
                       pengguna.email, 
                       biodata_pengguna.nik, 
                       biodata_pengguna.tanggal_lahir, 
                       biodata_pengguna.alamat, 
                       biodata_pengguna.nomor_telepon, 
                       biodata_pengguna.jenis_kelamin,
                       golongan_darah.golongan_darah');
        $this->db->from('pengguna');
        $this->db->join('biodata_pengguna', 'pengguna.id = biodata_pengguna.pengguna', 'left');
        $this->db->join('golongan_darah', 'golongan_darah.id = biodata_pengguna.golongan_darah', 'left');
        $this->db->where('pengguna.id', $user_id);
        $data['biodata'] = $this->db->get()->row();

        $data['golongan_darah'] = $this->GolonganDarah->select();
        $data['title'] = 'Dashboard';
        $data['content'] = $this->load->view('backend/user/dashboard/index', [
            'biodata' => $data['biodata'],
            'golongan_darah' => $data['golongan_darah']
        ], TRUE);
        $data['additional_js'] = $this->load->view('backend/user/dashboard/js', [], TRUE);

        // print_r($data['biodata']);
        $this->load->view('templates/backend/main', $data);
    }

    public function simpanBiodata()
    {

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required');
        $this->form_validation->set_rules('nomor_telepon', 'Nomor Telepon', 'required|numeric');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('swal_type', 'error');
            $this->session->set_flashdata('swal_message', 'Isi data dengan benar');
            redirect(base_url('dashboard'));
        } else {
            $user_id = $this->session->userdata('user_id');

            $pengguna_data = [
                'nama' => $this->input->post('nama'),
            ];

            $this->db->where('id', $user_id);
            $this->db->update('pengguna', $pengguna_data);

            if ($this->db->affected_rows() > 0) {
                $biodata_data = [
                    'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                    'nomor_telepon' => $this->input->post('nomor_telepon'),
                    'alamat' => $this->input->post('alamat'),
                    'golongan_darah' => $this->input->post('golongan_darah'),
                ];

                $biodata = $this->db->get_where('biodata_pengguna', ['pengguna' => $user_id])->row();

                if ($biodata) {
                    $this->db->where('pengguna', $user_id);
                    $this->db->update('biodata_pengguna', $biodata_data);
                    $this->session->set_flashdata('swal_type', 'success');
                    $this->session->set_flashdata('swal_message', 'Simpan data biodata sukses');
                }

                redirect(base_url('dashboard'));
            }
        }
    }
}

/* End of file Dashboard.php and path \application\controllers\Dashboard.php */
