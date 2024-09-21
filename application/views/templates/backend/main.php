<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistem Informasi Stok Darah</title>

    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/sweetalert/sweetalert2.min.css') ?>">
    <?php if (isset($additional_css)) echo $additional_css; ?>
</head>

<body>
    <div id="app">
        <?php $this->load->view('templates/backend/sidebar'); ?>
        <div id="main">
            <?php $this->load->view('templates/backend/navbar'); ?>
            <?= $content; ?>
            <?php $this->load->view('templates/backend/footer'); ?>
        </div>
    </div>
    <?php if (isset($modal_costum)) echo $modal_costum; ?>
    <!-- jquery using cdn -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/feather-icons/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/vendors/sweetalert/sweetalert2.all.min.js') ?>"></script>
    <script>
        $('#btnLogout').click(function() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan keluar dari sistem",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('logout') ?>',
                        type: 'POST',
                        dataType: 'JSON',
                        cache: false,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Logout berhasil!', '', 'success').then(() => {
                                    window.location.href = '<?= base_url('login') ?>';
                                });
                            } else {
                                Swal.fire('Gagal', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'Logout gagal, coba lagi.', 'error');
                        }
                    });
                }
            });
        });

        function modalShow(title, sizeClass) {
            const $modal = $("#modalCostum");
            const $modalDialog = $modal.find(".modal-dialog");

            $modal.find(".modal-title").text(title);
            $modalDialog.attr('class', 'modal-dialog').addClass(sizeClass);
            $modal.modal("show");
        }

        function deleteData(id, url) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan menghapus data ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        cache: false,
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                            Swal.fire('Error', 'Hapus data gagal, coba lagi.', 'error');
                        },
                        success: function(result) {
                            Swal.fire(result.message, '', result.status === true ? 'success' : 'error');
                            $('#table1').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }
    </script>
    <?php $this->load->view('templates/message'); ?>
    <?php if (isset($additional_js)) echo $additional_js; ?>
</body>

</html>