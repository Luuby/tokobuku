<?php
session_start();
require_once 'config.php';

$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$user_name = '';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $user_name = $user_data ? $user_data['name'] : 'User';
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Toko Buku Online - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f6f9;
    padding-top: 80px;
    color: #343a40;
}

/* Navbar */
.navbar { background-color: #1e1e2f; }
.navbar-brand { font-weight: 700; font-size: 1.5rem; color: #fff; }
.nav-link { color: #dcdcdc !important; font-weight: 500; transition: 0.2s; }
.nav-link:hover { color: #fff !important; }

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: #fff;
    text-align: center;
    padding: 7rem 2rem;
    border-radius: 1.5rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.hero-section h1 { font-size: 3rem; font-weight: 700; margin-bottom: 1rem; }
.hero-section p { font-size: 1.2rem; margin-bottom: 2rem; }
.hero-section .btn { border-radius: 50px; font-weight: 600; padding: 14px 35px; font-size: 1rem; transition: 0.3s; }
.hero-section .btn:hover { transform: translateY(-3px); }

/* Feature Cards */
.feature-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 1rem;
}
.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.15);
}

/* Icons in Cards */
.feature-card .bi {
    font-size: 2rem;
    color: #2575fc;
    margin-bottom: 1rem;
}

/* Dashboard User */
.welcome-card {
    border-radius: 1.5rem;
    padding: 3rem 2rem;
    text-align: center;
    background-color: #fff;
    box-shadow: 0 6px 25px rgba(0,0,0,0.08);
}
.welcome-card h2 { font-size: 2rem; font-weight: 700; margin-bottom: 1rem; }
.welcome-card p { color: #6c757d; margin-bottom: 2rem; }

/* Footer */
footer {
    background-color: #1e1e2f;
    color: #dcdcdc;
    text-align: center;
    padding: 2rem 0;
    margin-top: 3rem;
}
footer a { color: #fff; text-decoration: none; }

/* Responsive */
@media(max-width:768px){
    .hero-section h1 { font-size: 2.2rem; }
    .hero-section p { font-size: 1rem; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">📚 Toko Buku</a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if ($is_logged_in): ?>
            <li class="nav-item"><span class="nav-link">👋 Hai, <?php echo htmlspecialchars($user_name); ?></span></li>
            <li class="nav-item"><a class="nav-link" href="books.php">Belanja</a></li>
            <li class="nav-item"><a class="nav-link" href="orders.php">Pesanan</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
            <li class="nav-item"><a class="nav-link text-danger fw-semibold" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Masuk</a></li>
            <li class="nav-item ms-2"><a class="btn btn-danger rounded-pill" href="register.php">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">

<?php if (!$is_logged_in): ?>
    <!-- HERO -->
    <section class="hero-section mb-5">
        <h1>Selamat Datang di Toko Buku Online!</h1>
        <p>Temukan buku terbaik untuk menemani perjalanan literasi Anda.</p>
        <a href="login.php" class="btn btn-light btn-lg me-2 rounded-pill">Masuk</a>
        <a href="register.php" class="btn btn-outline-light btn-lg rounded-pill">Daftar Sekarang</a>
    </section>

    <!-- FEATURE CARDS -->
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card feature-card p-4 h-100 shadow-sm">
                <i class="bi bi-book"></i>
                <h5 class="card-title mt-3">Belanja Buku</h5>
                <p class="text-muted">Pilih dari ratusan buku berkualitas di berbagai kategori.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card p-4 h-100 shadow-sm">
                <i class="bi bi-wallet2"></i>
                <h5 class="card-title mt-3">Pembayaran Aman</h5>
                <p class="text-muted">Gunakan E-Wallet, COD, atau Transfer Bank dengan aman.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card p-4 h-100 shadow-sm">
                <i class="bi bi-truck"></i>
                <h5 class="card-title mt-3">Pengiriman Cepat</h5>
                <p class="text-muted">Pesanan dikirim cepat ke seluruh Indonesia dengan tracking real-time.</p>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- DASHBOARD USER -->
    <div class="welcome-card mb-5">
        <h2>Selamat Datang, <?php echo htmlspecialchars($user_name); ?> 👋</h2>
        <p>Temukan buku baru dan kelola pesanan Anda dengan mudah.</p>
        <div class="row mt-4 g-3">
            <div class="col-md-4"><a href="orders.php" class="btn btn-dark w-100 rounded-pill">Lihat Pesanan</a></div>
            <div class="col-md-4"><a href="profile.php" class="btn btn-outline-dark w-100 rounded-pill">Profil Saya</a></div>
            <div class="col-md-4"><a href="books.php" class="btn btn-primary w-100 rounded-pill">Belanja Buku</a></div>
        </div>
    </div>
<?php endif; ?>

</div>

<!-- FOOTER -->
<footer>
    <p>&copy; 2025 Toko Buku Online. Semua Hak Dilindungi.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
