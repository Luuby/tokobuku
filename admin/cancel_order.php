<?php
session_start();
require_once '../config.php';

// Cek login user
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);

    // Cek order exists dan eligible (hanya pending/paid)
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ? AND status IN ('pending', 'paid')");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order) {
        $_SESSION['error'] = "Order tidak ditemukan atau tidak bisa dibatalkan (sudah shipped/delivered/cancelled).";
        header("Location: orders.php");
        exit;
    }

    // Mulai transaction
    $conn->autocommit(false);
    $conn->begin_transaction();

    try {
        // Update status ke cancelled
        $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        if (!$stmt->execute() || $stmt->affected_rows === 0) {
            throw new Exception("Gagal update status cancelled.");
        }
        $stmt->close();

        // Restore stok
        $stmt = $conn->prepare("SELECT book_id, quantity FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items = $stmt->get_result();
        $stmt->close();

        $restored_items = 0;
        while ($item = $items->fetch_assoc()) {
            $book_id = $item['book_id'];
            $quantity = $item['quantity'];

            $stmt = $conn->prepare("UPDATE books SET stock = stock + ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $book_id);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $restored_items++;
            }
            $stmt->close();
        }

        $conn->commit();

        $_SESSION['success'] = "Order #{$order_id} berhasil dibatalkan. Stok buku dikembalikan ({$restored_items} item).";

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Gagal membatalkan order: ' . $e->getMessage();
    } finally {
        $conn->autocommit(true);
    }

    header("Location: orders.php");
    exit;
} else {
    header("Location: orders.php");
    exit;
}
?>