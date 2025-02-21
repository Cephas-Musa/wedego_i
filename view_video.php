<?php
session_start();
if (isset($_GET['video'])) {
    $video_file = urldecode($_GET['video']);
    $video_path = 'uploads/' . $video_file;

    // Vérifie si le fichier vidéo existe
    if (!file_exists($video_path)) {
        echo "La vidéo demandée est introuvable.";
        exit;
    }
} else {
    echo "Aucune vidéo spécifiée.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture de la vidéo</title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: black;
        }
        video {
            width: 100%;
            max-width: 1200px;
            height: auto;
        }
    </style>
</head>
<body>
    <video controls autoplay>
        <source src="<?= htmlspecialchars($video_path); ?>" type="video/mp4">
        Votre navigateur ne prend pas en charge la balise vidéo.
    </video>
</body>
</html>
