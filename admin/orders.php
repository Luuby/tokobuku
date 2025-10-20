<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT o.*, u.username, u.email 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC
");
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manajemen Pesanan - Admin Bookstore</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
.card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; margin-bottom: 25px; }
.card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.table-hover tbody tr:hover { background-color: #f1f3f5; transition: background-color 0.2s; cursor: pointer; }
.status-badge { padding: 6px 14px; border-radius: 50px; font-weight: 600; text-transform: uppercase; font-size: 0.85em; color: #fff; display: inline-block; transition: transform 0.2s; }
.status-badge:hover { transform: scale(1.05); }
.status-pending { background-color: #ffca2c; color: #333; }       
.status-paid, .status-confirmed { background-color: #0d6efd; }   
.status-shipped { background-color: #0dcaf0; color: #fff; }      
.status-delivered { background-color: #198754; }                 
.status-cancelled { background-color: #dc3545; }                 
.proof-badge { padding: 0.3em 0.75em; border-radius: 12px; font-size: 0.8em; font-weight: 500; display: inline-block; margin-top: 0.2em; transition: all 0.2s; }
.proof-uploaded { background-color: #d4edda; color: #155724; }
.proof-pending { background-color: #fff3cd; color: #856404; }
.proof-missing { background-color: #f8d7da; color: #721c24; }
.proof-cod { background-color: #e2e3e5; color: #495057; }
.proof-link { color: #0d6efd; text-decoration: none; font-weight: 500; transition: all 0.2s; }
.proof-link:hover { background-color: #e7f3ff; color: #0a58ca; text-decoration: none; border-radius: 4px; padding: 0.15em 0.5em; }
h2 { font-weight: 700; color: #333; }
.btn-primary { transition: all 0.2s; }
.btn-primary:hover { background-color: #0b5ed7; transform: translateY(-1px); }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Pesanan</h2>
    </div>

    <?php if ($orders->num_rows === 0): ?>
        <div class="alert alert-info">Belum ada pesanan.</div>
    <?php else: ?>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Metode</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders->fetch_assoc()):
                            $status = strtolower($order['status'] ?: 'pending');
                            $status_class = 'status-' . $status;
                            $payment_method = $order['payment_method'] ?: 'payment_at_delivery';
                            $proof_file = $order['payment_proof'] ?? '';
                            $proof_path = __DIR__.'/../assets/uploads/payment_proofs/'.$proof_file;
                            $proof_exists = $proof_file && file_exists($proof_path);
                        ?>
                        <tr>
                            <td>#<?= $order['id']; ?></td>
                            <td><?= htmlspecialchars($order['username'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($order['email'] ?? 'N/A'); ?></td>
                            <td><?= date('d M Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>Rp <?= number_format($order['total_price'],0,',','.'); ?></td>
                            <td><span class="status-badge <?= $status_class; ?>"><?= ucfirst($status); ?></span></td>
                            <td><?= str_replace('_',' ', ucfirst($payment_method)); ?></td>
                            <td>
                                <?php if ($payment_method==='payment_at_delivery'): ?>
                                    <span class="proof-badge proof-cod">COD</span>
                                <?php elseif ($payment_method==='transfer_bank'): ?>
                                    <?php if($proof_exists): ?>
                                        <span class="proof-badge proof-uploaded">Uploaded</span>
                                        <a href="../assets/uploads/payment_proofs/<?= htmlspecialchars($proof_file); ?>" target="_blank" class="proof-link"><i class="bi bi-eye"></i> Lihat</a>
                                    <?php elseif (!empty($proof_file)): ?>
                                        <span class="proof-badge proof-missing">File Hilang</span>
                                    <?php else: ?>
                                        <span class="proof-badge proof-pending">Pending</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="proof-badge proof-cod">Tidak Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="order_detail.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-info-circle"></i> Detail</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
