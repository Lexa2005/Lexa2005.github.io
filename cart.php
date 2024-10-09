<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Корзина - Кампудахтерская</title>
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
      <h2>Корзина</h2>
      <?php if (count($cart) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Товар/Услуга</th>
              <th>Количество</th>
              <th>Цена за единицу</th>
              <th>Общая цена</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $total = 0;
            foreach ($cart as $item):
                $total += $item['price'] * $item['quantity'];
            ?>
            <tr>
              <td><?php echo htmlspecialchars($item['name']); ?></td>
              <td><?php echo htmlspecialchars($item['quantity']); ?></td>
              <td>$<?php echo htmlspecialchars($item['price']); ?></td>
              <td>$<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
              <td>
                <form action="remove_from_cart.php" method="post">
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                  <input type="hidden" name="type" value="<?php echo htmlspecialchars($item['type']); ?>">
                  <button type="submit" class="btn">Удалить</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3">Итого:</td>
              <td>$<?php echo htmlspecialchars($total); ?></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
        <form action="checkout.php" method="post">
          <button type="submit" class="btn">Оформить заказ</button>
        </form>
      <?php else: ?>
        <p>Ваша корзина пуста.</p>
      <?php endif; ?>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>
</body>
</html>