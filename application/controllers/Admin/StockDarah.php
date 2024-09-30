<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockDarah extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('StockDarah_model', 'StockDarah');
        $this->load->model('StockDarahLog_model', 'StockDarahLog');
        $this->load->library('form_validation');
        $this->load->library('ZyAuth');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('master/stok-darah');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = 'Master Stok Darah';
        $data['content'] = $this->load->view('backend/admin/stok_darah/index', [], TRUE);
        $data['additional_css'] = $this->load->view('backend/admin/stok_darah/css', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/stok_darah/js', [], TRUE);
        $data['modal_costum'] = $this->load->view('templates/backend/modal', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }

    public function getStokDarah()
    {
        $list = $this->StockDarah->get_stok_darah_log_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $stok) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $stok->gd;
            $row[] = $stok->jml_stok . ' Kantong';

            $row[] = '<button class="btn btn-info btn-sm" id="btnDetail" data-golongan="' . $stok->gd . '">Detail</button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->StockDarah->count_all_log(),
            "recordsFiltered" => $this->StockDarah->count_filtered_log(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function getStokDarahDetail()
    {
        $golongan = $this->input->get('golongan', true);

        $list = $this->StockDarah->get_stok_darah_by_golongan_datatables($golongan);

        $data = array();
        $no = $_POST['start'] ?? 0;

        foreach ($list as $stok) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $stok->jumlah . ' Kantong';
            $row[] = date('d/m/Y', strtotime($stok->tanggal_exp));
            $row['id'] = $stok->id;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'] ?? 1,
            "recordsTotal" => $this->StockDarah->count_all_by_golongan(),
            "recordsFiltered" => $this->StockDarah->count_filtered_by_golongan($golongan),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function form_add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required|trim');
            $this->form_validation->set_rules('jumlah', 'Jumlah Stok', 'required|numeric');
            $this->form_validation->set_rules('tanggal_exp', 'Tanggal Expired', 'required|trim');

            if ($this->form_validation->run() === FALSE) {
                echo json_encode([
                    'status' => false,
                    'type' => 'error',
                    'message' => validation_errors()
                ]);
                exit;
            } else {
                $golongan_darah = $this->input->post('golongan_darah');
                $jumlah_baru = $this->input->post('jumlah');
                $tanggal_exp = $this->input->post('tanggal_exp');

                $data = [
                    'golongan_darah' => $golongan_darah,
                    'jumlah' => $jumlah_baru,
                    'tanggal_exp' => $tanggal_exp,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $insert_id = $this->StockDarah->simpan($data);

                if ($insert_id) {

                    $dataLog = [
                        'stok_darah' => $insert_id,
                        'jumlah' => $jumlah_baru,
                        'jenis_transaksi' => 'Tambah',
                        'tanggal_log' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $log_success = false;

                    $log_success = $this->StockDarahLog->simpan($dataLog);

                    if ($log_success) {
                        echo json_encode([
                            'status' => true,
                            'type' => 'success',
                            'message' => 'Data berhasil disimpan dan dicatat di log.',
                        ]);
                    } else {
                        echo json_encode([
                            'status' => false,
                            'type' => 'error',
                            'message' => 'Data berhasil disimpan, namun pencatatan log gagal.',
                        ]);
                    }
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
            $kirim["golongan_darah"] = $this->GolonganDarah->select();
            $html = $this->load->view('backend/admin/stok_darah/form', $kirim, TRUE);
            echo json_encode(["status" => true, "html" => $html]);
        }
    }

    public function deleteStok()
    {
        $id = $this->input->post('id', true);

        // Start a transaction
        $this->db->trans_begin();

        // Get the stock log entry by ID
        $get_log = $this->StockDarahLog->get_by_id($id);

        // Attempt to delete the stock master entry
        $delete_master = $this->StockDarah->delete($id);

        if ($delete_master) {
            // If log exists, attempt to delete it
            if ($get_log) {
                $delete_log = $this->StockDarahLog->delete($get_log->id);
                if (!$delete_log) {
                    // Log deletion failed, but we proceed as stock was deleted successfully
                    $this->db->trans_rollback(); // Rollback to maintain consistency
                    echo json_encode([
                        'status' => false,
                        'message' => 'Data stok master berhasil dihapus, tetapi gagal menghapus data log.',
                    ]);
                    return; // Exit early
                }
            }

            // Commit the transaction if stock deletion is successful
            $this->db->trans_commit();
            echo json_encode([
                'status' => true,
                'message' => 'Data stok master berhasil dihapus.',
            ]);
        } else {
            // Rollback the transaction if stock deletion failed
            $this->db->trans_rollback();
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menghapus data stok master.',
            ]);
        }
    }
}

/* End of file StockDarah.php and path \application\controllers\admin\StockDarah.php */
