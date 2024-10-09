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

// Получаем данные из формы
$service = $_POST['service'];
$rating = $_POST['rating'];
$recommend = $_POST['recommend'];
$features = $_POST['features'];
$improvements = $_POST['improvements'];
$devices = isset($_POST['devices']) ? implode(', ', $_POST['devices']) : '';

// Подготавливаем и выполняем SQL-запрос
$stmt = $conn->prepare("INSERT INTO polls (service, rating, recommend, features, improvements, devices) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissss", $service, $rating, $recommend, $features, $improvements, $devices);

if ($stmt->execute()) {
    header("Location: success.php");
    exit();
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>