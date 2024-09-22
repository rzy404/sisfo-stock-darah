<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Donor extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/donor');
        $this->load->model('GolonganDarah_model', 'GolonganDarah');
        $this->load->model('DonorDarah_Model', 'DonorDarah');
        $this->load->model('StockDarah_model', 'StokDarah');
        $this->load->model('StockDarahLog_model', 'StokDarahLog');
        $this->load->model('TransaksiDarah_model', 'TransaksiDarah');
        $this->load->model('Pendonor_model', 'Pendonor');
        $this->load->model('Pengguna_model', 'Pengguna');
    }

    public function index()
    {
        $data['title'] = 'Data Pendonor';
        $data['content'] = $this->load->view('backend/admin/pendonor/index', [], TRUE);
        $data['additional_css'] = $this->load->view('backend/admin/pendonor/css', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/pendonor/js', [], TRUE);
        $data['modal_costum'] = $this->load->view('templates/backend/modal', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }

    public function getPendonor()
    {
        $list = $this->Pendonor->get_pendonor_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $pendonor) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pendonor->golongan_darah;
            $row[] = $pendonor->total_pendonor;
            $row[] = '<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#modalCostum" data-golongan="' . $pendonor->golongan_darah . '">Detail</button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Pendonor->count_all(),
            "recordsFiltered" => $this->Pendonor->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function getDetailPendonor()
    {
        $golongan = $this->input->get('golongan', true);
        $list = $this->Pendonor->get_detail_datatables($golongan);

        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;

        foreach ($list as $pendonor) {
            $no++;
            $row = array();
            $row[] = $no; // Add the row number here
            $row[] = $pendonor->nama_pendonor;
            $row[] = $pendonor->email;
            $row[] = date('d-m-Y', strtotime($pendonor->tanggal_donor));
            $data[] = $row;
        }

        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : 0,
            "recordsTotal" => $this->Pendonor->count_all_detail(),
            "recordsFiltered" => $this->Pendonor->count_filtered_detail($golongan),
            "data" => $data,
        );

        echo json_encode($output);
    }
}

/* End of file Donor.php and path \application\controllers\Admin\Donor.php */
