<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LandingPage extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Sistem Informasi Stok Darah';
        $this->load->view('frontend/index', $data);
    }
}

/* End of file LandingPage.php and path \application\controllers\LandingPage.php */
