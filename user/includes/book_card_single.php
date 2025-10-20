<?php
// /user/includes/book_card_single.php
// expects $book array supplied by the including file
if (!isset($book) || !is_array($book)) return;

// determine image path using your provided logic
$imageFile = !empty($book['image']) ? $book['image'] : '';
$imagePath = ($imageFile && file_exists("../assets/images/" . $imageFile)) ? "../assets/images/" . $imageFile : "../assets/images/no-image.png";

// safe fields
$id = htmlspecialchars($book['id'] ?? '');
$title = htmlspecialchars($book['title'] ?? 'Untitled');
$author = htmlspecialchars($book['author'] ?? 'Umum');
$category = htmlspecialchars($book['category_name'] ?? ($book['category'] ?? 'Umum'));
$stock = intval($book['stock'] ?? 0);
$price = isset($book['price']) ? number_format($book['price'], 0, ',', '.') : '0';
?>
<div>
    <div class="card card-book h-100 bw-card">
        <div class="card-thumb">
            <img src="<?php echo $imagePath; ?>" 
                 class="card-img-top" 
                 alt="<?php echo $title . ' oleh ' . $author; ?>" 
                 onerror="this.src='../assets/images/no-image.png';" />
        </div>

        <div class="card-body d-flex flex-column p-3">
            <div class="mb-2 d-flex justify-content-between align-items-start">
                <span class="category-badge"><?php echo $category; ?></span>
                <?php if ($stock > 0 && $stock < 5): ?>
                    <span class="low-stock-badge">Stok <?php echo $stock; ?></span>
                <?php endif; ?>
            </div>

            <h5 class="card-title"><?php echo $title; ?></h5>
            <p class="card-text author small"><?php echo $author; ?></p>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="price small">Rp <?php echo $price; ?></div>
                <div class="btn-row">
                    <a href="book_detail.php?id=<?php echo $id; ?>" class="btn btn-sm btn-ghost-outline me-2" title="Lihat Detail">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>
                    <a href="add_to_cart.php?id=<?php echo $id; ?>" class="btn btn-sm btn-ghost" title="Tambah ke Keranjang">
                        <i class="bi bi-cart me-1"></i>Add
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
