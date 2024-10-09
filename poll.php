<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Опрос - Кампудахтерская</title>
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
      <h2>Опрос</h2>
      <form action="submit_poll.php" method="post">
        <div class="form-group">
          <label for="service">Какой сервис вам больше всего нравится?</label>
          <input type="text" id="service" name="service" required>
        </div>

        <div class="form-group">
          <label for="rating">Оцените наш сайт от 1 до 5:</label>
          <select id="rating" name="rating" required>
            <option value="1">1 звезда</option>
            <option value="2">2 звезды</option>
            <option value="3">3 звезды</option>
            <option value="4">4 звезды</option>
            <option value="5">5 звезд</option>
          </select>
        </div>

        <div class="form-group">
          <label for="recommend">Рекомендовали бы вы нас друзьям?</label>
          <select id="recommend" name="recommend" required>
            <option value="yes">Да</option>
            <option value="no">Нет</option>
            <option value="maybe">Возможно</option>
          </select>
        </div>

        <div class="form-group">
          <label for="features">Какие функции вам нравятся больше всего?</label>
          <input type="text" id="features" name="features" required>
        </div>

        <div class="form-group">
          <label for="improvements">Что бы вы хотели улучшить?</label>
          <textarea id="improvements" name="improvements" rows="5" cols="50" required></textarea>
        </div>

        <div class="form-group">
          <label>Какие устройства вы используете для доступа к нашему сайту?</label>
          <div>
            <input type="checkbox" id="device_pc" name="devices[]" value="pc">
            <label for="device_pc">ПК</label>
          </div>
          <div>
            <input type="checkbox" id="device_mobile" name="devices[]" value="mobile">
            <label for="device_mobile">Мобильный телефон</label>
          </div>
          <div>
            <input type="checkbox" id="device_tablet" name="devices[]" value="tablet">
            <label for="device_tablet">Планшет</label>
          </div>
        </div>

        <button type="submit" class="btn">Отправить</button>
      </form>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>
</body>
</html>