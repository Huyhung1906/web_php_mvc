<?php
require_once('../../model/Invoice.php');
$phone = $_GET['phone'] ?? '';
if ($phone) {
    $invoiceModel = new InvoiceModel();
    $customer = $invoiceModel->getCustomerByPhone($phone);
    if ($customer) {
        // Lấy địa chỉ
        $address = $invoiceModel->getAddressByUserId($customer['id_user']);
        if ($address && $address['CustomerAddress']) {
            $customer['CustomerAddress'] = $address['CustomerAddress'];
        } else {
            $customer['CustomerAddress'] = 'chưa có địa chỉ';
        }
        echo json_encode($customer);
    } else {
        echo json_encode([]);
    }
}