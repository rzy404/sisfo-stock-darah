<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ZyAuth');
        $this->load->library('Pdf');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/laporan');
        $this->load->model('TransaksiDarah_model', 'TransaksiDarah');
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $filters = $this->input->post(); // Ambil data filter dari form

            $data['transaksi'] = $this->TransaksiDarah->get_filtered_transaksi($filters);

            // Load view untuk PDF
            $html = $this->load->view('backend/admin/laporan/pdf_report', $data, TRUE);

            // Set PDF options
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait'); // Ganti dengan SetPaper
            $this->pdf->render();

            // Output the generated PDF (force download)
            $this->pdf->stream('laporan_transaksi' . uniqid() . '.pdf', array('Attachment' => true));
        } else {
            $data['title'] = 'Laporan';
            $data['content'] = $this->load->view('backend/admin/laporan/index', [], TRUE);
            $data['additional_js'] = $this->load->view('backend/admin/laporan/js', [], TRUE);
            $this->load->view('templates/backend/main', $data);
        }
    }
}

/* End of file Laporan.php and path \application\controllers\admin\Laporan.php */
