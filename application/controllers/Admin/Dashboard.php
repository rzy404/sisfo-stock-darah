<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ZyAuth');
        $this->zyauth->check_login();
        $this->zyauth->check_session_timeout();
        $this->zyauth->check_permission('admin/dashboard');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data["additional_css"] = $this->load->view('backend/admin/dashboard/css', [], TRUE);
        $data['content'] = $this->load->view('backend/admin/dashboard/index', [], TRUE);
        $data['additional_js'] = $this->load->view('backend/admin/dashboard/js', [], TRUE);
        $this->load->view('templates/backend/main', $data);
    }
}

/* End of file Dashboard.php and path \application\controllers\Admin\Dashboard.php */
