<?php
// Highlight current page
$activePage = 'variants';

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý biến thể sản phẩm</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="/web_php_mvc/View/admin/slidebar.css">
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

        .sidebar {
            width: 50px; /* Đặt chiều rộng cho sidebar */
            background-color: #1a1f37; /* Màu nền */
            color: white; /* Màu chữ */
            padding: 20px 0; /* Padding cho sidebar */
            position: fixed; /* Giữ sidebar cố định */
            top: 0;
            left: 0;
            height: 100%; /* Chiều cao đầy đủ */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Đổ bóng tùy chọn */
        }

        .sidebar a {
            color: #a3a6b4; /* Màu chữ cho liên kết */
            display: block; /* Hiển thị liên kết dưới dạng khối */
            padding: 15px; /* Padding cho liên kết */
            text-decoration: none; /* Bỏ gạch chân */
        }

        .sidebar a:hover, .sidebar a.active {
            color: white; /* Màu chữ khi hover hoặc active */
            background-color: #2c3149; /* Màu nền khi hover/active */
        }

        .main-content {
            margin-left: auto; /* Điều chỉnh margin để phù hợp với chiều rộng của sidebar */
            flex-grow: 1; /* Cho phép main-content mở rộng */
            padding: 20px; /* Padding cho main-content */
        }

        .action-buttons .btn {
            margin: 0 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <?php include(__DIR__ . '/slidebar.php'); ?>
        </div>

        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý biến thể sản phẩm</h2>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                    Thêm biến thể mới
                </button>
            </div>

            <!-- Bộ lọc -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sản phẩm</label>
                            <select class="form-select" id="product" name="product">
                                <option value="">Tất cả</option>
                                <?php foreach ($products as $prd): ?>
                                    <option value="<?php echo $prd['id_product']; ?>"><?php echo htmlspecialchars($prd['name_product']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kích cỡ</label>
                            <select class="form-select" id="size" name="size">
                                <option value="">Tất cả</option>
                                <?php foreach ($sizes as $sz): ?>
                                    <option value="<?php echo $sz['id_size']; ?>"><?php echo htmlspecialchars($sz['size_value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Màu sắc</label>
                            <select class="form-select" id="color" name="color">
                                <option value="">Tất cả</option>
                                <?php foreach ($colors as $col): ?>
                                    <option value="<?php echo $col['id_color']; ?>"><?php echo htmlspecialchars($col['color_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Lọc</button>
                            <button type="reset" class="btn btn-secondary">Đặt lại</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bảng biến thể -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Kích cỡ</th>
                                    <th>Màu sắc</th>
                                    <th>Số lượng</th>
                                    <th>Hết hạn</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="variantTableBody">
                                <!-- Dữ liệu sẽ được load bằng AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa biến thể này?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm biến thể -->
    <div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm biến thể mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addVariantForm">
                        <div class="mb-3">
                            <label for="add_product" class="form-label">Sản phẩm</label>
                            <select class="form-select" id="add_product" name="product" required>
                                <option value="">Chọn sản phẩm</option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id_product'] ?>"><?= $p['name_product'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_size" class="form-label">Kích cỡ</label>
                            <select class="form-select" id="add_size" name="size" required>
                                <option value="">Chọn kích cỡ</option>
                                <?php foreach ($sizes as $s): ?>
                                    <option value="<?= $s['id_size'] ?>"><?= $s['size_value'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_color" class="form-label">Màu sắc</label>
                            <select class="form-select" id="add_color" name="color" required>
                                <option value="">Chọn màu sắc</option>
                                <?php foreach ($colors as $c): ?>
                                    <option value="<?= $c['id_color'] ?>"><?= $c['name_color'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="expired_date" class="form-label">Ngày hết hạn</label>
                            <input type="date" class="form-control" id="expired_date" name="expired_date" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="saveVariantBtn">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let variantIdToDelete = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Hàm load biến thể
        function loadVariants(filters = {}) {
            fetch('/web_php_mvc/index.php?url=admin/product_variants/get-variants', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(filters)
            })
            .then(res => res.ok ? res.json() : Promise.reject(res.statusText))
            .then(data => {
                const tbody = document.getElementById('variantTableBody');
                tbody.innerHTML = '';
                if (!data.length) {
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center">Không tìm thấy biến thể nào</td></tr>`;
                    return;
                }
                data.forEach(v => {
                    const row = `
                        <tr>
                            <td>${v.id_variant}</td>
                            <td>${v.name_product}</td>
                            <td>${v.size_value}</td>
                            <td>${v.color_name}</td>
                            <td>${v.quantity}</td>
                            <td>${v.expired_date}</td>
                            <td class="action-buttons">
                                <a href="/web_php_mvc/admin/product_variants/edit/${v.id_variant}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(${v.id_variant})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    tbody.innerHTML += row;
                });
            })
            .catch(err => {
                console.error(err);
                document.getElementById('variantTableBody').innerHTML = `<tr><td colspan="7" class="text-center text-danger">Lỗi: ${err}</td></tr>`;
            });
        }

        document.addEventListener('DOMContentLoaded', () => loadVariants());

        document.getElementById('filterForm').addEventListener('submit', e => {
            e.preventDefault();
            const filters = {
                product: document.getElementById('product').value,
                size: document.getElementById('size').value,
                color: document.getElementById('color').value
            };
            loadVariants(filters);
        });

        document.querySelector('button[type="reset"]').addEventListener('click', () => setTimeout(() => loadVariants(), 0));

        function confirmDelete(id) {
            variantIdToDelete = id;
            deleteModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            fetch('/web_php_mvc/index.php?url=admin/product_variants/delete/${variantIdToDelete}', { method: 'POST' })
            .then(res => res.json())
            .then(res => {
                if (res.success) loadVariants();
                else alert('Xóa thất bại');
            })
            .catch(() => alert('Có lỗi xảy ra'));
            deleteModal.hide();
        });

        // Xử lý thêm biến thể mới
        document.getElementById('saveVariantBtn').addEventListener('click', () => {
            console.log({
                product: document.getElementById('add_product').value,
                size: document.getElementById('add_size').value,
                color: document.getElementById('add_color').value,
                quantity: document.getElementById('quantity').value,
                expired_date: document.getElementById('expired_date').value
            });
            const data = {
                product: document.getElementById('add_product').value,
                size: document.getElementById('add_size').value,
                color: document.getElementById('add_color').value,
                quantity: document.getElementById('quantity').value,
                expired_date: document.getElementById('expired_date').value
            };
            fetch('/web_php_mvc/index.php?url=admin/product_variants/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    loadVariants();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addVariantModal'));
                    modal.hide();
                    document.getElementById('addVariantForm').reset();
                } else {
                    alert('Thêm thất bại: ' + (res.error || 'Lỗi không xác định'));
                }
            })
            .catch(() => alert('Có lỗi xảy ra'));
        });
    </script>
</body>

</html>
