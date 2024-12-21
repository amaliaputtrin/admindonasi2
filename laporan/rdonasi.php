<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Fetch data from 'donasi' table with LEFT JOIN to 'bank', 'admin', and 'user' (for donor info)
$sql = "
    SELECT donasi.*, donasi_detail.*, bank.payment, admin.nama_admin, user.username, user.email, user.no_telp
    FROM donasi
    LEFT JOIN bank ON donasi.id_bank = bank.id
    LEFT JOIN admin ON donasi.id_admin = admin.id_admin
    JOIN donasi_detail ON donasi.id_donasi = donasi_detail.id_donasi
    JOIN user ON donasi_detail.id_user = user.id_user 
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$donasiList = $stmt->fetchAll();
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Laporan Galang Dana</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Galang Dana</li>
                <li class="breadcrumb-item active">Laporan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Data Table -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Donasi</h5>

                        <!-- Displaying the list of donasi -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Donatur</th> <!-- New column for Donatur (Username) -->
                                    <th>Email</th> <!-- New column for Email -->
                                    <th>No. Telp</th> <!-- New column for Phone -->
                                    <th>Judul</th>
                                    <th>Tanggal Donasi</th>
                                    <th>Jumlah Donasi</th>
                                    <th>Pembayaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($donasiList as $donasi): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($donasi['username']); ?></td>
                                    <!-- Display Donatur (Username) -->
                                    <td><?php echo htmlspecialchars($donasi['email']); ?></td> <!-- Display Email -->
                                    <td><?php echo htmlspecialchars($donasi['no_telp']); ?></td>
                                    <!-- Display Phone Number -->
                                    <td><?php echo htmlspecialchars($donasi['judul']); ?></td>
                                    <td><?php echo htmlspecialchars($donasi['tanggal_donasi']); ?></td>
                                    <td>Rp. <?php echo htmlspecialchars(number_format($donasi['nominal_donasi'])); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($donasi['payment']); ?></td>
                                    <td>
                                        <!-- Displaying status with badge -->
                                        <?php if ($donasi['status'] == 1): ?>
                                        <span class="badge bg-success">Completed</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Not Completed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php include '../template/footer.php'; ?>