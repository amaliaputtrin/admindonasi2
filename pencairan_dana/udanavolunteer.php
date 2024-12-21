<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Ambil ID Volunteer dari URL
$id_volunteer = isset($_GET['id']) ? $_GET['id'] : null;

// Cek jika ID Volunteer tidak ada
if (!$id_volunteer) {
    echo "ID Volunteer tidak ditemukan!";
    exit;
}

// Periksa apakah ini edit pencairan dana
$isEdit = isset($_GET['edit']) && $_GET['edit'] == 1;

// Ambil data volunteer, termasuk total kontributor dan total kontribusi dari tabel detail_volunteer
$sql = "
SELECT volunteer.*, 
bank.*, 
admin.nama_admin, 
COUNT(detail_volunteer.id_volunteer) AS total_kontributor, 
IFNULL(SUM(detail_volunteer.nominal_donasi), 0) AS total_kontribusi,
pencairan_dana_volunteer.tanggal_pencairan, 
IF(pencairan_dana_volunteer.id_volunteer IS NOT NULL, 'Sudah Dicairkan', 'Belum Dicairkan') AS status
FROM volunteer
LEFT JOIN bank ON volunteer.id_bank = bank.id
LEFT JOIN admin ON volunteer.id_admin = admin.id_admin
LEFT JOIN detail_volunteer ON volunteer.id_volunteer = detail_volunteer.id_volunteer
LEFT JOIN pencairan_dana_volunteer ON volunteer.id_volunteer = pencairan_dana_volunteer.id_volunteer
WHERE volunteer.id_volunteer = :id
GROUP BY volunteer.id_volunteer,
        volunteer.judul, 
         volunteer.kategori, 
         volunteer.lokasi, 
         volunteer.waktu_pelaksanaan, 
         volunteer.keterangan, 
         volunteer.status, 
         volunteer.gambar, 
         volunteer.id_bank, 
         volunteer.id_admin, 
         bank.no_rekening, 
         bank.nama_akun, 
         admin.nama_admin, 
         pencairan_dana_volunteer.tanggal_pencairan;
";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id_volunteer]);
$volunteer = $stmt->fetch();

// Jika edit, ambil data pencairan dana volunteer
$pencairanDana = null;
if ($isEdit) {
    $sqlPencairan = "SELECT * FROM pencairan_dana_volunteer WHERE id_volunteer = :id_volunteer";
    $stmtPencairan = $conn->prepare($sqlPencairan);
    $stmtPencairan->execute([':id_volunteer' => $id_volunteer]);
    $pencairanDana = $stmtPencairan->fetch();
}
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1><?php echo $isEdit ? 'Edit Pencairan Dana Volunteer' : 'Cairkan Dana Volunteer'; ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Volunteer</li>
                <li class="breadcrumb-item active"><?php echo $isEdit ? 'Edit Pencairan Dana' : 'Cairkan Dana'; ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form <?php echo $isEdit ? 'Edit' : 'Pencairan'; ?> Dana Volunteer</h5>

                        <!-- Form Pencairan Dana -->
                        <form enctype="multipart/form-data" method="POST" action="controller.php">
                            <input type="hidden" name="action"
                                value="<?php echo $isEdit ? 'update-volunteer-dana' : 'create-volunteer-dana'; ?>">
                            <input type="hidden" name="id_volunteer" value="<?php echo $volunteer['id_volunteer']; ?>">

                            <!-- Nama Volunteer -->
                            <div class="mb-3">
                                <label for="nama_volunteer" class="form-label">Nama Volunteer</label>
                                <input type="text" class="form-control" id="nama_volunteer" name="nama_volunteer"
                                    value="<?php echo $volunteer['judul']; ?>" readonly>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <input type="text" class="form-control" id="metode_pembayaran" name="metode_pembayaran"
                                    value="<?php echo "{$volunteer['payment']} ({$volunteer['no_rekening']} - {$volunteer['nama_akun']})"; ?>"
                                    readonly>
                            </div>

                            <!-- Total Kontributor -->
                            <div class="mb-3">
                                <label for="total_kontributor" class="form-label">Total Kontributor</label>
                                <input type="text" class="form-control" id="total_kontributor" name="total_kontributor"
                                    value="<?php echo $volunteer['total_kontributor']; ?>" readonly>
                            </div>

                            <!-- Total Kontribusi -->
                            <div class="mb-3">
                                <label for="total_kontribusi" class="form-label">Total Kontribusi</label>
                                <input type="text" class="form-control" id="total_kontribusi" name="total_kontribusi"
                                    value="Rp. <?php echo number_format($volunteer['total_kontribusi']); ?>" readonly>
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