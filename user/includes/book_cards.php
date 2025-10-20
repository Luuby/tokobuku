<?php
// /user/includes/book_cards.php
if (empty($result)) {
    echo '<div class="empty-notice">Tidak ada buku yang ditemukan.</div>';
    return;
}
?>
<div class="books-grid" role="list">
  <?php foreach ($result as $book):
    $id = htmlspecialchars($book['id']);
    $title = htmlspecialchars($book['title']);
    $author = !empty($book['author']) ? htmlspecialchars($book['author']) : 'Unknown';
    $price = isset($book['price']) ? number_format($book['price'], 0, ',', '.') : '-';
    $price_text = 'Rp ' . $price;
    // cover path fallback
    $cover = !empty($book['cover_url']) ? $book['cover_url'] : '/assets/images/naruto.jpg';
    // safe description limited for data attribute (no quotes)
    $desc = isset($book['description']) ? strip_tags($book['description']) : '';
    $desc_sanit = htmlspecialchars(mb_substr($desc,0,800));
  ?>
    <a href="/user/book_detail.php?id=<?php echo $id; ?>" class="book-card" role="listitem"
       data-id="<?php echo $id; ?>"
       data-title="<?php echo $title; ?>"
       data-author="<?php echo $author; ?>"
       data-description="<?php echo $desc_sanit; ?>"
       data-cover="<?php echo htmlspecialchars($cover); ?>"
       data-price_text="<?php echo htmlspecialchars($price_text); ?>">
      <div class="cover-wrap">
        <img class="book-cover" src="<?php echo htmlspecialchars($cover); ?>" loading="lazy" alt="<?php echo $title; ?> - cover">
      </div>
      <div class="book-meta">
        <div class="book-title"><?php echo $title; ?></div>
        <div class="book-author"><?php echo $author; ?></div>
        <div class="book-bottom">
          <div class="book-price"><?php echo $price_text; ?></div>
          <div class="book-btn">Lihat</div>
        </div>
      </div>
    </a>
  <?php endforeach; ?>
</div>
