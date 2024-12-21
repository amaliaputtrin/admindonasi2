<?php
// Menggunakan koneksi dari db.php
require_once '../db/db.php'; // Pastikan ini mengandung session_start() dan koneksi DB yang benar

// Cek apakah sudah login, jika sudah, redirect ke halaman utama
if (isset($_SESSION['login']['id_admin'])) {
    header("Location: ../main/index.php");
    exit();
}

// Proses login ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data input dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk cek email dan password
    $sql = "SELECT * FROM admin WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    // Jika login berhasil
    if ($stmt->rowCount() === 1) {
        $row = $stmt->fetch();
        // Menyimpan id_admin dan nama_admin ke session
        $_SESSION['login']['id_admin'] = $row['id_admin'];
        $_SESSION['login']['nama_admin'] = $row['nama_admin'];

        // Redirect ke halaman utama setelah login berhasil
        header("Location: ../main/index.php");
        exit();
    } else {
        // Jika login gagal
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Peduly Jember</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/logo_peduly.png" rel="icon">
    <link href="../assets/img/logo_peduly.png" rel="peduly-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a class="logo d-flex align-items-center w-auto">
                                    <img src="../assets/img/logo_peduly.png" alt="">
                                    <span class="d-none d-lg-block">PedulyJember</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login</h5>

                                        <!-- Tampilkan error jika login gagal -->
                                        <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                        <?php endif; ?>

                                        <form class="row g-3 needs-validation" action="" method="POST">
                                            <div class="col-12">
                                                <label for="email" class="form-label">Email</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                    <input type="email" name="email" class="form-control" id="email"
                                                        required>
                                                    <div class="invalid-feedback">Masukkan email terlebih dahulu</div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="yourPassword" class="form-label">Password</label>
                                                <input type="password" name="password" class="form-control"
                                                    id="yourPassword" required>
                                                <div class="invalid-feedback">Masukkan password terlebih dahulu</div>
                                            </div>

                                            <div class="col-12">
                                                <button class="btn btn-primary w-100 c-#000"
                                                    type="submit">Login</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

</body>

</html>