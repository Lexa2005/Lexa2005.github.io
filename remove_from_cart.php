<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $id && $item['type'] == $type) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

header("Location: cart.php");
exit();
?>