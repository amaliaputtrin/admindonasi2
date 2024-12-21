<?php
require_once '../db/db.php'; // Pastikan sudah ada session_start() di db.php

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Create new donasi
    if ($action == 'add-donasi') {
        addDonasi($conn);
    }

    // Update donasi
    if ($action == 'update-donasi') {
        updateDonasi($conn);
    }
}

if (isset($_GET['id'])) {
    $id_donasi = $_GET['id'];
    deleteDonasi($conn, $id_donasi);
}

function addDonasi($conn) {
    $judul = $_POST['judul'];
    $id_bank = $_POST['id_bank'];
    $kategori = $_POST['kategori'];
    $target = $_POST['target'];
    $lokasi = $_POST['lokasi'];
    $tanggal_tenggat = $_POST['tanggal_tenggat'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];
    $gambar = $_FILES['gambar']['name'];

    // Get the admin ID from session, or default to 1 if not logged in
    $id_admin = isset($_SESSION['login']['id_admin']) ? $_SESSION['login']['id_admin'] : 1;

    // Check if the file was uploaded successfully
    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = "Error uploading file.";
        $_SESSION['message_type'] = "danger";
    }

    // Generate a unique file name for the image
    $ext = pathinfo($gambar, PATHINFO_EXTENSION); // Get the file extension
    $gambarName = uniqid('donasi_') . '.' . $ext; // Generate a unique file name
    $gambarPath = 'uploads/' . $gambarName;

    // Upload gambar
    if (!move_uploaded_file($_FILES['gambar']['tmp_name'],  $gambarPath)) {
        $_SESSION['message'] = "Failed to move uploaded file.";
        $_SESSION['message_type'] = "danger";
    }

    // Insert into database
    $sql = "INSERT INTO donasi (judul, id_bank, kategori, target, lokasi, tanggal_tenggat, keterangan, status, gambar, id_admin) 
            VALUES (:judul, :id_bank, :kategori, :target, :lokasi, :tanggal_tenggat, :keterangan, :status, :gambar, :id_admin)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':judul' => $judul,
        ':id_bank' => $id_bank,
        ':kategori' => $kategori,
        ':target' => $target,
        ':lokasi' => $lokasi,
        ':tanggal_tenggat' => $tanggal_tenggat,
        ':keterangan' => $keterangan,
        ':status' => $status,
        ':gambar' => $gambarPath,
        ':id_admin' => $id_admin
    ]);

    // Set session message and redirect to mdonasi.php
    $_SESSION['message'] = "Donasi berhasil ditambahkan";
    $_SESSION['message_type'] = "success";
    header('Location: mdonasi.php'); // Redirect to 'mdonasi.php' after successful add
    exit; // Make sure no further code is executed
}

// Update Donasi Function
function updateDonasi($conn) {
    $id_donasi = $_POST['id_donasi'];
    $judul = $_POST['judul'];
    $id_bank = $_POST['id_bank'];
    $kategori = $_POST['kategori'];
    $target = $_POST['target'];
    $lokasi = $_POST['lokasi'];
    $tanggal_tenggat = $_POST['tanggal_tenggat'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];
    $gambar = $_FILES['gambar']['name'];

    // Get the admin ID from session, or default to 1 if not logged in
    $id_admin = isset($_SESSION['login']['id_admin']) ? $_SESSION['login']['id_admin'] : 1;

    // Retrieve current image path for the donasi
    $sql = "SELECT gambar FROM donasi WHERE id_donasi = :id_donasi";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_donasi' => $id_donasi]);
    $donasi = $stmt->fetch();

    // If there's no new image uploaded, keep the current image path
    if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        // Generate a unique file name for the image
        $ext = pathinfo($gambar, PATHINFO_EXTENSION); // Get the file extension
        $gambarName = uniqid('donasi_') . '.' . $ext; // Generate a unique file name
        $gambarPath = 'uploads/' . $gambarName;

        // Upload gambar
        if (!move_uploaded_file($_FILES['gambar']['tmp_name'],  $gambarPath)) {
            $_SESSION['message'] = "Failed to move uploaded file.";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        // If no new image is uploaded, use the existing image path
        $gambarPath = $donasi['gambar'];
    }

    // Update the database
    $sql = "UPDATE donasi SET 
                judul = :judul, 
                id_bank = :id_bank, 
                kategori = :kategori, 
                target = :target, 
                lokasi = :lokasi, 
                tanggal_tenggat = :tanggal_tenggat, 
                keterangan = :keterangan, 
                status = :status, 
                gambar = :gambar, 
                id_admin = :id_admin
            WHERE id_donasi = :id_donasi";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':judul' => $judul,
        ':id_bank' => $id_bank,
        ':kategori' => $kategori,
        ':target' => $target,
        ':lokasi' => $lokasi,
        ':tanggal_tenggat' => $tanggal_tenggat,
        ':keterangan' => $keterangan,
        ':status' => $status,
        ':gambar' => $gambarPath,
        ':id_admin' => $id_admin,
        ':id_donasi' => $id_donasi
    ]);

    // Set session message and redirect to mdonasi.php
    $_SESSION['message'] = "Donasi berhasil diperbarui";
    $_SESSION['message_type'] = "success";
    header('Location: mdonasi.php'); // Redirect to 'mdonasi.php' after successful update
    exit; // Make sure no further code is executed
}

// Delete Donasi Function
function deleteDonasi($conn) {
    var_dump(1);
    $id_donasi = $_GET['id'];

    // Get the current gambar path for deletion
    $sql = "SELECT gambar FROM donasi WHERE id_donasi = :id_donasi";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_donasi' => $id_donasi]);
    $donasi = $stmt->fetch();

    // Delete the record from the database
    $sql = "DELETE FROM donasi WHERE id_donasi = :id_donasi";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_donasi' => $id_donasi]);

    // Delete the associated image file
    if ($donasi && file_exists($donasi['gambar'])) {
        unlink($donasi['gambar']);
    }

    // Set session message and redirect to mdonasi.php
    $_SESSION['message'] = "Donasi berhasil dihapus";
    $_SESSION['message_type'] = "success";
    header('Location: mdonasi.php'); // Redirect to 'mdonasi.php' after successful delete
    exit; // Make sure no further code is executed
}