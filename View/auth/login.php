<?php
require_once('../../Controller/authcontroller/LoginController.php');

$loginController = new LoginController();
$loginController->login();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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

        .login-container {
            max-width: 450px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .login-container:hover {
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

        .btn-login {
            background: #667eea;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .btn-google {
            background: #ffffff;
            color: #666;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            color: #444;
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
    <div class="login-container">
        <h3 class="text-center">Đăng nhập</h3>

        <?php if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger text-center">
                <?php echo $_SESSION['error_msg']; ?>
                <?php unset($_SESSION['error_msg']); ?> <!-- Xóa thông báo lỗi sau khi hiển thị -->
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success text-center">
                <?php echo $_SESSION['success_msg']; ?>
                <?php unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-login w-100 text-white">Đăng nhập</button>
        </form>

        <div class="text-center mt-3">
            <a href="user/forgot_password.php" class="text-link">Quên mật khẩu?</a>
        </div>
        <div class="text-center mt-3">
            <p class="mb-2">Chưa có tài khoản? <a href="../auth/register.php" class="text-link">Đăng ký ngay</a></p>
        </div>

        <div class="mt-4">
            <a href="<?php echo $google_login_url; ?>" class="btn btn-google w-100">
                <i class="bi bi-google me-2"></i>Đăng nhập bằng Google
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>