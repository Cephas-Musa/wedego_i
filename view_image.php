<?php
session_start();
if (isset($_GET['image'])) {
    $image_file = urldecode($_GET['image']);
    $image_path = 'uploads/' . $image_file;

    // Vérifie si le fichier image existe
    if (!file_exists($image_path)) {
        echo "L'image demandée est introuvable.";
        exit;
    }
} else {
    echo "Aucune image spécifiée.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage de l'image</title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: black;
        }
        img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <img src="<?= htmlspecialchars($image_path); ?>" alt="Image">
</body>
</html>
