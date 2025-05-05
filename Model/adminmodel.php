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
    public function addUser($username, $password, $fullname, $email, $phone, $id_role)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $queryStr = $this->conn->prepare("INSERT INTO user (username, password, fullname, email, phone, id_role, is_active) 
                                      VALUES (:username, :password, :fullname, :email, :phone, :id_role, 1)");

        $queryStr->bindParam(':username', $username);
        $queryStr->bindParam(':password', $hashed_password);
        $queryStr->bindParam(':fullname', $fullname);
        $queryStr->bindParam(':email', $email);
        $queryStr->bindParam(':phone', $phone);
        $queryStr->bindParam(':id_role', $id_role); // thêm dòng này nè bạn

        return $queryStr->execute();
    }
    public function checkUsernameExists($username)
{
    $query = $this->conn->prepare("SELECT COUNT(*) FROM user WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();
    return $query->fetchColumn() > 0; // trả về true nếu tồn tại
}


    public function getAllRoles() {
        $query = "SELECT id_role, name_role FROM role";
        $result = $this->conn->query($query);

        $roles = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = $row;
        }
        return $roles;
    }
    public function getAllUsersWithRole() {
        $sql = "SELECT user.*, role.name_role 
                FROM user 
                JOIN role ON user.id_role = role.id_role";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function canPerformAction($id_role, $permission_id) {
        $stmt = $this->conn->prepare("SELECT * FROM phanrole WHERE id_role = :id_role AND id_chitietrole = :permission_id");
        $stmt->bindParam(':id_role', $id_role);
        $stmt->bindParam(':permission_id', $permission_id);
        $stmt->execute();
    
        return $stmt->rowCount() > 0;
    }
    
}
