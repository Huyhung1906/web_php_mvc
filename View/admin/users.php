<?php
require_once('../../Controller/admincontroller/usercontroller.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="slidebar.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 60px;
            background-color: #1a1f37;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .sidebar a {
            color: #a3a6b4;
            display: block;
            padding: 15px 0;
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
            padding: 30px 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .search-bar {
            display: flex;
            gap: 8px;
        }

        .search-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 220px;
        }

        .search-bar button {
            padding: 8px 16px;
            background-color: #1a1f37;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #2c3149;
        }

        .header a {
            background-color: #1a1f37;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .header a:hover {
            background-color: #2c3149;
        }

        .table {
            width: 100%;
            background: white;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        .table thead {
            background-color: #1a1f37;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f0f2f8;
        }

        .actions a {
            margin: 0 6px;
            text-decoration: none;
            font-size: 18px;
            transition: transform 0.2s ease;
        }

        .actions a:hover {
            transform: scale(1.2);
        }

        .actions a.delete {
            color: #e74c3c;
        }

        .actions a.edit {
            color: #2980b9;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .search-bar input {
                width: 100%;
            }

            .table th,
            .table td {
                font-size: 14px;
                padding: 10px 6px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include('slidebar.php'); ?>

        <div class="main-content">
            <div class="header">
                <form method="GET" class="search-bar">
                    <input type="text" name="search" placeholder="Tìm kiếm user..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Tìm</button>
                </form>
                <a href="adduser.php">+ Thêm User</a>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['id_user']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['name_role']); ?></td>
                            <td class="actions">
                                <a href="edit_user.php?id=<?php echo $user['id_user']; ?>"><i class="fas fa-edit"></i></a>
                                <a href="users.php?delete=<?php echo $user['id_user']; ?>" class="delete" onclick="return confirm('Xóa user này?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>