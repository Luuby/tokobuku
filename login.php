<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: user/index.php");
    }
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Email dan password harus diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashed_password, $role);
        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            if ($role === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: user/index.php");
            }
            exit;
        } else {
            $message = "Email atau password salah.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    body, html {
        height: 100%;
        background: linear-gradient(135deg, #71b7e6, #9b59b6);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }
    .login-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        padding: 40px 30px;
        width: 100%;
        max-width: 380px;
        transition: transform 0.3s ease;
    }
    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .login-card h2 {
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
    .btn-login {
        background: #6f42c1;
        border: none;
        width: 100%;
        padding: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 50px;
        transition: background 0.3s ease;
    }
    .btn-login:hover {
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
    <div class="login-container">
    <div class="login-card shadow-sm">
        <h2>Login</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" novalidate>
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" />
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
        <p class="mt-4 text-center text-muted" style="font-size: 0.9rem;">
            Belum punya akun? <a href="register.php" class="text-link">Daftar di sini</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>