<?php
session_start(); // Memulai session

// Menghancurkan semua session
session_unset();  // Menghapus semua data session
session_destroy(); // Menghancurkan session

// Arahkan pengguna kembali ke halaman login
header("Location: ../auth/login.php");
exit();
?>