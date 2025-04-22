<?php
class AdminModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCustomerCount() {
        $query = $this->conn->prepare("SELECT COUNT(*) as total FROM user");
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getOrderCount() {
        $query = $this->conn->prepare("SELECT COUNT(*) as total FROM invoice");
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC)['total'];
        
    }
    public function getUsers($search = '') {
        $queryStr = "SELECT * FROM user";
        if ($search) {
            $queryStr .= " WHERE username LIKE :search OR email LIKE :search";
        }

        $query = $this->conn->prepare($queryStr);
        if ($search) {
            $query->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }

        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id) {
        // Xóa các chi tiết bảo hành liên quan
        $deleteWarrantyDetailQuery = $this->conn->prepare("DELETE FROM warrantydetail WHERE id_warranty IN (SELECT id_warranty FROM warranty WHERE id_invoice IN (SELECT id_invoice FROM invoice WHERE id_user = ?))");
        $deleteWarrantyDetailQuery->execute([$id]);
        // Xóa các bảo hành liên quan
        $deleteWarrantyQuery = $this->conn->prepare("DELETE FROM warranty WHERE id_invoice IN (SELECT id_invoice FROM invoice WHERE id_user = ?)");
        $deleteWarrantyQuery->execute([$id]);

        // Xóa các chi tiết hóa đơn liên quan
        $deleteInvoiceDetailQuery = $this->conn->prepare("DELETE FROM invoicedetail WHERE id_invoice IN (SELECT id_invoice FROM invoice WHERE id_user = ?)");
        $deleteInvoiceDetailQuery->execute([$id]);

        // Xóa các hóa đơn liên quan
        $deleteInvoiceQuery = $this->conn->prepare("DELETE FROM invoice WHERE id_user = ?");
        $deleteInvoiceQuery->execute([$id]);

        $deleteQuery = $this->conn->prepare("DELETE FROM user WHERE id_user = ?");
        return $deleteQuery->execute([$id]);
    }
}
