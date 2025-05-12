<?php
require_once('../../Controller/admincontroller/StatisticsController.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê khách hàng</title>
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1f37;
            margin: 0;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 32px;
            border: none;
        }
        .card-body {
            padding: 24px 20px;
        }
        form.row.g-3 {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .form-label {
            font-weight: 600;
            color: #1a1f37;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            font-size: 1rem;
            background: #f9fafb;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: none;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2980b9;
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 2px #eaf6fb;
        }
        .btn-primary {
            background: #2980b9;
            border: none;
            border-radius: 8px;
            padding: 10px 22px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #1a5a8a;
        }
        .btn-info {
            background: #1a1f37;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 500;
            padding: 6px 16px;
            transition: background 0.2s;
        }
        .btn-info:hover {
            background: #2980b9;
            color: #fff;
        }
        .table {
            width: 100%;
            background: white;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
            margin-bottom: 0;
        }
        .table th,
        .table td {
            padding: 14px 12px;
            border-bottom: 1px solid #f0f2f8;
            text-align: left;
            font-size: 1rem;
            vertical-align: middle;
        }
        .table thead {
            background-color:rgb(149, 167, 255);
            color: Black;
        }
        .table tbody tr:hover {
            background-color: #f0f2f8;
        }
        .table-sm th, .table-sm td {

            padding: 8px 8px;
            font-size: 0.98rem;
        }
        .fw-bold.text-primary {
            color: #2980b9 !important;
            font-weight: 700;
        }
        .alert-info {
            background: #eaf6fb;
            color: #2980b9;
            border: none;
            border-radius: 8px;
            padding: 18px 20px;
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        .badge {
            font-size: 0.95rem;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }
        .card.card-body.bg-light {
            background: #f9fafb !important;
            border-radius: 10px;
            margin-top: 10px;
        }
        /* Responsive */
        @media (max-width: 900px) {
            .main-content {
                padding: 18px 6px;
            }
            .card-body {
                padding: 16px 6px;
            }
            .table th, .table td {
                font-size: 0.95rem;
                padding: 8px 4px;
            }
        }
        @media (max-width: 600px) {
            .header h2 {
                font-size: 1.2rem;
            }
            .form-label, .btn-primary, .btn-info {
                font-size: 0.95rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php include('slidebar.php'); ?>
    <div class="main-content">
        <div class="header">
            <h2>Thống kê 5 khách hàng mua hàng nhiều nhất</h2>
        </div>
        <div class="card mb-4" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <div class="card-body">
                <form method="GET" class="row g-3" style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div>
                        <label for="start_date" class="form-label">Từ ngày:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                               value="<?php echo htmlspecialchars($startDate); ?>" required>
                    </div>
                    <div>
                        <label for="end_date" class="form-label">Đến ngày:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                               value="<?php echo htmlspecialchars($endDate); ?>" required>
                    </div>
                    <div>
                        <label for="order_by" class="form-label">Sắp xếp:</label>
                        <select class="form-select" id="order_by" name="order_by">
                            <option value="DESC" <?php echo $orderBy === 'DESC' ? 'selected' : ''; ?>>Giảm dần</option>
                            <option value="ASC" <?php echo $orderBy === 'ASC' ? 'selected' : ''; ?>>Tăng dần</option>
                        </select>
                    </div>
                    <div style="align-self: flex-end;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Xem thống kê
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ tên</th>
                            <th>Tên đăng nhập</th>
                            <th>Số đơn hàng</th>
                            <th>Tổng tiền mua</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($topCustomers as $index => $customer): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($customer['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                <td><?php echo number_format($customer['total_orders']); ?></td>
                                <td class="fw-bold text-primary">
                                    <?php echo number_format($customer['total_purchase'], 0, ',', '.'); ?> VNĐ
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="6" class="p-0">
                                    <div class="collapse" id="orders-<?php echo $customer['id_user']; ?>">
                                        <div class="card card-body bg-light">
                                            <h6 class="mb-3">
                                                <i class="fas fa-shopping-cart"></i>
                                                Danh sách đơn hàng của <?php echo htmlspecialchars($customer['fullname']); ?>
                                            </h6>
                                            <table class="table table-sm">
                                                <thead>
                                                <tr>
                                                    <th>Mã đơn hàng</th>
                                                    <th>Ngày đặt</th>
                                                    <th>Số sản phẩm</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($customerOrders[$customer['id_user']] as $order): ?>
                                                    <tr>
                                                        <td>#<?php echo $order['id_invoice']; ?></td>
                                                        <td><?php echo date('d/m/Y', strtotime($order['InvoiceDate'])); ?></td>
                                                        <td><?php echo number_format($order['total_items']); ?></td>
                                                        <td class="fw-bold">
                                                            <?php echo number_format($order['TotalAmount'], 0, ',', '.'); ?> VNĐ
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $statusClass = '';
                                                            switch($order['Status']) {
                                                                case 'pending':
                                                                    $statusClass = 'warning';
                                                                    $statusText = 'Chờ xử lý';
                                                                    break;
                                                                case 'processing':
                                                                    $statusClass = 'info';
                                                                    $statusText = 'Đang xử lý';
                                                                    break;
                                                                case 'completed':
                                                                case 'Hoàn thành':
                                                                    $statusClass = 'success';
                                                                    $statusText = 'Hoàn thành';
                                                                    break;
                                                                case 'cancelled':
                                                                    $statusClass = 'danger';
                                                                    $statusText = 'Đã hủy';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'secondary';
                                                                    $statusText = $order['Status'];
                                                            }
                                                            ?>
                                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                                <?php echo $statusText; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="invoice_detail.php?id=<?php echo $order['id_invoice']; ?>"
                                                               class="btn btn-primary btn-sm">
                                                                <i class="fas fa-eye"></i> Xem chi tiết
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');

    // Khi thay đổi ngày bắt đầu, cập nhật min cho ngày kết thúc
    startInput.addEventListener('change', function() {
        endInput.min = this.value;
        if (endInput.value < this.value) {
            endInput.value = this.value;
        }
    });

    // Khi thay đổi ngày kết thúc, nếu nhỏ hơn ngày bắt đầu thì đặt lại
    endInput.addEventListener('change', function() {
        if (this.value < startInput.value) {
            this.value = startInput.value;
        }
    });

    // Khởi tạo min cho ngày kết thúc khi load trang
    window.addEventListener('DOMContentLoaded', function() {
        if (startInput.value) {
            endInput.min = startInput.value;
        }
    });
</script>
</body>
</html> 