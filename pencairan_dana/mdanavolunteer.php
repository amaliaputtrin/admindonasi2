<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Fetch data from 'volunteer' table with LEFT JOIN to 'bank', 'admin', 'detail_volunteer', and 'pencairan_dana_volunteer'
$sql = "
    SELECT volunteer.id_volunteer, 
           volunteer.judul, 
           bank.payment AS metode_pembayaran, 
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
    GROUP BY volunteer.id_volunteer, 
             volunteer.judul, 
             bank.payment, 
             admin.nama_admin, 
             pencairan_dana_volunteer.tanggal_pencairan
";


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
                <li class="breadcrumb-item">Pencairan Dana</li>
                <li class="breadcrumb-item active">Volunteer</li>
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
                                    <th>Nama Volunteer</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Total Kontributor</th>
                                    <th>Total Kontribusi</th>
                                    <th>Tanggal Pencairan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($volunteerList as $volunteer): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['judul']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['metode_pembayaran']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['total_kontributor']); ?></td>
                                    <td>Rp.
                                        <?php echo htmlspecialchars(number_format($volunteer['total_kontribusi'])); ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($volunteer['tanggal_pencairan'])): ?>
                                        <?php echo htmlspecialchars($volunteer['tanggal_pencairan']); ?>
                                        <?php else: ?>
                                        <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Displaying status with badge -->
                                        <?php if ($volunteer['status'] == 'Sudah Dicairkan'): ?>
                                        <span class="badge bg-success">Sudah Dicairkan</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Belum Dicairkan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Button Group for Action -->
                                        <div class="btn-group" role="group" aria-label="Action Button Group">
                                            <?php if ($volunteer['status'] == 'Belum Dicairkan'): ?>
                                            <a href="udanavolunteer.php?id=<?php echo $volunteer['id_volunteer']; ?>"
                                                class="btn btn-info btn-sm" title="Cairkan Dana">
                                                <i class="bi bi-cash-coin"></i> <!-- Cash Icon for Cairkan Dana -->
                                            </a>
                                            <?php else: ?>
                                            <a href="udanavolunteer.php?id=<?php echo $volunteer['id_volunteer']; ?>&edit=1"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i> <!-- Edit Icon -->
                                            </a>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmDeleteDana(<?php echo $volunteer['id_volunteer']; ?>)"
                                                title="Delete">
                                                <i class="bi bi-trash"></i> <!-- Trash Icon -->
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
            window.location.href = 'controller.php?action=delete-pencairan-volunteer&id=' + id;
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