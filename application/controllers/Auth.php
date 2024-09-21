<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public $zyauth;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model', 'Auth');
        $this->load->library('form_validation');
        $this->load->library('ZyAuth');
        $this->zyauth = $this->zyauth;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|min_length[10]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('password', 'Password', 'required|strip_tags');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('swal_message', 'Lengkapi form dengan benar');
                $this->session->set_flashdata('swal_type', 'error');
                redirect(base_url('login'));
            }

            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $userModel = $this->Auth->login($email);

            if ($userModel->email == $email) {
                if (password_verify($password, $userModel->password)) {
                    if ($userModel->is_active == 1) {
                        $this->zyauth->login($userModel);
                        $this->session->set_flashdata('swal_message', 'Login Berhasil');
                        $this->session->set_flashdata('swal_type', 'success');

                        if ($this->session->userdata('role') != 'user') {
                            redirect(base_url('admin/dashboard'));
                        } else {
                            redirect(base_url('dashboard'));
                        }
                    } else {
                        $this->session->set_flashdata('swal_message', 'Akun anda tidak aktif');
                        $this->session->set_flashdata('swal_type', 'error');
                        redirect(base_url('login'));
                    }
                } else {
                    $this->session->set_flashdata('swal_message', 'Password anda salah');
                    $this->session->set_flashdata('swal_type', 'error');
                    redirect(base_url('login'));
                }
            } else {
                $this->session->set_flashdata('swal_message', 'Email tidak terdaftar');
                $this->session->set_flashdata('swal_type', 'error');
                redirect(base_url('login'));
            }
        }

        $data['title'] = 'Login';
        $data['content'] = $this->load->view('auth/login/index', [], TRUE);
        $data['additional_js'] = $this->load->view('auth/login/js', [], TRUE);
        $this->load->view('templates/auth/main', $data);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('nama_lengkap', 'nama_lengkap', 'trim|required|min_length[3]|max_length[20]|strip_tags');
            $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[10]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[50]|strip_tags');
            $this->form_validation->set_rules('konfirmasi_password', 'konfirmasi password', 'required|strip_tags|matches[password]');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('swal_message', 'Lengkapi form dengan benar');
                $this->session->set_flashdata('swal_type', 'error');
                redirect(base_url('register'));
            }

            $nik = $this->input->post('nik');
            $namaLengkap = $this->input->post('nama_lengkap');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->Auth->nik_exists($nik)) {
                $this->session->set_flashdata('swal_message', 'NIK sudah terdaftar.');
                $this->session->set_flashdata('swal_type', 'info');
                redirect(base_url('register'));
            }

            if ($this->Auth->email_exists($email)) {
                $this->session->set_flashdata('swal_message', 'Email sudah terdaftar. Silakan gunakan email lain.');
                $this->session->set_flashdata('swal_type', 'info');
                redirect(base_url('register'));
            }

            $encryptedPassword = password_hash($password, PASSWORD_BCRYPT);

            $data = [
                'nama' => $namaLengkap,
                'email' => $email,
                'password' => $encryptedPassword,
                'role' => 'User',
                'is_active' => 1
            ];
            $insertUser = $this->Auth->register($data);

            if ($insertUser) {
                $userId = $this->db->insert_id();

                $biodataData = [
                    'pengguna' => $userId,
                    'nik' => $nik,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $insertBiodata = $this->Auth->insert_biodata($biodataData);
                if ($insertBiodata) {
                    $this->session->set_flashdata('swal_message', 'Akun anda berhasil dibuat. Silakan login');
                    $this->session->set_flashdata('swal_type', 'success');
                    redirect(base_url('login'));
                } else {
                    $this->session->set_flashdata('swal_message', 'Gagal melakukan insert biodata. Silakan coba lagi.');
                    $this->session->set_flashdata('swal_type', 'error');
                    redirect(base_url('register'));
                }
            }
        }

        $data['title'] = 'Register';
        $data['content'] = $this->load->view('auth/register/index', [], TRUE);
        $data['additional_js'] = $this->load->view('auth/register/js', [], TRUE);
        $this->load->view('templates/auth/main', $data);
    }

    public function logout()
    {
        try {
            $this->session->sess_destroy();

            $response = array(
                'success' => true,
                'message' => 'Logout berhasil!'
            );
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'message' => 'Logout gagal, coba lagi.'
            );
        }

        // Send the JSON response
        echo json_encode($response);
    }
}


/* End of file Auth.php and path \application\controllers\Auth\Auth.php  */
