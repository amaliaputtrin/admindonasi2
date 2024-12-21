<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Fetch data from 'bank' table
$sql = "
    SELECT payment, no_rekening, nama_akun
    FROM bank
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$bankList = $stmt->fetchAll();
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Rekening Bank</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Rekening</li>
                <li class="breadcrumb-item active">Daftar Rekening</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Data Table -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Rekening Bank</h5>

                        <!-- Displaying the list of bank accounts -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Payment</th>
                                    <th>No Rekening</th>
                                    <th>Nama Akun</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($bankList as $bank): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($bank['payment']); ?></td>
                                    <td><?php echo htmlspecialchars($bank['no_rekening']); ?></td>
                                    <td><?php echo htmlspecialchars($bank['nama_akun']); ?></td>
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