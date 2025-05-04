<?php
require_once('../../Controller/admincontroller/promotionController.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Khuyến Mãi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="slidebar.css">
    <style>
        body { background-color: #f4f4f9; font-family: Arial, sans-serif; }
        .add-promotion-container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
        }
        h2 { text-align: center; margin-bottom: 28px; color: #1a1f37; letter-spacing: 1px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 7px; color: #333; font-weight: 500; }
        input[type="text"], input[type="number"], input[type="date"], select, textarea {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 15px; background: #f9f9f9; transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="number"]:focus, input[type="date"]:focus, select:focus, textarea:focus {
            border: 1.5px solid #4CAF50; outline: none; background: #fff;
        }
        button[type="submit"] {
            width: 100%; padding: 12px 0; background: #4CAF50; color: #fff; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; margin-top: 10px; letter-spacing: 1px;
        }
        button[type="submit"]:hover { background: #388e3c; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #1a1f37; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="add-promotion-container">
        <h2>Thêm Khuyến Mãi</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name_promotion">Tên khuyến mãi</label>
                <input type="text" id="name_promotion" name="name_promotion" required>
            </div>
            <div class="form-group">
                <label for="start_date">Ngày bắt đầu</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">Ngày kết thúc</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>
            <div class="form-group">
                <label for="discount_type">Loại giảm giá</label>
                <select id="discount_type" name="discount_type" required>
                    <option value="percentage">Phần trăm (%)</option>
                    <option value="fixed">Số tiền (VNĐ)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="discount_value">Giá trị giảm</label>
                <input type="number" id="discount_value" name="discount_value" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="status" required>
                    <option value="1">Đang hoạt động</option>
                    <option value="0">Đã kết thúc</option>
                </select>
            </div>
            <button type="submit">Thêm khuyến mãi</button>
        </form>
        <a href="promotions.php" class="back-link">Quay lại danh sách</a>
    </div>
</body>
</html> 