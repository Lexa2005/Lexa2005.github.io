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

// Получаем данные из формы
$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : (isset($_POST['name']) ? $_POST['name'] : null);
$rating = $_POST['rating'];
$commentText = isset($_POST['commentText']) ? $_POST['commentText'] : '';

// Проверяем, что имя пользователя предоставлено
if (empty($name)) {
    die("Ошибка: Имя пользователя не может быть пустым.");
}

// Подготавливаем и выполняем SQL-запрос
$stmt = $conn->prepare("INSERT INTO reviews (name, rating, comment_text) VALUES (?, ?, ?)");

if ($stmt === false) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

$stmt->bind_param("sis", $name, $rating, $commentText);

if ($stmt->execute()) {
    header("Location: reviews.php");
    exit();
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>