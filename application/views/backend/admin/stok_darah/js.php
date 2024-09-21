<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('master/getStokDarah'); ?>",
                "type": "POST",
                "error": function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            },
        });

        var detailTable;

        $('#btnAdd').click(function() {
            $.ajax({
                url: '<?= base_url('master/stok-darah/add') ?>',
                method: 'GET',
                dataType: 'JSON',
                cache: false,
                success: function(result) {
                    if (result.status) {
                        $("#modalCostum .modal-body").html(result.html);
                        modalShow('Tambah Stok Darah', 'modal-dialog modal-md');
                        $('#btnSave').show();
                        $('#btnCancel').text('Cancel');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            });
        });

        $('#modalCostum').on('click', '#btnSave', function(event) {
            event.preventDefault();

            $('.form-group').removeClass('has-error');
            $('.error-message').remove();

            var isValid = validateForm();

            if (isValid) {
                var url = $('#action').val() === 'edit' ?
                    '<?= base_url('master/stok-darah/edit') ?>' :
                    '<?= base_url('master/stok-darah/add') ?>';

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
                        console.error(xhr.responseText);
                        Swal.fire('Terjadi kesalahan, coba lagi.', '', 'error');
                    }
                });
            }
        });

        $(document).on('click', '#btnDetail', function() {
            var golongan = $(this).data('golongan');

            if (detailTable) {
                detailTable.destroy();
            }

            $("#modalCostum .modal-body").html('<table class="table table-striped" id="tableDetail">' +
                '<thead><tr><th>No</th><th>Jumlah</th><th>Tanggal Expired</th><th>Aksi</th></tr></thead>' +
                '<tbody></tbody></table>');

            modalShow('Detail Stok Darah', 'modal-dialog modal-lg');
            $('#btnSave').hide();
            $('#btnCancel').text('Tutup');

            detailTable = $('#tableDetail').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?= base_url('master/stok-darah/detail') ?>',
                    type: 'GET',
                    data: {
                        golongan: golongan
                    },
                    dataType: 'JSON',
                    cache: false,
                    dataSrc: function(json) {
                        console.log(json.data)
                        return json.data;
                    },
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: '1',
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: '2',
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: '3',
                        render: function(data, type, row) {
                            return '<button class="btn btn-danger btn-sm" id="btnDeleteStokDarah" data-id="' + row.id + '">Hapus</button>';
                        }
                    }
                ],
                ordering: false,
                searching: false,
                autoWidth: false,
            });
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

        $(document).on('click', '#btnDeleteStokDarah', function() {
            var id = $(this).data('id');
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
                        url: '<?= base_url('master/stok-darah/delete-stok') ?>',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id,
                        },
                        cache: false,
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                            Swal.fire('Error', 'Hapus data gagal, coba lagi.', 'error');
                        },
                        success: function(result) {
                            console.log(result);
                            Swal.fire(result.message, '', result.status === true ? 'success' : 'error');
                            $('#table1').DataTable().ajax.reload();
                            $('#tableDetail').DataTable().ajax.reload();
                            $('#modalCostum').modal('hide');
                        }
                    });
                }
            });
        });
    });
</script>