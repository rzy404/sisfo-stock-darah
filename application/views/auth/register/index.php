<div class="container">
    <div class="row">
        <div class="col-md-5 col-sm-12 mx-auto">
            <div class="card pt-4">
                <div class="card-body">
                    <div class="text-center mb-5">
                        <img src="<?= base_url('assets/images/logo.png') ?>" height="48" class='mb-4'>
                        <h3>Register</h3>
                        <p>Silakan buat akun baru di sini untuk menjadi pendonor darah. Dengan mendaftarkan diri, Anda dapat membantu menyelamatkan nyawa dan memantau riwayat donor darah Anda secara mudah.</p>
                    </div>
                    <form action="<?= base_url('register') ?>" method="post">
                        <div class="form-group position-relative has-icon-left">
                            <label for="nik">NIK</label>
                            <div class="position-relative">
                                <input type="text" class="form-control" name="nik" id="nik">
                                <div class="form-control-icon">
                                    <i data-feather="hash"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 position-relative has-icon-left">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap">
                                    <div class="form-control-icon">
                                        <i data-feather="user"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 position-relative has-icon-left">
                                <label for="email">Email</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" name="email" id="email">
                                    <div class="form-control-icon">
                                        <i data-feather="mail"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 position-relative has-icon-left">
                                <label for="password">Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" name="password" id="password">
                                    <div class="form-control-icon">
                                        <i data-feather="lock"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 position-relative has-icon-left">
                                <label for="konfirmasi_password">Konfirmasi Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" name="konfirmasi_password" id="konfirmasi_password">
                                    <div class="form-control-icon">
                                        <i data-feather="lock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='form-check clearfix my-4'>
                            <div class="float-end">
                                <a href="<?= base_url('login') ?>">Sudah punya akun? Silahkan login</a>
                            </div>
                        </div>
                        <div class="clearfix">
                            <button class="btn btn-danger float-end">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>