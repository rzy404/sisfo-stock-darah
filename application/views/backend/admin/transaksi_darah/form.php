<form class="form form-horizontal" id="formSubmit">
    <input type="hidden" id="action" name="action" value="<?= $action ?>">
    <?php if ($action == 'edit'): ?>
        <input type="hidden" id="id" name="id" value="<?= $pengguna->id ?>">
    <?php endif; ?>
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <label>Nama Lengkap</label>
            </div>
            <div class="col-md-8 form-group">
                <input type="text" id="nama_lengkap" class="form-control" name="nama_lengkap" value="<?= isset($pengguna) ? $pengguna->nama : '' ?>">
            </div>
            <div class="col-md-4">
                <label>Email</label>
            </div>
            <div class="col-md-8 form-group">
                <input type="email" id="email" class="form-control" name="email" value="<?= isset($pengguna) ? $pengguna->email : '' ?>">
            </div>
            <div class="col-md-4">
                <label>Password</label>
            </div>
            <div class="col-md-8 form-group">
                <input type="password" id="password" class="form-control" name="password">
                <?php if ($action == 'edit'): ?>
                    <small>Kosongkan jika tidak ingin mengganti password.</small>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <label>Role</label>
            </div>
            <div class="col-md-8 form-group">
                <select class="form-select" id="role" name="role">
                    <option value="">Pilih Role</option>
                    <option value="Admin" <?= isset($pengguna) && $pengguna->role == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Staf" <?= isset($pengguna) && $pengguna->role == 'Staf' ? 'selected' : '' ?>>Staf</option>
                </select>
            </div>
        </div>
    </div>
</form>