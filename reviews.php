<?php
session_start();
include 'db.php'; // Убедитесь, что путь к файлу db.php правильный
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Отзывы - Кампудахтерская</title>
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
      <h2>Отзывы</h2>
      <form action="submit_review.php" method="post">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <label for="name">Ваше имя:</label>
          <input type="text" id="name" name="name" required>
        <?php endif; ?>
        <label for="rating">Оценка:</label>
        <select id="rating" name="rating" required>
          <option value="1">1 звезда</option>
          <option value="2">2 звезды</option>
          <option value="3">3 звезды</option>
          <option value="4">4 звезды</option>
          <option value="5">5 звезд</option>
        </select>
        <label for="commentCheckbox">Комментарий:</label>
        <input type="checkbox" id="commentCheckbox" name="commentCheckbox">
        <label for="commentText" id="commentTextLabel" style="display: none;">Ваш комментарий:</label>
        <textarea id="commentText" name="commentText" rows="10" cols="50" style="display: none;" required></textarea>
        <button type="submit" class="btn">Отправить</button>
      </form>
    </section>

    <section class="reviews">
      <h2>Отзывы наших клиентов</h2>
      <div class="review-list">
        <?php
        if (isset($conn) && $conn) {
            $sql = "SELECT * FROM reviews ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="review">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p>' . str_repeat('⭐', $row['rating']) . '</p>';
                    echo '<p>' . htmlspecialchars($row['comment_text']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Пока нет отзывов.</p>';
            }
            $conn->close();
        } else {
            echo '<p>Ошибка подключения к базе данных.</p>';
        }
        ?>
      </div>
    </section>
  </main>
  
  <footer>
    <p>&copy; 2024 Кампудахтерская. Все права защищены.</p>
  </footer>

  <script>
    document.getElementById('commentCheckbox').addEventListener('change', function() {
      var commentText = document.getElementById('commentText');
      var commentTextLabel = document.getElementById('commentTextLabel');
      if (this.checked) {
        commentText.style.display = 'block';
        commentTextLabel.style.display = 'block';
        commentText.required = true;
      } else {
        commentText.style.display = 'none';
        commentTextLabel.style.display = 'none';
        commentText.required = false;
      }
    });
  </script>
</body>
</html>