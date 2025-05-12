<?php
require_once('../../Controller/admincontroller/warranty.php');
$repairStatusOptions = [
    'Đã gửi đơn',
    'Xác nhận',
    'Từ chối bảo hành',
    'Đang bảo hành',
    'Đang gửi hàng',
    'Hoàn thành'
];
if (isset($_GET['saved'])) {
    echo '<script>
       
        if (window.history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.delete("saved");
            window.history.replaceState({}, document.title, url.pathname + url.search);
        }
    </script>';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bảo hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/web_php_mvc/View/admin/slidebar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            background-color: #1a1f37;
            color: white;
 
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
        }

        .sidebar a {
            color: #a3a6b4;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover, .sidebar a.active {
            color: white;
            background-color: #2c3149;
        }

        .main-content {
            flex-grow: 1;
            padding: 12px;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .action-buttons .btn {
            margin: 0 2px;
        }

        .warranty-title {
            margin-bottom: 32px;
            font-weight: 600;
            font-size: 2rem;
        }
        .warranty-section {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 24px 20px 20px 20px;
            margin-bottom: 32px;
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
        .table td, .table th {
            vertical-align: middle !important;
            font-size: 15px;
        }
        .table th:first-child, .table td:first-child {
            border-top-left-radius: 5px;
        }
        .table th:last-child, .table td:last-child {
            border-top-right-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container-fluid" style="max-width: 1200px; margin: 30px auto 40px auto;">
    <div class="container">
        <div class="sidebar">
            <?php include(__DIR__ . '/slidebar.php'); ?>
        </div>
        <div class="main-content" style="flex-grow:1; padding: 2px;">
            <h2>Quản lý bảo hành</h2>
            <div class="search-box bg-white rounded shadow-sm p-3 mb-4">
                <form class="row g-3 mb-4" method="get">
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái bảo hành</label>
                        <select class="form-select" name="repair_status">
                            <option value="">Tất cả</option>
                            <?php foreach ($repairStatusOptions as $status): ?>
                                <option value="<?= $status ?>" <?= (isset($filter_status) && $filter_status == $status) ? 'selected' : '' ?>><?= $status ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" name="from_date" value="<?= isset($filter_from) ? htmlspecialchars($filter_from) : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" name="to_date" value="<?= isset($filter_to) ? htmlspecialchars($filter_to) : '' ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Lọc</button>
                        <a href="warranty.php" class="btn btn-secondary">Đặt lại</a>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Cost</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($warrantyDetails)): ?>
                            <?php usort($warrantyDetails, function($a, $b) { return $a['id_warrantydetail'] - $b['id_warrantydetail']; }); ?>
                            <?php foreach ($warrantyDetails as $row): ?>
                                <tr>
                                    <form method="post" style="display:contents;">
                                        <td><?= $row['id_warrantydetail'] ?></td>
                                        <td><?= htmlspecialchars($row['id_invoice']) ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                        <td><?= htmlspecialchars($row['name_product']) ?></td>
                                        <td><?= $row['repair_date'] ?></td>
                                        <td>
                                            <select class="form-select" name="repair_status">
                                                <?php foreach ($repairStatusOptions as $status): ?>
                                                    <option value="<?= $status ?>" <?= $row['repair_status'] == $status ? 'selected' : '' ?>><?= $status ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="cost" value="<?= htmlspecialchars($row['cost']) ?>">
                                        </td>
                                        <td style="min-width:150px;max-width:300px;">
                                            <textarea class="form-control" name="notes" rows="2"><?= htmlspecialchars($row['notes']) ?></textarea>
                                        </td>
                                        <td>
                                            <input type="hidden" name="id_warrantydetail" value="<?= $row['id_warrantydetail'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Lưu</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">Không có dữ liệu</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html> 