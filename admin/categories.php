<?php
session_start();
require_once '../config.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';

// Tambah kategori
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        header("Location: categories.php");
        exit;
    } else {
        $message = "Nama kategori tidak boleh kosong.";
    }
}

// Update kategori
if (isset($_POST['update_category'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    if ($name !== '' && $id > 0) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: categories.php");
        exit;
    } else {
        $message = "Nama kategori tidak boleh kosong dan ID harus valid.";
    }
}

// Delete kategori
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM books WHERE category_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $stmt_del = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt_del->bind_param("i", $id);
            $stmt_del->execute();
            $stmt_del->close();
        } else {
            $message = "Tidak bisa menghapus kategori karena masih dipakai oleh buku.";
        }
    }
    header("Location: categories.php");
    exit;
}

// Ambil semua kategori
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Kelola Kategori - Admin Bookstore</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
body { background-color: #f8f9fa; }
.table thead th { vertical-align: middle; }
.btn-icon { width: 36px; height: 36px; padding: 0; text-align: center; line-height: 36px; border-radius: 50%; }
.table-wrapper { background-color: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.modal-header { background-color: #f8f9fa; border-bottom: none; }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Kategori Buku</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-wrapper table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Nama Kategori</th>
                    <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories->num_rows == 0): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada kategori.</td>
                    </tr>
                <?php else: ?>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2 align-items-center" onsubmit="return confirm('Yakin ingin mengupdate kategori ini?');">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>" />
                                <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" class="form-control form-control-sm" required />
                                <button type="submit" name="update_category" class="btn btn-sm btn-success" title="Update">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <a href="categories.php?delete=<?php echo $category['id']; ?>" 
                               class="btn btn-sm btn-danger btn-icon" 
                               title="Hapus" 
                               onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" name="name" placeholder="Masukkan nama kategori" required />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="add_category" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
