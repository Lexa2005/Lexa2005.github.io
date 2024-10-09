<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Перенаправляем на страницу авторизации, если пользователь не авторизован
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

// Обработка загрузки аватарки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
    $target_dir = "uploads/";
    $original_name = basename($_FILES["avatar"]["name"]);
    $imageFileType = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $unique_name = time() . '_' . rand(1000, 9999) . '.' . $imageFileType;
    $target_file = $target_dir . $unique_name;
    $uploadOk = 1;

    // Проверка, является ли файл изображением
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Файл не является изображением.";
        $uploadOk = 0;
    }

    // Проверка размера файла
    if ($_FILES["avatar"]["size"] > 500000) {
        echo "Извините, ваш файл слишком большой.";
        $uploadOk = 0;
    }

    // Разрешенные форматы файлов
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif") {
        echo "Извините, разрешены только файлы JPG, JPEG, PNG и GIF.";
        $uploadOk = 0;
    }

    // Проверка, была ли загрузка успешной
    if ($uploadOk == 0) {
        echo "Извините, ваш файл не был загружен.";
    } else {
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            // Вызываем Python-скрипт для обрезки изображения в круг
            $python_script = "crop_to_circle.py";
            $output = shell_exec("python3 $python_script $target_file $target_file");

            // Обновляем путь к аватарке в базе данных
            $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            if (!$stmt) {
                die("Ошибка подготовки запроса: " . $conn->error);
            }
            $stmt->bind_param("si", $target_file, $user_id);
            if ($stmt->execute()) {
                echo "Файл ". htmlspecialchars($original_name). " был загружен и обрезан.";
            } else {
                echo "Ошибка: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Извините, произошла ошибка при загрузке вашего файла.";
        }
    }
}

$conn->close();
?>