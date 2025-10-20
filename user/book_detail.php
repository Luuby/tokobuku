<?php
session_start();
include '../config.php';

$book_id = intval($_GET['id'] ?? 0);
if ($book_id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT b.*, c.name as category_name FROM books b 
    LEFT JOIN categories c ON b.category_id = c.id 
    WHERE b.id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$book) {
    echo "<div class='text-center mt-5'>Buku tidak ditemukan.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Detail Buku - <?= htmlspecialchars($book['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background: #f8f9fa; }
        .book-card { border: none; border-radius: 12px; }
        .book-img { border-radius: 12px; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card book-card shadow-sm p-3">
                <?php if ($book['image']): ?>
                    <img src="../assets/images/<?= htmlspecialchars($book['image']); ?>" 
                         class="img-fluid book-img" 
                         alt="<?= htmlspecialchars($book['title']); ?>">
                <?php else: ?>
                    <img src="../assets/images/no-image.png" class="img-fluid book-img" alt="No Image">
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4 shadow-sm book-card">
                <h2 class="fw-bold"><?= htmlspecialchars($book['title']); ?></h2>
                <p class="text-muted"><?= htmlspecialchars($book['author']); ?></p>
                
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Kategori:</strong> <?= htmlspecialchars($book['category_name']); ?></li>
                    <li class="list-group-item"><strong>Harga:</strong> Rp <?= number_format($book['price'], 0, ',', '.'); ?></li>
                    <li class="list-group-item"><strong>Stok:</strong> <?= $book['stock']; ?></li>
                </ul>

                <p><?= nl2br(htmlspecialchars($book['description'])); ?></p>

                <div class="mt-3 d-flex gap-2">
                    <?php if ($book['stock'] > 0): ?>
                        <a href="add_to_cart.php?id=<?= $book['id']; ?>" class="btn btn-success px-4">+ Add to Cart</a>
                    <?php else: ?>
                        <button class="btn btn-secondary px-4" disabled>Stok Habis</button>
                    <?php endif; ?>
                    
                    <a href="index.php" class="btn btn-outline-dark px-4">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
