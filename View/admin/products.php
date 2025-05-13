<?php
try {
    // Load các file cần thiết
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../../Model/adminProductModel.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Khởi tạo model và lấy dữ liệu
    $model = new AdminProductModel();
    $check = new AdminProductModel();
    $brands = $model->getAllBrands();
    $categories = $model->getAllCategories();

    // Debug: Kiểm tra biến
    error_log('View: Checking variables');
    error_log('View: $brands: ' . (isset($brands) ? 'set' : 'NOT set'));
    error_log('View: $categories: ' . (isset($categories) ? 'set' : 'NOT set'));

    // Khởi tạo giá trị filter mặc định
    $initFilters = [
        'category' => $_GET['category'] ?? '',
        'brand'    => $_GET['brand'] ?? '',
        'status'   => $_GET['status'] ?? '',
        'search'   => $_GET['search'] ?? ''
    ];
} catch (Exception $e) {
    error_log('Error in products.php: ' . $e->getMessage());
    echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    // Khởi tạo biến rỗng để tránh lỗi undefined
    $brands = [];
    $categories = [];
    $initFilters = [
        'category' => '',
        'brand'    => '',
        'status'   => '',
        'search'   => ''
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <!-- Debug: Hiển thị dữ liệu -->

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
            background-color: #1a1f37;
            color: white;

            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
        }

        .sidebar a {
            color: #a3a6b4;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background-color: #2c3149;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .action-buttons .btn {
            margin: 0 2px;
        }

        .table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background: #1a1f37;
            color: white;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        .table td,
        .table th {
            vertical-align: middle !important;
            font-size: 15px;
        }

        .table th:first-child,
        .table td:first-child {
            border-top-left-radius: 5px;
        }

        .table th:last-child,
        .table td:last-child {
            border-top-right-radius: 5px;
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
    <?php
    // Highlight current page
    $activePage = 'products';
    ?>
    <div class="container">
        <div class="sidebar">
            <?php include(__DIR__ . '/slidebar.php'); ?>
        </div>

        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý sản phẩm</h2>
                <?php if ($check->canPerformAction($_SESSION['id_role'], 8)) { ?>
                    <a href="/web_php_mvc/admin/products/add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm sản phẩm mới
                    </a> <?php } else { ?>
                    <a href="javascript:void(0);" class="no-permission-link">Thêm sản phẩm mới</a> <!-- Liên kết màu xám khi không có quyền -->
                <?php } ?>

            </div>

            <!-- Bộ lọc -->
            <div class="card mb-4">
                <form id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Danh mục</label>
                        <select class="form-select" name="category" id="category">
                            <option value="">Tất cả</option>
                            <?php if (isset($categories) && !empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id_category']; ?>">
                                        <?php echo $category['name_category']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Thương hiệu</label>
                        <select class="form-select" name="brand" id="brand">
                            <option value="">Tất cả</option>
                            <?php if (isset($brands) && !empty($brands)): ?>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?php echo $brand['id_brand']; ?>">
                                        <?php echo $brand['name_brand']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">Tất cả</option>
                            <option value="Còn hàng">Còn hàng</option>
                            <option value="Hết hàng">Hết hàng</option>
                            <option value="Hidden">Đã ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="Nhập tên sản phẩm...">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <button type="reset" class="btn btn-secondary">Đặt lại</button>
                    </div>
                </form>
            </div>

            <!-- Bảng sản phẩm -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Thương hiệu</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
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
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa sản phẩm này?</p>
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
        let productIdToDelete = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Hàm load danh sách sản phẩm
        function loadProducts(filters = {}) {
            fetch('/web_php_mvc/admin/products/get-products', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(filters)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    const tableBody = document.getElementById('productTableBody');
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center">Không tìm thấy sản phẩm nào</td>
                        </tr>
                    `;
                        return;
                    }

                    data.forEach(product => {
                        const row = `
                        <tr>
                            <td>${product.id_product}</td>
                            <td>
                                <img src="/web_php_mvc/public/images/${product.imageUrl}" 
                                     class="product-image" alt="${product.name_product}"
                                     onerror="this.src='/web_php_mvc/public/images/no-image.jpg'">
                            </td>
                            <td>${product.name_product}</td>
                            <td>${product.category_name}</td>
                            <td>${product.name_brand}</td>
                            <td>${new Intl.NumberFormat('vi-VN').format(product.price)} VNĐ</td>
                            <td>
                                <span class="badge bg-${product.status === 'Còn hàng' ? 'success' : 
                                    (product.status === 'Hết hàng' ? 'danger' : 'secondary')}">
                                    ${product.status}
                                </span>
                            </td>
                            <td class="action-buttons">
                            <?php if ($check->canPerformAction($_SESSION['id_role'], 9)) { ?>
                                <a href="/web_php_mvc/admin/products/edit/${product.id_product}" 
                                            class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                <?php } else { ?>
                                <a href="javascript:void(0);" class="no-permission-link"><i class="fas fa-edit"></i></a> <!-- Liên kết màu xám khi không có quyền -->
                            <?php } ?>
                                <?php if ($check->canPerformAction($_SESSION['id_role'], 11)) { ?>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete(${product.id_product})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php } else { ?>
                                <a href="javascript:void(0);" class="no-permission-link"><button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete(${product.id_product})">
                                </button></i></a> <!-- Liên kết màu xám khi không có quyền -->
                            <?php } ?>
                                
                            </td>
                        </tr>
                    `;
                        tableBody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    const tableBody = document.getElementById('productTableBody');
                    tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            Có lỗi xảy ra khi tải danh sách sản phẩm: ${error.message}
                        </td>
                    </tr>
                `;
                });
        }

        // Load sản phẩm khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
        });

        // Xử lý form lọc
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const filters = {
                category: document.getElementById('category').value,
                brand: document.getElementById('brand').value,
                status: document.getElementById('status').value,
                search: document.getElementById('search').value
            };
            loadProducts(filters);
        });

        // Xử lý nút đặt lại
        document.querySelector('button[type="reset"]').addEventListener('click', function() {
            setTimeout(() => {
                loadProducts();
            }, 0);
        });

        // Xử lý xóa sản phẩm
        function confirmDelete(id) {
            productIdToDelete = id;
            deleteModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (productIdToDelete) {
                fetch(`/web_php_mvc/admin/products/delete/${productIdToDelete}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            loadProducts(); // Load lại danh sách sau khi xóa
                        } else {
                            throw new Error(data.error || 'Có lỗi xảy ra khi xóa sản phẩm!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message);
                    });
            }
            deleteModal.hide();
        });
    </script>
</body>

</html>