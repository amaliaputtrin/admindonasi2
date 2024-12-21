<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Start session to get logged-in admin data
session_start();
$adminId = $_SESSION['login']['id_admin'];  // Assume the session holds 'admin_id'

// Fetch logged-in admin data
$sql = "SELECT * FROM admin WHERE id_admin = :id_admin";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_admin' => $adminId]);
$adminData = $stmt->fetch();

// Check if the admin exists
if (!$adminData) {
    die("Admin not found");
}

?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Form for Edit Profile -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Profile</h5>

                        <!-- Form to edit admin profile -->
                        <form method="POST" action="update_profile.php">
                            <!-- Nama Admin -->
                            <div class="mb-3">
                                <label for="nama_admin" class="form-label">Nama Admin</label>
                                <input type="text" class="form-control" id="nama_admin" name="nama_admin"
                                    value="<?php echo htmlspecialchars($adminData['nama_admin']); ?>" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($adminData['email']); ?>" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-muted">Leave blank if you do not want to change your
                                    password.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<!-- Include Footer -->
<?php include '../template/footer.php'; ?>

<!-- SweetAlert Script for Success/Error Messages -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Show SweetAlert for Success or Error messages
<?php if (isset($_SESSION['profile_update'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?php echo $_SESSION['profile_update']; ?>',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    <?php unset($_SESSION['profile_update']); ?>
});
<?php elseif (isset($_SESSION['profile_update_error'])): ?>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?php echo $_SESSION['profile_update_error']; ?>',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    <?php unset($_SESSION['profile_update_error']); ?>
});
<?php endif; ?>
</script>