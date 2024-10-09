<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

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

// Обработка редактирования товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Обработка редактирования услуги
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("UPDATE services SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $service_id);
    $stmt->execute();
    $stmt->close();
}

// Получение списка товаров для редактирования
$sql = "SELECT id, name, description, price, image_url FROM products";
$products_result = $conn->query($sql);

// Получение списка услуг для редактирования
$sql = "SELECT id, name, description, price, image_url FROM services";
$services_result = $conn->query($sql);

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Панель администратора</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Панель администратора</h1>
    <nav>
      <a href="index.php">Вернуться на сайт</a>
      <a href="admin_logout.php">Выйти</a>
    </nav>
  </header>
  
  <main>
    <section>
      <h2>Редактирование товаров</h2>
      <?php while($row = $products_result->fetch_assoc()): ?>
        <form action="admin_dashboard.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
          <label for="name_<?php echo $row['id']; ?>">Название:</label>
          <input type="text" id="name_<?php echo $row['id']; ?>" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
          <label for="description_<?php echo $row['id']; ?>">Описание:</label>
          <textarea id="description_<?php echo $row['id']; ?>" name="description" rows="5" cols="50"><?php echo htmlspecialchars($row['description']); ?></textarea>
          <label for="price_<?php echo $row['id']; ?>">Цена:</label>
          <input type="number" id="price_<?php echo $row['id']; ?>" name="price" step="0.01" value="<?php echo htmlspecialchars($row['price']); ?>" required>
          <label for="image_url_<?php echo $row['id']; ?>">URL изображения:</label>
          <input type="text" id="image_url_<?php echo $row['id']; ?>" name="image_url" value="<?php echo htmlspecialchars($row['image_url']); ?>">
          <button type="submit" class="btn">Сохранить</button>
        </form>
      <?php endwhile; ?>
    </section>

    <section>
      <h2>Редактирование услуг</h2>
      <?php while($row = $services_result->fetch_assoc()): ?>
        <form action="admin_dashboard.php" method="post">
          <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
          <label for="name_<?php echo $row['id']; ?>">Название:</label>
          <input type="text" id="name_<?php echo $row['id']; ?>" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
          <label for="description_<?php echo $row['id']; ?>">Описание:</label>
          <textarea id="description_<?php echo $row['id']; ?>" name="description" rows="5" cols="50"><?php echo htmlspecialchars($row['description']); ?></textarea>
          <label for="price_<?php echo $row['id']; ?>">Цена:</label>
          <input type="number" id="price_<?php echo $row['id']; ?>" name="price" step="0.01" value="<?php echo htmlspecialchars($row['price']); ?>" required>
          <label for="image_url_<?php echo $row['id']; ?>">URL изображения:</label>
          <input type="text" id="image_url_<?php echo $row['id']; ?>" name="image_url" value="<?php echo htmlspecialchars($row['image_url']); ?>">
          <button type="submit" class="btn">Сохранить</button>
        </form>
      <?php endwhile; ?>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>
</body>
</html>