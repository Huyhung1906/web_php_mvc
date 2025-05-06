<?php
require_once('../../Model/Promotion.php');
require_once('../../Model/Product.php');
require_once('../../Model/PromotionProduct.php');

$promotionModel = new PromotionModel();
$productModel = new Product();

$promotions = $promotionModel->getPromotions();
$products = $productModel->getAllProducts();

// Filter active promotions
$activePromotions = array_filter($promotions, function($promotion) {
    $currentDate = date('Y-m-d');
    return $promotion['status'] == 1 && 
           $promotion['start_date'] <= $currentDate && 
           $promotion['end_date'] >= $currentDate;
});

$promotionProductModel = new PromotionProductModel();
$allPromotionProducts = $promotionProductModel->getAllPromotionProducts();
$productsWithPromotion = [];
foreach ($allPromotionProducts as $pp) {
    $productsWithPromotion[] = $pp['id_product'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm vào khuyến mãi</title>
    <style>
        body {
            background: linear-gradient(120deg, #f4f4f9 60%, #e0e7ff 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 950px;
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
        label {
            font-weight: 500;
            color: #1a1f37;
            margin-bottom: 8px;
            display: inline-block;
            font-size: 1.05rem;
        }
        select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1.5px solid #bfc9d9;
            font-size: 1rem;
            background: #f8fafc;
            margin-left: 8px;
            margin-bottom: 18px;
            transition: border 0.2s;
        }
        select:focus {
            border: 1.5px solid #4f8cff;
            outline: none;
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
            text-align: center;
            margin-top: 24px;
        }
        .actions button {
            padding: 12px 28px;
            border: none;
            border-radius: 6px;
            background: linear-gradient(90deg, #4f8cff 60%, #38b6ff 100%);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(79,140,255,0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .actions button:hover {
            background: linear-gradient(90deg, #38b6ff 60%, #4f8cff 100%);
            box-shadow: 0 4px 16px rgba(79,140,255,0.13);
        }
        .actions a {
            display: inline-block;
            margin-top: 18px;
            color: #4f8cff;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .actions a:hover {
            color: #2d3a4a;
            text-decoration: underline;
        }
        input[type='checkbox'] {
            width: 18px;
            height: 18px;
            accent-color: #4f8cff;
            cursor: pointer;
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
</head>
<body>
    <div class="container">
        <h2>Thêm sản phẩm vào khuyến mãi</h2>
        <form method="POST" action="../../Controller/admincontroller/promotion_productController.php">
            <label for="id_promotion">Chọn khuyến mãi:</label>
            <select name="id_promotion" id="id_promotion" required onchange="updatePromotionPrices()">
                <option value="">-- Chọn khuyến mãi --</option>
                <?php foreach ($activePromotions as $promotion): ?>
                    <option 
                        value="<?php echo $promotion['id_promotions']; ?>"
                        data-type="<?php echo $promotion['discount_type']; ?>"
                        data-value="<?php echo $promotion['discount_value']; ?>"
                    >
                        <?php echo $promotion['name_promotion']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <table>
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>ID Sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá gốc</th>
                        <th>Giá khuyến mãi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <?php if (!in_array($product['id_product'], $productsWithPromotion)): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="product_ids[]" value="<?php echo $product['id_product']; ?>">
                            </td>
                            <td><?php echo $product['id_product']; ?></td>
                            <td><?php echo $product['name_product']; ?></td>
                            <td class="product-price" data-price="<?php echo $product['price']; ?>">
                                <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                            </td>
                            <td>
                                <span class="promotion-price" id="promotion_price_<?php echo $product['id_product']; ?>">0đ</span>
                                <input type="hidden" name="promotion_price[<?php echo $product['id_product']; ?>]" id="input_promotion_price_<?php echo $product['id_product']; ?>" value="0">
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="footer-actions">
                <a href="promotion_product.php" class="footer-btn left-btn">Quay lại danh sách sản phẩm khuyến mãi</a>
                <a href="add_promotion_product.php" class="footer-btn right-btn">Thêm sản phẩm khuyến mãi</a>
            </div>
        </form>
    </div>
    <script>
    var productsWithPromotion = <?php echo json_encode($productsWithPromotion); ?>;
    function updatePromotionPrices() {
        var select = document.getElementById('id_promotion');
        var type = select.options[select.selectedIndex].getAttribute('data-type');
        var value = parseFloat(select.options[select.selectedIndex].getAttribute('data-value'));
        var rows = document.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            var productId = row.querySelector('input[type=checkbox]').value;
            // Ẩn sản phẩm đã có khuyến mãi
            if (productsWithPromotion.includes(productId)) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
                var price = parseFloat(row.querySelector('.product-price').getAttribute('data-price'));
                var promotionPrice = 0;
                if (type === 'percentage') {
                    promotionPrice = price * (1 - value / 100);
                } else if (type === 'fixed') {
                    promotionPrice = price - value;
                }
                if (promotionPrice < 0) promotionPrice = 0;
                var priceText = Math.round(promotionPrice).toLocaleString('vi-VN') + 'đ';
                row.querySelector('.promotion-price').textContent = priceText;
                row.querySelector('input[type=hidden]').value = Math.round(promotionPrice);
            }
        });
    }
    window.onload = updatePromotionPrices;
    </script>
</body>
</html>
