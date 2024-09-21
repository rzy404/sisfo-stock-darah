<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengguna_model', 'Pengguna');
        $this->load->library('form_validation');
        $this->load->library('ZyAuth');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('master/pengguna');
        $this->pengguna = new Pengguna_model();
    }

    public function index()
    {
        $data['title'] = 'Master Pengguna';
        $data['content'] = $this->load->view('backend/admin/pengguna/index', [], TRUE);
        $data['additional_css'] = $this->load->view('backend/admin/pengguna/css', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/pengguna/js', [], TRUE);
        $data['modal_costum'] = $this->load->view('templates/backend/modal', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }

    public function getPengguna()
    {
        $list = $this->Pengguna->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $pengguna) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pengguna->nama;
            $row[] = $pengguna->email;
            $row[] = $pengguna->role;

            $row[] = '<button class="btn btn-primary btn-sm" id="btnedit" data-id="' . $pengguna->id . '">Edit</button>
                      <button class="btn btn-danger btn-sm" id="btnDelete" data-id="' . $pengguna->id . '">Hapus</button>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Pengguna->count_all(),
            "recordsFiltered" => $this->Pengguna->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function form_add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required|min_length[3]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|min_length[10]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('role', 'Role', 'trim|required');

            if ($this->form_validation->run() === FALSE) {
                echo json_encode([
                    'status' => false,
                    'type' => 'error',
                    'message' => "Lengkapi form dengan benar.",
                ]);
            } else {
                $data = [
                    'nama' => $this->input->post('nama_lengkap'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                    'role' => $this->input->post('role'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $insert_id = $this->Pengguna->simpan($data);

                if ($insert_id) {
                    echo json_encode([
                        'status' => true,
                        'type' => 'success',
                        'message' => 'Pengguna berhasil ditambahkan.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Terjadi kesalahan, coba lagi.'
                    ]);
                }
            }
        } else {
            $kirim["title"] = "Form Tambah Pengguna";
            $kirim["action"] = "add";
            $html = $this->load->view('backend/admin/pengguna/form', $kirim, TRUE);
            echo json_encode(["status" => true, "html" => $html]);
        }
    }

    public function form_edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required|min_length[3]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|min_length[10]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]|max_length[80]|strip_tags');
            $this->form_validation->set_rules('role', 'Role', 'trim|required');

            if ($this->form_validation->run() === FALSE) {
                echo json_encode([
                    'status' => false,
                    'type' => 'error',
                    'message' => "Lengkapi form dengan benar.",
                ]);
            } else {
                $id = $this->input->post('id');
                $current_user_id = $this->session->userdata('user_id');

                if ($id == $current_user_id) {
                    echo json_encode([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Anda tidak dapat mengedit diri sendiri.',
                    ]);
                    return;
                }

                $data = [
                    'nama' => $this->input->post('nama_lengkap'),
                    'email' => $this->input->post('email'),
                    'role' => $this->input->post('role'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $password = $this->input->post('password');
                if (!empty($password)) {
                    $data['password'] = password_hash($password, PASSWORD_BCRYPT);
                }

                $updated = $this->Pengguna->update(['id' => $id], $data);

                if ($updated) {
                    echo json_encode([
                        'status' => true,
                        'type' => 'success',
                        'message' => 'Pengguna berhasil diubah.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Terjadi kesalahan, coba lagi.'
                    ]);
                }
            }
        } else {
            $id = $this->input->get('id', true);
            $kirim["title"] = "Form Edit Pengguna";
            $kirim["action"] = "edit";
            $kirim["pengguna"] = $this->Pengguna->get_by_id($id);
            $html = $this->load->view('backend/admin/pengguna/form', $kirim, TRUE);
            echo json_encode(["status" => true, "html" => $html]);
        }
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $current_user_id = $this->session->userdata('user_id');

        if ($id == $current_user_id) {
            echo json_encode([
                'status' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ]);
            return;
        }

        $deleted = $this->Pengguna->delete($id);

        if ($deleted) {
            echo json_encode([
                'status' => true,
                'message' => 'Data Berhasil dihapus',
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menghapus data',
            ]);
        }
    }
}

/* End of file Pengguna.php and path \application\controllers\Admin\Pengguna.php */
