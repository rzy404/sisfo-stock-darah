<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('master/getPengguna'); ?>",
                "type": "POST",
            },
        });

        $('#btnAdd').click(function() {
            $.ajax({
                url: '<?= base_url('master/pengguna/add') ?>',
                method: 'GET',
                dataType: 'JSON',
                cache: false,
                success: function(result) {
                    if (result.status) {
                        $("#modalCostum .modal-body").html(result.html);
                        modalShow('Tambah Pengguna', 'modal-dialog modal-md');
                    }
                },
                error: function(ajax, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            });
        })

        $('#modalCostum').on('click', '#btnSave', function(event) {
            event.preventDefault();

            $('.form-group').removeClass('has-error');
            $('.error-message').remove();

            var namaLengkap = $('#nama_lengkap').val().trim();
            var email = $('#email').val().trim();
            var password = $('#password').val().trim();
            var role = $('#role').val().trim();
            var action = $('#action').val();

            var isValid = true;

            if (namaLengkap === '') {
                $('#nama_lengkap').closest('.form-group').addClass('has-error');
                $('#nama_lengkap').after('<span class="error-message">Nama Lengkap wajib diisi.</span>');
                isValid = false;
            }

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === '' || !emailPattern.test(email)) {
                $('#email').closest('.form-group').addClass('has-error');
                $('#email').after('<span class="error-message">Email tidak valid.</span>');
                isValid = false;
            }

            if (action === 'add' && password === '') {
                $('#password').closest('.form-group').addClass('has-error');
                $('#password').after('<span class="error-message">Password wajib diisi.</span>');
                isValid = false;
            }

            if (role === '') {
                $('#role').closest('.form-group').addClass('has-error');
                $('#role').after('<span class="error-message">Role wajib diisi.</span>');
                isValid = false;
            }

            if (isValid) {
                var url = action === 'edit' ? '<?= base_url('master/pengguna/edit') ?>' : '<?= base_url('master/pengguna/add') ?>';
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'JSON',
                    data: $('#formSubmit').serialize(),
                    success: function(response) {
                        Swal.fire(response.message, '', response.type);
                        $('#table1').DataTable().ajax.reload();
                        $('#modalCostum').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        Swal.fire('Terjadi kesalahan, coba lagi.', '', 'error');
                    }
                });
            }
        });

        $(document).on('click', '#btnedit', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('master/pengguna/edit') ?>',
                method: 'GET',
                dataType: 'JSON',
                data: {
                    id: id
                },
                success: function(result) {
                    if (result.status) {
                        $("#modalCostum .modal-body").html(result.html);
                        modalShow('Edit Pengguna', 'modal-dialog modal-md');
                    }
                },
                error: function(ajax, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            });
        });

        $(document).on('click', '#btnDelete', function() {
            var id = $(this).data('id');
            deleteData(id, '<?= base_url('master/pengguna/delete') ?>');
        });

    });
</script>