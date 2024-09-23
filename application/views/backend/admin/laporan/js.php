<script>
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();

        // Ambil nilai input
        var tanggalDari = $('input[name="tanggal_dari"]').val();
        var tanggalSampai = $('input[name="tanggal_sampai"]').val();

        // Validasi
        if (!tanggalDari) {
            Swal.fire('Error', 'Tanggal Dari tidak boleh kosong.', 'error');
            return;
        }
        if (!tanggalSampai) {
            Swal.fire('Error', 'Tanggal Sampai tidak boleh kosong.', 'error');
            return;
        }
        if (new Date(tanggalDari) > new Date(tanggalSampai)) {
            Swal.fire('Error', 'Tanggal Dari tidak boleh lebih besar dari Tanggal Sampai.', 'error');
            return;
        }

        $.ajax({
            url: '<?= base_url("admin/laporan") ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);
                // Tambahkan logika untuk mengunduh laporan jika diperlukan
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error');
            }
        });
    });
</script>