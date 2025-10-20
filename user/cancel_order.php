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

    $conn->begin_transaction();
    try {
        // Cek order milik user dan status bisa dibatalkan
        $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ? AND status IN ('pending', 'paid')");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) {
            throw new Exception("Order tidak bisa dibatalkan.");
        }
        $stmt->close();

        // Update status
        $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $stmt->close();

        // Restore stok
        $stmt = $conn->prepare("SELECT book_id, quantity FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items = $stmt->get_result();
        while ($item = $items->fetch_assoc()) {
            $stmt_restore = $conn->prepare("UPDATE books SET stock = stock + ? WHERE id = ?");
            $stmt_restore->bind_param("ii", $item['quantity'], $item['book_id']);
            $stmt_restore->execute();
            $stmt_restore->close();
        }
        $stmt->close();

        $conn->commit();
        $_SESSION['success'] = "Order dibatalkan. Stok dikembalikan.";

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: orders.php");
    exit;
} else {
    // Jika GET, tampilkan halaman konfirmasi pembatalan
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "ID pesanan tidak valid.";
        exit;
    }
    $order_id = intval($_GET['id']);
    // Cek order milik user dan status bisa dibatalkan
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ? AND status IN ('pending', 'paid')");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$order) {
        echo "Order tidak ditemukan, bukan milik Anda, atau tidak bisa dibatalkan.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Batalkan Pesanan - Bookstore</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2>Batalkan Pesanan #<?php echo htmlspecialchars($order_id); ?></h2>
        
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

        <div class="alert alert-warning">
            <strong>Konfirmasi Pembatalan:</strong> Apakah Anda yakin ingin membatalkan pesanan #<?php echo htmlspecialchars($order_id); ?>? 
            Status akan diubah ke 'Cancelled' dan stok buku akan dikembalikan.
        </div>

        <form method="POST" action="cancel_order.php">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <button type="submit" class="btn btn-danger">Ya, Batalkan Pesanan</button>
            <a href="orders.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>