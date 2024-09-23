<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Laporan</h3>
            </div>
        </div>
    </div>
    <section class="section mt-4">
        <div class="card">
            <div class="card-body">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Tanggal Dari</label>
                            <input type="date" name="tanggal_dari" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Sampai</label>
                            <input type="date" name="tanggal_sampai" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Jenis Transaksi</label>
                            <select name="jenis_transaksi" class="form-control">
                                <option value="">Semua</option>
                                <option value="Donasi">Donasi</option>
                                <option value="Penggunaan">Penggunaan</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Download Laporan</button>
                </form>
            </div>
        </div>
    </section>
</div>