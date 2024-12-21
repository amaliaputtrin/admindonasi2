<?php
require_once '../db/db.php'; // Pastikan sudah ada session_start() di db.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Cairkan Dana
        if ($action == 'cairkan-dana') {
            cairkanDana($conn);
        }

        if ($action == 'update-dana') {
            updateDana($conn);
        }

        if ($action == 'create-volunteer-dana') {
            createVolunteerDana($conn);
        }

        // Update Dana Volunteer
        if ($action == 'update-volunteer-dana') {
            updateVolunteerDana($conn);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete-pencairan-dana') {
    $id_donasi = $_GET['id'];
    deletePencairanDana($conn, $id_donasi);
}

if (isset($_GET['action']) && $_GET['action'] == 'delete-pencairan-volunteer') {
    $id_volunteer = $_GET['id'];
    deleteVolunteerDana($conn, $id_volunteer);
}

function deletePencairanDana($conn, $id_donasi) {
    try {
        // Hapus data pencairan dana berdasarkan ID Donasi
        $sql = "DELETE FROM pencairan_dana_donasi WHERE id_donasi = :id_donasi";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_donasi' => $id_donasi]);

        // Set pesan sukses ke session
        $_SESSION['message'] = "Pencairan dana untuk ID Donasi $id_donasi berhasil dihapus.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        // Tangani error
        $_SESSION['message'] = "Terjadi kesalahan";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect kembali ke halaman daftar
    header('Location: ../pencairan_dana/mdanadonasi.php'); // Redirect ke halaman pencairan dana
    exit();
}


function cairkanDana($conn) {
    $id_donasi = $_POST['id_donasi'] ?? null;
    $tanggal_pencairan = $_POST['tanggal_pencairan'] ?? null;
    $total_donasi = $_POST['total_donasi'] ?? 0;

    // Hapus teks "Rp." dan karakter koma
    $total_donasi = str_replace(['Rp.', ',', ' '], '', $total_donasi);

    if (!$id_donasi || !$tanggal_pencairan) {
        $_SESSION['message'] = "ID Donasi atau Tanggal Pencairan tidak valid.";
        $_SESSION['message_type'] = "danger";
        header('Location: ../pencairan_dana/mdanadonasi.php'); // Redirect ke halaman pencairan dana
        exit();
    }

    try {
        // Insert data pencairan dana ke database
        $sqlInsert = "INSERT INTO pencairan_dana_donasi (id_donasi, nominal, tanggal_pencairan) 
                      VALUES (:id_donasi, :nominal, :tanggal_pencairan)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([
            ':id_donasi' => $id_donasi,
            ':nominal' => (int)$total_donasi,
            ':tanggal_pencairan' => $tanggal_pencairan
        ]);
        
        // Set pesan sukses ke session
        $_SESSION['message'] = "Dana berhasil dicairkan";
        $_SESSION['message_type'] = "success";
        header('Location: ../pencairan_dana/mdanadonasi.php'); // Redirect ke halaman pencairan dana
        exit();

    } catch (Exception $e) {
        // Tangani error dan set pesan ke session
        $_SESSION['message'] = "Terjadi kesalahan";
        $_SESSION['message_type'] = "danger";

        header('Location: ../pencairan_dana/mdanadonasi.php'); // Redirect ke halaman pencairan dana
        exit();
    }
}

function updateDana($conn) {
    $id_donasi = $_POST['id_donasi'];
    $tanggal_pencairan = $_POST['tanggal_pencairan'];

    try {
        // Update data pencairan dana
        $sqlUpdate = "UPDATE pencairan_dana_donasi 
                      SET tanggal_pencairan = :tanggal_pencairan 
                      WHERE id_donasi = :id_donasi";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':id_donasi' => $id_donasi,
            ':tanggal_pencairan' => $tanggal_pencairan
        ]);

        // Set pesan sukses ke session
        $_SESSION['message'] = "Pencairan dana berhasil diperbarui ";
        $_SESSION['message_type'] = "success";
        header('Location: ../pencairan_dana/mdanadonasi.php'); // Redirect ke halaman pencairan dana
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = "Terjadi kesalahan";
        $_SESSION['message_type'] = "danger";
        
        header('Location: ../pencairan_dana/mdanadonasi.php'); // Redirect ke halaman pencairan dana
        exit();
    }
}

