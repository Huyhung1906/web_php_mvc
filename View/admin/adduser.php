<?php
require_once('../../Controller/admincontroller/adduser.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>

    <!-- Link Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="slidebar.css"> <!-- Slidebar riêng -->

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

        /* Phần nội dung form */
        .main-content {
            flex-grow: 1;
            padding: 40px;
        }

        .form-container {
            background: white;
            padding: 30px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1a1f37;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input:focus,
        select:focus {
            border-color: #1a1f37;
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #1a1f37;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #2c3149;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Sidebar -->
        <?php include('slidebar.php'); ?>

        <!-- Nội dung form -->
        <div class="main-content">
            <div class="form-container">
                <h2>Thêm Người Dùng</h2>
                <!-- Thông báo lỗi -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <!-- Form thêm người dùng -->
                <form method="POST" action="adduser.php">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                    </div>

                    <div class="form-group">
                        <label for="fullname">Họ và tên</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nhập họ và tên" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                    </div>

                    <!-- Chọn vai trò -->
                    <div class="form-group">
                        <label for="role">Vai trò</label>
                        <select id="role" name="role" class="form-control" required>
                            <option selected disabled>Chọn vai trò</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id_role']; ?>"><?php echo $role['name_role']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nút thêm người dùng -->
                    <button type="submit" class="btn btn-primary">Thêm Người Dùng</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>