<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Dashboard Sistem Informasi Stok Darah</h3>
        <p class="text-subtitle text-muted">Monitor stok darah secara realtime</p>
    </div>
    <section class="section">
        <div class="row mb-2">
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>Total Stok Darah</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p id="totalBloodStock">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>Donor Bulan Ini</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p id="donorsThisMonth">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>Permintaan Pending</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p id="pendingRequests">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>Transaksi Bulan Ini</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p id="transactionsThisMonth">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class='card-heading p-1 pl-3'>Stok Darah per Golongan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="bloodStockChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class='card-heading p-1 pl-3'>Tren Donasi vs Penggunaan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="donationUsageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>