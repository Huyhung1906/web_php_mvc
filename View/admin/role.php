<?php
require_once('../../Controller/admincontroller/rolecontroler.php');
$assignedPermissions = isset($assignedPermissions) && is_array($assignedPermissions) ? $assignedPermissions : [];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Role</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="slidebar.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
            margin: 0 !important;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            display: flex;
            gap: 20px;
            flex-grow: 1;
            padding: 30px;
        }

        .sidebar {
            width: 5 0px;
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

        .left-panel,
        .right-panel {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .left-panel {
            width: 40%;
        }

        .right-panel {
            width: 60%;
        }

        h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #2e7d32;
            border-left: 5px solid #81c784;
            padding-left: 10px;
        }

        h4 {
            margin-bottom: 15px;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }

        .form-section,
        .role-list,
        .permission-form {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            background-color: #fafafa;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            padding: 8px 16px;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1565c0;
        }

        .permission-row label {
            margin-right: 12px;
            font-size: 16px;
        }

        .permission-row input[type="checkbox"] {
            margin-right: 6px;
        }

        .readonly-input {
            width: 100%;
            font-weight: bold;
            background: #eee;
            padding: 10px;
            border-radius: 6px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .role-row {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .role-row input[type="text"] {
            width: 120px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
            font-size: 16px;
        }

        .role-row a button {
            padding: 6px 12px;
            background-color: #28a745;
            font-size: 14px;
        }

        .role-row a button:hover {
            background-color: #218838;
        }

        .success-message {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px;
        }

        .group-title {
            width: 250px;
            font-weight: bold;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 6px;
        }

        .no-permission i {
            color: gray;
            /* Màu xám cho biểu tượng */
            pointer-events: none;
            /* Ngăn không cho người dùng click vào */
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
            <!-- Left Panel -->
            <div class="left-panel">
                <h3>Quản lý Chức Năng</h3>

                <!-- Thêm role mới -->
                <div class="form-section">
                    <h4>Thêm Role mới</h4>
                    <form method="POST" action="">
                        <input type="text" name="name_role" placeholder="Tên Role" required>
                        <input type="text" name="description" placeholder="Mô tả" required>
                        <?php if (isset($check) && $check->canPerformAction($_SESSION['id_role'], 20)) { ?>
                            <button type="submit" name="add_role">Thêm</button>
                        <?php } else { ?>
                            <button type="button" class="no-permission-link" disabled style="opacity: 0.6; cursor: not-allowed;">Thêm</button>
                        <?php } ?>

                    </form>
                </div>

                <!-- Danh sách Role -->
                <div class="role-list">
                    <h4>Danh sách các Role</h4>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <div class="role-row">
                                <input type="text" value="<?= htmlspecialchars($role['name_role']) ?>" readonly style="width:120px">
                                <input type="text" value="<?= htmlspecialchars($role['description']) ?>" readonly>
                                <!-- Nút Chức năng (ID chức năng giả sử là 6) -->
                                <?php if (isset($check) && $check->canPerformAction($_SESSION['id_role'], 22)) { ?>
                                    <a href="role.php?id=<?= $role['id_role'] ?>">
                                        <button type="button">Chức năng</button>
                                    </a>
                                <?php } else { ?>
                                    <button type="button" class="no-permission-link" disabled style="opacity: 0.6; cursor: not-allowed;">Chức năng</button>
                                <?php } ?>

                                <!-- Nút Xóa (ID chức năng giả sử là 7) -->
                                <?php if (isset($check) && $check->canPerformAction($_SESSION['id_role'], 23)) { ?>
                                    <a href="?delete=<?= $role['id_role'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa role này?')">
                                        <button type="button">Xóa</button>
                                    </a>
                                <?php } else { ?>
                                    <button type="button" class="no-permission-link" disabled style="opacity: 0.6; cursor: not-allowed;">Xóa</button>
                                <?php } ?>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có dữ liệu.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="right-panel">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="success-message"><?= $_SESSION['message'] ?></div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>


                <form method="POST" action="">
                    <div class="permission-form">
                        <!-- Tên role (readonly) -->
                        <input type="text" value="<?= isset($selectedRole['name_role']) ? htmlspecialchars($selectedRole['name_role']) : '' ?>" readonly class="readonly-input">
                        <!-- Hidden ID role -->
                        <input type="hidden" name="id_role" value="<?= isset($selectedRole['id_role']) ? $selectedRole['id_role'] : '' ?>">

                        <h4>Danh sách chức năng</h4>

                        <?php
                        // Nhóm quyền và các ID chi tiết tương ứng
                        $permissions = [
                            'Quản lý Người Dùng' => [4 => 'Tạo Mới', 6 => 'Xem Danh Sách', 5 => 'Cập Nhật', 7 => 'Xóa'],
                            'Quản lý Quyền'      => [20 => 'Tạo Mới', 22 => 'Xem Danh Sách', 21 => 'Cập Nhật', 23 => 'Xóa'],
                            'Quản lý Sản Phẩm'   => [8 => 'Tạo Mới', 10 => 'Xem Danh Sách', 9 => 'Cập Nhật', 11 => 'Xóa'],
                            'Quản lý Hóa Đơn'    => [12 => 'Tạo Mới', 14 => 'Xem Danh Sách', 13 => 'Cập Nhật', 15 => 'Xóa'],
                            'Quản lý Khuyên Mãi'    => [16 => 'Tạo Mới', 18 => 'Xem Danh Sách', 17 => 'Cập Nhật', 19 => 'Xóa'],
                        ];

                        // Lấy danh sách ID quyền đã được gán (chuyển sang mảng các ID)
                        $assignedIds = array_map(function ($perm) {
                            return is_array($perm) && isset($perm['id_chitietrole']) ? $perm['id_chitietrole'] : $perm;
                        }, $assignedPermissions);

                        foreach ($permissions as $groupName => $actions): ?>
                            <div class="permission-row">
                                <input type="text" value="<?= $groupName ?>" readonly class="readonly-input group-title">
                                <?php foreach ($actions as $id => $label): ?>
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="<?= $id ?>"
                                            <?= in_array($id, $assignedIds) ? 'checked' : '' ?>>
                                        <?= $label ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                        <br>
                        <?php if (isset($check) && $check->canPerformAction($_SESSION['id_role'], 21)) { ?>
                                        <button type="submit" name="save_permissions">Lưu</button>
                                <?php } else { ?>
                                    <button type="button" class="no-permission-link" disabled style="opacity: 0.6; cursor: not-allowed;">Lưu</button>
                                <?php } ?>

                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra nếu có thông báo trong session
            var successMessage = document.querySelector('.success-message');

            if (successMessage) {
                // Hiển thị thông báo
                successMessage.style.display = 'block';
                setTimeout(function() {
                    // Ẩn thông báo sau 2 giây
                    successMessage.style.opacity = 0;
                }, 2000);

                // Ẩn thông báo hoàn toàn sau khi hiệu ứng ẩn kết thúc
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 2500);
            }
        });
    </script>
</body>

</html>