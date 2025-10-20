<?php
// /user/index.php
session_start();
require_once '../config.php';

// pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// ambil username dari DB
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

// ambil buku terbaru
$sql = "SELECT b.*, c.name AS category_name 
        FROM books b 
        LEFT JOIN categories c ON c.id = b.category_id 
        ORDER BY b.id DESC LIMIT 12";
$books = $conn->query($sql);
if (!$books || !($books instanceof mysqli_result)) $books = [];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Bookstore — Home</title>
  <meta name="description" content="Bookstore — koleksi buku pilihan.">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- AOS CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

  <!-- Main CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-bw">

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<!-- Hero -->
<header class="hero hero-image hero-bwc" style="margin-top:80px;background-image: url('/assets/images/1759365338_68ddc8da322cd.jpg');">
  <div class="hero-overlay"></div>
  <div class="container hero-inner text-center" data-aos="fade-up">
    <h1 class="hero-title">Satu halaman dapat mengubah hidupmu.</h1>
    <p class="hero-sublead">Temukan bacaan yang menginspirasi — desain monokrom, fokus pada isi.</p>
    <div class="mt-4">
      <a href="#books" class="btn btn-ghost btn-lg rounded-pill me-2">Lihat Koleksi</a>
      <a href="contact.php" class="btn btn-ghost-outline btn-lg rounded-pill">Hubungi Kami</a>
    </div>
  </div>
</header>

<!-- Main content -->
<main class="py-5">
  <div class="container" id="books">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h2 class="section-title mb-0" data-aos="fade-up">Koleksi Buku Unggulan</h2>
        <p class="text-muted small mt-1" data-aos="fade-up" data-aos-delay="60">Klik cover atau tombol untuk melihat detail. Grid responsif 4 kolom.</p>
      </div>

      <div class="d-none d-md-block" data-aos="fade-up" data-aos-delay="100">
        <form action="/user/index.php" method="get" class="d-flex search-form">
          <input name="search" class="form-control form-control-sm input-ghost" type="search" placeholder="Cari judul atau penulis" aria-label="Cari">
          <button class="btn btn-sm btn-ghost-outline ms-2" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>

    <?php if ($books instanceof mysqli_result && $books->num_rows > 0): ?>
      <div class="row g-4">
        <?php
          $delay = 0;
          while ($book = $books->fetch_assoc()):
            $delay += 60;
        ?>
          <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
            <?php include 'includes/book_card_single.php'; ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="empty-notice text-center py-5" data-aos="fade-up">
        <p class="text-muted">Belum ada buku untuk ditampilkan.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({ duration: 700, once: true, easing: 'ease-in-out' });

  // Glass nav shrink on scroll
  (function(){
    const nav = document.querySelector('.glass-nav');
    if (!nav) return;
    function onScroll(){
      if(window.scrollY > 30){
        nav.classList.add('glass-small');
      } else {
        nav.classList.remove('glass-small');
      }
    }
    document.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  })();
</script>

</body>
</html>
