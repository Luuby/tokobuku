<?php
session_start();
require_once '../config.php';

// Debug: Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../debug.log');  // Log ke debug.log di root

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Bookstore</title>

</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5" style="max-width:700px;">

</body>

</html>




<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Kode checkout seperti di konteks
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Checkout - Bookstore</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .img-thumb {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h2>Checkout</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <a href="cart.php" class="btn btn-secondary w-100 mb-3">Kembali ke Keranjang</a>
        <?php endif; ?>

        <?php if (!isset($_POST['step']) || $error): ?>
            <h5>Ringkasan Pesanan</h5>
            <ul class="list-group mb-3">
                <?php foreach ($cart_items as $book_id => $item):
                    $subtotal = $item['price'] * $item['qty'];
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <?php if (isset($item['image']) && file_exists(__DIR__ . '/../assets/images/' . $item['image'])): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="img-thumb me-2">
                            <?php endif; ?>
                            <?php echo htmlspecialchars($item['title']) . " (x{$item['qty']})"; ?>
                        </div>
                        <span class="fw-bold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light fw-bold">
                    Total <span>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                </li>
            </ul>

            <form method="POST" action="">
                <input type="hidden" name="step" value="payment">
                <button type="submit" class="btn btn-primary w-100">Lanjut ke Pembayaran</button>
            </form>

        <?php else: ?>
            <!-- Langkah 2: Form Metode Pembayaran -->
            <h5>Metode Pembayaran</h5>
            <form method="POST" action="" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="step" value="payment" />
                <div class="mb-3">
                    <label for="payment_method" class="form-label fw-semibold">Pilih Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="form-select" required onchange="toggleProofUpload()">
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="transfer_bank" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'transfer_bank') ? 'selected' : ''; ?>>Transfer Bank</option>
                        <option value="payment_at_delivery" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'payment_at_delivery') ? 'selected' : ''; ?>>Bayar di Tempat (COD)</option>
                    </select>
                    <?php if (isset($_POST['payment_method']) && empty($_POST['payment_method'])): ?>
                        <div class="invalid-feedback">Pilih metode pembayaran.</div>
                    <?php endif; ?>
                </div>

                <div class="mb-3" id="payment_proof_container" style="display: none;">
                    <label for="payment_proof" class="form-label fw-semibold">Upload Bukti Pembayaran (JPG/PNG, max 2MB)</label>
                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept=".jpg,.jpeg,.png"
                        <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'transfer_bank') ? 'required' : ''; ?> />
                    <div class="form-text">Pastikan bukti transfer jelas (nama pengirim, nominal, tanggal).</div>
                    <?php if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK): ?>
                        <div class="text-danger">Error upload: <?php echo $_FILES['payment_proof']['error']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">Selesaikan Pesanan</button>
                    <a href="cart.php" class="btn btn-outline-secondary">← Kembali ke Keranjang</a>
                </div>
            </form>
        <?php endif; ?>

        <!-- Debug Link (Hapus setelah sukses) -->
        <?php if ($error): ?>
            <div class="mt-3">
                <a href="../debug.log" target="_blank" class="btn btn-sm btn-warning">Download Debug Log</a>
                <p class="text-muted small">Cek log untuk detail error.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleProofUpload() {
            const method = document.getElementById('payment_method').value;
            const proofContainer = document.getElementById('payment_proof_container');
            const proofInput = document.getElementById('payment_proof');
            if (method === 'transfer_bank') {
                proofContainer.style.display = 'block';
                proofInput.setAttribute('required', 'required');
            } else {
                proofContainer.style.display = 'none';
                proofInput.removeAttribute('required');
                proofInput.value = '';
            }
        }

        // Trigger onload untuk persist jika error di langkah 2
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', toggleProofUpload);
        } else {
            toggleProofUpload();
        }
    </script>
</body>

</html>