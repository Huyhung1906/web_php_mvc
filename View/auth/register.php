<?php
require_once('../../Controller/authcontroller/RegisterController.php');
$registerController = new RegisterController();
$registerController->handleRegister();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            max-width: 650px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .register-container:hover {
            transform: translateY(-5px);
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }

        .btn-register {
            background: #667eea;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 12px;
        }

        .text-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .text-link:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h3 class="text-center">Đăng ký tài khoản</h3>
        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger text-center">
                <?php echo $_SESSION['error_msg']; ?>
                <?php unset($_SESSION['error_msg']); ?> <!-- Xóa thông báo lỗi sau khi hiển thị -->
            </div>
        <?php endif; ?>

        <!-- Hiển thị thông báo thành công nếu có -->
        <?php if (isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])): ?>
            <div class="alert alert-success text-center">
                <?php echo $_SESSION['success_msg']; ?>
                <?php unset($_SESSION['success_msg']); ?> <!-- Xóa thông báo sau khi hiển thị -->
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="fullname" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required pattern="[0-9]{10,11}" title="Số điện thoại phải từ 10 đến 11 chữ số">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-4 col-md-6">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-register w-100 text-white">Đăng ký</button>
        </form>

        <div class="text-center mt-4">
            <p class="mb-0">Đã có tài khoản? <a href="../auth/login.php" class="text-link">Đăng nhập ngay</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>