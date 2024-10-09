<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['admin'] = true;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вход для администратора</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Вход для администратора</h1>
  </header>
  
  <main>
    <section>
      <form action="admin_login.php" method="post">
        <label for="username">Логин:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" class="btn">Войти</button>
        <?php if (isset($error)): ?>
          <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
      </form>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>
</body>
</html>