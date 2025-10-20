<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') exit;
if(!isset($_POST['user_id'], $_POST['reply_message'])) exit;

$user_id = intval($_POST['user_id']);
$reply_message = trim($_POST['reply_message']);
if($reply_message==='') exit;

// Pastikan ada message_id terbaru dari user
$stmt = $conn->prepare("SELECT message_id FROM messages WHERE user_id=? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$stmt->bind_result($message_id);
if(!$stmt->fetch()){
    // jika belum ada pesan sama sekali, buat message dummy
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $dummy_msg = "Halo, admin mulai chat.";
    $stmt->bind_param("is",$user_id,$dummy_msg);
    $stmt->execute();
    $message_id = $conn->insert_id;
    $stmt->close();
} else {
    $stmt->close();
}

// Insert balasan admin
$stmt = $conn->prepare("INSERT INTO replies (message_id, reply_message) VALUES (?, ?)");
$stmt->bind_param("is", $message_id, $reply_message);
if($stmt->execute()){
    echo "success";
}else{
    echo "error";
}
$stmt->close();
