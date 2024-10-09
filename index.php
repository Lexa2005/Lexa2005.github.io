<?php
session_start();
include 'db.php'; // Убедитесь, что путь к файлу db.php правильный
include 'header.php';
?>

<main>
  <section class="slider">
    <div class="slides">
      <div class="slide" id="s1">
        <img src="images/image1.jpg" alt="Ноутбук премиум-класса">
      </div>
      <div class="slide" id="s2">
        <img src="images/image2.jpg" alt="Мощный игровой компьютер">
      </div>
      <div class="slide" id="s3">
        <img src="images/image3.jpg" alt="Компактный офисный компьютер">
      </div>
      <div class="slide" id="s4">
        <img src="images/image4.jpg" alt="Новая картинка">
      </div>
    </div>
  </section>

  <section class="about">
    <h2>О нашей компании</h2>
    <p>Мы предлагаем широкий выбор высококачественных компьютеров для любых нужд. Наша компания была основана в 2010 году с целью предоставления лучших решений в сфере компьютерной техники.</p>
    <p>Мы гордимся тем, что предлагаем только проверенные и качественные продукты от ведущих производителей. Наша команда экспертов всегда готова помочь вам выбрать идеальный компьютер, исходя из ваших потребностей и бюджета.</p>
  </section>

  <section class="reviews">
    <h2>Отзывы наших клиентов</h2>
    <div class="review-list">
      <?php
      if (isset($conn) && $conn) {
          $sql = "SELECT * FROM reviews ORDER BY created_at DESC LIMIT 4";
          $result = $conn->query($sql);

          if ($result) {
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
              $result->free(); // Освобождаем результат
          } else {
              echo '<p>Ошибка выполнения запроса: ' . $conn->error . '</p>';
          }
      } else {
          echo '<p>Ошибка подключения к базе данных.</p>';
      }
      ?>
    </div>
  </section>
</main>

<?php
include 'footer.php'; // Убедитесь, что путь к файлу footer.php правильный

// Закрываем соединение с базой данных
if (isset($conn) && $conn) {
    $conn->close();
}
?>