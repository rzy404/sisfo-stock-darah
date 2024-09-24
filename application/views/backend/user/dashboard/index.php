<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Dashboard</h3>
        <p class="text-subtitle text-muted">Selamat datang di dashboard Sisfo Darah untuk memantau informasi dan riwayat donor darah Anda secara realtime.</p>
    </div>
    <section class="section">
        <div class="row mb-2">
            <?php foreach ($totalBloodStock as $stock): ?>
                <div class="col-12 col-md-3">
                    <div class="card card-statistic <?php echo $stock->total == 0 ? 'no-stock' : ''; ?>">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class="card-title"><?php echo $stock->golongan_darah; ?></h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p class="card-right d-flex align-items-center"><?php echo $stock->total; ?> </p>
                                        <p> Kantong</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class='card-heading'>Biodata</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($biodata->tanggal_lahir) || empty($biodata->jenis_kelamin) || empty($biodata->nomor_telepon) || empty($biodata->alamat) || empty($biodata->golongan_darah)): ?>
                            <form action="<?= base_url('biodata/simpan'); ?>" method="post">
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" class="form-control" id="nik" name="nik" value="<?= isset($biodata->nik) ? $biodata->nik : ''; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= isset($biodata->nama) ? strtoupper($biodata->nama) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= isset($biodata->tanggal_lahir) ? $biodata->tanggal_lahir : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" <?= isset($biodata->jenis_kelamin) && $biodata->jenis_kelamin == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="Perempuan" <?= isset($biodata->jenis_kelamin) && $biodata->jenis_kelamin == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="golongan_darah">Golongan Darah</label>
                                    <select class="form-control" id="golongan_darah" name="golongan_darah">
                                        <option value="">Pilih Golongan Darah</option>
                                        <?php foreach ($golongan_darah as $golongan): ?>
                                            <option value="<?= $golongan->id ?>" <?= isset($biodata->golongan_darah) && $biodata->golongan_darah == $golongan->id ? 'selected' : ''; ?>>
                                                <?= $golongan->golongan_darah ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nomor_telepon">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="<?= isset($biodata->nomor_telepon) ? $biodata->nomor_telepon : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat"><?= isset($biodata->alamat) ? $biodata->alamat : ''; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Simpan</button>
                            </form>
                        <?php else: ?>
                            <div>
                                <p><strong>Nama:</strong> <?= isset($biodata->nama) ? $biodata->nama : ''; ?></p>
                                <p><strong>NIK:</strong> <?= isset($biodata->nik) ? $biodata->nik : ''; ?></p>
                                <p><strong>Tanggal Lahir:</strong> <?= isset($biodata->tanggal_lahir) ? date('d-m-Y', strtotime($biodata->tanggal_lahir)) : ''; ?></p>
                                <p><strong>Jenis Kelamin:</strong> <?= isset($biodata->jenis_kelamin) ? $biodata->jenis_kelamin : ''; ?></p>
                                <p><strong>Nomor Telepon:</strong> <?= isset($biodata->nomor_telepon) ? $biodata->nomor_telepon : ''; ?></p>
                                <p><strong>Alamat:</strong> <?= isset($biodata->alamat) ? $biodata->alamat : ''; ?></p>
                                <p><strong>Golongan Darah:</strong> <?= isset($biodata->golongan_darah) ? $biodata->golongan_darah : ''; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card widget-todo">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-heading">Riwayat Donor</h3>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <table class="table mb-0" id="table1">
                                <thead>
                                    <tr>
                                        <th>Nama Pendonor</th>
                                        <th>Email</th>
                                        <th>Golongan Darah</th>
                                        <th>Tanggal Donor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendonor as $donor) { ?>
                                        <tr>
                                            <td><?= $donor['nama_pendonor']; ?></td>
                                            <td><?= $donor['email']; ?></td>
                                            <td><?= $donor['golongan_darah']; ?></td>
                                            <td><?= $donor['tanggal_donor_terakhir']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>