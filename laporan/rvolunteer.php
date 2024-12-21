<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Fetch data from 'volunteer' table, joining with 'admin' table for admin info, 
// 'detail_volunteer' for volunteer count, and 'user' for user data (username, email, phone)
$sql = "SELECT v.*, dv.* , a.nama_admin, u.username, u.email, u.no_telp, bank.payment
        FROM volunteer v
        JOIN detail_volunteer dv ON v.id_volunteer = dv.id_volunteer
        LEFT JOIN admin a ON v.id_admin = a.id_admin
        LEFT JOIN user u ON dv.id_user = u.id_user 
        LEFT JOIN bank ON v.id_bank = bank.id
        ";

$stmt = $conn->prepare($sql);
$stmt->execute();
$volunteerList = $stmt->fetchAll();
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Laporan Volunteer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Volunteer</li>
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
                        <h5 class="card-title">Daftar Volunteer</h5>

                        <!-- Displaying the list of volunteer -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($volunteerList as $volunteer): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['username']); ?></td>
                                    <!-- Display Donatur (Username) -->
                                    <td><?php echo htmlspecialchars($volunteer['email']); ?></td> <!-- Display Email -->
                                    <td><?php echo htmlspecialchars($volunteer['no_telp']); ?></td>
                                    <!-- Display Phone Number -->
                                    <td><?php echo htmlspecialchars($volunteer['judul']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['tanggal_daftar']); ?></td>
                                    <td>Rp. <?php echo htmlspecialchars(number_format($volunteer['nominal_donasi'])); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($volunteer['payment']); ?></td>
                                    <td>
                                        <!-- Displaying status with badge -->
                                        <?php if ($volunteer['status'] == 1): ?>
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