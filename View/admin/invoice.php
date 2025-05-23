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
            height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .sidebar {
            background-color: #1a1f37;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }

        .sidebar a {
            color: #a3a6b4;
            display: block;
            padding: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background-color: #2c3149;
        }

        .main-content {
            flex-grow: 1;
            padding: 10px 30px;
            margin-left: 60px;
            width: calc(100% - 60px);
            z-index: 1000;
            height: 100vh;
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
                <form method="GET" class="search-bar" style="flex-wrap: wrap; gap: 10px; width: 100%;">
                    <input type="text" name="search" placeholder="Tìm kiếm hóa đơn..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <select name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="Đang xử lý" <?php if (isset($_GET['status']) && $_GET['status'] == 'Đang xử lý') echo 'selected'; ?>>Đang xử lý</option>
                        <option value="Đã xác nhận" <?php if (isset($_GET['status']) && $_GET['status'] == 'Đã xác nhận') echo 'selected'; ?>>Đã xác nhận</option>
                        <option value="Đang đóng hàng" <?php if (isset($_GET['status']) && $_GET['status'] == 'Đang đóng hàng') echo 'selected'; ?>>Đang đóng hàng</option>
                        <option value="Đang giao" <?php if (isset($_GET['status']) && $_GET['status'] == 'Đang giao') echo 'selected'; ?>>Đang giao</option>
                        <option value="Hoàn thành" <?php if (isset($_GET['status']) && $_GET['status'] == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                    </select>
                    <input type="date" name="from_date" value="<?php echo isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : ''; ?>" placeholder="Từ ngày">
                    <input type="date" name="to_date" value="<?php echo isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : ''; ?>" placeholder="Đến ngày">
                    <select name="province" id="province-select">
                        <option value="">Tất cả tỉnh/thành</option>
                        <?php foreach ($provinces as $province): ?>
                            <option value="<?php echo htmlspecialchars($province); ?>" <?php if (isset($_GET['province']) && $_GET['province'] == $province) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($province); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="district" id="district-select">
                        <option value="">Tất cả quận/huyện</option>
                        <!-- Các option sẽ được JS fill dựa trên tỉnh/thành đã chọn -->
                    </select>
                    <button type="submit">Lọc</button>
                    <a href="invoice.php" class="btn btn-secondary" style="padding: 8px 15px; background: #ccc; color: #222; border-radius: 4px; text-decoration: none;">Đặt lại</a>
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
                                            <option value="Đã xác nhận" <?php if ($invoice['Status'] == 'Đã xác nhận') echo 'selected'; ?>>Đã xác nhận</option>
                                            <option value="Đang đóng hàng" <?php if ($invoice['Status'] == 'Đang đóng hàng') echo 'selected'; ?>>Đang đóng hàng</option>
                                            <option value="Đang giao" <?php if ($invoice['Status'] == 'Đang giao') echo 'selected'; ?>>Đang giao</option>
                                            <option value="Hoàn thành" <?php if ($invoice['Status'] == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
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
            // Lưu trạng thái ban đầu
            const originalStatus = select.value;

            select.addEventListener('change', function() {
                const statusOrder = ['Đang xử lý', 'Đã xác nhận', 'Đang đóng hàng', 'Đang giao', 'Hoàn thành'];
                const currentIndex = statusOrder.indexOf(originalStatus);
                const newIndex = statusOrder.indexOf(this.value);

                // Kiểm tra nếu cố gắng quay ngược trạng thái
                if (newIndex < currentIndex) {
                    alert('Không thể quay ngược trạng thái đơn hàng!');
                    this.value = originalStatus; // Reset về trạng thái cũ
                    return;
                }

                // Kiểm tra nếu bỏ qua trạng thái
                if (newIndex - currentIndex > 1) {
                    alert('Vui lòng cập nhật trạng thái theo thứ tự!');
                    this.value = originalStatus; // Reset về trạng thái cũ
                    return;
                }

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
                            this.value = originalStatus; // Reset về trạng thái cũ nếu cập nhật thất bại
                        }
                    })
                    .catch(error => {
                        alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                        this.value = originalStatus; // Reset về trạng thái cũ nếu có lỗi
                    });
            });
        });

        // Dữ liệu quận/huyện theo tỉnh/thành từ PHP sang JS
        const districtsByProvince = <?php echo json_encode($districtsByProvince); ?>;
        const provinceSelect = document.getElementById('province-select');
        const districtSelect = document.getElementById('district-select');

        function fillDistricts() {
            const province = provinceSelect.value;
            districtSelect.innerHTML = '<option value="">Tất cả quận/huyện</option>';
            if (province && districtsByProvince[province]) {
                districtsByProvince[province].forEach(function(d) {
                    const selected = (d === '<?php echo isset($_GET['district']) ? addslashes($_GET['district']) : ''; ?>') ? 'selected' : '';
                    districtSelect.innerHTML += `<option value="${d}" ${selected}>${d}</option>`;
                });
            }
        }
        provinceSelect.addEventListener('change', fillDistricts);
        window.addEventListener('DOMContentLoaded', fillDistricts);
    </script>
</body>

</html>