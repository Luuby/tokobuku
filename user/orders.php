<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil pesanan milik user (dengan JOIN untuk total verifikasi jika perlu)
$stmt = $conn->prepare("
    SELECT o.*, 
           (SELECT SUM(oi.quantity * oi.price) FROM order_items oi WHERE oi.order_id = o.id) as calculated_total
    FROM orders o 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Riwayat Pesanan - Bookstore</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"> <!-- Tambahan untuk icon -->
    <style>
        /* Status badges dengan background warna (sudah ada) */
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-paid { background-color: #d1ecf1; color: #0c5460; }
        .status-shipped { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-badge { padding: 0.5em 1em; border-radius: 20px; font-size: 0.875em; font-weight: 600; }

        /* Bukti Payment badges dengan background warna (sudah ada) */
        .cod-badge { background-color: #e2e3e5; color: #495057; padding: 0.25em 0.75em; border-radius: 15px; font-size: 0.875em; font-weight: 500; }
        .proof-uploaded-badge { background-color: #d4edda; color: #155724; padding: 0.25em 0.75em; border-radius: 15px; font-size: 0.875em; font-weight: 500; }
        .proof-pending-badge { background-color: #fff3cd; color: #856404; padding: 0.25em 0.75em; border-radius: 15px; font-size: 0.875em; font-weight: 500; }
        .proof-missing-badge { background-color: #f8d7da; color: #721c24; padding: 0.25em 0.75em; border-radius: 15px; font-size: 0.875em; font-weight: 500; }
        .proof-unavailable { background-color: #6c757d; color: #fff; padding: 0.25em 0.75em; border-radius: 15px; font-size: 0.875em; font-weight: 500; }

        /* Style tambahan untuk link Lihat Bukti (perubahan baru) */
        .proof-link { 
            color: #0d6efd; 
            text-decoration: none; 
            display: inline-block; 
            margin-top: 0.25em; 
            padding: 0.25em 0.5em; 
            border-radius: 4px; 
            font-weight: 500; 
            font-size: 0.875em;
            transition: all 0.2s ease-in-out;
        }
        .proof-link:hover { 
            background-color: #e7f3ff; 
            color: #0a58ca; 
            text-decoration: none; 
        }
        .no-proof { color: #6c757d; font-style: italic; font-size: 0.875em; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2>Riwayat Pesanan</h2>

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

    <?php if ($orders->num_rows === 0): ?>
        <div class="alert alert-info">Belum ada pesanan. <a href="../user/index.php">Lihat buku dan belanja sekarang!</a></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Metode Pembayaran</th>
                        <th>Bukti Payment</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <?php 
                        $current_status = $order['status'] ?: 'pending';  // Fix jika kosong
                        $status_class = 'status-' . $current_status;
                        $display_status = ucfirst($current_status);
                        $calculated_total = $order['calculated_total'] ?: $order['total_price'];  // Verifikasi total
                        $payment_method = $order['payment_method'] ?: 'payment_at_delivery';
                        $payment_display = str_replace('_', ' ', ucfirst($payment_method));
                        ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>Rp <?php echo number_format($calculated_total, 0, ',', '.'); ?></td>
                            <td>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $display_status; ?>
                                </span>
                            </td>
                            <td><?php echo $payment_display; ?></td>
                            <td>
                                <!-- Bukti Payment dengan badge warna background -->
                                <?php if ($payment_method === 'payment_at_delivery'): ?>
                                    <span class="cod-badge">COD (Bayar di Tempat)</span>
                                <?php elseif ($payment_method === 'transfer_bank'): ?>
                                    <?php if (!empty($order['payment_proof'])): ?>
                                        <?php $proof_path = __DIR__ . '/../assets/uploads/payment_proofs/' . $order['payment_proof']; ?>
                                        <?php if (file_exists($proof_path)): ?>
                                            <span class="proof-uploaded-badge">Uploaded</span>
                                            <a href="../assets/uploads/payment_proofs/<?php echo htmlspecialchars($order['payment_proof']); ?>" 
                                               target="_blank" class="proof-link">
                                                <i class="bi bi-eye"></i> Lihat Bukti
                                            </a>
                                        <?php else: ?>
                                            <span class="proof-missing-badge">File Hilang</span>
                                            <span class="no-proof">File <?php echo htmlspecialchars($order['payment_proof']); ?> tidak ditemukan</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="proof-pending-badge">Pending Upload</span>
                                        <span class="no-proof">Belum ada bukti</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="proof-unavailable">Tidak Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">Detail</a>
                                
                                <?php if ($current_status === 'pending'): ?>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                            data-bs-target="#confirmModal<?php echo $order['id']; ?>">Konfirmasi</button>
                                <?php endif; ?>
                                
                                <?php if (in_array($current_status, ['pending', 'paid'])): ?>
                                    <form method="POST" action="cancel_order.php" style="display: inline;" 
                                          onsubmit="return confirm('Yakin membatalkan pesanan #<?php echo $order['id']; ?>? Stok akan dikembalikan.');">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Batal</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Modal Konfirmasi Pembayaran (Hanya untuk pending) -->
                        <?php if ($current_status === 'pending'): ?>
                            <div class="modal fade" id="confirmModal<?php echo $order['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Pembayaran - Order #<?php echo $order['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="confirm_payment.php" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="payment_method_<?php echo $order['id']; ?>" class="form-label">Metode Pembayaran</label>
                                                    <select name="payment_method" id="payment_method_<?php echo $order['id']; ?>" class="form-select" required onchange="toggleProof(<?php echo $order['id']; ?>)">
                                                        <option value="">-- Pilih Metode --</option>
                                                        <option value="transfer_bank">Transfer Bank</option>
                                                        <option value="payment_at_delivery">Bayar di Tempat (COD)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3" id="proof_container_<?php echo $order['id']; ?>" style="display: none;">
                                                    <label for="payment_proof_<?php echo $order['id']; ?>" class="form-label">Upload Bukti (JPG/PNG, max 2MB)</label>
                                                    <input type="file" name="payment_proof" id="payment_proof_<?php echo $order['id']; ?>" class="form-control" accept=".jpg,.jpeg,.png">
                                                    <div class="form-text">Pastikan bukti jelas (nama, nominal, tanggal).</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="shipping_address_<?php echo $order['id']; ?>" class="form-label">Alamat Pengiriman</label>
                                                    <textarea name="shipping_address" id="shipping_address_<?php echo $order['id']; ?>" class="form-control" rows="3" placeholder="Isi alamat lengkap Anda..." required></textarea>
                                                    <div class="form-text">Wajib diisi untuk pengiriman (untuk semua metode pembayaran).</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Konfirmasi & Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleProof(orderId) {
    const method = document.getElementById('payment_method_' + orderId).value;
    const proofContainer = document.getElementById('proof_container_' + orderId);
    const proofInput = document.getElementById('payment_proof_' + orderId);
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