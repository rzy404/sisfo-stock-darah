<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ZyAuth
{
    protected $CI;
    protected $role_redirects;
    protected $permissions;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('cookie');

        $this->role_redirects = [
            'admin' => 'admin/dashboard',
            'staf'  => 'admin/dashboard',
            'user'  => 'dashboard',
        ];

        $this->permissions = [
            'admin' => [
                'admin/dashboard',
                'master/pengguna',
                'master/golongan-darah',
                'master/stok-darah',
                'admin/donor',
                'admin/permintaan-darah',
                'admin/transaksi-darah',
                'admin/laporan'
            ],
            'staf'  => [
                'admin/dashboard',
                'master/golongan-darah',
                'master/stok-darah',
                'admin/donor',
                'admin/permintaan-darah',
                'admin/transaksi-darah',
                'admin/laporan'
            ],
            'user'  => [
                'dashboard',
                'donor-darah',
            ]
        ];
    }

    public function isLoggedIn()
    {
        return $this->CI->session->userdata('logged_in') === TRUE;
    }

    public function check_login()
    {
        if (!$this->isLoggedIn()) {
            $this->logout();
        }
    }

    public function redirect_based_on_role()
    {
        $user_role = $this->get_user_role();
        if (isset($this->role_redirects[$user_role])) {
            redirect($this->role_redirects[$user_role]);
        } else {
            $this->logout();
        }
    }

    public function check_permission($page)
    {
        $user_role = $this->get_user_role();
        if (!isset($this->permissions[$user_role]) || !in_array($page, $this->permissions[$user_role])) {
            $this->CI->session->set_flashdata('swal_message', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            $this->CI->session->set_flashdata('swal_type', 'error');
            $this->redirect_based_on_role();
        }
    }

    public function get_permissions_for_role($role)
    {
        $permissions = [
            'admin' => [
                'master' => [
                    'pengguna',
                    'golongan-darah',
                    'stok-darah'
                ],
                'donor' => [],
                'permintaan-darah' => [],
                'transaksi-darah' => [],
                'laporan' => []
            ],
            'staf' => [
                'master' => [
                    'golongan-darah',
                    'stok-darah'
                ],
                'donor' => [],
                'permintaan-darah' => [],
                'transaksi-darah' => [],
                'laporan' => []
            ],
            'user' => [
                'donor-darah' => []
            ]
        ];

        return $permissions[strtolower($role)] ?? [];
    }

    public function login($user)
    {
        $this->CI->session->set_userdata([
            'logged_in' => TRUE,
            'user_id'   => $user->id,
            'nama'      => $user->nama,
            'email'     => $user->email,
            'role'      => strtolower($user->role),
            'login_time' => time()
        ]);
        $this->refresh_csrf_token();
        $this->redirect_based_on_role();
    }

    public function logout()
    {
        $this->CI->session->sess_destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        delete_cookie('csrf_cookie_name');
        session_regenerate_id(true);
        redirect('login');
    }

    private function get_user_role()
    {
        return strtolower($this->CI->session->userdata('role'));
    }

    private function refresh_csrf_token()
    {
        $this->CI->security->csrf_set_cookie();
    }

    public function check_session_timeout($timeout = 1800) // 30 menit default
    {
        $last_activity = $this->CI->session->userdata('login_time');
        if ($last_activity && (time() - $last_activity > $timeout)) {
            $this->logout();
        } else {
            $this->CI->session->set_userdata('login_time', time());
        }
    }
}
