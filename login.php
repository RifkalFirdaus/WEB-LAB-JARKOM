<?php
session_start();
include "Koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Query untuk mencari admin berdasarkan username
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verifikasi password
        if (md5($password) === $admin['password']) {
            // Simpan session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];

            // Redirect ke halaman Admin
            header("Location: Admin.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Login Admin</h1>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn-login">Login</button>
        </form>
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
