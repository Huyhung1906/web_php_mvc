<?php
require_once('../../config/config.php'); // Kết nối database

class InvoiceModel {
	private $conn;
	public function __construct() {
		global $conn;
		$this->conn = $conn;
	}
	// Lấy danh sách hóa đơn
	public function getInvoices($search = '') {
		try {
			if ($search !== '') {
				$stmt = $this->conn->prepare(
					"SELECT * FROM invoice 
					 WHERE id_invoice LIKE :search 
					 OR CustomerName LIKE :search 
					 OR CustomerPhone LIKE :search"
				);
				$likeSearch = "%$search%";
				$stmt->bindParam(':search', $likeSearch);
			} else {
				$stmt = $this->conn->prepare("SELECT * FROM invoice");
			}
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			error_log("Error getting invoices: " . $e->getMessage());
			return [];
		}
	}
	// Lấy thông tin hóa đơn theo ID
	public function getInvoiceById($invoiceId) {
		try {
			$stmt = $this->conn->prepare("SELECT * FROM invoice WHERE id_invoice = :id_invoice");
			$stmt->bindParam(':id_invoice', $invoiceId);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			error_log("Error getting invoice by ID: " . $e->getMessage());
			return null;
		}
	}
	// Thêm hóa đơn
	public function addInvoice($id_user, $CustomerName, $CustomerPhone, $InvoiceDate, $Status, $CustomerAddress) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO invoice (id_user, CustomerName, CustomerPhone, InvoiceDate, TotalAmount, Status, CustomerAddress) VALUES (:id_user, :CustomerName, :CustomerPhone, :InvoiceDate, 0, :Status, :CustomerAddress)");
			$stmt->bindParam(':id_user', $id_user);
			$stmt->bindParam(':CustomerName', $CustomerName);
			$stmt->bindParam(':CustomerPhone', $CustomerPhone);
			$stmt->bindParam(':InvoiceDate', $InvoiceDate);
			$stmt->bindParam(':Status', $Status);
			$stmt->bindParam(':CustomerAddress', $CustomerAddress);
			return $stmt->execute();
		} catch (Exception $e) {
			error_log("Error adding invoice: " . $e->getMessage());
			return false;
		}
	}
	// Thêm sản phẩm vào hóa đơn
	public function addInvoiceProduct($invoiceId, $productId, $quantity, $price) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO invoice_product (id_invoice, id_product, quantity, price) VALUES (:id_invoice, :id_product, :quantity, :price)");
			$stmt->bindParam(':id_invoice', $invoiceId);
			$stmt->bindParam(':id_product', $productId);
			$stmt->bindParam(':quantity', $quantity);
			$stmt->bindParam(':price', $price);
			return $stmt->execute();
		} catch (Exception $e) {
			error_log("Error adding invoice product: " . $e->getMessage());
			return false;
		}
	}
	// Cập nhật trạng thái hóa đơn
	public function updateInvoiceStatus($invoiceId, $status) {
		try {
			$stmt = $this->conn->prepare("UPDATE invoice SET status = :status WHERE id_invoice = :id_invoice");
			$stmt->bindParam(':status', $status);
			$stmt->bindParam(':id_invoice', $invoiceId);
			return $stmt->execute();
		} catch (Exception $e) {
			error_log("Error updating invoice status: " . $e->getMessage());
			return false;
		}
	}
	// Xóa hóa đơn
	public function deleteInvoice($invoiceId) {
		try {
			$stmt = $this->conn->prepare("DELETE FROM invoice WHERE id_invoice = :id_invoice");
			$stmt->bindParam(':id_invoice', $invoiceId);
			return $stmt->execute();
		} catch (Exception $e) {
			error_log("Error deleting invoice: " . $e->getMessage());
			return false;
		}
	}

	public function getCustomerByPhone($phone) {
		$stmt = $this->conn->prepare("SELECT id_user, fullname AS CustomerName, phone AS CustomerPhone FROM user WHERE phone = :phone LIMIT 1");
		$stmt->bindParam(':phone', $phone);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getAddressByUserId($id_user) {
		$stmt = $this->conn->prepare("
			SELECT 
				CONCAT_WS(', ', street, district, province) AS CustomerAddress
			FROM address 
			WHERE id_user = :id_user 
			ORDER BY update_date DESC 
			LIMIT 1
		");
		$stmt->bindParam(':id_user', $id_user);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}