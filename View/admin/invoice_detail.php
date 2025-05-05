<?php
require_once('../../Controller/admincontroller/invoiceDetailController.php');
$id_invoice = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết hóa đơn #<?php echo htmlspecialchars($id_invoice); ?></title>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
            color: #1a1f37;
            margin-top: 30px;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .detail-table th, .detail-table td {
            border: 1px solid #ddd;
            padding: 12px 10px;
            text-align: center;
            font-size: 15px;
        }
        .detail-table th {
            background: #1a1f37;
            color: #fff;
            font-weight: bold;
        }
        .detail-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .detail-table tr:hover {
            background: #f1f1f1;
        }
        .actions a {
            margin: 0 5px;
            color: #333;
            text-decoration: none;
            font-size: 15px;
            padding: 3px 8px;
            border-radius: 4px;
            transition: background 0.2s, color 0.2s;
        }
        .actions a.delete {
            color: #dc3545;
            border: 1px solid #dc3545;
        }
        .actions a.delete:hover {
            background: #dc3545;
            color: #fff;
        }
        .add-form {
            max-width: 500px;
            margin: 0 auto 30px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 24px 20px 18px 20px;
        }
        .add-form .form-group {
            margin-bottom: 18px;
        }
        .add-form label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-weight: 500;
        }
        .add-form select,
        .add-form input[type="number"] {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            background: #f9f9f9;
            transition: border 0.2s;
            margin-bottom: 5px;
        }
        .add-form select:focus,
        .add-form input[type="number"]:focus {
            border: 1.5px solid #4CAF50;
            outline: none;
            background: #fff;
        }
        .add-form button[type="submit"] {
            width: 100%;
            padding: 12px 0;
            background: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
            letter-spacing: 1px;
        }
        .add-form button[type="submit"]:hover {
            background: #388e3c;
        }
        #sub_total_text {
            font-weight: bold;
            color: #388e3c;
            font-size: 17px;
            margin-left: 5px;
        }
        a[href="invoice.php"] {
            display: inline-block;
            margin: 0 auto 30px auto;
            background: #1a1f37;
            color: #fff;
            padding: 10px 22px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        a[href="invoice.php"]:hover {
            background: #4CAF50;
            color: #fff;
        }
        @media (max-width: 700px) {
            .add-form, .detail-table {
                width: 98%;
                font-size: 14px;
            }
            .add-form {
                padding: 10px 4px;
            }
        }
    </style>
</head>
<body>
    <h2>Chi tiết hóa đơn #<?php echo htmlspecialchars($id_invoice); ?></h2>
    <table class="detail-table">
        <thead>
            <tr>
                <th>ID Invoice</th>
                <th>ID Variant</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($details)): ?>
                <?php foreach ($details as $row): ?>
                    <tr>
                        <td><?php echo $row['id_invoice']; ?></td>
                        <td><?php echo $row['id_variant']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['sub_total']; ?></td>
                        <td class="actions">
                            <a href="invoice_detail.php?id=<?php echo $id_invoice; ?>&delete_detail=<?php echo $row['id_variant']; ?>" class="delete" onclick="return confirm('Xóa dòng này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Chưa có chi tiết hóa đơn</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <form class="add-form" method="post" action="">
        <input type="hidden" name="id_invoice" value="<?php echo htmlspecialchars($id_invoice); ?>">
        <div class="form-group">
            <label>Chọn Variant</label>
            <select name="id_variant" required>
                <option value="">-- Chọn variant --</option>
                <?php foreach ($variants as $variant): ?>
                    <option value="<?php echo $variant['id_variant']; ?>">
                        <?php echo $variant['id_variant'] ; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Số lượng</label>
            <select name="quantity" id="quantity-select" required>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Thành tiền:</label>
            <span id="sub_total_text">0</span> VNĐ
            <input type="hidden" name="sub_total" id="sub_total_hidden" value="0">
        </div>
        <button type="submit" name="add_detail">Thêm chi tiết</button>
    </form>
    <a href="invoice.php">Quay lại danh sách hóa đơn</a>
    <script>
    let currentPrice = 0;

    document.querySelector('select[name="id_variant"]').addEventListener('change', function() {
        var id_variant = this.value;
        if (!id_variant) return;
        fetch('../../Controller/admincontroller/invoiceDetailController.php?ajax=get_price&id_variant=' + encodeURIComponent(id_variant))
            .then(response => response.text())
            .then(text => {
                console.log(text); // Xem response thực tế
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    alert('Lỗi dữ liệu trả về từ server:\n' + text);
                    return;
                }
                if (data && data.price) {
                    currentPrice = parseFloat(data.price);
                    let quantity = parseInt(document.getElementById('quantity-select').value) || 1;
                    document.getElementById('sub_total_text').textContent = (currentPrice * quantity).toLocaleString();
                    document.getElementById('sub_total_hidden').value = currentPrice * quantity;
                }
            });
    });

    document.getElementById('quantity-select').addEventListener('change', function() {
        let quantity = parseInt(this.value) || 1;
        document.getElementById('sub_total_text').textContent = (currentPrice * quantity).toLocaleString();
        document.getElementById('sub_total_hidden').value = currentPrice * quantity;
    });
    </script>
</body>
</html>
