<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Pendonor</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class='breadcrumb-header'>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Donor</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section mt-4">
        <div class="card col-7">
            <div class="card-header">
            </div>
            <div class="card-body">
                <table class='table table-striped' id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Golongan Darah</th>
                            <th>Total Pendonor</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <?php if (!empty($donor_data)): ?>
                            <?php foreach ($donor_data as $index => $donor): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($donor->golongan_darah_name) ?></td>
                                    <td><?= htmlspecialchars($donor->total) ?></td>
                                    <td>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        <?php endif; ?> -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>