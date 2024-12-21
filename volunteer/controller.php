<?php
require_once '../db/db.php'; // Pastikan sudah ada session_start() di db.php

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Create new volunteer
    if ($action == 'add-volunteer') {
        addVolunteer($conn);
    }

    // Update volunteer
    if ($action == 'update-volunteer') {
        updateVolunteer($conn);
    }
}

if (isset($_GET['id'])) {
    $id_volunteer = $_GET['id'];
    deleteVolunteer($conn, $id_volunteer);
}

function addVolunteer($conn) {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $lokasi = $_POST['lokasi'];
    $waktu_pelaksanaan = $_POST['waktu_pelaksanaan'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];
    $gambar = $_FILES['gambar']['name'];
    $id_bank = $_POST['id_bank'];

    // Get the admin ID from session, or default to 1 if not logged in
    $id_admin = isset($_SESSION['login']['id_admin']) ? $_SESSION['login']['id_admin'] : 1;

    // Check if the file was uploaded successfully
    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = "Error uploading file.";
        $_SESSION['message_type'] = "danger";
        return; // Exit early if upload fails
    }

    // Generate a unique file name for the image
    $ext = pathinfo($gambar, PATHINFO_EXTENSION); // Get the file extension
    $gambarName = uniqid('volunteer_') . '.' . $ext; // Generate a unique file name
    $gambarPath = 'uploads/' . $gambarName; // Set the path where the file will be stored

    // Upload the image file
    if (!move_uploaded_file($_FILES['gambar']['tmp_name'],  $gambarPath)) {
        $_SESSION['message'] = "Failed to move uploaded file.";
        $_SESSION['message_type'] = "danger";
        return; // Exit early if move fails
    }

    // Insert into database
    $kuota = $_POST['kuota'] ?? 0;

    $sql = "INSERT INTO volunteer (judul, kategori, lokasi, waktu_pelaksanaan, keterangan, status, gambar, id_bank, id_admin, kuota) 
            VALUES (:judul, :kategori, :lokasi, :waktu_pelaksanaan, :keterangan, :status, :gambar, :id_bank, :id_admin, :kuota)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':judul' => $judul,
        ':kategori' => $kategori,
        ':lokasi' => $lokasi,
        ':waktu_pelaksanaan' => $waktu_pelaksanaan,
        ':keterangan' => $keterangan,
        ':status' => $status,
        ':gambar' => $gambarPath,
        ':id_bank' => $id_bank,
        ':id_admin' => $id_admin,
        ':kuota' => $kuota
    ]);

    // Set session message and redirect to ../volunteer/mvolunteer.php
    $_SESSION['message'] = "Volunteer berhasil ditambahkan";
    $_SESSION['message_type'] = "success";
    header('Location: ../volunteer/mvolunteer.php'); // Redirect to '../volunteer/mvolunteer.php' after successful add
    exit; // Ensure no further code is executed
}

// Update Volunteer Function
function updateVolunteer($conn) {
    $id_volunteer = $_POST['id_volunteer'];
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $lokasi = $_POST['lokasi'];
    $waktu_pelaksanaan = $_POST['waktu_pelaksanaan'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];
    $gambar = $_FILES['gambar']['name'];
    $id_bank = $_POST['id_bank'];

    // Get the admin ID from session, or default to 1 if not logged in
    $id_admin = isset($_SESSION['login']['id_admin']) ? $_SESSION['login']['id_admin'] : 1;

    // Retrieve current image path for the volunteer
    $sql = "SELECT gambar FROM volunteer WHERE id_volunteer = :id_volunteer";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_volunteer' => $id_volunteer]);
    $volunteer = $stmt->fetch();

    // If there's a new image uploaded, handle it
    if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        // Generate a unique file name for the new image
        $ext = pathinfo($gambar, PATHINFO_EXTENSION); // Get the file extension
        $gambarName = uniqid('volunteer_') . '.' . $ext; // Generate a unique file name
        $gambarPath = 'uploads/' . $gambarName; // Set the new image path

        // Upload the new image file
        if (!move_uploaded_file($_FILES['gambar']['tmp_name'],  $gambarPath)) {
            $_SESSION['message'] = "Failed to move uploaded file.";
            $_SESSION['message_type'] = "danger";
            return; // Exit early if move fails
        }
    } else {
        // If no new image, keep the current image path
        $gambarPath = $volunteer['gambar'];
    }

    // Update the database
    $sql = "UPDATE volunteer SET 
                judul = :judul, 
                kategori = :kategori, 
                lokasi = :lokasi, 
                waktu_pelaksanaan = :waktu_pelaksanaan, 
                keterangan = :keterangan, 
                status = :status, 
                gambar = :gambar, 
                id_bank = :id_bank,
                id_admin = :id_admin
            WHERE id_volunteer = :id_volunteer";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':judul' => $judul,
        ':kategori' => $kategori,
        ':lokasi' => $lokasi,
        ':waktu_pelaksanaan' => $waktu_pelaksanaan,
        ':keterangan' => $keterangan,
        ':status' => $status,
        ':gambar' => $gambarPath,
        ':id_admin' => $id_admin,
        ':id_bank' => $id_bank,
        ':id_volunteer' => $id_volunteer
    ]);

    // Set session message and redirect to ../volunteer/mvolunteer.php
    $_SESSION['message'] = "Volunteer berhasil diperbarui";
    $_SESSION['message_type'] = "success";
    header('Location: ../volunteer/mvolunteer.php'); // Redirect to '../volunteer/mvolunteer.php' after successful update
    exit; // Ensure no further code is executed
}

// Delete Volunteer Function
function deleteVolunteer($conn) {
    $id_volunteer = $_GET['id'];

    // Get the current gambar path for deletion
    $sql = "SELECT gambar FROM volunteer WHERE id_volunteer = :id_volunteer";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_volunteer' => $id_volunteer]);
    $volunteer = $stmt->fetch();

    // Delete the record from the database
    $sql = "DELETE FROM volunteer WHERE id_volunteer = :id_volunteer";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_volunteer' => $id_volunteer]);

    // Delete the associated image file
    if ($volunteer && file_exists($volunteer['gambar'])) {
        unlink($volunteer['gambar']);
    }

    // Set session message and redirect to ../volunteer/mvolunteer.php
    $_SESSION['message'] = "Volunteer berhasil dihapus";
    $_SESSION['message_type'] = "success";
    header('Location: ../volunteer/mvolunteer.php'); // Redirect to '../volunteer/mvolunteer.php' after successful delete
    exit; // Ensure no further code is executed
}
?>