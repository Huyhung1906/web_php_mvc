<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Model/user.php';

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user ID from session
    $userId = $_SESSION['id_user'];
    
    // Get form data
    $province = $_POST['province'] ?? '';
    $district = $_POST['district'] ?? '';
    $ward = $_POST['ward'] ?? '';
    $street = $_POST['street'] ?? '';
    $addressType = $_POST['address_type'] ?? '';
    
    // Validate form data
    if (empty($province) || empty($district) || empty($ward) || empty($street) || empty($addressType)) {
        $_SESSION['address_error'] = "All fields are required.";
        header("Location: profile.php");
        exit();
    }
    
    try {
        // Create UserModel instance
        $userModel = new UserModel();
        
        // Add address
        $result = $userModel->addAddress($userId, $province, $district, $ward, $street, $addressType);
        
        if ($result) {
            $_SESSION['address_success'] = "Address added successfully.";
        } else {
            $_SESSION['address_error'] = "Failed to add address. Please try again.";
        }
    } catch (Exception $e) {
        $_SESSION['address_error'] = "An error occurred: " . $e->getMessage();
    }
    
    // Redirect back to profile page
    header("Location: profile.php");
    exit();
} else {
    // If not a POST request, redirect to profile page
    header("Location: profile.php");
    exit();
}
?>
