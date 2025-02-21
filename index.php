<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

$stmt = $pdo->query("SELECT * FROM tours ORDER BY created_at DESC"); // Tri des programmes par date de redaction
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupération des données sous forme de tableau associatif

// Vérification que les données sont bien récupérées avant d'afficher la boucle
if (!$tours) {
    echo "<p>Aucun programme de voyage disponible.</p>";
}

// Récupération des actualités et des événements depuis la base de données.
try {
    // $news = $pdo->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    // $events = $pdo->query("SELECT * FROM events ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
    $gallery = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC LIMIT 5 ")->fetchAll();
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <title>we.de.goo</title>

    <!-- Fonts et styles externes -->
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
    <link rel="stylesheet" href="css/sortis.css">

    <style>
        .tours-container {
            display: grid;
            grid-auto-flow: column;
            grid-template-rows: 1fr;
            grid-auto-columns: minmax(395px, 1fr);
            gap: 5px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 20px 0;
        }

        /* Cache les barres de défilement sur les navigateurs modernes */
        .tours-container::-webkit-scrollbar {
            display: none;
        }

        .tours-container {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        /* Carte de programme */
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
            border-bottom: 1px solid #eee;
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

        .btn-primary {
            background-color: #198754;
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            display: block;
            width: 100%;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn-primary:hover {
            background-color: #166c43;
        }

        h2 {
            color: #198754;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        @media (max-width: 576px) {
            .card img {
                height: 150px;
            }
        }
    </style>
</head>

<body>

    <!-- Inclure les différentes sections -->
    <?php require 'navbar.php'; ?>
    <?php require 'page_acc.php'; ?>


    <div class="container-s" style="margin-bottom: 25px;">
        <h1 class="display-5 text-capitalize mb-5" id="title" style="text-align: center;color:#1f2e4e;padding-top:60px;">WEDEGOO <span class="text-primary" style="color:#1f2e4e;;">Trips</span></h1>
        <div class="tours-container">
            <?php foreach ($tours as $tour): ?>
                <div class="card">
                    <!-- Image du programme -->
                    <?php if ($tour['image']): ?>
                        <img src="uploads/<?= htmlspecialchars($tour['image']); ?>" alt="<?= htmlspecialchars($tour['title']); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/600x400" alt="Image de programme de voyage">
                    <?php endif; ?>

                    <div class="card-body">
                        <!-- Titre -->
                        <h5 class="card-title"><?= htmlspecialchars($tour['title']); ?></h5>

                        <!-- Description -->
                        <p class="card-text"><?= substr(htmlspecialchars($tour['description']), 0, 50); ?>...</p>

                        <!-- Prix -->
                        <p class="card-text"><strong>From :</strong> <?= number_format($tour['price'], 2); ?> USD</p>

                        <!-- Places disponibles -->
                        <p class="card-text"><strong> Avaible places :</strong> <?= htmlspecialchars($tour['available_places']); ?></p>

                        <!-- Bouton Réserver -->
                        <a href="reservation.php?id=<?= $tour['id']; ?>" class="btn-primary">Book now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<div id="tours">
<?php require 'other_tours.php'; ?>
</div>
    
   <div id="about">
        <?php require 'about.php'; ?>
   </div>
    <?php require 'fact.php'; ?>





    <!-- gallery -->

    <div class="container" style="margin-bottom: 50px; margin-top: 60px;">
    <h1 class="display-5 text-capitalize mb-4 text-center" id="title-r" style="color: #1f2e4e;">WeDeGoo <span class="text-primary" style="color: #1f2e4e;">gallery</span></h1>


    <div class="gallery-container">
    <!-- 3 premières images -->
    <div class="gallery-row">
        <?php foreach (array_slice($gallery, 0, 3) as $item): ?>
            <div class="gallery-item" style="height: 250px;"> <!-- Hauteur uniforme pour tous les éléments -->
                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $item['filename'])): ?>
                    <!-- Redirection pour les images -->
                    <div onclick="window.location.href='view_image.php?image=<?= urlencode($item['filename']); ?>'">
                        <img src="uploads/<?= htmlspecialchars($item['filename']); ?>" alt="<?= htmlspecialchars($item['image_title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $item['filename'])): ?>
                    <!-- Redirection pour les vidéos -->
                    <div onclick="window.location.href='view_video.php?video=<?= urlencode($item['filename']); ?>'" style="height: 250px;">
                        <video controls width="100%" height="100%" style="object-fit: cover;">
                            <source src="uploads/<?= htmlspecialchars($item['filename']); ?>" type="video/mp4">
                            <source src="uploads/<?= htmlspecialchars($item['filename']); ?>" type="video/webm">
                            <source src="uploads/<?= htmlspecialchars($item['filename']); ?>" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php else: ?>
                    <!-- Code pour les autres types de fichiers -->
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 2 dernières images -->
    <div class="gallery-row">
        <?php foreach (array_slice($gallery, 3, 2) as $item): ?>
            <div class="gallery-item" style="height: 250px;"> <!-- Hauteur uniforme pour tous les éléments -->
                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $item['filename'])): ?>
                    <!-- Redirection pour les images -->
                    <div onclick="window.location.href='view_image.php?image=<?= urlencode($item['filename']); ?>'">
                        <img src="uploads/<?= htmlspecialchars($item['filename']); ?>" alt="<?= htmlspecialchars($item['image_title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $item['filename'])): ?>
                    <!-- Redirection pour les vidéos -->
                    <div onclick="window.location.href='view_video.php?video=<?= urlencode($item['filename']); ?>'" style="height: 250px;">
                        <video controls width="100%" height="100%" style="object-fit: cover;">
                            <source src="uploads/<?= htmlspecialchars($item['filename']); ?>" type="video/mp4">
                            <source src="uploads/<?= htmlspecialchars($item['filename']); ?>" type="video/webm">
                            <source src="uploads/<?= htmlspecialchars($item['filename']); ?>" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php else: ?>
                    <!-- Code pour les autres types de fichiers -->
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="gallery.php" class="btn btn-primary mt-3 btn-more d-block text-center" style="width: 180px; position: relative; left: 50%; transform: translateX(-50%);">More images</a>
</div>
    </div>




    <?php require 'testimonial.php'; ?>

    <div id="map" style="display: flex; align-items: center; justify-content:center; margin-bottom: 20px;margin-top: 20px;">
        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m8!1m3!1d239.2129753142765!2d29.37600154819474!3d-3.351780803036329!3m2!1i1024!2i768!4f13.1!4m6!3e6!4m0!4m3!3m2!1d-3.351861966927401!2d29.376041681941665!5e1!3m2!1sfr!2sbi!4v1738200276665!5m2!1sfr!2sbi"
            width="84%"
            height="450"
            style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">

        </iframe>
    </div>
    <?php require 'footer.php'; ?>







    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
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