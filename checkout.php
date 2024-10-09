<?php
session_start();

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
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

// Проверка существования user_id в базе данных
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Ошибка: user_id не найден в базе данных.");
}
$stmt->close();

foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $total_price = $item['price'] * $item['quantity'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }
    $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total_price);

    if (!$stmt->execute()) {
        die("Ошибка выполнения запроса: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();

$_SESSION['cart'] = [];

header("Location: success.php");
exit();
?>