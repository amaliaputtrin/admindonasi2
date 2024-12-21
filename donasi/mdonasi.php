<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Fetch data from 'donasi' table with LEFT JOIN to 'bank' and 'admin'
$sql = "
    SELECT donasi.*, bank.payment, admin.nama_admin, 
    IFNULL(SUM(donasi_detail.nominal_donasi), 0) AS terkumpul
    FROM donasi
    LEFT JOIN bank ON donasi.id_bank = bank.id
    LEFT JOIN admin ON donasi.id_admin = admin.id_admin
    LEFT JOIN donasi_detail ON donasi.id_donasi = donasi_detail.id_donasi
    GROUP BY donasi.id_donasi
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$donasiList = $stmt->fetchAll();
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Kelola Galang Dana</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Galang Dana</li>
                <li class="breadcrumb-item active">Penggalangan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Data Table -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Penggalangan</h5>

                        <!-- Displaying the list of donasi -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Target</th>
                                    <th>Terkumpul</th>
                                    <th>Tenggat</th>
                                    <th>Bank</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($donasiList as $donasi): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <!-- Displaying the image -->
                                        <img src="<?php echo htmlspecialchars($donasi['gambar']); ?>"
                                            alt="Gambar Donasi" style="width: 100px; height: auto;">
                                    </td>
                                    <td><?php echo htmlspecialchars($donasi['judul']); ?></td>
                                    <td><?php echo htmlspecialchars($donasi['kategori']); ?></td>
                                    <td>Rp. <?php echo htmlspecialchars(number_format($donasi['target'])); ?></td>
                                    <td>Rp. <?php echo htmlspecialchars(number_format($donasi['terkumpul'])); ?></td>
                                    <td><?php echo htmlspecialchars($donasi['tanggal_tenggat']); ?></td>
                                    <td><?php echo htmlspecialchars($donasi['payment']); ?></td>
                                    <td><?php echo htmlspecialchars($donasi['keterangan']); ?></td>
                                    <td>
                                        <!-- Displaying status with badge -->
                                        <?php if ($donasi['status'] == 1): ?>
                                        <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <!-- Button Group for Update and Delete -->
                                        <div class="btn-group" role="group" aria-label="Action Button Group">
                                            <a href="cudonasi.php?id=<?php echo $donasi['id_donasi']; ?>"
                                                class="btn btn-warning btn-sm" title="Update">
                                                <i class="bi bi-pencil"></i> <!-- Update Icon -->
                                            </a>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(<?php echo $donasi['id_donasi']; ?>)"
                                                title="Delete">
                                                <i class="bi bi-trash"></i> <!-- Delete Icon -->
                                            </button>
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
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data ini akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'controller.php?action=delete-donasi&id=' + id;
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