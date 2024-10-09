<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $id = $_POST['product_id'] ?? $_POST['service_id'];
    $quantity = $_POST['quantity'];

    if ($type == 'product') {
        $sql = "SELECT id, name, price FROM products WHERE id = ?";
    } else {
        $sql = "SELECT id, name, price FROM services WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    if ($item) {
        $item['quantity'] = $quantity;
        $item['type'] = $type;

        // Проверяем, есть ли уже такой товар/услуга в корзине
        $found = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['id'] == $item['id'] && $cart_item['type'] == $item['type']) {
                $cart_item['quantity'] += $item['quantity'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $item;
        }
    }
}

$conn->close();
header("Location: products_and_services.php");
exit();
?>