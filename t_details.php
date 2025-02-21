<?php
session_start();
require 'db.php';

// Vérifier si le pays est passé dans l'URL
if (isset($_GET['country'])) {
    $selectedCountry = $_GET['country'];

    // Vérifier si le pays sélectionné est valide
    $countries = ['rwanda', 'uganda', 'kenya', 'tanzanie'];
    if (!in_array($selectedCountry, $countries)) {
        // Si le pays n'est pas valide, rediriger vers la page d'accueil ou afficher une erreur
        header('Location: index.php');
        exit;
    }

    // Récupérer les voyages pour le pays sélectionné
    $stmt = $pdo->prepare("SELECT * FROM trips WHERE country = ?");
    $stmt->execute([$selectedCountry]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si aucun pays n'est sélectionné, rediriger ou afficher un message d'erreur
    header('Location: index.php');
    exit;
}
// Tour data for each country
$tourData = [
    'rwanda' => [
        'country_name' => 'Rwanda',
        'tours' => [
            [
                'name' => "The land of a Thousand hills",

                'description' => 'Rwanda, a breathtaking country of green hills and pristine wildlife, offers a unique blend of natural beauty and cultural heritage. Known as the "Land of a Thousand Hills," Rwanda’s serene landscapes and vibrant wildlife make it an unforgettable travel destination.

Top Attractions:

Volcanoes National Park: The world-famous home of the endangered mountain gorillas. A trek through the forest to see these magnificent creatures up close is one of Africa\'s most extraordinary experiences.
Lake Kivu: One of the Great Lakes of Africa, offering pristine beaches, watersports, and charming lakeside villages.
Nyungwe Forest National Park: A haven for birdwatchers and nature lovers, with diverse flora and fauna, including chimpanzees and colobus monkeys.
Kigali: The capital city, which blends modernity with history, offering cultural museums, local art, and a thriving food scene.
Wildlife: Rwanda is known for its incredible biodiversity, including the critically endangered mountain gorillas, golden monkeys, and over 600 bird species. A trip to Rwanda is an opportunity to witness Africa\'s natural wonders firsthand.

',
                'image' => 'img/Rwandas-safari-micato.jpg',
            ]
        ]
    ],
    'uganda' => [
        'country_name' => 'Uganda',
        'tours' => [
            [
                'name' => "The pearl of Africa",

                'description' => 'Uganda, known as the "Pearl of Africa," is a stunning destination that boasts rich biodiversity, lush landscapes, and unforgettable wildlife experiences. This landlocked country offers an authentic African adventure, where visitors can explore its pristine rainforests, shimmering lakes, and encounter some of the world\'s most fascinating wildlife.

Top Attractions:

Bwindi Impenetrable Forest: Home to the endangered mountain gorillas, Bwindi is a UNESCO World Heritage site that offers visitors the rare opportunity to go on a gorilla trekking adventure and observe these magnificent creatures in their natural habitat.
Murchison Falls National Park: Famous for the powerful Murchison Falls, where the Nile River forces its way through a narrow gorge, creating a stunning waterfall. The park is also home to elephants, lions, giraffes, and many bird species.
Queen Elizabeth National Park: This park offers diverse landscapes, from savannahs to wetlands, and is home to a wide variety of animals, including tree-climbing lions, hippos, and over 600 bird species. It’s an ideal destination for a classic safari experience.
Lake Bunyonyi: A serene and beautiful lake surrounded by rolling hills. Known for its tranquil atmosphere, it’s a perfect place to relax, kayak, or explore the nearby islands.
Wildlife: Uganda is one of the best destinations in Africa for wildlife enthusiasts. Apart from the famous mountain gorillas, you can also encounter chimpanzees, elephants, lions, leopards, and a variety of birds. The country’s diverse ecosystems provide a unique chance to see a wide array of animals and experience different landscapes.

Why Visit Uganda?
Gorilla Trekking Experience: Uganda is one of the only places in the world where you can trek to see mountain gorillas in their natural habitat. The unforgettable experience of spending time with these incredible creatures is something that travelers cherish forever.
Unparalleled Biodiversity: From the rainforests of Bwindi to the savannahs of Queen Elizabeth National Park, Uganda offers a mix of ecosystems that are home to a rich diversity of wildlife, including rare species such as the golden monkey and the rare shoebill stork.
Adventure and Exploration: For those looking for more than just safaris, Uganda offers unique experiences such as hiking up the Rwenzori Mountains, bird watching, visiting the source of the Nile, and boat safaris on Lake Victoria.
Uganda offers a blend of adventure, wildlife, and stunning landscapes that promises to captivate every traveler. Whether you\'re an avid safari-goer, a hiking enthusiast, or someone who seeks cultural immersion, Uganda has something extraordinary to offer.


',
                'image' => 'img/zebra.jpg',
            ]
        ]
    ],
    'kenya' => [
        'country_name' => 'Kenya',
        'tours' => [
            [
                'name' => "The heart of Africa’s safari",

                'description' => 'Kenya is a world-renowned destination for safari enthusiasts, offering vast savannas, towering mountains, and diverse wildlife. Whether you’re seeking thrilling wildlife encounters or relaxing on pristine beaches, Kenya has something for everyone.

                                    Top Attractions:

                                    Masai Mara National Reserve: Famous for the annual Great Migration, where millions of wildebeests, zebras, and gazelles cross the Mara River, attracting lions, cheetahs, and leopards.
                                    Mount Kenya: Africa’s second-highest peak, offering stunning trekking opportunities through glaciers, forests, and alpine meadows.
                                    Amboseli National Park: A photographer’s paradise, known for its magnificent views of Mount Kilimanjaro and large elephant herds.
                                    Lamu Island: A beautiful blend of historical Swahili culture and tropical beaches, perfect for a tranquil escape.
                                    Wildlife: Kenya is home to the "Big Five" (lions, elephants, buffaloes, leopards, and rhinoceroses) as well as giraffes, cheetahs, zebras, and a variety of bird species. A safari here is an experience like no other.

',
                'image' => 'img/massai.jpg',
            ]
        ]
    ],
    'tanzanie' => [
        'country_name' => 'Tanzanie',
        'tours' => [
            [
                'name' => "The ultimate African safari",

                'description' => 'Tanzania is the epitome of an African adventure, where travelers can explore vast savannas, climb iconic mountains, and discover rich cultural heritage. From its world-famous national parks to pristine beaches, Tanzania offers unforgettable experiences for every traveler.

                                    Top Attractions:

                                    Serengeti National Park: A UNESCO World Heritage site and one of the most famous safari destinations in the world, known for the Great Migration and its rich wildlife.
                                    Mount Kilimanjaro: Africa’s tallest mountain, offering a once-in-a-lifetime trekking experience for those looking to conquer the summit or explore its foothills.
                                    Ngorongoro Crater: A stunning natural wonder, home to a wide array of wildlife, including the Big Five, all within the world’s largest inactive volcanic caldera.
                                    Zanzibar Archipelago: Famous for its pristine beaches, historical Stone Town, and vibrant coral reefs perfect for diving and snorkeling.
                                    Wildlife: Tanzania is a wildlife haven, with the Serengeti and Ngorongoro Crater being the stars of the show. The country is home to an incredible range of animals, including elephants, lions, giraffes, and cheetahs. Whether you’re on a safari or relaxing on the beach, you’ll experience Tanzania’s beauty and charm.

',
                'image' => 'img/Tanzanie.jpg',
            ]
        ]
    ]
];

// Vérifier si le pays sélectionné existe dans le tableau $tourData
if (!isset($tourData[$selectedCountry])) {
    die("Aucun tour disponible pour ce pays.");
}
// Get the current country's data
$currentCountry = $tourData[$selectedCountry];
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Details - <?php echo $currentCountry['country_name']; ?></title>
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
    <link href="css/t_details.css" rel="stylesheet">
    <style>
        .hero-r {
            position: relative;
            width: 100%;
            height: 700px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .hero-r::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.69);
            z-index: 1;
        }

        .hero-content-r {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .hero-title-r {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 1rem;
            color: #ffff;
        }
        .tw-2 {
            font-size: 4rem;
            font-weight: 500;
            text-align: center;
        }

        .back-button {
            position: absolute;
            left: 2rem;
            top: 2rem;
            padding: 0.75rem 1.5rem;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            backdrop-filter: blur(5px);
            z-index: 2;
        }

        .back-button:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .container-w {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .tours-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .tour-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .tour-content {
            padding: 1.5rem;
        }

        .tour-name {
            font-size: 2rem;
            margin-top: -40px;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .tour-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            color: #666;
        }

        .tour-description {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .book-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            width: 100%;
            text-align: center;
        }

        .book-button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .container-w {
                padding: 1rem;
            }

            .tours-grid {
                grid-template-columns: 1fr;
            }

            .hero-title-r {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body>

    <?php require 'navbar.php'; ?>

    <div class="hero-r" style="background-image: url('<?php echo $currentCountry['tours'][0]['image']; ?>');">
        <a href="index.php#tours" class="back-button">← Back</a>
        <div class="hero-content-r">
            <h1 class="hero-title-r tw-2"><?php echo $currentCountry['country_name']; ?></h1>
            <?php foreach ($currentCountry['tours'] as $tour): ?>
                        <h2 class="hero-title-r"><?php echo $tour['name']; ?></h2>
            <?php endforeach; ?>
        </div>
    </div>


            <?php foreach ($currentCountry['tours'] as $tour): ?>
                <div class="tour-card">
                    <div class="tour-content">
                        <p class="tour-description"><?php echo $tour['description']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>


            <div class="container-s" style="margin-bottom: 25px;">
        <h1 class="display-5 text-capitalize mb-3" id="title">WEDEGOO<span class="text-primary">Trips</span></h1>
        <div class="tours-container">
            <?php foreach ($trips as $trip): ?>
                <div class="tour-card">
                    <?php if ($trip['image']): ?>
                        <img class="tour-img" src="uploads/<?= htmlspecialchars($trip['image']); ?>" 
                             alt="<?= htmlspecialchars($trip['title']); ?>">
                    <?php else: ?>
                        <img class="tour-img" src="https://via.placeholder.com/600x400" 
                             alt="Image de programme de voyage">
                    <?php endif; ?>

                    <div class="tour-content">
                        <h5 class="tour-title"><?= htmlspecialchars($trip['title']); ?></h5>
                        <p class="tour-description">
                            <?= substr(htmlspecialchars($trip['description']), 0, 50); ?>...
                        </p>
                        <p class="tour-price">
                            <strong>From:</strong> <?= number_format($trip['price'], 2); ?> USD
                        </p>
                        <p class="tour-availability">
                            <strong>Available places:</strong> <?= htmlspecialchars($trip['available_places']); ?>
                        </p>
                        <a href="reservation_trips.php?id=<?= $trip['id']; ?>" class="btn-primary">Book now</a>
                    </div>
                </div>
            <?php endforeach; ?>
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
    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>