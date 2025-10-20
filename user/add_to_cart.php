<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['id'] ?? 0);

if ($book_id <= 0) {
    header("Location: index.php");
    exit;
}

// Cek apakah user sudah punya cart aktif
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($cart_id);
    $stmt->fetch();
} else {
    // Buat cart baru
    $stmt_insert = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $stmt_insert->bind_param("i", $user_id);
    $stmt_insert->execute();
    $cart_id = $stmt_insert->insert_id;
    $stmt_insert->close();
}
$stmt->close();

// Cek apakah buku sudah ada di cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND book_id = ?");
$stmt->bind_param("ii", $cart_id, $book_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update quantity
    $stmt->bind_result($cart_item_id, $quantity);
    $stmt->fetch();
    $new_quantity = $quantity + 1;
    $stmt_update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $stmt_update->bind_param("ii", $new_quantity, $cart_item_id);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    // Insert item baru
    $stmt_insert = $conn->prepare("INSERT INTO cart_items (cart_id, book_id, quantity) VALUES (?, ?, 1)");
    $stmt_insert->bind_param("ii", $cart_id, $book_id);
    $stmt_insert->execute();
    $stmt_insert->close();
}
$stmt->close();

header("Location: cart.php");
exit;