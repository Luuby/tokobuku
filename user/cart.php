<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil cart user
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

$cart_id = 0;
if ($stmt->num_rows > 0) {
    $stmt->bind_result($cart_id);
    $stmt->fetch();
}
$stmt->close();

// Update kuantitas
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $cart_item_id => $qty) {
        $qty = intval($qty);
        if ($qty <= 0) {
            $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ? AND cart_id = ?");
            $stmt->bind_param("ii", $cart_item_id, $cart_id);
        } else {
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND cart_id = ?");
            $stmt->bind_param("iii", $qty, $cart_item_id, $cart_id);
        }
        $stmt->execute();
        $stmt->close();
    }
    header("Location: cart.php");
    exit;
}

// Ambil items
$items = [];
if ($cart_id > 0) {
    $stmt = $conn->prepare(
        "SELECT ci.id as cart_item_id, b.id as book_id, b.title, b.price, ci.quantity, b.stock 
         FROM cart_items ci 
         JOIN books b ON ci.book_id = b.id 
         WHERE ci.cart_id = ?"
    );
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Checkout
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout']) && $cart_id > 0 && count($items) > 0) {
    $stock_error = false;

    foreach ($items as $item) {
        if ((int)$item['stock'] < $item['quantity']) {
            $error = "Stok '{$item['title']}' tidak cukup (tersedia: {$item['stock']}, dibutuhkan: {$item['quantity']}).";
            $stock_error = true;
            break;
        }
    }

    if (!$stock_error) {
        $total_price = 0;
        foreach ($items as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        $conn->autocommit(false);
        $conn->begin_transaction();

        try {
            $status = 'pending';
            $payment_method = 'payment_at_delivery';
            $created_at = date('Y-m-d H:i:s');

            $stmt = $conn->prepare(
                "INSERT INTO orders (user_id, total_price, status, payment_method, created_at) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("idsss", $user_id, $total_price, $status, $payment_method, $created_at);
            $stmt->execute();
            $order_id = $conn->insert_id;
            $stmt->close();

            $stmt_insert = $conn->prepare(
                "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)"
            );

            foreach ($items as $item) {
                $stmt_insert->bind_param("iiid", $order_id, $item['book_id'], $item['quantity'], $item['price']);
                $stmt_insert->execute();
                $stmt_update = $conn->prepare("UPDATE books SET stock = stock - ? WHERE id = ?");
                $stmt_update->bind_param("ii", $item['quantity'], $item['book_id']);
                $stmt_update->execute();
                $stmt_update->close();
            }

            $stmt_insert->close();

            $conn->query("DELETE FROM cart_items WHERE cart_id = $cart_id");
            $conn->query("DELETE FROM carts WHERE id = $cart_id");

            $conn->commit();
            $message = "Pesanan #{$order_id} berhasil dibuat! Total: Rp " . number_format($total_price, 0, ',', '.') . ".";
            $items = [];
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Checkout gagal: " . $e->getMessage();
        } finally {
            $conn->autocommit(true);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 border-bottom pb-2">Keranjang Belanja</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <h5>🛒 Keranjang Anda kosong</h5>
            <p>Mulai belanja buku favorit Anda sekarang!</p>
            <a href="index.php" class="btn btn-dark mt-3">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="table-responsive mb-4">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul Buku</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Stok</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($items as $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td>Rp <?= number_format($item['price'],0,',','.') ?></td>
                            <td>
                                <input type="number" class="form-control form-control-sm" 
                                       name="quantities[<?= $item['cart_item_id'] ?>]" 
                                       value="<?= $item['quantity'] ?>" min="0" max="<?= $item['stock'] ?>">
                            </td>
                            <td><?= $item['stock'] ?></td>
                            <td><strong>Rp <?= number_format($subtotal,0,',','.') ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th>Rp <?= number_format($total,0,',','.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" name="update_cart" class="btn btn-outline-dark">Update Keranjang</button>
                <button type="submit" name="checkout" class="btn btn-dark" <?= $error ? 'disabled' : '' ?>>Checkout</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<footer class="text-center py-3 text-muted">
    &copy; <?= date('Y') ?> Bookstore | Desain Minimalis Hitam Putih
</footer>

</body>
</html>
