<?php
session_start();
require_once('../../Controller/admincontroller/edituser.php');

// Kiểm tra quyền truy cập
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['id_role'] == 3) {
    header("Location: ../view/auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Người Dùng</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="slidebar.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #eef2f7;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            background: #fff;
            padding: 40px 30px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4A6CF7;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #4A6CF7;
            box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.2);
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #4A6CF7;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(74, 108, 247, 0.3);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(110, 142, 251, 0.4);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #5b73e8, #946ee2);
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(110, 142, 251, 0.5);
        }

        .btn-back {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #ddd;
            color: #333;
            padding: 8px 14px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-back:hover {
            background-color: #bbb;
            transform: scale(1.05);
        }



        .alert {
            background-color: #ffe0e0;
            color: #d8000c;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
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
                <h2>Chỉnh sửa Người Dùng</h2>

                <!-- Thông báo lỗi -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Form chỉnh sửa người dùng -->
                <form method="POST" action="edit_user.php?id=<?php echo $user['id_user']; ?>">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" class="form-control"
                            value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="fullname">Họ và tên</label>
                        <input type="text" id="fullname" name="fullname" class="form-control"
                            value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-control"
                            value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Vai trò</label>
                        <select id="role" name="role" class="form-control" required>
                            <option disabled>Chọn vai trò</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id_role']; ?>"
                                    <?php if ($role['id_role'] == $user['id_role']) echo 'selected'; ?>>
                                    <?php echo $role['name_role']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">Cập nhật Người Dùng</button>
                    <a href="javascript:history.back()" class="btn-back">Quay Lại</a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>