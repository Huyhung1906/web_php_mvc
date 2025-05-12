<?php
require_once('../../config/config.php');

class StatisticsModel
{
    private $conn;
    public function __construct($db)
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getTopCustomersByPurchaseAmount($startDate, $endDate, $limit = 5, $orderBy = 'DESC')
    {
        $orderBy = strtoupper($orderBy) === 'ASC' ? 'ASC' : 'DESC';
        $sql = "SELECT 
                    u.id_user,
                    u.fullname,
                    u.username,
                    COUNT(DISTINCT i.id_invoice) as total_orders,
                    SUM(i.TotalAmount) as total_purchase
                FROM user u
                JOIN invoice i ON u.id_user = i.id_user
                WHERE i.InvoiceDate BETWEEN :start_date AND :end_date
                GROUP BY u.id_user, u.fullname, u.username
                ORDER BY total_purchase $orderBy
                LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':start_date', $startDate);
        $stmt->bindValue(':end_date', $endDate);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerOrders($userId, $startDate, $endDate)
    {
        $sql = "SELECT 
                    i.id_invoice,
                    i.InvoiceDate,
                    i.TotalAmount,
                    i.Status,
                    COUNT(idt.id_invoice) as total_items
                FROM invoice i
                LEFT JOIN invoicedetail idt ON i.id_invoice = idt.id_invoice
                WHERE i.id_user = :user_id 
                AND i.InvoiceDate BETWEEN :start_date AND :end_date
                GROUP BY i.id_invoice, i.InvoiceDate, i.TotalAmount, i.Status
                ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':start_date', $startDate);
        $stmt->bindValue(':end_date', $endDate);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 