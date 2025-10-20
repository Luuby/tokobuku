<?php
session_start();
require_once '../config.php';

// Cek admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua user (kecuali admin)
$users = $conn->query("SELECT id, username, email, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar User - Admin Bookstore</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
.card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; margin-bottom: 25px; }
.card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.table-hover tbody tr:hover { background-color: #f1f3f5; transition: background-color 0.2s; cursor: pointer; }
.status-badge { padding: 6px 14px; border-radius: 50px; font-weight: 600; text-transform: uppercase; font-size: 0.85em; color: #fff; display: inline-block; transition: transform 0.2s; }
.status-badge:hover { transform: scale(1.05); }
.status-active { background-color: #198754; }
.status-inactive { background-color: #dc3545; }
h2 { font-weight: 700; color: #333; margin-bottom: 1.5rem; }
.btn-primary { transition: all 0.2s; }
.btn-primary:hover { background-color: #0b5ed7; transform: translateY(-1px); }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2>Daftar User Terdaftar</h2>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th style="width: 180px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users->num_rows === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada user yang terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['id']; ?></td>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                            <td><?= htmlspecialchars($user['email']); ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-center">
                                <a href="user_orders.php?user_id=<?= $user['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Lihat Pesanan
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
