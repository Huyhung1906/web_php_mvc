<?php
require_once('../../Controller/admincontroller/rolecontroler.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Role</title>
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
            border-collapse: collapse;
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

        .table td a {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
        }

        .table td a.edit {
            background-color: #2980b9;
        }

        .table td a.delete {
            background-color: #e74c3c;
        }

        .table td a:hover {
            opacity: 0.8;
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
            <h1>Danh sách Role</h1>
            <a href="createrole.php">Tạo mới Role</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Role</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <tr>
                                <td><?= htmlspecialchars($role['id_role']) ?></td>
                                <td><?= htmlspecialchars($role['name_role']) ?></td>
                                <td><?= htmlspecialchars($role['description']) ?></td>
                                <td class="actions">
                                    <a class="edit" href="edit.php?id=<?= $role['id_role']; ?>">Sửa</a>
                                    <a class="delete" href="destroy.php?id=<?= $role['id_role']; ?>">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
