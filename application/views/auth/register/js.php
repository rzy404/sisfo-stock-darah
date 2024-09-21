<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
            let isValid = true;
            const nik = document.getElementById('nik').value.trim();
            const namaLengkap = document.getElementById('nama_lengkap').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const konfirmasiPassword = document.getElementById('konfirmasi_password').value.trim();

            document.querySelectorAll('.error-message').forEach(function(msg) {
                msg.remove();
            });

            // Validate NIK
            if (nik === '') {
                showError('NIK harus diisi');
                isValid = false;
            } else if (nik.length < 12) {
                showError('NIK harus minimal 12 karakter');
                isValid = false;
            }

            // Validate Nama Lengkap
            if (namaLengkap === '') {
                showError('Nama Lengkap harus diisi');
                isValid = false;
            }

            // Validate Email
            if (email === '') {
                showError('Email harus diisi');
                isValid = false;
            } else if (!validateEmail(email)) {
                showError('Email tidak valid');
                isValid = false;
            }

            // Validate Password
            if (password === '') {
                showError('Password harus diisi');
                isValid = false;
            } else if (password !== konfirmasiPassword) {
                showError('Password dan Konfirmasi Password tidak cocok');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // Prevent form submission
            }
        });

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message
            });
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
</script>