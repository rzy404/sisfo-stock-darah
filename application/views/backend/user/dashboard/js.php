<script>
    $(document).ready(function() {
        // Add an event listener for form submission
        $('form').on('submit', function(event) {
            let isValid = true;

            // Validate NIK (NIK should be readonly, no need to validate if empty)
            const nik = $('#nik').val();
            if (!nik || nik.length < 12 || nik.length > 16) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid NIK',
                    text: 'NIK harus terdiri dari 12 hingga 16 karakter.'
                });
                isValid = false;
            }

            // Validate Nama
            const nama = $('#nama').val().trim();
            if (!nama) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nama Tidak Valid',
                    text: 'Nama tidak boleh kosong.'
                });
                isValid = false;
            }

            // Validate Tanggal Lahir
            const tanggal_lahir = $('#tanggal_lahir').val();
            if (!tanggal_lahir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal Lahir Tidak Valid',
                    text: 'Tanggal lahir tidak boleh kosong.'
                });
                isValid = false;
            }

            // Validate Jenis Kelamin
            const jenis_kelamin = $('#jenis_kelamin').val();
            if (!jenis_kelamin) {
                Swal.fire({
                    icon: 'error',
                    title: 'Jenis Kelamin Tidak Valid',
                    text: 'Silakan pilih jenis kelamin.'
                });
                isValid = false;
            }

            // Validate Golongan Darah
            const golongan_darah = $('#golongan_darah').val();
            if (!golongan_darah) {
                Swal.fire({
                    icon: 'error',
                    title: 'Golongan Darah Tidak Valid',
                    text: 'Silakan pilih golongan darah.'
                });
                isValid = false;
            }

            // Validate Nomor Telepon
            const nomor_telepon = $('#nomor_telepon').val().trim();
            const phoneRegex = /^[0-9]+$/;
            if (!nomor_telepon || !phoneRegex.test(nomor_telepon)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nomor Telepon Tidak Valid',
                    text: 'Nomor telepon harus diisi dan hanya berisi angka.'
                });
                isValid = false;
            }

            // Validate Alamat
            const alamat = $('#alamat').val().trim();
            if (!alamat) {
                Swal.fire({
                    icon: 'error',
                    title: 'Alamat Tidak Valid',
                    text: 'Alamat tidak boleh kosong.'
                });
                isValid = false;
            }

            // If the form is not valid, prevent submission
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>