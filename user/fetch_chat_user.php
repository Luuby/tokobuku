<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) exit;

$user_id = $_SESSION['user_id'];

// Ambil semua pesan user
$messages = $conn->prepare("SELECT * FROM messages WHERE user_id=? ORDER BY created_at ASC");
$messages->bind_param("i", $user_id);
$messages->execute();
$res_msg = $messages->get_result();

$output = '';
$last_date = '';

while($msg = $res_msg->fetch_assoc()) {
    // Tanggal
    $msg_date = date('Y-m-d', strtotime($msg['created_at']));
    if ($msg_date != $last_date) {
        $label = ($msg_date == date('Y-m-d')) ? 'Hari ini' :
                 (($msg_date == date('Y-m-d', strtotime('-1 day'))) ? 'Kemarin' :
                 date('d M Y', strtotime($msg_date)));
        $output .= '<div class="date-separator">'.$label.'</div>';
        $last_date = $msg_date;
    }

    // Pesan user
    $output .= '<div class="message user">'.htmlspecialchars($msg['message']).
               '<span class="message-time">'.date('H:i', strtotime($msg['created_at'])).'</span></div>';

    // Balasan admin
    $replies = $conn->prepare("SELECT * FROM replies WHERE message_id=? ORDER BY created_at ASC");
    $replies->bind_param("i", $msg['message_id']);
    $replies->execute();
    $res_rep = $replies->get_result();
    while($r = $res_rep->fetch_assoc()){
        $output .= '<div class="message admin">'.htmlspecialchars($r['reply_message']).
                   '<span class="message-time">'.date('H:i', strtotime($r['created_at'])).'</span></div>';
    }
    $replies->close();
}

echo $output;
$messages->close();
?>
