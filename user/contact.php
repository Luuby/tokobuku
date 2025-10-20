<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chat dengan Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
.chat-card { max-width: 700px; margin: 50px auto; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
.chat-header { background:#000; color:#fff; padding:15px; font-weight:600; text-align:center; }
.chat-messages { height: 400px; overflow-y:auto; padding:15px; background:#fff; display:flex; flex-direction:column; gap:10px; }
.message { padding:10px 15px; border-radius:18px; max-width:70%; position:relative; word-wrap:break-word; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
.message.user { background:#000; color:#fff; align-self:flex-end; }
.message.admin { background:#fff; border:1px solid #e0e0e0; align-self:flex-start; }
.message-time { font-size:0.7em; opacity:0.6; position:absolute; bottom:-14px; right:10px; }
.date-separator { text-align:center; color:#777; font-size:0.8em; margin:10px 0; }
.chat-input { display:flex; padding:10px; background:#fff; border-top:1px solid #ddd; }
.chat-input textarea { flex:1; resize:none; border-radius:20px; padding:10px; border:1px solid #ccc; }
.chat-input button { margin-left:10px; border-radius:50%; width:42px; display:flex; justify-content:center; align-items:center; }
</style>
</head>
<body>
    <?php include 'navbar.php' ?>
<div class="chat-card">
    <div class="chat-header">💬 Chat dengan Admin</div>
    <div class="chat-messages" id="chatMessages"></div>
    <div class="chat-input">
        <textarea id="chatInput" rows="1" placeholder="Ketik pesan..."></textarea>
        <button id="sendBtn" class="btn btn-dark"><i class="bi bi-send"></i></button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let lastChatHTML = '';
function fetchChat() {
    $.post('fetch_chat_user.php', {}, function(data){
        if(data !== lastChatHTML){
            $('#chatMessages').html(data);
            $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
            lastChatHTML = data;
        }
    });
}

$('#sendBtn').click(function(){
    let msg = $('#chatInput').val().trim();
    if(msg === '') return;
    $.post('send_chat_user.php', {message: msg}, function(resp){
        if(resp==='success'){
            $('#chatInput').val('');
            fetchChat();
        }
    });
});

$('#chatInput').keypress(function(e){
    if(e.which === 13 && !e.shiftKey){
        e.preventDefault();
        $('#sendBtn').click();
    }
});

setInterval(fetchChat, 2000);
fetchChat();
</script>
</body>
</html>
