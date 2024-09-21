<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Donor extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/donor');
        date_default_timezone_set('Asia/Jakarta');
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
}

/* End of file Donor.php and path \application\controllers\Admin\Donor.php */
