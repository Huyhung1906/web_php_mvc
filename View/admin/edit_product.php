<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
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

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .preview-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin: 5px;
            border-radius: 5px;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .existing-image {
            position: relative;
            display: inline-block;
        }

        .delete-image {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .delete-image:hover {
            background: darkred;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Sửa sản phẩm</h2>
                <a href="/web_php_mvc/View/admin/products.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="/web_php_mvc/admin/products/edit/<?php echo $product['id_product']; ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="current_image" value="<?php echo $product['imageUrl']; ?>">

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Thông tin cơ bản -->
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?php echo $product['name_product']; ?>" required>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Danh mục</label>
                                        <select class="form-select" name="category" id="category" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php if (!empty($categories)): ?>
                                                <?php foreach ($categories as $category): ?>
                                                    <?php $selected = ($category['id_category'] == ($product['id_category'] ?? '')) ? 'selected' : ''; ?>
                                                    <option value="<?php echo htmlspecialchars($category['id_category']); ?>" <?php echo $selected; ?>>
                                                        <?php echo htmlspecialchars($category['name_category']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Dòng sản phẩm</label>
                                        <select class="form-select" name="line" id="line" required>
                                            <option value="">Chọn dòng sản phẩm</option>
                                            <?php foreach ($lines as $line): ?>
                                                <option value="<?php echo $line['id_line']; ?>"
                                                    data-category="<?php echo $line['id_category']; ?>"
                                                    <?php echo $line['id_line'] == $product['id_line'] ? 'selected' : ''; ?>>
                                                    <?php echo $line['category_name'] . ' - ' . $line['line_name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Thương hiệu</label>
                                        <select class="form-select" name="brand" required>
                                            <option value="">Chọn thương hiệu</option>
                                            <?php if (isset($brands) && !empty($brands)): ?>
                                                <?php foreach ($brands as $brand): ?>
                                                    <option value="<?php echo $brand['id_brand']; ?>"
                                                        <?php echo $brand['id_brand'] == $product['id_brand'] ? 'selected' : ''; ?>>
                                                        <?php echo $brand['name_brand']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Giá</label>
                                        <input type="number" class="form-control" name="price"
                                            value="<?php echo $product['price']; ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea class="form-control" name="description" rows="4"><?php echo $product['description']; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Chất liệu</label>
                                    <input type="text" class="form-control" name="material"
                                        value="<?php echo $product['material']; ?>">
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Ngày phát hành</label>
                                        <input type="date" class="form-control" name="release" placeholder="dd/mm/yyyy"
                                            value="<?php echo htmlspecialchars($product['release'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-select" name="status" required>
                                            <option value="Còn hàng" <?php echo $product['status'] == 'Còn hàng' ? 'selected' : ''; ?>>Còn hàng</option>
                                            <option value="Hết hàng" <?php echo $product['status'] == 'Hết hàng' ? 'selected' : ''; ?>>Hết hàng</option>
                                            <option value="Hidden" <?php echo $product['status'] == 'Hidden' ? 'selected' : ''; ?>>Ẩn</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Hình ảnh sản phẩm -->
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh chính</label>
                                    <input type="file" class="form-control" name="image" accept="image/*"
                                        onchange="previewMainImage(this)">
                                    <div id="mainImagePreview" class="mt-2">
                                        <img src="/web_php_mvc/public/images/<?php echo $product['imageUrl']; ?>"
                                            class="preview-image" alt="Hình ảnh hiện tại">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh phụ</label>
                                    <input type="file" class="form-control" name="images[]" accept="image/*"
                                        multiple onchange="previewAdditionalImages(this)">
                                    <div id="additionalImagesPreview" class="image-preview-container mt-2">
                                        <?php foreach ($images as $image): ?>
                                            <div class="existing-image">
                                                <img src="/web_php_mvc/public/images/<?php echo $image['imageUrl']; ?>"
                                                    class="preview-image" alt="Hình ảnh phụ">
                                                <span class="delete-image" onclick="deleteImage(<?php echo $image['id_image']; ?>)">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Xử lý hiển thị hình ảnh chính
        function previewMainImage(input) {
            const preview = document.getElementById('mainImagePreview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Xử lý hiển thị hình ảnh phụ
        function previewAdditionalImages(input) {
            const preview = document.getElementById('additionalImagesPreview');

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image';
                        preview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Xử lý xóa hình ảnh phụ
        function deleteImage(imageId) {
            if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
                fetch(`/web_php_mvc/View/admin/products/delete-image/${imageId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Xóa phần tử hình ảnh khỏi DOM
                            const imageElement = document.querySelector(`[onclick="deleteImage(${imageId})"]`).parentElement;
                            imageElement.remove();
                        } else {
                            alert('Có lỗi xảy ra khi xóa hình ảnh!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa hình ảnh!');
                    });
            }
        }

        // Xử lý lọc dòng sản phẩm theo danh mục
        document.getElementById('category').addEventListener('change', function() {
            const categoryId = this.value;
            const lineSelect = document.getElementById('line');
            const options = lineSelect.getElementsByTagName('option');

            for (let option of options) {
                if (option.value === '') continue; // Bỏ qua option mặc định

                if (categoryId === '' || option.dataset.category === categoryId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }

            // Reset line selection
            lineSelect.value = '';
        });
    </script>
</body>

</html>