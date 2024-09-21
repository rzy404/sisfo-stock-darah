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
                <input type="text" id="golongan_darah" class="form-control" name="golongan_darah" value="<?= isset($golonganDarah) ? $golonganDarah->golongan_darah : '' ?>" required>
            </div>
        </div>
    </div>
</form>