<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Перенаправляем на страницу авторизации, если пользователь не авторизован
    exit();
}

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

$user_id = $_SESSION['user_id'];

// Получаем данные пользователя
$stmt = $conn->prepare("SELECT name, email, created_at, avatar FROM users WHERE id = ?");
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $created_at, $avatar);
$stmt->fetch();
$stmt->close();

// Обработка удаления аватарки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_avatar'])) {
    // Удаляем аватарку из базы данных
    $stmt = $conn->prepare("UPDATE users SET avatar = NULL WHERE id = ?");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        // Удаляем файл с сервера
        if (file_exists($avatar)) {
            unlink($avatar);
        }
        $avatar = null;
        echo "Аватарка успешно удалена.";
    } else {
        echo "Ошибка: " . $stmt->error;
    }
    $stmt->close();
}

// Получаем список заказов пользователя с общей суммой заказа
$stmt = $conn->prepare("SELECT orders.id, products.name, orders.quantity, orders.total_price, orders.created_at
                        FROM orders 
                        JOIN products ON orders.product_id = products.id 
                        WHERE orders.user_id = ?");
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Вычисляем общую сумму заказа
$total_order_amount = 0;
foreach ($orders as $order) {
    $total_order_amount += $order['total_price'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Личный кабинет - Кампудахтерская</title>
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
    <section class="dashboard">
      <h2>Личный кабинет</h2>
      <p>Добро пожаловать, <?php echo htmlspecialchars($name); ?>!</p>
      <p>Ваш email: <?php echo htmlspecialchars($email); ?></p>
      <p>Дата регистрации: <?php echo htmlspecialchars($created_at); ?></p>

      <!-- Отображение аватарки -->
      <div class="avatar">
        <?php if ($avatar): ?>
          <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Аватарка">
          <form action="dashboard.php" method="post">
            <input type="hidden" name="delete_avatar" value="1">
            <button type="submit" class="btn">Удалить аватарку</button>
          </form>
        <?php else: ?>
          <p>Аватарка не загружена</p>
        <?php endif; ?>
      </div>

      <!-- Форма для загрузки аватарки -->
      <form action="upload_avatar.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="avatar">Загрузить аватарку:</label>
          <input type="file" id="avatar" name="avatar" accept="image/*">
        </div>
        <button type="submit" class="btn">Загрузить</button>
      </form>

      <form action="dashboard.php" method="post">
        <div class="form-group">
          <label for="name">Имя:</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <button type="submit" class="btn" name="update_profile">Обновить профиль</button>
      </form>

      <form action="dashboard.php" method="post">
        <div class="form-group">
          <label for="current_password">Текущий пароль:</label>
          <input type="password" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
          <label for="new_password">Новый пароль:</label>
          <input type="password" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
          <label for="confirm_password">Подтвердите новый пароль:</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn" name="change_password">Сменить пароль</button>
      </form>

      <a href="logout.php" class="btn">Выйти</a>

      <h3>Мои заказы</h3>
      <table>
        <thead>
          <tr>
            <th>ID заказа</th>
            <th>Товар</th>
            <th>Количество</th>
            <th>Общая цена</th>
            <th>Дата заказа</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td><?php echo htmlspecialchars($order['id']); ?></td>
              <td><?php echo htmlspecialchars($order['name']); ?></td>
              <td><?php echo htmlspecialchars($order['quantity']); ?></td>
              <td>$<?php echo htmlspecialchars($order['total_price']); ?></td>
              <td><?php echo htmlspecialchars($order['created_at']); ?></td>
              <td>
                <form action="cancel_order.php" method="post">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                  <button type="submit" class="btn">Отменить заказ</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3">Итого:</td>
            <td>$<?php echo htmlspecialchars($total_order_amount); ?></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>

  <script>
    document.querySelector('form[action="upload_avatar.php"]').addEventListener('submit', function(event) {
      event.preventDefault();
      var formData = new FormData(this);

      fetch(this.action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        alert(data); // Показываем сообщение от сервера
        location.reload(); // Перезагружаем страницу для обновления аватарки
      })
      .catch(error => {
        console.error('Ошибка:', error);
      });
    });
  </script>
</body>
</html>