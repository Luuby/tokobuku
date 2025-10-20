<?php
session_start();
require_once '../config.php';

// Proteksi hanya untuk admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_id = (int)$_POST['message_id'];
    $reply_message = trim($_POST['reply_message']);
    $admin_id = $_SESSION['user_id'];

    if ($reply_message) {
        $stmt = $conn->prepare("INSERT INTO replies (message_id, admin_id, reply_message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $message_id, $admin_id, $reply_message);
        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION['reply_success'] = "Balasan berhasil dikirim.";
        } else {
            $_SESSION['reply_error'] = "Gagal mengirim balasan.";
        }
    } else {
        $_SESSION['reply_error'] = "Pesan balasan tidak boleh kosong.";
    }
}

header("Location: messages.php");
exit;
