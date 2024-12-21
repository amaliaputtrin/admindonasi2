<?php
// Mendapatkan nama file saat ini
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : 'collapsed'; ?>"
                href="../main/index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link 
            <?php echo ($current_page == 'mdonasi.php' || $current_page == 'cudonasi.php') ? '' : 'collapsed'; ?>"
                data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-currency-exchange"></i><span>Galang Dana</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav"
                class="nav-content <?php echo ($current_page == 'mdonasi.php' || $current_page == 'cudonasi.php') ? 'show' : 'collapse'; ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="../donasi/cudonasi.php"
                        class="<?php echo ($current_page == 'cudonasi.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Kelola</span>
                    </a>
                </li>
                <li>
                    <a href="../donasi/mdonasi.php"
                        class="<?php echo ($current_page == 'mdonasi.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Penggalangan</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Galang Dana Nav -->

        <li class="nav-item">
            <a class="nav-link
            <?php echo ($current_page == 'cuvolunteer.php' || $current_page == 'mvolunteer.php') ? '' : 'collapsed'; ?>"
                data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-heart"></i><span>Volunteer</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav"
                class="nav-content <?php echo ($current_page == 'cuvolunteer.php' || $current_page == 'mvolunteer.php') ? 'show' : 'collapse'; ?>"
                data-bs-parent="
                #sidebar-nav">
                <li>
                    <a href="../volunteer/cuvolunteer.php"
                        class="<?php echo ($current_page == 'cuvolunteer.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Kelola</span>
                    </a>
                </li>
                <li>
                    <a href="../volunteer/mvolunteer.php"
                        class="<?php echo ($current_page == 'mvolunteer.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Kegiatan</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Volunteer Nav -->

        <li class="nav-item">
            <a class="nav-link 
            <?php echo ($current_page == 'rvolunteer.php' || $current_page == 'rdonasi.php') ? '' : 'collapsed'; ?>"
                data-bs-target="
                #charts-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav"
                class="nav-content <?php echo ($current_page == 'rdonasi.php' || $current_page == 'rvolunteer.php') ? 'show' : 'collapse'; ?>"
                data-bs-parent="
                #sidebar-nav">
                <li>
                    <a href="../laporan/rdonasi.php"
                        class="<?php echo ($current_page == 'rdonasi.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Data Donasi</span>
                    </a>
                </li>
                <li>
                    <a href="../laporan/rvolunteer.php"
                        class="<?php echo ($current_page == 'rvolunteer.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Data Volunteer</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Laporan Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'mrekening.php') ? 'active' : 'collapsed'; ?>"
                href="../rekening/mrekening.php">
                <i class="bi bi-credit-card"></i>
                <span>No. Rekening</span>
            </a>
        </li><!-- End No. rekening Page Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'mdanadonasi.php' || $current_page == 'mdanavolunteer.php' ||  $current_page == 'udanadonasi.php' || $current_page == 'udanavolunteer.php') ? '' : 'collapsed'; ?>"
                data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-cash-stack"></i><span>Pencairan Dana</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav"
                class="nav-content <?php echo ($current_page == 'mdanadonasi.php' || $current_page == 'mdanavolunteer.php' ||  $current_page == 'udanadonasi.php' || $current_page == 'udanavolunteer.php') ? 'show' : 'collapse'; ?> "
                data-bs-parent="
                #sidebar-nav">
                <li>
                    <a href="../pencairan_dana/mdanadonasi.php"
                        class="<?php echo ($current_page == 'mdanadonasi.php' || $current_page == 'udanadonasi.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Galang Dana</span>
                    </a>
                </li>
                <li>
                    <a href="../pencairan_dana/mdanavolunteer.php"
                        class="<?php echo ($current_page == 'mdanavolunteer.php' || $current_page == 'udanavolunteer.php') ? 'active' : ''; ?>">
                        <i class="bi bi-circle"></i><span>Volunteer</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Pencairan Dana Nav -->

        <!-- 
        <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li> -->
        <!-- End Profile Page Nav -->

        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-login.php">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login</span>
            </a>
        </li> -->
        <!-- End Login Page Nav -->

        <!-- <li class="nav-item">
            <a class="nav-link collapsed1" href="#">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul> -->
        <!-- End Logout Page Nav -->

</aside><!-- End Sidebar-->