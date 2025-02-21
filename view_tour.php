<?php
session_start();
require 'db.php'; // Assurez-vous que ce fichier contient votre connexion à la base de données

try {
    // Récupération des programmes de voyage depuis la base de données
    $stmt = $pdo->query("SELECT * FROM tours");
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of trip progams</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@100;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/galerie.css" rel="stylesheet">
    <style>
        /* Styles pour la section */
        .tours-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #198754;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            text-align: center;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #198754;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #166c43;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b02a37;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
        }

        h2 {
            color: #198754;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php require 'navbar.php'; ?>

    <div class="container">
        <h2>List of trip progams</h2>
        <div class="tours-container">
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="card">
                        <!-- Image du programme -->
                        <?php if (!empty($tour['image'])): ?>
                            <img src="<?= htmlspecialchars('uploads/' . $tour['image']); ?>" alt="">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/600x400" alt="Image de programme de voyage">
                        <?php endif; ?>

                        <div class="card-body">
                            <!-- Titre -->
                            <h5 class="card-title"><?= htmlspecialchars($tour['title']); ?></h5>

                            <!-- Description -->
                            <p class="card-text"><?= substr(htmlspecialchars($tour['description']), 0, 150); ?>...</p>

                            <!-- Prix -->
                            <p class="card-text"><strong>from:</strong> <?= number_format($tour['price'], 2); ?> USD</p>

                            <!-- Places disponibles -->
                            <p class="card-text"><strong>Available Places:</strong> <?= htmlspecialchars($tour['available_places']); ?></p>

                            <!-- Boutons Update et Delete -->
                            <div class="btn-group">
                                <!-- Bouton de mise à jour -->
                                <a href="update_tour.php?id=<?= $tour['id']; ?>" class="btn btn-primary">Modify</a>

                                <!-- Formulaire pour supprimer le tour -->
                                <form method="POST" action="delete_tour.php" onsubmit="return confirm('Are you sure you want to delete this program ?');">
                                    <input type="hidden" name="id" value="<?= $tour['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No trip program available</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <?php require 'footer.php'; ?>

</body>
</html>
