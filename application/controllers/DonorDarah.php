<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DonorDarah extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('ZyAuth');
        $this->load->library('form_validation');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('donor-darah');
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('DonorDarah_Model', 'DonorDarah');
        $this->load->model('StockDarah_model', 'StokDarah');
        $this->load->model('StockDarahLog_model', 'StokDarahLog');
        $this->load->model('TransaksiDarah_model', 'TransaksiDarah');
        $this->load->model('Pengguna_model', 'Pengguna');
    }

    public function index()
    {
        if ($this->checkBiodata() === false) {
            $this->session->set_flashdata('swal_type', 'info');
            $this->session->set_flashdata('swal_message', 'Harap lengkapi biodata terlebih dahulu');
            redirect(base_url('dashboard'));
        } else {
            $data['title'] = 'Donor Darah';
            $data['additional_css'] = $this->load->view('backend/user/donor-darah/css', [], TRUE);
            $data['content'] = $this->load->view('backend/user/donor-darah/index', [
                'modal' => $this->load->view('templates/backend/modal', TRUE),
            ], TRUE);
            $data['additional_js'] = $this->load->view('backend/user/donor-darah/js', [], TRUE);

            $this->load->view('templates/backend/main', $data);
        }
    }

    public function getDonor()
    {
        $list = $this->DonorDarah->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $donor) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $this->session->userdata('nama');
            $row[] = date('d-m-Y', strtotime($donor->tanggal_donor_terakhir));
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->DonorDarah->count_all(),
            "recordsFiltered" => $this->DonorDarah->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function form_add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi input form
            $this->form_validation->set_rules('tanggal_donor', 'Tanggal Donor', 'required|trim|strip_tags');

            if ($this->form_validation->run() === FALSE) {
                echo json_encode([
                    'status' => false,
                    'type' => 'error',
                    'message' => validation_errors() // Pesan validasi
                ]);
                exit;
            } else {
                // Mengambil data input dari form
                $id_user = $this->session->userdata('user_id');
                $tanggal_donor = $this->input->post('tanggal_donor');

                // Mengambil biodata pengguna berdasarkan session
                $this->db->select(
                    'pengguna.id, 
                    biodata_pengguna.golongan_darah,
                    golongan_darah.golongan_darah as gol_darah',
                );
                $this->db->from('pengguna');
                $this->db->join('biodata_pengguna', 'pengguna.id = biodata_pengguna.pengguna', 'left');
                $this->db->join('golongan_darah', 'golongan_darah.id = biodata_pengguna.golongan_darah', 'left');
                $this->db->where('pengguna.id', $id_user);
                $biodata = $this->db->get()->row();

                if (!$biodata) {
                    echo json_encode([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Biodata pengguna tidak ditemukan.'
                    ]);
                    exit;
                }

                $data = [
                    'pengguna' => $id_user,
                    'golongan_darah' => $biodata->golongan_darah,
                    'tanggal_donor_terakhir' => date('Y-m-d', strtotime($tanggal_donor)),
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                // Cek apakah sudah ada data donor darah pada tanggal yang sama
                if ($this->DonorDarah->isExistDonorDarahByTanggal(date('Y-m-d', strtotime($tanggal_donor))) > 0) {
                    echo json_encode([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Data donor darah untuk tanggal ' . date('d-m-Y', strtotime($tanggal_donor)) . ' sudah ada.'
                    ]);
                    exit;
                }

                // Simpan data donor darah
                $insert_id = $this->DonorDarah->simpan($data);

                if ($insert_id) {
                    // Hitung tanggal kedaluwarsa stok darah (+90 hari dari tanggal donor)
                    $tanggal_exp = date('Y-m-d', strtotime($tanggal_donor . ' +90 days'));

                    // Persiapan data stok darah
                    $dataStok = [
                        'golongan_darah' => $biodata->golongan_darah,
                        'jumlah' => 1,
                        'tanggal_exp' => $tanggal_exp,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    $insert_Stok = $this->StokDarah->simpan($dataStok);

                    if ($insert_Stok) {
                        // Log stok darah
                        $dataLog = [
                            'stok_darah' => $insert_Stok,
                            'jumlah' => $dataStok['jumlah'],
                            'jenis_transaksi' => 'Tambah',
                            'tanggal_log' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        $log_success = $this->StokDarahLog->simpan($dataLog);

                        if ($log_success) {
                            $dataTransaksi = [
                                'stok_darah' => $insert_Stok,
                                'donor' => $insert_id,
                                'jenis_transaksi' => 'Donasi',
                                'jumlah' => $dataStok['jumlah'],
                                'tanggal_transaksi' => date('Y-m-d H:i:s'),
                                'catatan' => 'Donasi darah Golongan ' . $biodata->gol_darah,
                                'created_at' => date('Y-m-d H:i:s'),
                            ];

                            $this->TransaksiDarah->simpan($dataTransaksi);

                            echo json_encode([
                                'status' => true,
                                'type' => 'success',
                                'message' => 'Donor darah berhasil disimpan, terima kasih telah melakukan donor :).'
                            ]);
                            exit;
                        } else {
                            echo json_encode([
                                'status' => false,
                                'type' => 'error',
                                'message' => 'Log stok darah gagal disimpan.'
                            ]);
                            exit;
                        }
                    } else {
                        echo json_encode([
                            'status' => false,
                            'type' => 'error',
                            'message' => 'Stok darah gagal disimpan.'
                        ]);
                        exit;
                    }
                } else {
                    echo json_encode([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Data donor darah gagal disimpan.'
                    ]);
                    exit;
                }
            }
        } else {
            // Menampilkan form donor darah dalam modal
            $kirim["title"] = "Form Donor Darah";
            $kirim["action"] = "add";
            $html = $this->load->view('backend/user/donor-darah/form', $kirim, TRUE);
            echo json_encode(["status" => true, "html" => $html]);
        }
    }

    private function checkBiodata()
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->select('biodata_pengguna.tanggal_lahir, 
                       biodata_pengguna.alamat, 
                       biodata_pengguna.nomor_telepon, 
                       biodata_pengguna.jenis_kelamin,
                       biodata_pengguna.golongan_darah');
        $this->db->from('pengguna');
        $this->db->join('biodata_pengguna', 'pengguna.id = biodata_pengguna.pengguna', 'left');
        $this->db->where('pengguna.id', $user_id);

        $biodata = $this->db->get()->row();

        if (
            !$biodata || empty($biodata->tanggal_lahir) || empty($biodata->alamat) ||
            empty($biodata->nomor_telepon) || empty($biodata->jenis_kelamin) ||
            empty($biodata->golongan_darah)
        ) {
            return false;
        }

        return true;
    }
}

/* End of file DonorDarah.php and path \application\controllers\DonorDarah.php */
