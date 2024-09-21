<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GolonganDarah extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->library('form_validation');
        $this->load->library('ZyAuth');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('master/golongan-darah');
        $this->golongan_darah = new GolonganDarah_model();
    }

    public function index()
    {
        $data['title'] = 'Master Golongan Darah';
        $data['content'] = $this->load->view('backend/admin/golongan_darah/index', [], TRUE);
        $data['additional_css'] = $this->load->view('backend/admin/golongan_darah/css', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/golongan_darah/js', [], TRUE);
        $data['modal_costum'] = $this->load->view('templates/backend/modal', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }

    public function getGolonganDarah()
    {
        $list = $this->golongan_darah->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $golongan) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $golongan->golongan_darah;

            $row[] = '<button class="btn btn-primary btn-sm" id="btnedit" data-id="' . $golongan->id . '">Edit</button>
                      <button class="btn btn-danger btn-sm" id="btnDelete" data-id="' . $golongan->id . '">Hapus</button>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->golongan_darah->count_all(),
            "recordsFiltered" => $this->golongan_darah->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function form_add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required|trim|is_unique[golongan_darah.golongan_darah]');

            if ($this->form_validation->run() === FALSE) {
                echo json_encode([
                    'status' => false,
                    'type' => 'error',
                    'message' => validation_errors()
                ]);
                exit;
            } else {
                $data = [
                    'golongan_darah' => strtoupper($this->input->post('golongan_darah')),
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $insert_id = $this->golongan_darah->simpan($data);

                if ($insert_id) {
                    echo json_encode([
                        'status' => true,
                        'type' => 'success',
                        'message' => 'Data berhasil disimpan.'
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
            $kirim["title"] = "Form Tambah Golongan Darah";
            $kirim["action"] = "add";
            $html = $this->load->view('backend/admin/golongan_darah/form', $kirim, TRUE);
            echo json_encode(["status" => true, "html" => $html]);
        }
    }

    public function form_edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required|trim|is_unique[golongan_darah.golongan_darah]');


            if ($this->form_validation->run() === FALSE) {
                echo json_encode([
                    'status' => false,
                    'type' => 'error',
                    'message' => validation_errors()
                ]);
                exit;
            } else {
                $id = $this->input->post('id');
                $data = [
                    'golongan_darah' => strtoupper($this->input->post('golongan_darah')),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $updated = $this->golongan_darah->update(['id' => $id], $data);

                if ($updated) {
                    echo json_encode([
                        'status' => true,
                        'type' => 'success',
                        'message' => 'Data berhasil diubah.'
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
            $kirim["title"] = "Form Edit Golongan Darah";
            $kirim["action"] = "edit";
            $kirim["golonganDarah"] = $this->golongan_darah->get_by_id($id);
            $html = $this->load->view('backend/admin/golongan_darah/form', $kirim, TRUE);
            echo json_encode(["status" => true, "html" => $html]);
        }
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $deleted = $this->golongan_darah->delete($id);

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

/* End of file GolonganDarah.php and path \application\controllers\Admin\GolonganDarah.php */
