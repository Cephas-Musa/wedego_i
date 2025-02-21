<?php
session_start();
require 'db.php';

// Vérifie si l'utilisateur est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = $_FILES['file']['name'];
    $target = "uploads/" . basename($file);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        $stmt = $pdo->prepare("INSERT INTO gallery (filename) VALUES (:filename)");
        $stmt->bindParam(':filename', $file);
        $stmt->execute();

        echo "Field added successfully.";
    } else {
        echo "Error while downloading.";
    }

    header('Location: dashboard.php');
}
