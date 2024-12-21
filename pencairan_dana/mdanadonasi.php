<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Fetch data from 'donasi' table with LEFT JOIN to 'bank', 'admin', 'donasi_detail', and 'pencairan_dana'
$sql = "
    SELECT donasi.id_donasi, 
           donasi.judul, 
           bank.payment AS metode_pembayaran,
           admin.nama_admin, 
           COUNT(donasi_detail.id_donasi) AS total_donatur,
           IFNULL(SUM(donasi_detail.nominal_donasi), 0) AS total_donasi,
           pencairan_dana_donasi.tanggal_pencairan,
           IF(pencairan_dana_donasi.id_donasi IS NOT NULL, 'Sudah Dicairkan', 'Belum Dicairkan') AS status
    FROM donasi
    LEFT JOIN bank ON donasi.id_bank = bank.id
    LEFT JOIN admin ON donasi.id_admin = admin.id_admin
    LEFT JOIN donasi_detail ON donasi.id_donasi = donasi_detail.id_donasi
    LEFT JOIN pencairan_dana_donasi ON donasi.id_donasi = pencairan_dana_donasi.id_donasi
    GROUP BY donasi.id_donasi, donasi.judul, bank.payment, admin.nama_admin, pencairan_dana_donasi.tanggal_pencairan
";


$stmt = $conn->prepare($sql);
$stmt->execute();
$donasiList = $stmt->fetchAll();
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Pencairan Dana</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Pencairan Dana</li>
                <li class="breadcrumb-item active">Galang Dana</li>
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
                                    <th>Nama Donasi</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Total Donatur</th>
                                    <th>Total Donasi</th>
                                    <th>Tanggal Pencairan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($donasiList as $donasi): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($donasi['judul']); ?></td>
                                    <td><?php echo htmlspecialchars($donasi['metode_pembayaran']); ?></td>
                                    <td><?php echo htmlspecialchars($donasi['total_donatur']); ?></td>
                                    <td>Rp. <?php echo htmlspecialchars(number_format($donasi['total_donasi'])); ?></td>
                                    <td>
                                        <?php if (!empty($donasi['tanggal_pencairan'])): ?>
                                        <?php echo htmlspecialchars($donasi['tanggal_pencairan']); ?>
                                        <?php else: ?>
                                        <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Displaying status with badge -->
                                        <?php if ($donasi['status'] == 'Sudah Dicairkan'): ?>
                                        <span class="badge bg-success">Sudah Dicairkan</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Belum Dicairkan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Button Group for Action -->
                                        <div class="btn-group" role="group" aria-label="Action Button Group">
                                            <?php if ($donasi['status'] == 'Belum Dicairkan'): ?>
                                            <a href="udanadonasi.php?id=<?php echo $donasi['id_donasi']; ?>"
                                                class="btn btn-info btn-sm" title="Cairkan Dana">
                                                <i class="bi bi-cash-coin"></i> <!-- Cash Icon for Cairkan Dana -->
                                            </a>
                                            <?php else: ?>
                                            <a href="udanadonasi.php?id=<?php echo $donasi['id_donasi']; ?>&edit=1"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i> <!-- Edit Icon -->
                                            </a>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmDeleteDana(<?php echo $donasi['id_donasi']; ?>)"
                                                title="Delete">
                                                <i class="bi bi-trash"></i> <!-- Delete Icon -->
                                            </button>
                                            <?php endif; ?>
                                        </div>
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

<script>
// Function to show SweetAlert confirmation before deletion
function confirmDeleteDana(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data pencairan dana ini akan dihapus!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'controller.php?action=delete-pencairan-dana&id=' + id;
        }
    });
}

// SweetAlert for success or error message after form submission
<?php if (isset($_SESSION['message'])): ?>
Swal.fire({
    icon: '<?php echo $_SESSION['message_type'] == 'success' ? 'success' : 'error'; ?>',
    title: '<?php echo $_SESSION['message_type'] == 'success' ? 'Berhasil!' : 'Gagal!'; ?>',
    text: '<?php echo $_SESSION['message']; ?>',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    <?php unset($_SESSION['message']); ?>
});
<?php endif; ?>
</script>