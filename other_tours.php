<?php
// session_start();
require 'db.php';

$countries = ['rwanda', 'uganda', 'kenya', 'tanzanie'];
$tripsByCountry = [];

// Récupération des voyages par pays
foreach ($countries as $country) {
    $stmt = $pdo->prepare("SELECT * FROM trips WHERE country = ?"); // Remplacer COUNT(*) par * pour récupérer les données des voyages
    $stmt->execute([$country]);
    $tripsByCountry[$country] = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les voyages d'un pays donné
}

// Informations sur les pays avec leurs descriptions et images
$countryData = [
    'rwanda' => [
        'name' => 'Rwanda',
        'description' => 'Rwanda, known as the "Land of a Thousand Hills," is a breathtakingly beautiful country in East Africa...',
        'backgroundImage' => 'url("img/Rwandas-safari-micato.jpg")'
    ],
    'uganda' => [
        'name' => 'Uganda',
        'description' => 'Uganda, often referred to as the "Pearl of Africa," is a destination full of natural beauty...',
        'backgroundImage' => 'url("img/zebra.jpg")'
    ],
    'kenya' => [
        'name' => 'Kenya',
        'description' => 'Kenya offers an unparalleled safari experience with its famous national parks like Maasai Mara...',
        'backgroundImage' => 'url("img/massai.jpg")'
    ],
    'tanzanie' => [
        'name' => 'Tanzanie',
        'description' => 'Tanzania is a dream destination for adventure seekers and nature enthusiasts...',
        'backgroundImage' => 'url("img/Tanzanie.jpg")'
    ],
];

$selectedCountry = 'rwanda';  // pays par défaut
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pays et Tours</title>
    <link rel="stylesheet" href="css/tours.css">
</head>

<body>

    <div class="container-t">
        <h1 class="display-6 text-primary" style="text-align: center;padding:20px;">Other <span style="color: #1f2e4e">tours</span> </h1>

        <!-- Section des boutons de navigation -->
        <div class="buttons-container">
            <?php foreach ($countryData as $key => $country): ?>
                <button class="country-button" data-country="<?php echo $key; ?>">
                    <?php echo $country['name']; ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Section de détails des pays -->
        <div class="country-details-r" id="country-details" style="background-image: <?php echo $countryData[$selectedCountry]['backgroundImage']; ?>;">
            <h2 id="country-name"><?php echo $countryData[$selectedCountry]['name']; ?></h2>
            <p id="country-description"><?php echo $countryData[$selectedCountry]['description']; ?></p>

            <!-- Afficher les voyages spécifiques au pays sélectionné -->
            <ul id="tours-list">
                <?php foreach ($tripsByCountry[$selectedCountry] as $trip): ?>
                    <li><?php echo $trip['name']; ?> - <?php echo $trip['description']; ?></li>
                <?php endforeach; ?>
            </ul>

            <!-- Bouton qui redirige vers la page t_details.php avec le pays sélectionné -->
            <a href="t_details.php?country=<?= $selectedCountry ?>" id="view-tours-button">View Tours</a>
        </div>

    </div>

    <script>
        // Informations sur les pays avec leurs images et descriptions
        const countryData = {
            rwanda: {
                name: 'Rwanda',
                description: 'Rwanda, known as the "Land of a Thousand Hills," is a breathtakingly beautiful country in East Africa...',
                backgroundImage: 'url("img/Rwandas-safari-micato.jpg")'
            },
            uganda: {
                name: 'Uganda',
                description: 'Uganda, often referred to as the "Pearl of Africa," is a destination full of natural beauty...',
                backgroundImage: 'url("img/zebra.jpg")'
            },
            kenya: {
                name: 'Kenya',
                description: 'Kenya offers an unparalleled safari experience with its famous national parks like Maasai Mara...',
                backgroundImage: 'url("img/massai.jpg")'
            },
            tanzanie: {
                name: 'Tanzanie',
                description: 'Tanzania is a dream destination for adventure seekers and nature enthusiasts...',
                backgroundImage: 'url("img/Tanzanie.jpg")'
            },
        };

        // Fonction pour mettre à jour la section de détails
        function updateCountryDetails(countryKey) {
            const country = countryData[countryKey];
            document.getElementById('country-name').innerText = country.name;
            document.getElementById('country-description').innerText = country.description;
            document.getElementById('country-details').style.backgroundImage = country.backgroundImage;

            // Mise à jour de la liste des voyages pour le pays sélectionné
            const toursList = document.getElementById('tours-list');
            toursList.innerHTML = ''; // Réinitialiser la liste
            fetchTours(countryKey).then(tours => {
                tours.forEach(tour => {
                    const listItem = document.createElement('li');
                    listItem.innerText = `${tour.name} - ${tour.description}`;
                    toursList.appendChild(listItem);
                });
            });
        }

        // Fonction pour récupérer les voyages depuis la base de données (via l'API ou la logique du backend)
        async function fetchTours(countryKey) {
            const response = await fetch(`get_tours.php?country=${countryKey}`);
            const data = await response.json();
            return data;
        }

        // Mettre à jour les boutons pour le pays sélectionné
        function updateButtonSelection(selectedCountry) {
            const buttons = document.querySelectorAll('.country-button');
            buttons.forEach(button => {
                button.classList.remove('selected');
                if (button.getAttribute('data-country') === selectedCountry) {
                    button.classList.add('selected');
                }
            });
        }

        // Ajout des événements sur les boutons pour afficher les informations du pays
        document.querySelectorAll('.country-button').forEach(button => {
            button.addEventListener('click', function() {
                const countryKey = button.getAttribute('data-country');
                updateCountryDetails(countryKey);
                updateButtonSelection(countryKey);
                // Mettre à jour l'URL du bouton "View Tours"
                const viewToursLink = document.getElementById('view-tours-button');
                viewToursLink.setAttribute('href', 't_details.php?country=' + countryKey);
            });
        });

        // Affichage par défaut du premier pays (ou sélectionné par l'utilisateur)
        document.addEventListener('DOMContentLoaded', () => {
            updateCountryDetails('rwanda');
            updateButtonSelection('rwanda');
        });
    </script>

</body>

</html>
