<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Fetch data from 'volunteer' table, joining with 'admin' table for admin info and 'detail_volunteer' for volunteer count
$sql = "SELECT v.*, a.nama_admin, bank.payment, COUNT(dv.id_volunteer) AS jumlah_volunteer
        FROM volunteer v
        LEFT JOIN detail_volunteer dv ON v.id_volunteer = dv.id_volunteer
        LEFT JOIN admin a ON v.id_admin = a.id_admin
        LEFT JOIN bank ON v .id_bank = bank.id
        GROUP BY v.id_volunteer";
$stmt = $conn->prepare($sql);
$stmt->execute();
$volunteerList = $stmt->fetchAll();
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Kelola Volunteer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Volunteer</li>
                <li class="breadcrumb-item active">Kelola</li>
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
                                    <th>#</th>
                                    <th>Gambar</th> <!-- Column for displaying image -->
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Pelaksanaan</th>
                                    <th>Lokasi</th>
                                    <th>Bank</th>
                                    <th>Keterangan</th> <!-- Column for displaying admin's name -->
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($volunteerList as $volunteer): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <?php if (!empty($volunteer['gambar'])): ?>
                                        <img src="<?php echo htmlspecialchars($volunteer['gambar']); ?>"
                                            alt="Volunteer Image" style="max-width: 100px; height: auto;">
                                        <?php else: ?>
                                        <span>No image available</span>
                                        <?php endif; ?>
                                    </td> <!-- Displaying volunteer image -->
                                    <td><?php echo htmlspecialchars($volunteer['judul']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['kategori']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['waktu_pelaksanaan']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['lokasi']); ?></td>
                                    </td>
                                    <td><?php echo htmlspecialchars($volunteer['payment']); ?></td>

                                    <td><?php echo htmlspecialchars($volunteer['keterangan']); ?></td>
                                    <!-- Displaying admin's name -->
                                    <td>
                                        <!-- Displaying status with badge -->
                                        <?php if ($volunteer['status'] == 1): ?>
                                        <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    <td>
                                        <div class="btn-group">
                                            <!-- Update Button -->
                                            <a href="cuvolunteer.php?id=<?php echo $volunteer['id_volunteer']; ?>"
                                                class="btn btn-warning btn-sm"> <i class="bi bi-pencil"></i>
                                                <!-- Update Icon --></a>

                                            <!-- Delete Button -->
                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(<?php echo $volunteer['id_volunteer']; ?>)"> <i
                                                    class="bi bi-trash"></i> <!-- Delete Icon --></button>
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