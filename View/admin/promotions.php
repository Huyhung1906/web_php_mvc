<?php
require_once('../../Controller/admincontroller/promotionController.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khuyến Mãi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="slidebar.css">
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

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            padding: 8px 12px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-bar button {
            padding: 8px 15px;
            background-color: #1a1f37;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #2c3149;
        }

        .add-button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .add-button:hover {
            background-color: #45a049;
        }

        .table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background: #1a1f37;
            color: white;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .actions a {
            color: #333;
            text-decoration: none;
            padding: 5px;
        }

        .actions a:hover {
            color: #1a1f37;
        }

        .actions a.delete {
            color: #dc3545;
        }

        .actions a.delete:hover {
            color: #c82333;
        }

        .status-select,
        .discount-type-select {
            padding: 6px 12px;
            border: 1.5px solid #4CAF50;
            border-radius: 5px;
            background: #f9f9f9;
            color: #1a1f37;
            font-size: 15px;
            font-weight: 500;
            transition: border 0.2s, background 0.2s;
            min-width: 120px;
            cursor: pointer;
            outline: none;
            margin: 0;
        }

        .status-select:focus,
        .discount-type-select:focus {
            border: 1.5px solid #388e3c;
            background: #fff;
        }

        .edit-link i {
            color: #2980b9;
            /* Xanh cho biểu tượng bút */
            transition: color 0.3s ease;
        }

        .delete-link i {
            color: #dc3545;
            /* Đỏ cho biểu tượng thùng rác */
            transition: color 0.3s ease;
        }

        .no-permission i {
            color: gray;
            /* Màu xám cho biểu tượng */
            pointer-events: none;
            /* Ngăn không cho người dùng click vào */
        }

        .no-permission-link {
            color: gray !important;
            /* Màu xám cho liên kết */
            pointer-events: none;
            /* Ngăn không cho người dùng click vào */
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include('slidebar.php'); ?>
        <div class="main-content">
            <div class="header">
                <form method="GET" class="search-bar">
                    <input type="text" name="search" placeholder="Tìm kiếm khuyến mãi..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Tìm</button>
                </form>
                <?php if ($check->canPerformAction($_SESSION['id_role'], 24)) { ?>
                    <a href="promotion_product.php" class="add-button">Sản Phẩm Khuyến Mãi</a>
                <?php } else { ?>
                    <a href="javascript:void(0);" class="no-permission-link">Sản Phẩm Khuyến Mãi</a> <!-- Liên kết màu xám khi không có quyền -->
                <?php } ?>

                <?php if ($check->canPerformAction($_SESSION['id_role'], 16)) { ?>
                    <a href="add_promotion.php" class="add-button">+ Thêm Khuyến mãi</a>
                <?php } else { ?>
                    <a href="javascript:void(0);" class="no-permission-link">+ Thêm Khuyến mãi</a> <!-- Liên kết màu xám khi không có quyền -->
                <?php } ?>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khuyến mãi</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Loại giảm</th>
                        <th>Giá trị</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($promotions) && is_array($promotions)): ?>
                        <?php foreach ($promotions as $promotion): ?>
                            <tr>
                                <td><?php echo $promotion['id_promotions']; ?></td>
                                <td><?php echo $promotion['name_promotion']; ?></td>
                                <td><?php echo $promotion['start_date']; ?></td>
                                <td><?php echo $promotion['end_date']; ?></td>
                                <td>
                                    <form class="discount-type-form" data-id="<?php echo $promotion['id_promotions']; ?>">
                                        <select name="discount_type" class="discount-type-select">
                                            <option value="percentage" <?php if ($promotion['discount_type'] == 'percentage') echo 'selected'; ?>>Phần trăm (%)</option>
                                            <option value="fixed" <?php if ($promotion['discount_type'] == 'fixed') echo 'selected'; ?>>Số tiền (VNĐ)</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?php echo $promotion['discount_value']; ?></td>
                                <td><?php echo $promotion['description']; ?></td>
                                <td>
                                    <form class="status-form" data-id="<?php echo $promotion['id_promotions']; ?>">
                                        <select name="status" class="status-select">
                                            <option value="1" <?php if ($promotion['status'] == 1) echo 'selected'; ?>>Đang hoạt động</option>
                                            <option value="0" <?php if ($promotion['status'] == 0) echo 'selected'; ?>>Đã kết thúc</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="actions">
                                    <?php if ($check->canPerformAction($_SESSION['id_role'], 19)) { ?>
                                        <a href="promotions.php?delete=<?php echo $promotion['id_promotions']; ?>" class="delete" onclick="return confirm('Xóa khuyến mãi này?');"><i class="fas fa-trash"></i></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0);" class="no-permission">
                                            <i class="fas fa-trash"></i> <!-- Biểu tượng thùng rác màu xám -->
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center;">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.querySelectorAll('.status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                var form = this.closest('.status-form');
                var id = form.getAttribute('data-id');
                var status = this.value;
                fetch('../../Controller/admincontroller/promotionController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_promotions=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status)
                }).then(response => response.text()).then(data => {
                    if (data.trim() === 'success') {
                        alert('Cập nhật trạng thái thành công!');
                    } else {
                        alert('Cập nhật trạng thái thất bại!');
                    }
                });
            });
        });
        document.querySelectorAll('.discount-type-select').forEach(function(select) {
            select.addEventListener('change', function() {
                var form = this.closest('.discount-type-form');
                var id = form.getAttribute('data-id');
                var discount_type = this.value;
                fetch('../../Controller/admincontroller/promotionController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_promotions=' + encodeURIComponent(id) + '&discount_type=' + encodeURIComponent(discount_type)
                }).then(response => response.text()).then(data => {
                    if (data.trim() === 'success') {
                        alert('Cập nhật loại giảm thành công!');
                    } else {
                        alert('Cập nhật loại giảm thất bại!');
                    }
                });
            });
        });
    </script>
</body>

</html>