<?php
require_once __DIR__ . '/../../Model/InvoiceDetail.php';
require_once __DIR__ . '/../../Model/Product.php';

if (!isset($_GET['id'])) {
    echo "No order selected.";
    exit;
}

$invoiceId = intval($_GET['id']);
$invoiceDetailModel = new InvoiceDetailModel();
$details = $invoiceDetailModel->getInvoiceDetails($invoiceId);

if (empty($details)) {
    echo "No products found for this order.";
    exit;
}

echo "<table class='table table-bordered'>";
echo "<thead><tr><th>Product</th><th>Size</th><th>Color</th><th>Quantity</th><th>Subtotal</th></tr></thead><tbody>";
foreach ($details as $item) {
    // Lấy thêm thông tin sản phẩm, size, màu nếu cần
    $info = $invoiceDetailModel->getVariantInfo($item['id_variant']);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($info['name_product'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($info['size_value'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($info['color_name'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
    echo "<td>" . number_format($item['sub_total'], 0, ',', '.') . " đ</td>";
    echo "</tr>";
}
echo "</tbody></table>";
?>
