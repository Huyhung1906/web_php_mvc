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
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
        body { background-color:#f4f4f9; }
        .container { display:flex; min-height:100vh; }
        .main-content { flex-grow:1; padding:20px; }
        .action-buttons .btn { margin:0 2px; }
    </style>
</head>

<body>
    <div class="container">
        <?php include(__DIR__ . '/slidebar.php'); ?>
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý biến thể sản phẩm</h2>
                <a href="/web_php_mvc/admin/product_variants/add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm biến thể mới
                </a>
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
    </script>
</body>

</html>
