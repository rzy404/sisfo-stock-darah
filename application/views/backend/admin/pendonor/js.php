<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('admin/donor/get-pendonor'); ?>",
                "type": "POST",
                "error": function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            },
        });

        var detailTable;

        $(document).on('click', '#btnDetail', function() {
            var golongan = $(this).data('golongan');

            console.log(golongan);

            $("#modalCostum .modal-body").html('<table class="table table-striped" id="tableDetail">' +
                '<thead><tr><th>No</th><th>Nama Pendonor</th><th>Email</th><th>Tanggal Donor</th></tr></thead>' +
                '<tbody></tbody></table>');

            modalShow('Detail Pendonor', 'modal-dialog modal-lg');
            $('#btnSave').hide();
            $('#btnCancel').text('Tutup');

            if (!detailTable) {
                // Initialize DataTable only once
                detailTable = $('#tableDetail').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: '<?= base_url('admin/donor/get-detail-pendonor') ?>',
                        type: 'GET',
                        data: {
                            golongan: golongan
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                        }
                    },
                    columns: [{
                            data: 0
                        }, // Use the first item of the array returned from the controller
                        {
                            data: 1
                        }, // nama_pendonor
                        {
                            data: 2
                        }, // email
                        {
                            data: 3
                        } // tanggal_donor
                    ],
                    deferRender: true,
                    ordering: false,
                    searching: false,
                    autoWidth: false,
                });
            } else {
                detailTable.clear().draw();
            }
        });

        $('#modalCostum').on('hidden.bs.modal', function() {
            if (detailTable) {
                detailTable.destroy();
                detailTable = null;
            }
            $(this).find('.modal-body').html('');
        });

        function validateForm() {
            var isValid = true;
            var golonganDarah = $('#golongan_darah').val().trim();
            var jumlah = $('#jumlah').val().trim();
            var tanggalExp = $('#tanggal_exp').val().trim();

            if (golonganDarah === '') {
                showError('golongan_darah', 'Golongan Darah harus diisi.');
                isValid = false;
            }

            if (jumlah === '') {
                showError('jumlah', 'Jumlah stok harus diisi.');
                isValid = false;
            } else if (isNaN(jumlah) || parseInt(jumlah) <= 0) {
                showError('jumlah', 'Jumlah stok harus berupa angka positif.');
                isValid = false;
            }

            if (tanggalExp === '') {
                showError('tanggal_exp', 'Tanggal expired harus diisi.');
                isValid = false;
            }

            return isValid;
        }

        function showError(fieldId, message) {
            $('#' + fieldId).closest('.form-group').addClass('has-error')
                .append('<span class="error-message" style="color:red;">' + message + '</span>');
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            return day + '/' + month + '/' + year;
        }
    });
</script>