<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Récupération des images depuis la base de données
$images = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Suppression d'une image
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT filename FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();

    if ($file) {
        // Supprime le fichier du répertoire
        unlink('uploads/' . $file['filename']);
        
        // Supprime l'entrée de la base de données
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: view_gallery.php");
    }
}

// Mise à jour d'une image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $description = htmlspecialchars($_POST['description']);

    if (!empty($_FILES['filename']['name'])) {
        $image = $_FILES['filename']['name'];
        move_uploaded_file($_FILES['filename']['tmp_name'], "uploads/" . $image);

        // Met à jour l'image et la description
        $stmt = $pdo->prepare("UPDATE gallery SET filename = ?, description = ? WHERE id = ?");
        $stmt->execute([$image, $description, $id]);
    } else {
        // Met à jour uniquement la description
        $stmt = $pdo->prepare("UPDATE gallery SET description = ? WHERE id = ?");
        $stmt->execute([$description, $id]);
    }

    header("Location: view_gallery.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }

        .gallery-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #fff;
            padding-bottom: 20px;
            text-align: center;
        }

        .gallery-item img {
            width: 100%;
            height: 90%;
            display: block;
            border-radius: 12px;
            transition: transform 0.3s;
            object-fit: cover;
        }

        .gallery-item:hover {
            transform: scale(1);
        }

        .gallery-item .description {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #265C43FF;
            color: #fff;
            text-align: center;
            padding: 3px;
            font-size: 14px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }

        .gallery-item:hover .description {
            transform: translateY(0);
        }

        .gallery-item .actions {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .gallery-item .actions a,
        .gallery-item .actions button {
            text-decoration: none;
            color: #fff;
            background-color: #198754;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .back-btn {
            text-decoration: none;
            color: #ffffff;
            background-color: #198754;
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

        .update-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <a href="add_image.php" class="back-btn"><i class="fas fa-arrow-left"></i>Back</a>
    <h1>Galerie</h1>

    <div class="gallery-container">
    <?php foreach ($images as $image): ?>
        <div class="gallery-item">
            <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $image['filename'])): ?>
                <img src="uploads/<?= htmlspecialchars($image['filename']); ?>" alt="Image">
            <?php elseif (preg_match('/\.(mp4|avi|mov)$/i', $image['filename'])): ?>
                <video controls width="100%" height="100%" style="object-fit: cover;">
                    <source src="uploads/<?= htmlspecialchars($image['filename']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
            <div class="description">
                <?= htmlspecialchars($image['description']); ?>
            </div>
            <div class="actions">
                <a href="view_gallery.php?delete=<?= $image['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette image ?');" style="background-color: #690F0FFF; color: white; text-decoration: none;">Delete</a>
                <button onclick="showUpdateForm(<?= $image['id']; ?>, '<?= htmlspecialchars($image['description']); ?>')" style="background-color: #198754">Upload</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>


    <form method="POST" enctype="multipart/form-data" class="update-form" id="update-form">
        <h2>Upload Image</h2>
        <input type="hidden" name="id" id="update-id">

        <label for="update-description">Description :</label> 
        <input type="text" name="description" id="update-description" required> <br/><br/>

        <label for="update-filename">New Image (optionnel) :</label>
        <input type="file" name="filename" id="update-filename" accept="image/*"><br/><br/>

        <button type="submit" name="update" class="back-btn" style="border: none; cursor: pointer">Update</button>
        <button type="button" onclick="closeUpdateForm()" class="back-btn" style="border: none; background-color: #690F0FFF; cursor: pointer">Cancel</button>
    </form>

    <script>
        function showUpdateForm(id, description) {
            document.getElementById('update-id').value = id;
            document.getElementById('update-description').value = description;
            document.getElementById('update-form').style.display = 'block';
        }

        function closeUpdateForm() {
            document.getElementById('update-form').style.display = 'none';
        }
    </script>
</body>
</html>
