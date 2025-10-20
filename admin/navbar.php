<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php">
            <i class="bi bi-book-half me-2"></i>Admin Bookstore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
            aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="adminNavbar">
            <!-- Menu kiri -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php 
                $menuItems = [
                    'index.php' => 'Dashboard',
                    'categories.php' => 'Kategori',
                    'books.php' => 'Buku',
                    'users.php' => 'User',
                    'orders.php' => 'Pesanan',
                    'messages.php' => 'Pesan User'
                ];
                foreach($menuItems as $file => $title):
                    $active = basename($_SERVER['PHP_SELF']) == $file ? ' active fw-bold' : '';
                ?>
                <li class="nav-item">
                    <a class="nav-link<?= $active ?>" href="<?= $file ?>"><?= $title ?></a>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- Menu kanan -->
            <div class="d-flex align-items-center">
                <span class="text-light me-3 fw-semibold"><i class="bi bi-person-circle me-1"></i> Admin</span>
                <a href="../logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
/* Hover dan transition menu */
.navbar-nav .nav-link {
    transition: all 0.2s;
}
.navbar-nav .nav-link:hover {
    color: #0d6efd !important;
    transform: translateY(-2px);
}
.navbar-nav .nav-link.active {
    color: #0d6efd !important;
    border-bottom: 2px solid #0d6efd;
}
.btn-outline-light:hover {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
    transform: translateY(-2px);
}
</style>
