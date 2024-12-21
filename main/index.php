<?php

include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';
// Include database connection

// Fetch data for the dashboard cards
$totalDonasiQuery = "SELECT COUNT(*) AS total_donasi FROM donasi";
$stmt = $conn->prepare($totalDonasiQuery);
$stmt->execute();
$totalDonasi = $stmt->fetchColumn();

$totalVolunteerQuery = "SELECT COUNT(*) AS total_volunteer FROM volunteer";
$stmt = $conn->prepare($totalVolunteerQuery);
$stmt->execute();
$totalVolunteer = $stmt->fetchColumn();

// Fetch total pencairan dana (sum of nominal from both tables)
$totalPencairanDonasiQuery = "SELECT COALESCE(SUM(nominal), 0) AS total_pencairan_donasi FROM pencairan_dana_donasi";
$stmt = $conn->prepare($totalPencairanDonasiQuery);
$stmt->execute();
$totalPencairanDonasi = $stmt->fetchColumn();

$totalPencairanVolunteerQuery = "SELECT COALESCE(SUM(nominal), 0) AS total_pencairan_volunteer FROM pencairan_dana_volunteer";
$stmt = $conn->prepare($totalPencairanVolunteerQuery);
$stmt->execute();
$totalPencairanVolunteer = $stmt->fetchColumn();

$totalPencairan = $totalPencairanDonasi + $totalPencairanVolunteer;

// Fetch recent donations (7 days)
$sqlDonations = "
    SELECT donasi.*, donasi_detail.*, bank.payment, admin.nama_admin, user.username, user.email, user.no_telp
    FROM donasi
    LEFT JOIN bank ON donasi.id_bank = bank.id
    LEFT JOIN admin ON donasi.id_admin = admin.id_admin
    JOIN donasi_detail ON donasi.id_donasi = donasi_detail.id_donasi
    JOIN user ON donasi_detail.id_user = user.id_user
    WHERE donasi_detail.tanggal_donasi >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
";
$stmt = $conn->prepare($sqlDonations);
$stmt->execute();
$recentDonations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent volunteers (7 days)
$sqlVolunteers = "
    SELECT v.*, dv.*, a.nama_admin, u.username, u.email, u.no_telp, bank.payment
    FROM volunteer v
    JOIN detail_volunteer dv ON v.id_volunteer = dv.id_volunteer
    LEFT JOIN admin a ON v.id_admin = a.id_admin
    LEFT JOIN user u ON dv.id_user = u.id_user
    LEFT JOIN bank ON v.id_bank = bank.id
    WHERE dv.tanggal_daftar >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
";
$stmt = $conn->prepare($sqlVolunteers);
$stmt->execute();
$recentVolunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Dashboard Cards -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Total Donasi Card -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Donasi</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $totalDonasi; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Volunteer Card -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Volunteer</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-check"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $totalVolunteer; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pencairan Dana Card -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Pencairan Dana</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-bank"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>Rp. <?php echo number_format($totalPencairan, 0, ',', '.'); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Recent Donations Table -->
            <div class="col-6">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">Donasi dalam 7 Hari Terakhir</h5>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Donatur</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Donasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDonations as $key => $donation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($donation['username']); ?></td>
                                    <td><?php echo htmlspecialchars($donation['keterangan']); ?></td>
                                    <td><?php echo htmlspecialchars($donation['tanggal_donasi']); ?></td>
                                    <td>Rp. <?php echo number_format($donation['nominal_donasi']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Volunteer Contributions Table -->
            <div class="col-6">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">Volunteer dalam 7 Hari Terakhir</h5>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Donatur</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Donasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentVolunteers as $key => $volunteer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($volunteer['username']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['keterangan']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['tanggal_daftar']); ?></td>
                                    <td>Rp. <?php echo number_format($volunteer['nominal_donasi']); ?></td>
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
</main>

<?php include '../template/footer.php'; ?>