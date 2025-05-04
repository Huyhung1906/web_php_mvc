<?php
    require_once('../../Controller/admincontroller/addInvoiceController.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Hóa Đơn</title>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .add-invoice-container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
        }
        h2 {
            text-align: center;
            margin-bottom: 28px;
            color: #1a1f37;
            letter-spacing: 1px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-weight: 500;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            background: #f9f9f9;
            transition: border 0.2s;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        select:focus {
            border: 1.5px solid #4CAF50;
            outline: none;
            background: #fff;
        }
        .info-box {
            background: #f4f4f4;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #4CAF50;
            color: #222;
            font-size: 15px;
        }
        .info-label {
            font-weight: bold;
            color: #1a1f37;
        }
        button[type="submit"] {
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
        button[type="submit"]:hover {
            background: #388e3c;
        }
        @media (max-width: 600px) {
            .add-invoice-container {
                padding: 18px 6px;
            }
        }
    </style>
</head>
<body>
    <div class="add-invoice-container">
        <h2>Thêm Hóa Đơn</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Số điện thoại khách hàng</label>
                <input type="text" name="CustomerPhone" id="CustomerPhone" required>
            </div>
            <div id="customer-info" class="info-box" style="display:none;">
                <div><span class="info-label">ID User:</span> <span id="id_user_show"></span></div>
                <div><span class="info-label">Tên Khách Hàng:</span> <span id="CustomerName_show"></span></div>
                <div><span class="info-label">Địa chỉ:</span> <span id="CustomerAddress_show"></span></div>
                <input type="hidden" name="id_user" id="id_user">
                <input type="hidden" name="CustomerName" id="CustomerName">
                <input type="hidden" name="CustomerAddress" id="CustomerAddress">
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select name="Status" required>
                    <option value="Đang xử lý">Đang xử lý</option>
                    <option value="Hoàn thành">Hoàn thành</option>
                    <option value="Đang giao">Đang giao</option>
                </select>
            </div>
            <button type="submit">Thêm Hóa Đơn</button>
        </form>
    </div>

    <script>
    document.getElementById('CustomerPhone').addEventListener('input', function() {
        var phone = this.value;
        if (phone.length >= 8) {
            fetch('../../Controller/admincontroller/getCustomerByPhone.php?phone=' + encodeURIComponent(phone))
                .then(response => response.json())
                .then(data => {
                    if (data && data.CustomerName) {
                        document.getElementById('id_user_show').textContent = data.id_user;
                        document.getElementById('CustomerName_show').textContent = data.CustomerName;
                        document.getElementById('CustomerAddress_show').textContent = data.CustomerAddress || '';
                        document.getElementById('id_user').value = data.id_user;
                        document.getElementById('CustomerName').value = data.CustomerName;
                        document.getElementById('CustomerAddress').value = data.CustomerAddress || '';
                        document.getElementById('customer-info').style.display = 'block';
                    } else {
                        document.getElementById('customer-info').style.display = 'none';
                        document.getElementById('id_user').value = '';
                    }
                });
        } else {
            document.getElementById('customer-info').style.display = 'none';
            document.getElementById('id_user').value = '';
        }
    });
    </script>
</body>
</html>