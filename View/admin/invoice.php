<?php
require_once('../../Controller/admincontroller/invoiceController.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Hóa Đơn</title>
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

        .sidebar {
            width: 50px;
            background-color: #1a1f37;
            color: white;
            padding: 20px 0;
            text-align: center;
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

        .status-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .status-form select.status-select {
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
        }

        .status-form select.status-select:focus {
            border: 1.5px solid #388e3c;
            background: #fff;
        }

        .status-form option {
            color: #1a1f37;
        }

        /* Màu sắc cho các biểu tượng khi có quyền */
        .edit-link i {
            color: #28a745;
            /* Màu xanh cho biểu tượng bút */
            transition: color 0.3s ease;
        }

        .delete-link i {
            color: #dc3545;
            /* Màu đỏ cho biểu tượng thùng rác */
            transition: color 0.3s ease;
        }

        /* Màu xám cho các biểu tượng khi không có quyền */
        .no-permission i {
            color: #6c757d;
            /* Màu xám cho các biểu tượng không có quyền */
        }

        /* Thêm hiệu ứng hover cho các biểu tượng */
        .edit-link i:hover,
        .delete-link i:hover {
            opacity: 0.8;
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
                    <input type="text" name="search" placeholder="Tìm kiếm hóa đơn..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Tìm</button>
                </form>
                <?php if ($check->canPerformAction($_SESSION['id_role'], 12)) { ?>
                <a href="add_invoice.php" class="add-button">+ Thêm Hóa đơn</a>
                <?php } else { ?>
                    <a href="javascript:void(0);" class="no-permission-link">+ Thêm Hóa đơn</a> <!-- Liên kết màu xám khi không có quyền -->
                <?php } ?>

            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID User</th>
                        <th>Tên Khách Hàng</th>
                        <th>Số điện thoại</th>
                        <th>Ngày Thêm Hóa Đơn</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Địa chỉ</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($invoice) && is_array($invoice)): ?>
                        <?php foreach ($invoice as $invoice): ?>
                            <tr>
                                <td><?php echo $invoice['id_invoice']; ?></td>
                                <td><?php echo $invoice['id_user']; ?></td>
                                <td><?php echo $invoice['CustomerName']; ?></td>
                                <td><?php echo $invoice['CustomerPhone']; ?></td>
                                <td><?php echo $invoice['InvoiceDate']; ?></td>
                                <td><?php echo $invoice['TotalAmount']; ?></td>
                                <td>
                                    <form class="status-form" data-id="<?php echo $invoice['id_invoice']; ?>">
                                        <select name="Status" class="status-select">
                                            <option value="Đang xử lý" <?php if ($invoice['Status'] == 'Đang xử lý') echo 'selected'; ?>>Đang xử lý</option>
                                            <option value="Đã xác nhận" <?php if($invoice['Status']=='Đã xác nhận') echo 'selected'; ?>>Đã xác nhận</option>
                                            <option value="Đang đóng hàng" <?php if($invoice['Status']=='Đang đóng hàng') echo 'selected'; ?>>Đang đóng hàng</option>
                                            <option value="Hoàn thành" <?php if ($invoice['Status'] == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                                            <option value="Đang giao" <?php if ($invoice['Status'] == 'Đang giao') echo 'selected'; ?>>Đang giao</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?php echo $invoice['CustomerAddress']; ?></td>
                                <td class="actions">
                                    <?php if ($check->canPerformAction($_SESSION['id_role'], 13) && $invoice['Status'] === 'Đang xử lý'): ?>
                                        <a href="invoice_detail.php?id=<?php echo $invoice['id_invoice']; ?>" class="edit-link">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="no-permission">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($check->canPerformAction($_SESSION['id_role'], 15) && $invoice['Status'] === 'Hoàn thành'): ?>
                                        <a href="invoice.php?delete=<?php echo $invoice['id_invoice']; ?>" class="delete-link" onclick="return confirm('Xóa Hóa đơn này?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="no-permission">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
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

                fetch('../../Controller/admincontroller/invoiceController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_invoice=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status)
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {

                            window.location.reload();
                        } else {
                            alert('Cập nhật trạng thái thất bại!');
                        }
                    });
            });
        });
    </script>
</body>

</html>