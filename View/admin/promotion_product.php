<?php
require_once('../../Model/PromotionProduct.php');
require_once('../../Model/Product.php');
require_once('../../Model/Promotion.php');

$promotionProductModel = new PromotionProductModel();
$productModel = new Product();
$promotionModel = new PromotionModel();

$all_promotion_products = $promotionProductModel->getAllPromotionProducts();
$all_promotions = $promotionModel->getPromotions();
$product_map = [];
$promotion_map = [];
foreach ($all_promotion_products as $item) {
    $product = $productModel->getProductById($item['id_product']);
    if ($product) {
        $product_map[$item['id_product']] = $product;
    }
    $promotion = $promotionModel->getPromotionById($item['id_promotion']);
    if ($promotion) {
        $promotion_map[$item['id_promotion']] = $promotion;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm khuyến mãi</title>
    <style>
        body {
            background: linear-gradient(120deg, #f4f4f9 60%, #e0e7ff 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(60,72,88,0.13);
            padding: 36px 32px 28px 32px;
            transition: box-shadow 0.2s;
        }
        .container:hover {
            box-shadow: 0 8px 32px rgba(60,72,88,0.18);
        }
        h2 {
            text-align: center;
            margin-bottom: 32px;
            color: #2d3a4a;
            font-size: 2.1rem;
            letter-spacing: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 10px;
            margin-bottom: 18px;
        }
        th, td {
            padding: 13px 16px;
            border: 1px solid #e3e8ee;
            text-align: left;
            font-size: 1rem;
        }
        th {
            background: #4f8cff;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background: #f7faff;
        }
        tr:hover {
            background: #eaf1ff;
            transition: background 0.2s;
        }
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }
        .actions form {
            display: inline;
        }
        .actions button, .actions a {
            padding: 7px 16px;
            border: none;
            border-radius: 5px;
            background: #4f8cff;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: background 0.2s;
        }
        .actions .delete-btn {
            background: #dc3545;
        }
        .actions .delete-btn:hover {
            background: #c82333;
        }
        .actions .update-btn {
            background: #ffc107;
            color: #222;
        }
        .actions .update-btn:hover {
            background: #e0a800;
        }
        .back-link {
            display: inline-block;
            text-align: center;
            margin-top: 18px;
            color: #4f8cff;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #2d3a4a;
            text-decoration: underline;
        }
        select {
            padding: 7px 12px;
            border: 1.5px solid #bfc9d9;
            border-radius: 5px;
            width: 210px;
            font-size: 1rem;
            background: #f8fafc;
            transition: border 0.2s;
        }
        select:focus {
            border: 1.5px solid #4f8cff;
            outline: none;
        }
        .promotion-price {
            font-weight: 600;
            color: #38b6ff;
            font-size: 1.08rem;
        }
        .footer-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .footer-btn {
            padding: 12px 28px;
            border: none;
            border-radius: 6px;
            background: linear-gradient(90deg, #4f8cff 60%, #38b6ff 100%);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(79,140,255,0.08);
            transition: background 0.2s, box-shadow 0.2s;
            display: inline-block;
            text-align: center;
        }
        .footer-btn:hover {
            background: linear-gradient(90deg, #38b6ff 60%, #4f8cff 100%);
            color: #fff;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(79,140,255,0.13);
        }
        .left-btn {
            margin-right: auto;
        }
        .right-btn {
            margin-left: auto;
        }
    </style>
    <script>
        function updatePromotion(form) {
            if (confirm('Bạn có chắc chắn muốn thay đổi khuyến mãi này?')) {
                form.submit();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Danh sách sản phẩm khuyến mãi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Khuyến mãi</th>
                    <th>Giá gốc</th>
                    <th>Giá khuyến mãi</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_promotion_products as $item): ?>
                    <?php if (isset($product_map[$item['id_product']]) && isset($promotion_map[$item['id_promotion']])): ?>
                        <tr>
                            <td><?php echo $item['id_product']; ?></td>
                            <td><?php echo $product_map[$item['id_product']]['name_product']; ?></td>
                            <td>
                                <form method="POST" action="../../Controller/admincontroller/promotion_productController.php" onchange="updatePromotion(this)">
                                    <input type="hidden" name="id_product" value="<?php echo $item['id_product']; ?>">
                                    <select name="id_promotion">
                                        <?php foreach ($all_promotions as $promotion): ?>
                                            <option value="<?php echo $promotion['id_promotions']; ?>" <?php echo $promotion['id_promotions'] == $item['id_promotion'] ? 'selected' : ''; ?>>
                                                <?php echo $promotion['name_promotion']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                            <td><?php echo number_format($product_map[$item['id_product']]['price'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo number_format($item['promotion_price'], 0, ',', '.'); ?>đ</td>
                            <td class="actions">
                                <form method="POST" action="../../Controller/admincontroller/promotion_productController.php" onsubmit="return confirm('Xóa sản phẩm này khỏi khuyến mãi?');">
                                    <input type="hidden" name="id_promotion" value="<?php echo $item['id_promotion']; ?>">
                                    <input type="hidden" name="id_product" value="<?php echo $item['id_product']; ?>">
                                    <button type="submit" name="delete" class="delete-btn">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (empty($all_promotion_products)): ?>
                    <tr><td colspan="6" style="text-align:center;">Chưa có sản phẩm nào trong khuyến mãi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="footer-actions">
            <a href="promotions.php" class="footer-btn left-btn">Quay lại danh sách khuyến mãi</a>
            <a href="add_promotion_product.php" class="footer-btn right-btn">Thêm sản phẩm khuyến mãi</a>
        </div>
    </div>
</body>
</html>
