<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chat Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; }
.chat-container { max-width: 800px; margin: 50px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); display: flex; flex-direction: column; height: 80vh; }
.chat-box { flex: 1; padding: 20px; overflow-y: auto; }
.chat-box div { margin-bottom: 10px; }
.chat-box .user-msg { text-align: right; }
.chat-box .user-msg span { background: #0d6efd; color: #fff; padding: 8px 12px; border-radius: 20px; display: inline-block; max-width: 70%; word-wrap: break-word; }
.chat-box .admin-msg span { background: #e4e6eb; color: #000; padding: 8px 12px; border-radius: 20px; display: inline-block; max-width: 70%; word-wrap: break-word; }
.chat-input { border-top: 1px solid #ddd; padding: 10px; display: flex; }
.chat-input input { flex: 1; padding: 10px 15px; border-radius: 50px; border: 1px solid #ccc; }
.chat-input button { margin-left: 10px; border-radius: 50px; }
</style>
</head>
<body>

<div class="chat-container">
    <div class="chat-box" id="chat-box">
        <p class="text-center text-muted">Memuat chat...</p>
    </div>
    <div class="chat-input">
        <input type="text" id="message" placeholder="Ketik pesan..." autocomplete="off">
        <button class="btn btn-primary" id="send-btn">Kirim</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
function loadChat() {
    $.ajax({
        url: 'fetch_chat_user.php',
        type: 'POST',
        data: { user_id: <?= $user_id ?> },
        success: function(data){
            $('#chat-box').html(data);
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        }
    });
}

$('#send-btn').click(function(){
    let msg = $('#message').val().trim();
    if(msg !== '') {
        $.ajax({
            url: 'send_chat_user.php',
            type: 'POST',
            data: { user_id: <?= $user_id ?>, message: msg },
            success: function(resp){
                if(resp === 'success') {
                    $('#message').val('');
                    loadChat();
                }
            }
        });
    }
});

$('#message').keypress(function(e){
    if(e.which === 13) { $('#send-btn').click(); }
});

// Fetch chat setiap 2 detik
setInterval(loadChat, 2000);
loadChat();
</script>
</body>
</html>
