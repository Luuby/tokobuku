<?php
session_start();
require_once '../config.php';

// Cek admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';

// Pagination
$items_per_page = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $items_per_page;

// Total buku & total page
$total_books = $conn->query("SELECT COUNT(*) FROM books")->fetch_row()[0];
$total_pages = max(1, ceil($total_books / $items_per_page));

// Ambil kategori
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

// Validasi upload
function validate_upload_image($file) {
    $allowed = ['image/jpeg','image/png','image/gif'];
    $max = 2*1024*1024;
    if($file['error']!==UPLOAD_ERR_OK) return "Error saat upload gambar.";
    if(!in_array($file['type'],$allowed)) return "Hanya JPG, PNG, GIF.";
    if($file['size']>$max) return "Maks 2MB.";
    return '';
}

// -----------------
// CRUD
// -----------------
if(isset($_POST['add_book'])){
    $category_id = intval($_POST['category_id']);
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = '';

    if(isset($_FILES['image']) && $_FILES['image']['name']!=''){
        $err = validate_upload_image($_FILES['image']);
        if($err) $message=$err;
        else{
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = time().'_'.uniqid().'.'.$ext;
            if(!move_uploaded_file($_FILES['image']['tmp_name'],'../assets/images/'.$image)){
                $message="Gagal upload gambar.";
            }
        }
    }

    if(!$message){
        $stmt = $conn->prepare("INSERT INTO books (category_id,title,author,description,price,stock,image) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("isssdis",$category_id,$title,$author,$desc,$price,$stock,$image);
        if($stmt->execute()) header("Location: books.php");
        else $message="Gagal menambah buku.";
        $stmt->close();
    }
}

if(isset($_POST['update_book'])){
    $id=intval($_POST['id']);
    $category_id=intval($_POST['category_id']);
    $title=trim($_POST['title']);
    $author=trim($_POST['author']);
    $desc=trim($_POST['description']);
    $price=floatval($_POST['price']);
    $stock=intval($_POST['stock']);
    $image_update='';

    if(isset($_FILES['image']) && $_FILES['image']['name']!=''){
        $err=validate_upload_image($_FILES['image']);
        if($err) $message=$err;
        else{
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_update=time().'_'.uniqid().'.'.$ext;
            if(!move_uploaded_file($_FILES['image']['tmp_name'],'../assets/images/'.$image_update)){
                $message="Gagal upload gambar.";
            } else {
                $stmt_old=$conn->prepare("SELECT image FROM books WHERE id=?");
                $stmt_old->bind_param("i",$id);
                $stmt_old->execute();
                $stmt_old->bind_result($old_image);
                $stmt_old->fetch();
                $stmt_old->close();
                if($old_image && file_exists('../assets/images/'.$old_image)) unlink('../assets/images/'.$old_image);
            }
        }
    }

    if(!$message){
        if($image_update){
            $stmt=$conn->prepare("UPDATE books SET category_id=?, title=?, author=?, description=?, price=?, stock=?, image=? WHERE id=?");
            $stmt->bind_param("isssdisi",$category_id,$title,$author,$desc,$price,$stock,$image_update,$id);
        } else {
            $stmt=$conn->prepare("UPDATE books SET category_id=?, title=?, author=?, description=?, price=?, stock=? WHERE id=?");
            $stmt->bind_param("isssdii",$category_id,$title,$author,$desc,$price,$stock,$id);
        }
        if($stmt->execute()) header("Location: books.php");
        else $message="Gagal update buku.";
        $stmt->close();
    }
}

if(isset($_GET['delete'])){
    $id=intval($_GET['delete']);
    if($id>0){
        $stmt=$conn->prepare("SELECT image FROM books WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $stmt->bind_result($old_image);
        $stmt->fetch();
        $stmt->close();
        if($old_image && file_exists('../assets/images/'.$old_image)) unlink('../assets/images/'.$old_image);
        $stmt_del=$conn->prepare("DELETE FROM books WHERE id=?");
        $stmt_del->bind_param("i",$id);
        $stmt_del->execute();
        $stmt_del->close();
        header("Location: books.php");
        exit;
    }
}

// -----------------
// AJAX Search
// -----------------
if(isset($_GET['ajax']) && $_GET['ajax']==='search'){
    $q=trim($_GET['q']??'');
    $limit=100;
    if($q===''){
        $stmt=$conn->prepare("SELECT b.*,c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id=c.id ORDER BY b.title LIMIT ?");
        $stmt->bind_param("i",$items_per_page);
    } else {
        $like='%'.$q.'%';
        $stmt=$conn->prepare("SELECT b.*,c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id=c.id WHERE b.title LIKE ? OR b.author LIKE ? OR c.name LIKE ? ORDER BY b.title LIMIT ?");
        $stmt->bind_param("sssi",$like,$like,$like,$limit);
    }
    $stmt->execute();
    $res=$stmt->get_result();
    if($res->num_rows===0){
        echo '<tr><td colspan="8" class="text-center text-muted">Tidak ada hasil ditemukan.</td></tr>';
    } else {
        while($book=$res->fetch_assoc()){
            $id=htmlspecialchars($book['id']);
            $title=htmlspecialchars($book['title']);
            $author=htmlspecialchars($book['author']);
            $cat=htmlspecialchars($book['category_name']);
            $price='Rp '.number_format($book['price'],0,',','.');
            $stock=intval($book['stock']);
            $img='../assets/images/no-image.png';
            if($book['image'] && file_exists('../assets/images/'.$book['image'])) $img='../assets/images/'.htmlspecialchars($book['image']);
            echo "<tr>
            <td>{$id}</td>
            <td><img src=\"{$img}\" class=\"img-thumb\" alt=\"Gambar\"></td>
            <td>{$title}</td>
            <td>{$author}</td>
            <td>{$cat}</td>
            <td>{$price}</td>
            <td>{$stock}</td>
            <td class=\"text-center\">
            <button class=\"btn btn-sm btn-warning btn-icon me-1\" data-bs-toggle=\"modal\" data-bs-target=\"#editBookModal{$id}\">
            <i class=\"bi bi-pencil\"></i></button>
            <a href=\"books.php?delete={$id}\" class=\"btn btn-sm btn-danger btn-icon\" onclick=\"return confirm('Yakin ingin menghapus?')\"><i class=\"bi bi-trash\"></i></a>
            </td></tr>";
        }
    }
    $stmt->close();
    exit;
}

// Buku untuk tampilan awal
$books=$conn->query("SELECT b.*,c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id=c.id ORDER BY b.title LIMIT $items_per_page OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Buku - Admin Bookstore</title>
    <!-- Gunakan CDN Bootstrap agar tidak bermasalah dengan path -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        .table thead th {
            vertical-align: middle;
        }
        .img-thumb {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            text-align: center;
            line-height: 36px;
            border-radius: 50%;
        }
        .modal-img-preview {
            max-width: 100%;
            max-height: 200px;
            margin-bottom: 10px;
            border-radius: 6px;
            object-fit: contain;
        }
        /* sedikit spacing untuk search area */
        .search-wrap { margin-bottom: 1rem; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Kelola Buku</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
            <i class="bi bi-plus-lg"></i> Tambah Buku
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Search bar (live) -->
    <div class="search-wrap">
        <form id="searchForm" onsubmit="return false;">
            <div class="input-group">
                <input type="search" id="liveSearch" class="form-control" placeholder="Cari judul, penulis, atau kategori..." aria-label="Pencarian buku" autocomplete="off">
                <button class="btn btn-outline-secondary" id="clearSearch" type="button" title="Clear" style="display:none;"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="searchHelp" class="form-text">Hasil akan muncul saat mengetik (live search).</div>
        </form>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0 bg-white" id="booksTable">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="booksTbody">
                <?php if ($books->num_rows == 0): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada buku.</td>
                    </tr>
                <?php else: ?>
                    <?php
                    // reset categories result pointer so modals can reuse it
                    while ($book = $books->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $book['id']; ?></td>
                        <td>
                            <?php if ($book['image'] && file_exists("../assets/images/" . $book['image'])): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="Gambar Buku" class="img-thumb" />
                            <?php else: ?>
                                <img src="../assets/images/no-image.png" alt="No Image" class="img-thumb" />
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['category_name']); ?></td>
                        <td>Rp <?php echo number_format($book['price'], 0, ',', '.'); ?></td>
                        <td><?php echo $book['stock']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning btn-icon me-1" data-bs-toggle="modal" data-bs-target="#editBookModal<?php echo $book['id']; ?>" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="books.php?delete=<?php echo $book['id']; ?>" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit Buku -->
                    <div class="modal fade" id="editBookModal<?php echo $book['id']; ?>" tabindex="-1" aria-labelledby="editBookModalLabel<?php echo $book['id']; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                          <form method="POST" action="" enctype="multipart/form-data">
                            <div class="modal-header">
                              <h5 class="modal-title" id="editBookModalLabel<?php echo $book['id']; ?>">Edit Buku - <?php echo htmlspecialchars($book['title']); ?></h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="id" value="<?php echo $book['id']; ?>" />
                              <div class="row g-3">
                                <div class="col-md-6">
                                  <label for="category_id_<?php echo $book['id']; ?>" class="form-label">Kategori</label>
                                  <select name="category_id" id="category_id_<?php echo $book['id']; ?>" class="form-select" required>
                                    <?php
                                    // rewind categories result set and show options
                                    $categories->data_seek(0);
                                    while ($cat = $categories->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $book['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                  </select>
                                </div>
                                <div class="col-md-6">
                                  <label for="title_<?php echo $book['id']; ?>" class="form-label">Judul Buku</label>
                                  <input type="text" name="title" id="title_<?php echo $book['id']; ?>" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required />
                                </div>
                                <div class="col-md-6">
                                  <label for="author_<?php echo $book['id']; ?>" class="form-label">Penulis</label>
                                  <input type="text" name="author" id="author_<?php echo $book['id']; ?>" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required />
                                </div>
                                <div class="col-md-3">
                                  <label for="price_<?php echo $book['id']; ?>" class="form-label">Harga</label>
                                  <input type="number" name="price" id="price_<?php echo $book['id']; ?>" class="form-control" step="0.01" value="<?php echo $book['price']; ?>" required />
                                </div>
                                <div class="col-md-3">
                                  <label for="stock_<?php echo $book['id']; ?>" class="form-label">Stok</label>
                                  <input type="number" name="stock" id="stock_<?php echo $book['id']; ?>" class="form-control" min="0" value="<?php echo $book['stock']; ?>" required />
                                </div>
                                <div class="col-12">
                                  <label for="description_<?php echo $book['id']; ?>" class="form-label">Deskripsi</label>
                                  <textarea name="description" id="description_<?php echo $book['id']; ?>" class="form-control" rows="3"><?php echo htmlspecialchars($book['description']); ?></textarea>
                                </div>
                                <div class="col-12">
                                  <label class="form-label">Gambar Buku</label>
                                  <?php if ($book['image'] && file_exists("../assets/images/" . $book['image'])): ?>
                                    <img src="../assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="Gambar Buku" class="modal-img-preview d-block mb-2" />
                                  <?php endif; ?>
                                  <input type="file" name="image" accept="image/*" class="form-control" />
                                  <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" name="update_book" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="paginationContainer">
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">&laquo;</a>
                </li>
            <?php endif; ?>

            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="" enctype="multipart/form-data" id="addBookForm">
        <div class="modal-header">
          <h5 class="modal-title" id="addBookModalLabel">Tambah Buku Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="add_category_id" class="form-label">Kategori</label>
              <select name="category_id" id="add_category_id" class="form-select" required>
                <option value="" disabled selected>Pilih Kategori</option>
                <?php
                $categories->data_seek(0);
                while ($cat = $categories->fetch_assoc()):
                ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="add_title" class="form-label">Judul Buku</label>
              <input type="text" name="title" id="add_title" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label for="add_author" class="form-label">Penulis</label>
              <input type="text" name="author" id="add_author" class="form-control" required />
            </div>
            <div class="col-md-3">
              <label for="add_price" class="form-label">Harga</label>
              <input type="number" name="price" id="add_price" class="form-control" step="0.01" min="0" required />
            </div>
            <div class="col-md-3">
              <label for="add_stock" class="form-label">Stok</label>
              <input type="number" name="stock" id="add_stock" class="form-control" min="0" required />
            </div>
            <div class="col-12">
              <label for="add_description" class="form-label">Deskripsi</label>
              <textarea name="description" id="add_description" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-12">
              <label for="add_image" class="form-label">Gambar Buku</label>
              <input type="file" name="image" id="add_image" class="form-control" accept="image/*" />
              <small class="text-muted">Format JPG, PNG, GIF. Maks 2MB.</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="add_book" class="btn btn-primary">Tambah Buku</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Live search script -->
<script>
(function(){
    const input = document.getElementById('liveSearch');
    const tbody = document.getElementById('booksTbody');
    const pagination = document.getElementById('paginationContainer');
    const clearBtn = document.getElementById('clearSearch');
    let timeout = null;

    function setLoading() {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Memuat...</td></tr>';
    }

    function fetchResults(q) {
        setLoading();
        // encode query
        const url = 'books.php?ajax=search&q=' + encodeURIComponent(q);
        fetch(url, { credentials: 'same-origin' })
            .then(res => res.text())
            .then(html => {
                tbody.innerHTML = html;
                // Sembunyikan pagination ketika ada query non-empty
                if (q.trim() !== '') {
                    pagination.style.display = 'none';
                    clearBtn.style.display = 'inline-block';
                } else {
                    pagination.style.display = '';
                    clearBtn.style.display = 'none';
                }
            })
            .catch(err => {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Terjadi kesalahan saat mencari.</td></tr>';
                console.error(err);
            });
    }

    // Debounce handler
    input.addEventListener('input', function(e){
        const q = e.target.value;
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(() => {
            fetchResults(q);
        }, 300); // 300ms debounce
    });

    // Clear button
    clearBtn.addEventListener('click', function(){
        input.value = '';
        fetchResults('');
    });

    // initial: no-op (page already has initial rows). But if you want to fetch initial via AJAX, uncomment:
    // fetchResults('');
})();
</script>
</body>
</html>
