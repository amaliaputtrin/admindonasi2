<?php
require_once '../db/db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['login']['id_admin'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in admin ID
$adminId = $_SESSION['login']['id_admin']; 

// Fetch logged-in admin data
$sql = "SELECT * FROM admin WHERE id_admin = :id_admin";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_admin' => $adminId]);
$adminData = $stmt->fetch();

// Check if the admin exists
if (!$adminData) {
    die("Admin not found");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_admin = $_POST['nama_admin'];
    $email = $_POST['email'];
    $password = $_POST['password'] ? $_POST['password'] : $adminData['password']; // Don't update password if empty

    // Update the admin profile
    $sqlUpdate = "
        UPDATE admin 
        SET nama_admin = :nama_admin, email = :email, password = :password
        WHERE id_admin = :id_admin
    ";

    $stmtUpdate = $conn->prepare($sqlUpdate);
    $success = $stmtUpdate->execute([
        ':nama_admin' => $nama_admin,
        ':email' => $email,
        ':password' => $password,
        ':id_admin' => $adminId
    ]);

    if ($success) {
        $_SESSION['profile_update'] = "Profile updated successfully!";
        header("Location: ../admin/eadminprofile.php"); // Redirect back to the profile page
        exit();
    } else {
        $_SESSION['profile_update_error'] = "Failed to update profile. Please try again.";
        header("Location: ../admin/eadminprofile.php"); // Redirect back to the profile page
        exit();
    }
}
?>