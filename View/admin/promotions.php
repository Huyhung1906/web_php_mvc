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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body { 
            background-color: #f5f7fb;
        }
        .container { 
            display: flex; 
            min-height: 100vh;
        }
        .main-content { 
            flex-grow: 1; 
            padding: 30px 40px;
        }
        .sidebar {
            width: 60px;
            background-color: #1a1f37;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .sidebar a {
            color: #a3a6b4;
            display: block;
            padding: 15px 0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background-color: #2c3149;
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 25px;
        }
        .search-bar { 
            display: flex; 
            gap: 8px;
        }
        .search-bar input { 
            padding: 8px 12px; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            width: 220px;
        }
        .search-bar button {
            padding: 8px 16px;
            background-color: #1a1f37;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-bar button:hover {
            background-color: #2c3149;
        }
        .header a {
            background-color: #1a1f37;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .header a:hover {
            background-color: #2c3149;
        }
        .table { 
            width: 100%; 
            background: white; 
            border-collapse: separate; 
            border-spacing: 0; 
            border-radius: 10px; 
            overflow: hidden; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td { 
            padding: 14px 12px; 
            border-bottom: 1px solid #eee; 
            text-align: center;
        }
        .table thead { 
            background-color: #1a1f37; 
            color: white;
        }
        .table tbody tr:hover { 
            background-color: #f0f2f8;
        }
        .actions { 
            display: flex; 
            gap: 10px; 
            justify-content: center;
        }
        .actions a { 
            margin: 0 6px; 
            text-decoration: none; 
            font-size: 18px; 
            transition: transform 0.2s ease;
        }
        .actions a:hover { 
            transform: scale(1.2);
        }
        .actions a.delete { 
            color: #e74c3c;
        }
        .actions a.edit { 
            color: #2980b9;
        }
        .status-select, .discount-type-select {
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
        .status-select:focus, .discount-type-select:focus {
            border: 1.5px solid #388e3c;
            background: #fff;
        }
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-bottom: 10px;
        }
        .filter-bar .filter-input,
        .filter-bar input[type="text"],
        .filter-bar input[type="date"],
        .filter-bar select {
            min-width: 180px;
            max-width: 250px;
            padding: 7px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 15px;
            background: #fff;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            .search-bar input {
                width: 100%;
            }
            .table th,
            .table td {
                font-size: 14px;
                padding: 10px 6px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include('slidebar.php'); ?>
        <div class="main-content">
            <div class="header">
                <form method="GET" class="search-bar filter-bar">
                    <input type="text" name="search" placeholder="Tìm kiếm khuyến mãi..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Tìm</button>
                </form>
                <a href="promotion_product.php" class="add-button">Sản Phẩm Khuyến Mãi</a>
                <a href="add_promotion.php" class="add-button">+ Thêm Khuyến mãi</a>
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
                    <?php if(isset($promotions) && is_array($promotions)): ?>
                        <?php foreach ($promotions as $promotion): ?>
                            <tr>
                                <td><?php echo $promotion['id_promotions']; ?></td>
                                <td><?php echo $promotion['name_promotion']; ?></td>
                                <td><?php echo $promotion['start_date']; ?></td>
                                <td><?php echo $promotion['end_date']; ?></td>
                                <td>
                                    <form class="discount-type-form" data-id="<?php echo $promotion['id_promotions']; ?>">
                                        <select name="discount_type" class="discount-type-select">
                                            <option value="percentage" <?php if($promotion['discount_type']=='percentage') echo 'selected'; ?>>Phần trăm (%)</option>
                                            <option value="fixed" <?php if($promotion['discount_type']=='fixed') echo 'selected'; ?>>Số tiền (VNĐ)</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?php echo $promotion['discount_value']; ?></td>
                                <td><?php echo $promotion['description']; ?></td>
                                <td>
                                    <form class="status-form" data-id="<?php echo $promotion['id_promotions']; ?>">
                                        <select name="status" class="status-select">
                                            <option value="1" <?php if($promotion['status']=='1') echo 'selected'; ?>>Đang hoạt động</option>
                                            <option value="0" <?php if($promotion['status']=='0') echo 'selected'; ?>>Không hoạt động</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="actions">
                                    <a href="edit_promotion.php?id=<?php echo $promotion['id_promotions']; ?>" class="edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_promotion.php?id=<?php echo $promotion['id_promotions']; ?>" class="delete" onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')"><i class="fas fa-trash"></i></a>
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
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id_promotions=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status)
            }).then(response => response.text()).then(data => {
                if(data.trim() === 'success') {
                    window.location.reload();
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
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id_promotions=' + encodeURIComponent(id) + '&discount_type=' + encodeURIComponent(discount_type)
            }).then(response => response.text()).then(data => {
                if(data.trim() === 'success') {
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
