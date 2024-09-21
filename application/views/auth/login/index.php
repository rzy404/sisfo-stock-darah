<div class="container">
    <div class="row">
        <div class="col-md-5 col-sm-12 mx-auto">
            <div class="card pt-4">
                <div class="card-body">
                    <div class="text-center mb-5">
                        <img src="<?= base_url('assets/images/logo-only.png') ?>" height="48" class='mb-4'>
                        <h3>Login</h3>
                        <p>Silahkan login terlebih dahulu untuk melanjutkan ke halaman dashboard.</p>
                    </div>
                    <form action="<?= base_url('login') ?>" method="post">
                        <div class="form-group position-relative has-icon-left">
                            <label for="email">Email</label>
                            <div class="position-relative">
                                <input type="text" class="form-control" name="email" id="email">
                                <div class="form-control-icon">
                                    <i data-feather="user"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left">
                            <label for="password">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" name="password" id="password">
                                <div class="form-control-icon">
                                    <i data-feather="lock"></i>
                                </div>
                            </div>
                        </div>

                        <div class='form-check clearfix my-4'>
                            <div class="float-end">
                                <a href="<?= base_url('register') ?>">Belum punya akun? Silahkan daftar</a>
                            </div>
                        </div>
                        <div class="clearfix">
                            <button class="btn btn-danger float-end" type="submit" id="btnLogin">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>