<form class="form form-horizontal" id="formSubmit">
    <input type="hidden" id="action" name="action" value="<?= $action ?>">
    <?php if ($action == 'edit'): ?>
        <input type="hidden" id="id" name="id" value="<?= $golonganDarah->id ?>">
    <?php endif; ?>
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <label>Golongan Darah</label>
            </div>
            <div class="col-md-8 form-group">
                <select id="golongan_darah" name="golongan_darah" class="form-control">
                    <option value="">Pilih Golongan Darah</option>
                    <?php foreach ($golongan_darah as $golongan): ?>
                        <option value="<?= $golongan->id ?>" <?= isset($stokDarah) && $stokDarah->golongan_darah == $golongan->id ? 'selected' : '' ?>>
                            <?= $golongan->golongan_darah ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Jumlah Stok</label>
            </div>
            <div class="col-md-8 form-group">
                <input type="number" id="jumlah" class="form-control" name="jumlah" value="<?= isset($stokDarah) ? $stokDarah->jumlah : '' ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Tanggal Expired</label>
            </div>
            <div class="col-md-8 form-group">
                <input type="date" id="tanggal_exp" class="form-control" name="tanggal_exp" value="<?= isset($stokDarah) ? $stokDarah->tanggal_exp : '' ?>">
            </div>
        </div>
    </div>
</form>