<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('donor-darah/get-pendonor'); ?>",
                "type": "POST",
            },
        });

        $('#btnAdd').click(function() {
            $.ajax({
                url: '<?= base_url('donor-darah/add') ?>',
                method: 'GET',
                dataType: 'JSON',
                cache: false,
                success: function(result) {
                    if (result.status) {
                        $("#modalCostum .modal-body").html(result.html);
                        modalShow('Donor Darah', 'modal-dialog modal-md');
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

            var tanggalDonor = $('#tanggal_donor').val().trim();
            var action = $('#action').val();
            var isValid = true;

            if (tanggalDonor === '') {
                $('#tanggal_donor').closest('.form-group').addClass('has-error');
                $('#tanggal_donor').closest('.form-group').append('<span class="error-message" style="color:red;">Tanggal Donor harus diisi.</span>');
                isValid = false;
            }

            if (isValid) {
                var url = action === 'edit' ? '<?= base_url('donor-darah/edit') ?>' : '<?= base_url('donor-darah/add') ?>';
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'JSON',
                    data: $('#formSubmit').serialize(),
                    success: function(response) {
                        console.log(response);
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

    });
</script>