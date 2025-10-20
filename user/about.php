<?php
session_start();
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tentang Kami - Bookstore</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      background-color: #fff;
      color: #1c1c1c;
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }

    h1, h2, h3, h4 {
      font-weight: 600;
    }

    /* ====== HERO ====== */
    .hero {
      background: #000;
      color: #fff;
      padding: 100px 0 120px;
      text-align: center;
      position: relative;
    }

    .hero h1 {
      font-size: 3rem;
      margin-bottom: 15px;
      letter-spacing: 1px;
    }

    .hero p {
      font-size: 1.1rem;
      color: #ccc;
      max-width: 700px;
      margin: 0 auto;
    }

    .hero::after {
      content: "";
      position: absolute;
      bottom: -40px;
      left: 50%;
      transform: translateX(-50%);
      width: 120px;
      height: 4px;
      background: #fff;
      border-radius: 2px;
    }

    /* ====== ABOUT ====== */
    .section {
      padding: 80px 0;
    }

    .about-text {
      font-size: 1.05rem;
      line-height: 1.8;
      color: #333;
    }

    .about-image {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .about-image img {
      width: 100%;
      height: auto;
      transition: transform 0.4s ease;
    }

    .about-image img:hover {
      transform: scale(1.05);
    }

    /* ====== INFO CARDS ====== */
    .info-card {
      background: #f8f8f8;
      border-radius: 14px;
      padding: 30px;
      border: 1px solid #e5e5e5;
      text-align: center;
      transition: all 0.3s ease;
    }

    .info-card:hover {
      background: #fff;
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
      transform: translateY(-5px);
    }

    .info-card i {
      font-size: 2.5rem;
      color: #000;
      margin-bottom: 15px;
    }

    .info-card h4 {
      margin-bottom: 10px;
    }

    /* ====== CONTACT ====== */
    .contact-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 5px 18px rgba(0,0,0,0.06);
      padding: 30px;
      border: 1px solid #eee;
      transition: .3s;
    }

    .contact-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .contact-item i {
      font-size: 1.5rem;
      color: #000;
      margin-right: 15px;
    }

    .contact-item p {
      margin: 0;
      color: #333;
    }

    /* ====== FOOTER ====== */
    footer {
      background: #000;
      color: #fff;
      text-align: center;
      padding: 25px 0;
      margin-top: 80px;
    }

    footer p {
      margin: 0;
      font-size: 0.95rem;
      opacity: 0.8;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <h1>Tentang Bookstore</h1>
    <p>Tempat terbaik untuk menemukan buku yang menginspirasi, memperluas wawasan, dan menumbuhkan semangat membaca.</p>
  </div>
</section>

<!-- ABOUT SECTION -->
<section class="section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-6">
        <div class="about-image">
          <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&q=80&w=1200" alt="Tentang Kami">
        </div>
      </div>
      <div class="col-md-6">
        <h2 class="mb-3">Mengenal Kami Lebih Dekat</h2>
        <p class="about-text">
          <strong>Bookstore</strong> hadir sebagai toko buku online yang mengutamakan kenyamanan dan kemudahan pembaca dalam menemukan bacaan terbaik.
          Kami percaya bahwa setiap buku memiliki kekuatan untuk mengubah cara pandang dan memperkaya kehidupan.
        </p>
        <p class="about-text">
          Dengan koleksi ribuan judul dari berbagai penerbit, kami berkomitmen untuk menyediakan pilihan buku yang relevan, autentik, dan inspiratif — dikirim langsung ke tangan Anda dengan cepat dan aman.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- MISSION & VISION SECTION -->
<section class="section bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2>Filosofi Kami</h2>
      <p class="text-muted">Kami percaya bahwa membaca adalah investasi terbaik untuk masa depan.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="info-card">
          <i class="bi bi-bullseye"></i>
          <h4>Misi Kami</h4>
          <p>Membuka akses ke berbagai literatur berkualitas dan mendorong budaya membaca di tengah masyarakat digital.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="info-card">
          <i class="bi bi-eye"></i>
          <h4>Visi Kami</h4>
          <p>Menjadi toko buku digital terpercaya yang menginspirasi generasi pembaca di seluruh Indonesia.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="info-card">
          <i class="bi bi-heart"></i>
          <h4>Nilai Utama</h4>
          <p>Integritas, inovasi, dan kecintaan terhadap ilmu pengetahuan menjadi fondasi setiap langkah kami.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT SECTION -->
<section class="section">
  <div class="container">
    <div class="text-center mb-5">
      <h2>Hubungi Kami</h2>
      <p class="text-muted">Kami siap membantu kebutuhan dan pertanyaan Anda kapan pun.</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="contact-card">
          <div class="contact-item"><i class="bi bi-geo-alt"></i><p>Jl. Merdeka No. 12, Jawa Tengah</p></div>
          <div class="contact-item"><i class="bi bi-telephone"></i><p>(021) 123-4567</p></div>
          <div class="contact-item"><i class="bi bi-envelope"></i><p>info@bookstore.com</p></div>
          <div class="contact-item"><i class="bi bi-clock"></i><p>Senin - Jumat, 09:00 - 18:00 WIB</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <p>&copy; 2025 Bookstore — Semua hak cipta dilindungi. Dibuat dengan ❤️ untuk para pembaca.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
