<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Товары и услуги - Кампудахтерская</title>
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
      <a href="cart.php">Корзина</a>
    </nav>
  </header>
  
  <main>
    <section>
      <h2>Товары</h2>
      <div class="products">
        <?php
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

        // Получаем список товаров
        $sql = "SELECT id, name, description, price, image_url FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<p>Цена: $' . htmlspecialchars($row['price']) . '</p>';
                echo '<form action="add_to_cart.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row['id']) . '">';
                echo '<input type="hidden" name="type" value="product">';
                echo '<input type="number" name="quantity" value="1" min="1">';
                echo '<button type="submit" class="btn">Добавить в корзину</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "Нет доступных товаров.";
        }
        ?>
      </div>
    </section>

    <section>
      <h2>Услуги</h2>
      <div class="services">
        <?php
        // Получаем список услуг
        $sql = "SELECT id, name, description, price, image_url FROM services";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="service">';
                echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<p>Цена: $' . htmlspecialchars($row['price']) . '</p>';
                echo '<form action="add_to_cart.php" method="post">';
                echo '<input type="hidden" name="service_id" value="' . htmlspecialchars($row['id']) . '">';
                echo '<input type="hidden" name="type" value="service">';
                echo '<input type="number" name="quantity" value="1" min="1">';
                echo '<button type="submit" class="btn">Добавить в корзину</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "Нет доступных услуг.";
        }

        $conn->close();
        ?>
      </div>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>
</body>
</html>