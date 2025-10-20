<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// juku nama
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Bookstore</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? ' active' : '' ?>" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? ' active' : '' ?>" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? ' active' : '' ?>" href="cart.php">Cart</a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? ' active' : '' ?>" href="orders.php">Orders</a></li>
                <!-- username -->
                <li class="nav-item ms-3"><span class="nav-link text-white">Hi, <?= htmlspecialchars($username) ?></span></li>
                <li class="nav-item ms-2"><a class="btn btn-sm btn-outline-light" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
