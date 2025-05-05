<!-- phanquyen.php -->
<h2>Gán quyền cho: <?= $role['role_name'] ?></h2>
<form method="POST" action="RoleController.php">
    <input type="hidden" name="action" value="update_permissions">
    <input type="hidden" name="id_role" value="<?= $role['id_role'] ?>">

    <?php foreach ($all_permissions as $perm): ?>
        <label>
            <input type="checkbox" name="permissions[]" value="<?= $perm['id_chitietrole'] ?>"
                <?= in_array($perm['id_chitietrole'], $current_role_permissions) ? 'checked' : '' ?>>
            <?= $perm['ten_chitietrole'] ?>
        </label><br>
    <?php endforeach; ?>
    <button type="submit">Cập nhật quyền</button>
</form>
