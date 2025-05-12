<?php
require_once('../../Controller/admincontroller/productVariant.php');

// Hiển thị alert thành công
if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) {
        echo '<script>alert("Thêm biến thể thành công!");</script>';
    } elseif ($_GET['success'] == 2) {
        echo '<script>alert("Cập nhật biến thể thành công!");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý biến thể sản phẩm</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="/web_php_mvc/View/admin/slidebar.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 50px;
            background-color: #1a1f37;
            color: white;
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            color: #a3a6b4;
            display: block;
            padding: 15px;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background-color: #2c3149;
        }

        .main-content {
            margin-left: auto;
            flex-grow: 1;
            padding: 20px;
        }

        .action-buttons .btn {
            margin: 0 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <?php include(__DIR__ . '/slidebar.php'); ?>
        </div>

        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý biến thể sản phẩm</h2>
                <a href="add_productVariant.php" class="btn btn-primary mb-3">Thêm biến thể mới</a>
            </div>

            <!-- Bộ lọc -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="filterForm" class="row g-3" method="get" action="">
                        <div class="col-md-4">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" name="search" value="<?= isset(
                            $search) ? htmlspecialchars($search) : '' ?>" placeholder="Nhập tên sản phẩm...">
                        </div>
                       
                        <div class="col-md-3">
                            <label class="form-label">Kích cỡ</label>
                            <select class="form-select" id="size" name="size">
                                <option value="">Tất cả</option>
                                <?php if (isset($sizes) && !empty($sizes)): ?>
                                    <?php foreach ($sizes as $sz): ?>
                                        <option value="<?php echo $sz['id_size']; ?>" <?php if ($filter_size == $sz['id_size']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($sz['size_value']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Màu sắc</label>
                            <select class="form-select" id="color" name="color">
                                <option value="">Tất cả</option>
                                <?php if (isset($colors) && !empty($colors)): ?>
                                    <?php foreach ($colors as $col): ?>
                                        <option value="<?php echo $col['id_color']; ?>" <?php if ($filter_color == $col['id_color']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($col['color_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Lọc</button>
                            <a href="?" class="btn btn-secondary">Đặt lại</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bảng biến thể -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Kích cỡ</th>
                                    <th>Màu sắc</th>
                                    <th>Số lượng</th>
                                    <th>Hết hạn</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($variants)): ?>
                                    <?php foreach ($variants as $v): ?>
                                        <tr>
                                            <td><?= $v['id_variant'] ?></td>
                                            <td><?= htmlspecialchars($v['name_product']) ?></td>
                                            <td><?= htmlspecialchars($v['size_value']) ?></td>
                                            <td><?= htmlspecialchars($v['color_name']) ?></td>
                                            <td><?= $v['quantity'] ?></td>
                                            <td><?= $v['expired_date'] ?></td>
                                            <td class="action-buttons">
                                                <a href="edit_productVariant.php?id=<?= $v['id_variant'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <a href="?delete=<?= $v['id_variant'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể này?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Không tìm thấy biến thể nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa biến thể này?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>