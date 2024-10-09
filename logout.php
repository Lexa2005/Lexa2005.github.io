<?php
session_start();
session_destroy();
header("Location: index.php"); // Перенаправляем на главную страницу
exit();
?>