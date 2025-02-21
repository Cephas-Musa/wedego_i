<?php
require 'db.php';

// Récupérer tout le contenu de la galerie.
$gallery = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Styles externes -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@100;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
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
</head>

<body>
<?php require 'navbar.php'; ?>

    <h1 class="display-5 text-capitalize mb-3 text-center mt-4" style="color: #1f2e4e;"id="gallery">WeDeGoo <span class="text-primary">gallery</span></h1>

        <div class="grid grid-cols-3 gap-4 p-5">
            <?php foreach ($gallery as $item): ?>
                <div class="relative group" onclick="window.location.href='view_image.php?image=<?= urlencode($item['filename']); ?>'">
                    <a href="view_image.php?image=<?= urlencode($item['filename']); ?>" class="block">
                        <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $item['filename'])): ?>
                            <img src="uploads/<?= $item['filename']; ?>" alt="<?= htmlspecialchars($item['description']); ?>" class="w-full h-48 object-cover rounded-lg">
                        <?php elseif (preg_match('/\.(mp4|mov)$/i', $item['filename'])): ?>
                            <video controls class="w-full h-48 object-cover rounded-lg">
                                <source src="uploads/<?= $item['filename']; ?>" type="video/mp4">
                            </video>
                        <?php endif; ?>
                    </a>

                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-lg px-2">
                        <p class="text-center"><?= htmlspecialchars($item['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


    
    <div class="flex justify-center items-center h-[100vh] my-4  fixed top-[140px] left-11 w-[40px] h-[40px] bg-green-800 rounded-full text-white" style="z-index: 980000;">
        <a href="index.php?#title-r" class="rounded-lg bg-green-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
    </div>
    <?php require 'footer.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
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
</body>

</html>