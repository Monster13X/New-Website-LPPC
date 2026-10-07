<?php
session_start();
require_once __DIR__ . '/../db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Masukkan username dan password.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM daftarasis WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            $hash = $user['pass'];
            $ok = false;
            
            if (password_verify($password, $hash)) {
                $ok = true;
            } elseif ($password === $hash) { // Fallback jika password masih terimpan teks biasa (plain text)
                $ok = true;
            }

            if ($ok) {
                // Keamanan: regenerasi ID session saat berhasil login
                session_regenerate_id(true);

                // Simpan data pengguna dan role ke dalam Session
                $_SESSION['admin_user'] = $user['username'];
                $_SESSION['admin_role'] = $user['role'];         // 'superadmin' atau 'admin_kursus'
                $_SESSION['admin_kursus_id'] = $user['kursus_id']; // ID kursus (null jika superadmin)

                header('Location: index.php');
                exit;
            }
        }
        $error = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .box { width: calc(100% - 32px); max-width: 420px; margin: 60px auto; padding: 24px; background: #111; color: #fff; border-radius: 8px; }
        .err { background: #e74c3c; color: #fff; padding: 8px; border-radius: 4px; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Login Admin</h2>
        <?php if ($error): ?>
            <div class="err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label>Username<br>
                <input type="text" name="username" required>
            </label>
            <br><br>
            <label>Password<br>
                <input type="password" name="password" required>
            </label>
            <br><br>
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>