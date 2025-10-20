<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: user/index.php");
    exit;
}

$message = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "Semua field harus diisi.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $message = "Username harus 3-20 karakter, hanya huruf, angka, dan underscore.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
    } elseif ($password !== $confirm_password) {
        $message = "Password dan konfirmasi password tidak sama.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = "Username sudah digunakan.";
        }
        $stmt->close();

        if (!$message) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $message = "Email sudah terdaftar.";
            }
            $stmt->close();
        }

        if (!$message) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt_insert->execute()) {
                $_SESSION['user_id'] = $stmt_insert->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                header("Location: user/index.php");
                exit;
            } else {
                $message = "Gagal mendaftar, silakan coba lagi.";
            }
            $stmt_insert->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <<style>
    body, html {
        height: 100%;
        background: linear-gradient(135deg, #9b59b6, #71b7e6);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }
    .register-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        padding: 40px 30px;
        width: 100%;
        max-width: 420px;
        transition: transform 0.3s ease;
    }
    .register-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .register-card h2 {
        color: #6f42c1;
        font-weight: 700;
        margin-bottom: 30px;
        text-align: center;
        letter-spacing: 1.2px;
    }
    .form-control:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 8px rgba(111, 66, 193, 0.4);
    }
    .btn-register {
        background: #6f42c1;
        border: none;
        width: 100%;
        padding: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 50px;
        transition: background 0.3s ease;
    }
    .btn-register:hover {
        background: #5936a2;
    }
    .text-link {
        color: #6f42c1;
        text-decoration: none;
        font-weight: 600;
    }
    .text-link:hover {
        text-decoration: underline;
    }
    .alert {
        border-radius: 8px;
        font-size: 0.9rem;
    }
</style>
</head>
<body>
    <div class="register-container">
    <div class="register-card shadow-sm">
        <h2>Register</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php" novalidate>
            <div class="mb-4">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control" id="username" name="username" required minlength="3" maxlength="20" pattern="[a-zA-Z0-9_]+" 
                    value="<?php echo htmlspecialchars($username); ?>" />
                <div class="form-text">3-20 karakter, huruf, angka, dan underscore saja.</div>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus value="<?php echo htmlspecialchars($email); ?>" />
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6" />
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" />
            </div>
            <button type="submit" class="btn btn-register">Register</button>
        </form>
        <p class="mt-4 text-center text-muted" style="font-size: 0.9rem;">
            Sudah punya akun? <a href="login.php" class="text-link">Login di sini</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>