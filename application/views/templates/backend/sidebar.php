<div id="sidebar" class='active'>
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class='sidebar-title'>Main Menu</li>

                <?php
                $role = $this->session->userdata('role');
                $permissions = $this->zyauth->get_permissions_for_role($role);
                ?>

                <?php
                $url = $role == 'user' ? 'dashboard' : 'admin/dashboard';
                $activeUrl = $role == 'user' ? $this->uri->segment(1) : $this->uri->segment(2);
                ?>
                <li class="sidebar-item <?= $activeUrl == 'dashboard' ? 'active' : '' ?>">
                    <a href="<?= base_url($url) ?>" class='sidebar-link'>
                        <i data-feather="home" width="20"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <?php if (isset($permissions['master'])): ?>
                    <li class="sidebar-item has-sub <?= $this->uri->segment(1) == 'master' ? 'active' : '' ?>">
                        <a href="#" class='sidebar-link'>
                            <i data-feather="triangle" width="20"></i>
                            <span>Master</span>
                        </a>
                        <ul class="submenu <?= $this->uri->segment(1) == 'master' ? 'active' : '' ?>">
                            <?php if (in_array('pengguna', $permissions['master'])): ?>
                                <li class="<?= $this->uri->segment(2) == 'pengguna' ? 'active' : '' ?>">
                                    <a href="<?= base_url('master/pengguna') ?>">Pengguna</a>
                                </li>
                            <?php endif; ?>

                            <?php if (in_array('golongan-darah', $permissions['master'])): ?>
                                <li class="<?= $this->uri->segment(2) == 'golongan-darah' ? 'active' : '' ?>">
                                    <a href="<?= base_url('master/golongan-darah') ?>">Golongan Darah</a>
                                </li>
                            <?php endif; ?>

                            <?php if (in_array('stok-darah', $permissions['master'])): ?>
                                <li class="<?= $this->uri->segment(2) == 'stok-darah' ? 'active' : '' ?>">
                                    <a href="<?= base_url('master/stok-darah') ?>">Stok Darah</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (isset($permissions['donor'])): ?>
                    <li class="sidebar-item <?= $this->uri->segment(2) == 'donor' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/donor') ?>" class='sidebar-link'>
                            <i data-feather="database" width="20"></i>
                            <span>Donor</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($permissions['permintaan-darah'])): ?>
                    <li class="sidebar-item <?= $this->uri->segment(2) == 'permintaan-darah' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/permintaan-darah') ?>" class='sidebar-link'>
                            <i data-feather="file-text" width="20"></i>
                            <span>Permintaan Darah</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($permissions['transaksi-darah'])): ?>
                    <li class="sidebar-item <?= $this->uri->segment(2) == 'transaksi-darah' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/transaksi-darah') ?>" class='sidebar-link'>
                            <i data-feather="file-text" width="20"></i>
                            <span>Transaksi Darah</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($permissions['laporan'])): ?>
                    <li class="sidebar-item <?= $this->uri->segment(2) == 'laporan' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/laporan') ?>" class='sidebar-link'>
                            <i data-feather="file-text" width="20"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($permissions['donor-darah'])): ?>
                    <li class="sidebar-item <?= $this->uri->segment(1) == 'donor-darah' ? 'active' : '' ?>">
                        <a href="<?= base_url('donor-darah') ?>" class='sidebar-link'>
                            <i data-feather="database" width="20"></i>
                            <span>Donor Darah</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>