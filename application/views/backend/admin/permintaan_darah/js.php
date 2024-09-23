<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#table1').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('admin/permintaan-darah/get-permintaan-darah'); ?>",
                "type": "POST",
            },
        });

        $('#table1').on('change', '.status-dropdown', function() {
            var newStatus = $(this).val();
            var id = $(this).data('id');

            console.log(newStatus);
            console.log(id);

            if ($(this).is(':disabled')) {
                Swal.fire({
                    title: 'Tidak Bisa Diubah!',
                    text: 'Status sudah diperbarui dan tidak bisa diubah lagi.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $.ajax({
                url: '<?= base_url('admin/permintaan-darah/update-status-permintaan') ?>',
                type: 'POST',
                data: {
                    id: id,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('select[data-id="' + id + '"]').prop('disabled', true);
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message, // Show the exact error message from the server
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan pada server.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        $(document).on('click', '#btnDetail', function() {
            var id = $(this).data('id');
            var golongan = $(this).data('golongan');

            getDetail(id, golongan);
        });

        function getDetail(id, golongan) {
            console.log(id);
            console.log(golongan);
            $.ajax({
                url: '<?= base_url("admin/permintaan-darah/detail") ?>',
                data: {
                    id: id
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    if (response.status) {
                        $("#modalCostum .modal-body").html(response.data.html);
                        $('#namaPemohon').text(response.data.data.nama_pemohon);
                        $('#golonganDarah').text(response.data.data.nama_golongan);
                        $('#jumlahDibutuhkan').text(response.data.data.jumlah_dibutuhkan + ' Kantong');
                        $('#nomorTelepon').text(response.data.data.nomor_telepon);
                        $('#statusPermintaan').text(response.data.data.status);
                        $('#catatanPermintaan').text(response.data.data.catatan || 'Tidak ada catatan');
                        $('#createdAt').text(response.data.data.created_at);
                        modalShow('Detail Permintaan Darah Golongan ' + golongan, 'modal-dialog modal-md');
                        $('#btnSave').hide();
                        $('#btnCancel').text('Tutup');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
                }
            });
        }
    });
</script>