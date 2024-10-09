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
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хешируем пароль

// Обработка загрузки аватарки
$avatar = null;
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
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
            $avatar = $target_file;
        } else {
            echo "Извините, произошла ошибка при загрузке вашего файла.";
        }
    }
}

// Подготавливаем и выполняем SQL-запрос
$stmt = $conn->prepare("INSERT INTO users (name, email, password, avatar) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $password, $avatar);

if ($stmt->execute()) {
    echo "<p>Регистрация прошла успешно!</p>";
    echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3000); // Перенаправление через 3 секунды
          </script>";
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>