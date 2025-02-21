<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $_FILES['filename']['name'];
    $description = htmlspecialchars($_POST['description']);

    // Vérification et déplacement du fichier dans le répertoire 'uploads'
    if (move_uploaded_file($_FILES['filename']['tmp_name'], "uploads/" . $image)) {
        $stmt = $pdo->prepare("INSERT INTO gallery (filename, description, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$image, $description]);
        header("Location: view_gallery.php");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Image</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            display: flex;
            /* flex-direction: column; */
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 70%;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-btn {
            text-decoration: none;
            color: #ffffff;
            background-color: #007bff;
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .back-btn i {
            margin-right: 8px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form label {
            font-weight: bold;
            color: #555;
        }

        form input[type="text"],
        form input[type="file"],
        form button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        form button {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i>Back</a>
        <h1>Add Image</h1>

        <form method="POST" enctype="multipart/form-data">
            <label for="description">Description (Title) :</label>
            <input type="text" name="description" id="description" placeholder="Entrez une description" required>

            <label for="filename">Field:</label>
            <input type="file" name="filename" id="filename" accept="image/*,video/*" required>

            <button type="submit">Add</button>
        </form>
    </div>
</body>
</html>
