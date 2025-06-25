<?php
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginId = $_POST['login_id'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($loginId === 'admin' && $password === 'admin') {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $errors[] = 'ログインIDまたはパスワードが間違っています。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン</title>
</head>
<body>
  <h1>ログイン</h1>

  <?php foreach ($errors as $error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
  <?php endforeach; ?>

  <form method="POST">
    <label>ログインID: <input type="text" name="login_id" required></label><br><br>
    <label>パスワード: <input type="password" name="password" required></label><br><br>
    <button type="submit">ログイン</button>
  </form>
</body>
</html>
