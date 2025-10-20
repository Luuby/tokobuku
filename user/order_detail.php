<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Validasi parameter id order
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID pesanan tidak valid.";
    exit;
}

$order_id = intval($_GET['id']);

// Query: Hanya order milik user
$stmt = $conn->prepare("SELECT o.* FROM orders o WHERE o.id = ? AND o.user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "Pesanan tidak ditemukan atau Anda tidak berhak mengaksesnya.";
    exit;
}

// Ambil detail item pesanan
$stmt = $conn->prepare("SELECT oi.quantity, b.title, b.price, b.image FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Detail Pesanan #<?= htmlspecialchars($order['id']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
.card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 25px; transition: transform 0.2s, box-shadow 0.2s; }
.card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.table-hover tbody tr:hover { background-color: #f1f3f5; cursor: pointer; transition: background-color 0.2s; }
.status-badge { padding: 6px 14px; border-radius: 50px; font-weight: 600; text-transform: uppercase; font-size: 0.85em; color: #fff; display: inline-block; }
.status-pending { background-color: #ffca2c; color: #333; }
.status-paid { background-color: #0d6efd; }
.status-shipped { background-color: #0dcaf0; color: #fff; }
.status-delivered { background-color: #198754; }
.status-cancelled { background-color: #dc3545; }
.img-thumb { width: 60px; height: 80px; object-fit: cover; border-radius: 6px; cursor: pointer; transition: 0.2s; }
.img-thumb:hover { transform: scale(1.05); }
.payment-proof-img { max-width: 100%; max-height: 250px; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
h2 { font-weight: 700; color: #333; }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4"><i class="bi bi-receipt"></i> Detail Pesanan #<?= htmlspecialchars($order['id']); ?></h2>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Info -->
            <div class="card p-4">
                <h5 class="mb-3"><i class="bi bi-info-circle"></i> Informasi Pesanan</h5>
                <p><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-<?= htmlspecialchars($order['status'] ?: 'pending'); ?>">
                        <?= htmlspecialchars(ucfirst($order['status'] ?: 'pending')); ?>
                    </span>
                </p>
                <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars(str_replace('_',' ', ucfirst($order['payment_method'] ?: 'COD'))); ?></p>
                <p><strong>Total Harga:</strong> Rp <?= number_format($order['total_price'],0,',','.'); ?></p>
                <p><strong>Alamat:</strong><br><?= nl2br(htmlspecialchars($order['shipping_address'] ?: '-')); ?></p>
            </div>

            <!-- Item Pesanan -->
            <div class="card p-4">
                <h5 class="mb-3"><i class="bi bi-card-list"></i> Item Pesanan</h5>
                <?php if ($order_items->num_rows === 0): ?>
                    <div class="alert alert-info">Tidak ada item dalam pesanan.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Judul Buku</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total=0; while($item=$order_items->fetch_assoc()):
                                    $subtotal = $item['price']*$item['quantity']; $total+=$subtotal; ?>
                                <tr>
                                    <td>
                                        <?php if ($item['image'] && file_exists(__DIR__.'/../assets/images/'.$item['image'])): ?>
                                            <img src="../assets/images/<?= htmlspecialchars($item['image']); ?>" class="img-thumb" data-bs-toggle="modal" data-bs-target="#imageModal" data-img="../assets/images/<?= htmlspecialchars($item['image']); ?>">
                                        <?php else: ?>
                                            <img src="../assets/no-image.png" class="img-thumb">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($item['title']); ?></td>
                                    <td>Rp <?= number_format($item['price'],0,',','.'); ?></td>
                                    <td><?= $item['quantity']; ?></td>
                                    <td>Rp <?= number_format($subtotal,0,',','.'); ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <tr class="table-info fw-bold">
                                    <td colspan="4" class="text-end">Total</td>
                                    <td>Rp <?= number_format($total,0,',','.'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-status">
                <!-- Payment Proof -->
                <div class="card p-3 mb-4">
                    <h5 class="mb-2"><i class="bi bi-wallet2"></i> Bukti Pembayaran</h5>
                    <?php 
                    $payment_method = $order['payment_method'] ?: 'payment_at_delivery';
                    $payment_proof_path = __DIR__ . '/../assets/uploads/payment_proofs/' . $order['payment_proof'];

                    if ($payment_method === 'payment_at_delivery'): ?>
                        <span class="status-badge status-pending">COD</span>
                    <?php elseif (!empty($order['payment_proof']) && file_exists($payment_proof_path)): ?>
                        <div class="d-flex flex-column align-items-center">
                            <img src="../assets/uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']); ?>" 
                                 class="payment-proof-img mb-2" 
                                 data-bs-toggle="modal" data-bs-target="#imageModal" 
                                 data-img="../assets/uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']); ?>" 
                                 alt="Bukti Pembayaran">
                            <a href="../assets/uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']); ?>" target="_blank" class="proof-link"><i class="bi bi-eye"></i> Lihat</a>
                        </div>
                    <?php elseif (!empty($order['payment_proof'])): ?>
                        <span class="status-badge status-cancelled">File Hilang</span>
                    <?php else: ?>
                        <span class="status-badge status-pending">Pending</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-5">
        <a href="orders.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Riwayat Pesanan</a>
    </div>
</div>

<!-- Modal Preview Image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3">
      <img src="" id="modalImg" class="img-fluid rounded">
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Modal preview
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImg');
    modal.addEventListener('show.bs.modal', function (event) {
        const img = event.relatedTarget.getAttribute('data-img');
        modalImg.src = img;
    });
});
</script>
</body>
</html>
