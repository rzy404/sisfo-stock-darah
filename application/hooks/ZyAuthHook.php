<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ZyAuthHook
{
    protected $CI;
    protected $zyauth;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('ZyAuth');
        $this->zyauth = $this->CI->zyauth;
    }

    public function check_auth()
    {
        $current_class = $this->CI->router->fetch_class();
        $method = $this->CI->router->fetch_method();
        $page = $current_class . '/' . $method;
        $public_routes = ['login'];

        if (!in_array($current_class, $public_routes)) {
            if (!$this->zyauth->isLoggedIn()) {
                $this->zyauth->logout();
            } else {
                $this->zyauth->check_permission($page);
            }
        } elseif ($current_class === 'login' && $this->zyauth->isLoggedIn()) {
            $this->zyauth->redirect_based_on_role();
        }
    }
}