function createVolunteerDana($conn) {
    $id_volunteer = $_POST['id_volunteer'] ?? null;
    $tanggal_pencairan = $_POST['tanggal_pencairan'] ?? null;
    $total_kontribusi = $_POST['total_kontribusi'] ?? 0;

    if (!$id_volunteer || !$tanggal_pencairan) {
        $_SESSION['message'] = "ID Volunteer atau Tanggal Pencairan tidak valid.";
        $_SESSION['message_type'] = "danger";
        header('Location: ../pencairan_dana/mdanavolunteer.php'); // Redirect ke halaman volunteer
        exit();
    }

        // Hapus teks "Rp." dan karakter koma
        $total_kontribusi = str_replace(['Rp.', ',', ' '], '', $total_kontribusi);

    try {
        // Insert data pencairan dana ke database
        $sqlInsert = "INSERT INTO pencairan_dana_volunteer (id_volunteer, nominal, tanggal_pencairan) 
                      VALUES (:id_volunteer, :nominal, :tanggal_pencairan)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([
            ':id_volunteer' => $id_volunteer,
            ':nominal' => (int)$total_kontribusi,
            ':tanggal_pencairan' => $tanggal_pencairan
        ]);
        
        // Set pesan sukses ke session
        $_SESSION['message'] = "Dana volunteer berhasil dicairkan.";
        $_SESSION['message_type'] = "success";
        header('Location: ../pencairan_dana/mdanavolunteer.php'); // Redirect ke halaman volunteer
        exit();

    } catch (Exception $e) {
        // Tangani error dan set pesan ke session
        $_SESSION['message'] = "Terjadi kesalahan.";
        $_SESSION['message_type'] = "danger";

        header('Location: ../pencairan_dana/mdanavolunteer.php'); // Redirect ke halaman volunteer
        exit();
    }
}

function updateVolunteerDana($conn) {
    $id_volunteer = $_POST['id_volunteer'];
    $tanggal_pencairan = $_POST['tanggal_pencairan'];

    try {
        // Update data pencairan dana volunteer
        $sqlUpdate = "UPDATE pencairan_dana_volunteer 
                      SET tanggal_pencairan = :tanggal_pencairan 
                      WHERE id_volunteer = :id_volunteer";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':id_volunteer' => $id_volunteer,
            ':tanggal_pencairan' => $tanggal_pencairan
        ]);

        // Set pesan sukses ke session
        $_SESSION['message'] = "Pencairan dana volunteer berhasil diperbarui.";
        $_SESSION['message_type'] = "success";
        header('Location: ../pencairan_dana/mdanavolunteer.php'); // Redirect ke halaman volunteer
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = "Terjadi kesalahan.";
        $_SESSION['message_type'] = "danger";
        
        header('Location: ../pencairan_dana/mdanavolunteer.php'); // Redirect ke halaman volunteer
        exit();
    }
}

function deleteVolunteerDana($conn, $id_volunteer) {
    try {
        // Hapus data pencairan dana volunteer berdasarkan ID
        $sql = "DELETE FROM pencairan_dana_volunteer WHERE id_volunteer = :id_volunteer";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_volunteer' => $id_volunteer]);

        // Set pesan sukses ke session
        $_SESSION['message'] = "Pencairan dana volunteer berhasil dihapus.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        // Tangani error
        $_SESSION['message'] = "Terjadi kesalahan.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect kembali ke halaman volunteer
    header('Location: ../pencairan_dana/mdanavolunteer.php'); // Redirect ke halaman volunteer
    exit();
}