<?php
session_start();
require 'db.php'; // Connexion à la base de données

// Vérification du rôle de l'utilisateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Récupérer les comptages
$toursCount = $pdo->query("SELECT COUNT(*) FROM tours")->fetchColumn();
$galleryCount = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();


$countries = ['rwanda', 'uganda', 'kenya', 'tanzanie'];
$tripsByCountry = [];

// Récupération des voyages par pays
foreach ($countries as $country) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM trips WHERE country = ?");
    $stmt->execute([$country]);
    $tripsByCountry[$country] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@100;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/galerie.css" rel="stylesheet">
    <style>
    /* Couleurs */
    :root {
        --primary-color: #00385EFF;
        --secondary-color: #198754;
        --bg-color: #f8f9fa;       
        --box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        --btn-hover-color: #155d42;
    }

    body {
        font-family: 'Lato', sans-serif;
        background-color: #f4f6f9;
        color: #333;
    }

    .container-dash {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .country-section {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-bottom: 2rem;
        padding: 1.5rem;
        border-radius: 15px;
        background-color: var(--bg-color);
        box-shadow: var(--box-shadow);
    }

    .country-header {
        background-color: var(--primary-color);
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .country-title {
        font-size: 1.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .trips-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* 2 colonnes */
        gap: 1.5rem;
    }

    .trip-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .trip-card:hover {
        transform: translateY(-5px);
    }

    .trip-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .trip-content {
        padding: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .add-trip-btn, .view-trips-btn {
        padding: 0.75rem 1.25rem;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s;
        display: inline-block;
    }

    .add-trip-btn {
        background-color: var(--secondary-color);
    }

    .add-trip-btn:hover {
        background-color: #218838;
    }

    .view-trips-btn {
        background-color: var(--primary-color);
    }

    .view-trips-btn:hover {
        background-color: #00385EFF;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 10px;
        color: #6c757d;
    }

    /* Responsive design */
    @media (max-width: 1200px) {
        .country-header {
            padding: 1rem;
        }

        .country-title {
            font-size: 1.5rem;
        }

        .trips-grid {
            grid-template-columns: repeat(2, 1fr); /* Toujours 2 colonnes */
        }
    }

    @media (max-width: 768px) {
        .country-section {
            padding: 1rem;
        }

        .add-trip-btn, .view-trips-btn {
            padding: 0.5rem 1rem;
        }

        .country-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .country-title {
            font-size: 1.25rem;
        }

        .trips-grid {
            grid-template-columns: 1fr; /* 1 colonne sur petits écrans */
        }
    }

    @media (max-width: 576px) {
        .container-dash {
            padding: 0 15px;
        }

        .country-header {
            padding: 1rem;
            text-align: center;
        }

        .country-title {
            font-size: 1.2rem;
        }

        .trips-grid {
            grid-template-columns: 1fr; /* 1 colonne sur petits écrans */
        }

        .add-trip-btn, .view-trips-btn {
            padding: 0.5rem 0.75rem;
        }

        .empty-state {
            padding: 1.5rem;
        }
    }

</style>


</head>
<body>

    <?php require 'navbar.php'; ?>

    <h1 style="text-align: center; margin-top:10px">Administrator Dashboard</h1>

    <div class="container-dash" style="
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 10px;
    ">
        <!-- Actualités -->
        <div class="box" style="
        width: 550px;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    position: relative;
        ">
            <i class="fas fa-newspaper" style="font-size: 48px;
    color: #00385EFF;"></i>
            <h3 style="font-size: 21px;margin: 10px 0;">Tours</h3>
            <p><?= $toursCount ?> published</p>
            <a href="ajouter_tour.php" class="btn btn-add" style="display: inline-block;
    padding: 5px 30px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    color: #ffffff;
    text-decoration: none;
    font-weight: bold;
    background-color: #198754;
    ">Ajouter</a>
            <a href="view_tour.php" class="btn btn-view" style="display: inline-block;
    padding: 5px 30px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    color: #ffffff;
    text-decoration: none;
    font-weight: bold;
    background-color: #00385EFF;
    ">Lookr</a>
        </div>

        <!-- Galerie -->
        <div class="box" style="width: 550px; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; position: relative;">
    <i class="fas fa-image" style="font-size: 48px; color: #00385EFF;"></i>
    <h3 style="font-size: 21px;margin: 10px 0;">Gallery</h3>
    <p><?= $galleryCount ?> images</p>
    <a href="add_image.php" class="btn btn-add" style="display: inline-block; padding: 5px 30px; margin: 5px; border: none; border-radius: 5px; color: #ffffff; text-decoration: none; font-weight: bold; background-color: #198754;">Ajouter</a>
    <a href="view_gallery.php" class="btn btn-view" style="display: inline-block; padding: 5px 30px; margin: 5px; border: none; border-radius: 5px; color: #ffffff; text-decoration: none; font-weight: bold; background-color: #00385EFF;">Voir</a>
</div>


<!-- sec -->



<div class="container-dash py-4">

        <?php foreach ($countries as $country): ?>
            <div class="country-section">
                <div class="country-header">
                    <h2 class="mb-0" style="color: #ffffff;"><?= ucfirst($country) ?></h2>
                </div>

                <div class="stats-container">
                    <div class="stat-item">
                        <h3 class="mb-0"><?= $tripsByCountry[$country] ?></h3>
                        <p class="text-muted mb-0">Voyages disponibles</p>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="add_trips.php?country=<?= $country ?>" class="btn btn-action btn-add text-decoration-none">
                        <i class="bi bi-plus-lg"></i> Ajouter un voyage
                    </a>
                    <a href="view_trips.php?country=<?= $country ?>" class="btn btn-action btn-view text-decoration-none">
                        <i class="bi bi-eye"></i> Voir les voyages
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>









    <script>
        function deleteTrip(tripId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce voyage ?')) {
                window.location.href = `delete_trips.php?id=${tripId}`;
            }
        }
    </script>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script src="like.js"></script>
</body>
</html>
