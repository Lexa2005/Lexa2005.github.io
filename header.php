<?php
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "drab";

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
$name = null;
$avatar = null;

if ($user_id) {
    // Получаем данные пользователя
    $stmt = $conn->prepare("SELECT name, avatar FROM users WHERE id = ?");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $avatar);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Кампудахтерская</title>
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
        <div class="user-info">
          <span class="username"><?php echo htmlspecialchars($name); ?></span>
          <?php if ($avatar): ?>
            <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="avatar">
          <?php else: ?>
            <div class="avatar-placeholder"></div>
          <?php endif; ?>
        </div>
        <a href="dashboard.php">Мой кабинет</a>
      <?php else: ?>
        <a href="login.php">Авторизация</a>
      <?php endif; ?>
    </nav>
  </header>
  
  <main>
    <!-- Здесь будет основной контент страницы -->
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>
</body>
</html>