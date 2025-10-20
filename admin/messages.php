<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua user yang punya pesan
$users = $conn->query("
    SELECT u.id, u.username, u.email
    FROM users u
    JOIN messages m ON u.id = m.user_id
    WHERE u.role='user'
    GROUP BY u.id
    ORDER BY u.username ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Chat Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #ffffff; font-family: 'Segoe UI', sans-serif; }
h2 { margin-bottom: 1.5rem; font-weight: 700; }
.card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; }
.card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.user-list { list-style:none; padding:0; margin:0; max-height:80vh; overflow-y:auto; background:#fff; }
.user-item { padding:12px 15px; border-bottom:1px solid #eee; cursor:pointer; transition: background 0.2s; }
.user-item:hover, .user-item.active { background-color: #f1f3f5; }
.chat-box { display:flex; flex-direction:column; height:80vh; background:#ffffff; border-radius:0 12px 12px 0; }
.chat-messages { flex:1; padding:15px; overflow-y:auto; scroll-behavior: smooth; display:flex; flex-direction:column; gap:10px; }
.message { padding:10px 15px; border-radius:20px; max-width:70%; position:relative; word-wrap:break-word; box-shadow: 0 2px 6px rgba(0,0,0,0.12); }
.message.user { background: #f1f3f5; align-self:flex-start; }
.message.admin { background: linear-gradient(135deg, #0d6efd, #4da0ff); color:#fff; align-self:flex-end; }
.message-time { font-size:0.7em; color:#666; margin-top:3px; display:block; text-align:right; }
.chat-input { display:flex; border-top:1px solid #ddd; padding:10px; background:#fff; }
.chat-input textarea { flex:1; resize:none; border-radius:20px; padding:10px; border:1px solid #ccc; }
.chat-input button { margin-left:10px; border-radius:50%; width:40px; display:flex; justify-content:center; align-items:center; }
::-webkit-scrollbar { width:8px; }
::-webkit-scrollbar-track { background:#f1f1f1; border-radius:4px; }
::-webkit-scrollbar-thumb { background:#ccc; border-radius:4px; }
::-webkit-scrollbar-thumb:hover { background:#999; }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Chat Panel</h2>
    </div>

    <div class="card d-flex flex-row overflow-hidden">
        <!-- Sidebar: Daftar User -->
        <div class="bg-white p-0" style="width:250px; border-right:1px solid #ddd;">
            <ul class="user-list" id="userList">
                <?php while($u = $users->fetch_assoc()): ?>
                <li class="user-item" data-userid="<?= $u['id']; ?>">
                    <strong><?= htmlspecialchars($u['username']); ?></strong><br>
                    <small><?= htmlspecialchars($u['email']); ?></small>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Chat Box -->
        <div class="chat-box flex-grow-1">
            <div class="chat-messages" id="chatMessages"></div>
            <div class="chat-input">
                <textarea id="chatInput" rows="1" placeholder="Ketik pesan..." disabled></textarea>
                <button id="sendBtn" class="btn btn-primary" disabled><i class="bi bi-send"></i></button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let selectedUserId = null;

function fetchChat() {
    if(!selectedUserId) return;
    $.post('fetch_chat_admin.php', {user_id:selectedUserId}, function(data){
        $('#chatMessages').html(data);
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    });
}

$('#userList').on('click', '.user-item', function(){
    $('.user-item').removeClass('active');
    $(this).addClass('active');
    selectedUserId = $(this).data('userid');
    $('#chatInput').prop('disabled', false);
    $('#sendBtn').prop('disabled', false);
    fetchChat();
});

$('#sendBtn').click(function(){
    let msg = $('#chatInput').val().trim();
    if(msg === '' || !selectedUserId) return;
    $.post('send_chat_admin.php', {user_id:selectedUserId, reply_message:msg}, function(resp){
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

setInterval(fetchChat, 1000);
</script>
</body>
</html>
