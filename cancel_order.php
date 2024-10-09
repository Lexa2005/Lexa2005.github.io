<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Перенаправляем на страницу авторизации, если пользователь не авторизован
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
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

    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // Проверка, что заказ принадлежит текущему пользователю
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        die("Ошибка: Заказ не найден или не принадлежит текущему пользователю.");
    }
    $stmt->close();

    // Удаление заказа
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: dashboard.php");
    exit();
}
?>