<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('master/getGolonganDarah'); ?>",
                "type": "POST",
            },
        });

        $('#btnAdd').click(function() {
            $.ajax({
                url: '<?= base_url('master/golongan-darah/add') ?>',
                method: 'GET',
                dataType: 'JSON',
                cache: false,
                success: function(result) {
                    if (result.status) {
                        $("#modalCostum .modal-body").html(result.html);
                        modalShow('Tambah Golongan Darah', 'modal-dialog modal-md');
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

            var golonganDarah = $('#golongan_darah').val().trim();
            var action = $('#action').val();
            var isValid = true;

            if (golonganDarah === '') {
                $('#golongan_darah').closest('.form-group').addClass('has-error');
                $('#golongan_darah').closest('.form-group').append('<span class="error-message" style="color:red;">Golongan Darah harus diisi.</span>');
                isValid = false;
            }

            if (isValid) {
                var url = action === 'edit' ? '<?= base_url('master/golongan-darah/edit') ?>' : '<?= base_url('master/golongan-darah/add') ?>';
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
                        Swal.fire('Terjadi kesalahan, coba lagi.', '', 'error');
                    }
                });
            }
        });

        $(document).on('click', '#btnedit', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('master/golongan-darah/edit') ?>',
                method: 'GET',
                dataType: 'JSON',
                data: {
                    id: id
                },
                success: function(result) {
                    if (result.status) {
                        $("#modalCostum .modal-body").html(result.html);
                        modalShow('Edit Golongan Darah', 'modal-dialog modal-md');
                    }
                },
                error: function(ajax, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            });
        });

        $(document).on('click', '#btnDelete', function() {
            var id = $(this).data('id');
            deleteData(id, '<?= base_url('master/golongan-darah/delete') ?>');
        });

    });
</script>