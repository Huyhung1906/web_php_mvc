<?php
require_once('../../Controller/admincontroller/productVariant_edit.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa biến thể sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Sửa biến thể sản phẩm</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Sản phẩm</label>
            <select class="form-select" name="product" required>
                <option value="">Chọn sản phẩm</option>
                <?php foreach ($products as $p): ?>
                    <option value="<?= $p['id_product'] ?>" <?= ($variant['id_product'] == $p['id_product']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name_product']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kích cỡ</label>
            <select class="form-select" name="size" required>
                <option value="">Chọn kích cỡ</option>
                <?php foreach ($sizes as $sz): ?>
                    <option value="<?= $sz['id_size'] ?>" <?= ($variant['id_size'] == $sz['id_size']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sz['size_value']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Màu sắc</label>
            <select class="form-select" name="color" required>
                <option value="">Chọn màu sắc</option>
                <?php foreach ($colors as $col): ?>
                    <option value="<?= $col['id_color'] ?>" <?= ($variant['id_color'] == $col['id_color']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($col['color_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Số lượng</label>
            <input type="number" class="form-control" name="quantity" min="0" required value="<?= htmlspecialchars($variant['quantity']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày hết hạn</label>
            <input type="date" class="form-control" name="expired_date" required value="<?= htmlspecialchars($variant['expired_date']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="product_variants.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html> 