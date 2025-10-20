<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $payment_method = trim($_POST['payment_method'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');

    if (empty($payment_method) || empty($shipping_address)) {
        $_SESSION['error'] = 'Metode pembayaran dan alamat pengiriman wajib diisi.';
        header("Location: orders.php");
        exit;
    }

    if (!in_array($payment_method, ['transfer_bank', 'payment_at_delivery'])) {
        $_SESSION['error'] = 'Metode pembayaran tidak valid.';
        header("Location: orders.php");
        exit;
    }

    $payment_proof_filename = null;
    $upload_path = null;
    if ($payment_method === 'transfer_bank') {
        if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Bukti pembayaran harus diupload untuk transfer bank.';
            header("Location: orders.php");
            exit;
        }

        $file_ext = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_ext, $allowed_types) || $_FILES['payment_proof']['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = 'Format atau ukuran bukti pembayaran tidak valid (JPG/PNG, max 2MB).';
            header("Location: orders.php");
            exit;
        }

        $upload_dir = __DIR__ . '/../assets/uploads/payment_proofs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $payment_proof_filename = 'proof_' . time() . '_' . $user_id . '.' . $file_ext;
        $upload_path = $upload_dir . $payment_proof_filename;
        if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $upload_path)) {
            $_SESSION['error'] = 'Gagal menyimpan bukti pembayaran.';
            header("Location: orders.php");
            exit;
        }
    }

    $conn->begin_transaction();
    try {
        // Cek order milik user dan status pending
        $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$order) {
            throw new Exception("Order tidak ditemukan, bukan milik Anda, atau bukan status pending.");
        }

        // Update order
        if ($payment_proof_filename) {
            $stmt = $conn->prepare("UPDATE orders SET payment_method = ?, payment_proof = ?, shipping_address = ?, status = 'paid' WHERE id = ?");
            $stmt->bind_param("sssi", $payment_method, $payment_proof_filename, $shipping_address, $order_id);
        } else {
            $stmt = $conn->prepare("UPDATE orders SET payment_method = ?, shipping_address = ?, status = 'paid' WHERE id = ?");
            $stmt->bind_param("ssi", $payment_method, $shipping_address, $order_id);
        }
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        $_SESSION['success'] = "Konfirmasi pembayaran berhasil untuk order #{$order_id}. Status diubah ke 'Paid'.";

    } catch (Exception $e) {
        $conn->rollback();
        if ($payment_proof_filename && $upload_path && file_exists($upload_path)) {
            unlink($upload_path);
        }
        $_SESSION['error'] = 'Gagal konfirmasi pembayaran: ' . $e->getMessage();
    }

    header("Location: orders.php");
    exit;
} else {
    // Jika GET, tampilkan form konfirmasi (misalnya untuk halaman terpisah jika diperlukan)
    // Tapi berdasarkan konteks, konfirmasi dilakukan via modal di orders.php
    // Jika ingin halaman terpisah, tambahkan validasi order_id dari GET
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "ID pesanan tidak valid.";
        exit;
    }
    $order_id = intval($_GET['id']);
    // Cek order milik user dan status pending
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$order) {
        echo "Order tidak ditemukan, bukan milik Anda, atau bukan status pending.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Konfirmasi Pembayaran - Bookstore</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2>Konfirmasi Pembayaran untuk Order #<?php echo htmlspecialchars($order_id); ?></h2>
        
        <!-- Alert Sukses/Error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="confirm_payment.php" enctype="multipart/form-data">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="form-select" required onchange="toggleProof()">
                    <option value="">-- Pilih Metode --</option>
                    <option value="transfer_bank">Transfer Bank</option>
                    <option value="payment_at_delivery">Bayar di Tempat (COD)</option>
                </select>
            </div>
            <div class="mb-3" id="proof_container" style="display: none;">
                <label for="payment_proof" class="form-label">Upload Bukti (JPG/PNG, max 2MB)</label>
                <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept=".jpg,.jpeg,.png">
                <div class="form-text">Pastikan bukti jelas (nama, nominal, tanggal).</div>
            </div>
            <div class="mb-3">
                <label for="shipping_address" class="form-label">Alamat Pengiriman</label>
                <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" placeholder="Isi alamat lengkap Anda..." required></textarea>
                <div class="form-text">Wajib diisi untuk pengiriman (untuk semua metode pembayaran).</div>
            </div>
            <button type="submit" class="btn btn-success">Konfirmasi & Update</button>
            <a href="orders.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function toggleProof() {
        const method = document.getElementById('payment_method').value;
        const proofContainer = document.getElementById('proof_container');
        const proofInput = document.getElementById('payment_proof');
        if (method === 'transfer_bank') {
            proofContainer.style.display = 'block';
            proofInput.required = true;
        } else {
            proofContainer.style.display = 'none';
            proofInput.required = false;
            proofInput.value = '';
        }
    }
    </script>
</body>
</html>
