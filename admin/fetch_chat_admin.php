<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') exit;
if(!isset($_POST['user_id'])) exit;

$user_id = intval($_POST['user_id']);

// Ambil pesan user beserta balasannya
$stmt = $conn->prepare("
    SELECT m.message_id, m.message AS user_msg, m.created_at AS user_time,
           r.reply_message, r.created_at AS reply_time
    FROM messages m
    LEFT JOIN replies r ON m.message_id = r.message_id
    WHERE m.user_id=?
    ORDER BY m.created_at ASC, r.created_at ASC
");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$res = $stmt->get_result();
$output = '';
$last_message_id = 0;
while($row = $res->fetch_assoc()){
    if($last_message_id != $row['message_id']){
        $output .= '<div class="message user">'.htmlspecialchars($row['user_msg']).'<br><small>'.date('H:i', strtotime($row['user_time'])).'</small></div>';
        $last_message_id = $row['message_id'];
    }
    if($row['reply_message']){
        $output .= '<div class="message admin">'.htmlspecialchars($row['reply_message']).'<br><small>'.date('H:i', strtotime($row['reply_time'])).'</small></div>';
    }
}
$stmt->close();
echo $output;
