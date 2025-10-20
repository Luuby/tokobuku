<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['message'])) exit;

$user_id = $_SESSION['user_id'];
$message = trim($_POST['message']);
if($message === '') exit;

$stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $message);
if($stmt->execute()){
    echo 'success';
}
$stmt->close();
?>
