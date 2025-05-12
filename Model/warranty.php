<?php
require_once(__DIR__ . '/../config/config.php');

class WarrantyModel {

    
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    public function getWarrantyDetails($status, $from, $to) {
        $sql = "SELECT wd.*, w.id_invoice, i.CustomerName as customer_name, p.name_product, i.id_invoice, i.InvoiceDate as created_at
                FROM warrantydetail wd
                JOIN warranty w ON wd.id_warranty = w.id_warranty
                JOIN invoice i ON w.id_invoice = i.id_invoice
                JOIN product_variant pv ON w.id_variant = pv.id_variant
                JOIN product p ON pv.id_product = p.id_product
                WHERE 1=1";
        $params = [];
        if ($status) {
            $sql .= " AND wd.repair_status = :status";
            $params[':status'] = $status;
        }
        if ($from) {
            $sql .= " AND DATE(wd.repair_date) >= :from";
            $params[':from'] = $from;
        }
        if ($to) {
            $sql .= " AND DATE(wd.repair_date) <= :to";
            $params[':to'] = $to;
        }
        $sql .= " ORDER BY wd.id_warrantydetail ASC";
        
        // Debug SQL and parameters
        error_log("SQL Query: " . $sql);
        error_log("Parameters: " . print_r($params, true));
        
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
            error_log("Binding $k with value: $v");
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Debug results
        error_log("Number of results: " . count($results));
        
        return $results;
    }

    public function updateWarrantyDetail($id, $status, $note, $cost = null) {
        $sql = "UPDATE warrantydetail SET repair_status = :status, notes = :note, cost = :cost WHERE id_warrantydetail = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':status' => $status, ':note' => $note, ':cost' => $cost, ':id' => $id]);
    }
}

if (isset($_GET['saved'])) {
    echo '<script>
        alert("Lưu thành công!");
        if (window.history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.delete("saved");
            window.history.replaceState({}, document.title, url.pathname + url.search);
        }
    </script>';
}
