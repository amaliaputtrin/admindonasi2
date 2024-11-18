<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Query untuk memeriksa data admin berdasarkan email
        $query = "SELECT * FROM admin WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if ($admin && $password === $admin['password']) { // Jika tidak ada hash
            $_SESSION['admin'] = $admin['nama_admin'];
            header("Location: index.html");
            exit();
        } else {
            $error = "Email atau password salah.";
        }
    } catch (Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Login Admin</h1>
            <!-- Tampilkan pesan error jika ada -->
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <!-- Form Login -->
            <form action="" method="POST">
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email" required>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
