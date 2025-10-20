<form method="GET" action="index.php" class="search-bar d-flex">
    <div class="position-relative flex-grow-1" style="min-width: 200px;">
        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        <input type="text" name="search" class="form-control ps-5" placeholder="Cari judul, penulis, atau deskripsi..."
            value="<?php echo htmlspecialchars($search ?? ''); ?>" />
    </div>
    <select name="category" class="form-select" style="max-width: 200px;">
        <option value="0">Semua Kategori</option>
        <?php 
        if (isset($categories)) {
            $categories->data_seek(0);
            while($cat = $categories->fetch_assoc()): 
        ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo ($category_filter ?? 0) == $cat['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php 
            endwhile; 
        }
        ?>
    </select>
    <select name="sort" class="form-select" style="max-width: 180px;">
        <option value="title_asc" <?php echo ($sort ?? 'title_asc') == 'title_asc' ? 'selected' : ''; ?>>Judul A-Z</option>
        <option value="title_desc" <?php echo ($sort ?? '') == 'title_desc' ? 'selected' : ''; ?>>Judul Z-A</option>
        <option value="price_asc" <?php echo ($sort ?? '') == 'price_asc' ? 'selected' : ''; ?>>Harga Rendah-Tinggi</option>
        <option value="price_desc" <?php echo ($sort ?? '') == 'price_desc' ? 'selected' : ''; ?>>Harga Tinggi-Rendah</option>
    </select>
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search me-1"></i>Cari</button>
    <a href="index.php" class="btn btn-outline-secondary px-3">Clear</a>
</form>