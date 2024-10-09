<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Установка соединения с базой данных
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "drab";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка соединения
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Проверка на авторизацию администратора
    if (isset($_POST['admin_login'])) {
        $admin_username = $_POST['admin_username'];
        $admin_password = $_POST['admin_password'];

        if ($admin_username === 'admin' && $admin_password === 'admin') {
            $_SESSION['admin'] = true;
            header("Location: admin_dashboard.php"); // Перенаправляем на страницу администратора
            exit();
        } else {
            echo "Неверный логин или пароль администратора!";
        }
    } else {
        // Получаем данные из формы
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Подготавливаем и выполняем SQL-запрос
        $stmt = $conn->prepare("SELECT id, password, name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashed_password, $user_name);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name; // Устанавливаем имя пользователя в сессию
            header("Location: dashboard.php"); // Перенаправляем на страницу личного кабинета
            exit();
        } else {
            echo "Неверный email или пароль!";
        }
    }

    // Закрытие соединения с базой данных
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Авторизация - Кампудахтерская</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Кампудахтерская</h1>
    <nav>
      <a href="index.php">Главная</a>
      <a href="catalog.php">Каталог</a>
      <a href="poll.php">Опрос</a>
      <a href="gallery.php">Галерея</a>
      <a href="contact.php">Контакты</a>
      <a href="reviews.php">Отзывы</a>
      <a href="registration.php">Регистрация</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Мой кабинет</a>
      <?php else: ?>
        <a href="login.php">Авторизация</a>
      <?php endif; ?>
    </nav>
  </header>
  
  <main>
    <section>
      <h2>Авторизация</h2>
      <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" class="btn">Войти</button>
      </form>

      <h2>Авторизация администратора</h2>
      <form action="login.php" method="post">
        <input type="hidden" name="admin_login" value="1">
        <label for="admin_username">Логин:</label>
        <input type="text" id="admin_username" name="admin_username" required>
        <label for="admin_password">Пароль:</label>
        <input type="password" id="admin_password" name="admin_password" required>
        <button type="submit" class="btn">Войти как администратор</button>
      </form>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>
</body>
</html>