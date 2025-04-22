<?php
session_start();
require_once('Model/user.php');

class UserProfileController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['id_user'])) {
            header("Location: ../auth/login.php");
            exit();
        }
        
        $userId = $_SESSION['id_user'];
        $userData = $this->getUserData($userId);
        $userAddresses = $this->userModel->getUserAddresses($userId);
        
        // Include the profile view
        require_once 'View/user/profile.php';
    }
    
    public function getUserData($userId) {
        // This method should be implemented in the UserModel
        // For now, we'll return basic user data from session
        return [
            'id' => $_SESSION['id_user'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['id_role']
        ];
    }
    
    public function updateProfile() {
        // This would handle profile updates
        // Not implemented in this initial version
    }
    
}
?> 