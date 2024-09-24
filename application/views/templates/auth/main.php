<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistem Informasi Stok Darah</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/sweetalert/sweetalert2.min.css') ?>">
    <?php if (isset($additional_css)) echo $additional_css; ?>
</head>

<body>
    <div id="auth">
        <?= $content ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/feather-icons/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/vendors/sweetalert/sweetalert2.all.min.js') ?>"></script>
    <?php $this->load->view('templates/message'); ?>
    <?php if (isset($additional_js)) echo $additional_js; ?>
</body>

</html>