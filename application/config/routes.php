<?php
defined('BASEPATH') or exit('No direct script access allowed');
$route['default_controller'] = 'landingpage';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';

// admin
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';

// user
$route['dashboard'] = 'dashboard';
$route['biodata/simpan'] = 'dashboard/simpanBiodata';

// donor darah
$route['donor-darah'] = 'donordarah';
$route['donor-darah/add'] = 'donordarah/form_add';
$route['donor-darah/get-pendonor'] = 'donordarah/getDonor';

// pengguna
$route['master/pengguna'] = 'admin/pengguna';
$route['master/getPengguna'] = 'admin/pengguna/getPengguna';
$route['master/pengguna/add'] = 'admin/pengguna/form_add';
$route['master/pengguna/edit'] = 'admin/pengguna/form_edit';
$route['master/pengguna/delete'] = 'admin/pengguna/delete';

// golongan darah
$route['master/golongan-darah'] = 'admin/golongandarah';
$route['master/getGolonganDarah'] = 'admin/golongandarah/getGolonganDarah';
$route['master/golongan-darah/add'] = 'admin/golongandarah/form_add';
$route['master/golongan-darah/edit'] = 'admin/golongandarah/form_edit';
$route['master/golongan-darah/delete'] = 'admin/golongandarah/delete';

// stok darah
$route['master/stok-darah'] = 'admin/stockdarah';
$route['master/getStokDarah'] = 'admin/stockdarah/getStokDarah';
$route['master/stok-darah/detail'] = 'admin/stockdarah/getStokDarahDetail';
$route['master/stok-darah/add'] = 'admin/stockdarah/form_add';
$route['master/stok-darah/edit'] = 'admin/stockdarah/form_edit';
$route['master/stok-darah/delete-stok'] = 'admin/stockdarah/deleteStok';

// pendonor
$route['admin/donor'] = 'admin/donor';
