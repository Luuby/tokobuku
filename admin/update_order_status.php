<?php
session_start();
require_once '../config.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Proses POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id   = intval($_POST['order_id'] ?? 0);
    $new_status = trim($_POST['status'] ?? '');
    $admin_note = trim($_POST['admin_note'] ?? '');

    if (empty($order_id) || empty($new_status)) {
        $_SESSION['error'] = 'Data tidak lengkap. Pastikan semua field diisi.';
        header("Location: order_detail.php?id=" . $order_id);
        exit;
    }

    // Validasi status yang diperbolehkan
    $valid_statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($new_status, $valid_statuses)) {
        $_SESSION['error'] = 'Status tidak valid.';
        header("Location: order_detail.php?id=" . $order_id);
        exit;
    }

    // Cek apakah pesanan ada
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order_result) {
        $_SESSION['error'] = 'Pesanan tidak ditemukan.';
        header("Location: orders.php");
        exit;
    }

    $old_status = $order_result['status'] ?? 'pending';

    // Mulai transaksi
    $conn->autocommit(false);
    $conn->begin_transaction();

    try {
        // Update status dan catatan admin
        if (!empty($admin_note)) {
            $stmt = $conn->prepare("UPDATE orders SET status = ?, admin_note = ? WHERE id = ?");
            $stmt->bind_param("ssi", $new_status, $admin_note, $order_id);
        } else {
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $new_status, $order_id);
        }

        if (!$stmt->execute()) {
            throw new Exception("Gagal mengupdate status pesanan.");
        }
        $stmt->close();

        // Jika dibatalkan, kembalikan stok
        if ($new_status === 'cancelled' && $old_status !== 'cancelled') {
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
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    $restored_items++;
                }
                $stmt->close();
            }

            $_SESSION['success'] = "Status diubah ke 'Cancelled'. Stok dikembalikan untuk {$restored_items} item buku.";
        } else {
            $msg = "Status berhasil diubah ke '" . ucfirst($new_status) . "'.";
            if (!empty($admin_note)) {
                $msg .= " Catatan: " . htmlspecialchars($admin_note);
            }
            $_SESSION['success'] = $msg;
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Gagal mengupdate pesanan: ' . $e->getMessage();
    } finally {
        $conn->autocommit(true);
    }

    header("Location: order_detail.php?id=" . $order_id);
    exit;
} else {
    // Jika bukan POST, redirect ke daftar pesanan
    header("Location: orders.php");
    exit;
}
?>
