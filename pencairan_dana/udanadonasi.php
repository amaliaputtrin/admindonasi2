<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Ambil ID Donasi dari URL
$id_donasi = isset($_GET['id']) ? $_GET['id'] : null;

// Cek jika ID Donasi tidak ada
if (!$id_donasi) {
    echo "ID Donasi tidak ditemukan!";
    exit;
}

// Periksa apakah ini edit pencairan dana
$isEdit = isset($_GET['edit']) && $_GET['edit'] == 1;

// Ambil data donasi, termasuk total donatur dan total donasi dari tabel donasi_detail
$sql = "
    SELECT 
        d.*, 
        b.payment AS metode_pembayaran, 
        b.no_rekening, 
        b.nama_akun,
        COUNT(dd.id_detail_donasi) AS total_donatur,
        COALESCE(SUM(dd.nominal_donasi), 0) AS total_donasi
    FROM donasi d
    LEFT JOIN donasi_detail dd ON d.id_donasi = dd.id_donasi
    JOIN bank b ON d.id_bank = b.id
    WHERE d.id_donasi = :id
    GROUP BY d.id_donasi
";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id_donasi]);
$donasi = $stmt->fetch();

// Jika edit, ambil data pencairan dana
$pencairanDana = null;
if ($isEdit) {
    $sqlPencairan = "SELECT * FROM pencairan_dana_donasi WHERE id_donasi = :id_donasi";
    $stmtPencairan = $conn->prepare($sqlPencairan);
    $stmtPencairan->execute([':id_donasi' => $id_donasi]);
    $pencairanDana = $stmtPencairan->fetch();
}

?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1><?php echo $isEdit ? 'Edit Pencairan Dana' : 'Cairkan Dana Donasi'; ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Donasi</li>
                <li class="breadcrumb-item active"><?php echo $isEdit ? 'Edit Pencairan Dana' : 'Cairkan Dana'; ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form <?php echo $isEdit ? 'Edit' : 'Pencairan'; ?> Dana</h5>

                        <!-- Form Pencairan Dana -->
                        <form enctype="multipart/form-data" method="POST" action="controller.php">
                            <input type="hidden" name="action"
                                value="<?php echo $isEdit ? 'update-dana' : 'cairkan-dana'; ?>">
                            <input type="hidden" name="id_donasi" value="<?php echo $donasi['id_donasi']; ?>">

                            <!-- Nama Donasi -->
                            <div class="mb-3">
                                <label for="nama_donasi" class="form-label">Nama Donasi</label>
                                <input type="text" class="form-control" id="nama_donasi" name="nama_donasi"
                                    value="<?php echo $donasi['judul']; ?>" readonly>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <input type="text" class="form-control" id="metode_pembayaran" name="metode_pembayaran"
                                    value="<?php echo "{$donasi['metode_pembayaran']} ({$donasi['no_rekening']} - {$donasi['nama_akun']})"; ?>"
                                    readonly>
                            </div>

                            <!-- Total Donatur -->
                            <div class="mb-3">
                                <label for="total_donatur" class="form-label">Total Donatur</label>
                                <input type="text" class="form-control" id="total_donatur" name="total_donatur"
                                    value="<?php echo $donasi['total_donatur']; ?>" readonly>
                            </div>

                            <!-- Total Donasi -->
                            <div class="mb-3">
                                <label for="total_donasi" class="form-label">Total Donasi</label>
                                <input type="text" class="form-control" id="total_donasi" name="total_donasi"
                                    value="Rp. <?php echo number_format($donasi['total_donasi']); ?>" readonly>
                            </div>

                            <!-- Tanggal Pencairan -->
                            <div class="mb-3">
                                <label for="tanggal_pencairan" class="form-label">Tanggal Pencairan</label>
                                <input type="date" class="form-control" id="tanggal_pencairan" name="tanggal_pencairan"
                                    value="<?php echo $isEdit ? $pencairanDana['tanggal_pencairan'] : date('Y-m-d'); ?>"
                                    min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <button type="submit"
                                class="btn btn-primary"><?php echo $isEdit ? 'Update' : 'Simpan'; ?></button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php include '../template/footer.php'; ?>