<?php
session_start();
require_once 'db.php';

// Récupération du pays depuis l'URL
$country = $_GET['country'] ?? '';
if (!in_array($country, ['rwanda', 'uganda', 'kenya', 'tanzanie'])) {
    header('Location: dashboard.php');
    exit();
}

// Récupération des voyages pour le pays spécifique
$stmt = $pdo->prepare("SELECT * FROM trips WHERE country = ? ORDER BY created_at DESC");
$stmt->execute([$country]);
$trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voyages - <?= ucfirst($country) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .trips-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem;
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
            padding: 1.5rem;
        }
        .country-header {
            background-color: #1f2e4e;
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 10px;
            margin: 2rem;
        }
        @media (max-width: 768px) {
            .trips-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            .country-header {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="country-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0"><?= ucfirst($country) ?> - Voyages</h1>
                <div>
                    <a href="add_trips.php?country=<?= $country ?>" class="btn btn-success me-2">
                        <i class="bi bi-plus-lg"></i> Ajouter un voyage
                    </a>
                    <a href="dashboard.php" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (empty($trips)): ?>
            <div class="empty-state">
                <i class="bi bi-map" style="font-size: 3rem; color: #6c757d;"></i>
                <h3 class="mt-3">Aucun voyage disponible</h3>
                <p class="text-muted">Commencez par ajouter des voyages pour <?= ucfirst($country) ?></p>
                <a href="add_trips.php?country=<?= $country ?>" class="btn btn-primary mt-3">
                    Ajouter un voyage
                </a>
            </div>
        <?php else: ?>
            <div class="trips-container">
                <?php foreach ($trips as $trip): ?>
                    <div class="trip-card">
                        <img src="uploads/<?= htmlspecialchars($trip['image']) ?>" 
                             class="trip-image" 
                             alt="<?= htmlspecialchars($trip['title']) ?>">
                        <div class="trip-content">
                            <h5 class="mb-3"><?= htmlspecialchars($trip['title']) ?></h5>
                            <p class="text-muted mb-2">
                                <?= substr(htmlspecialchars($trip['description']), 0, 100) ?>...
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-cash"></i> 
                                <strong><?= number_format($trip['price'], 2) ?> USD</strong>
                            </p>
                            <p class="mb-3">
                                <i class="bi bi-people"></i> 
                                <?= $trip['available_places'] ?> places disponibles
                            </p>
                            <div class="d-flex gap-2">
                                <a href="edite_trips.php?id=<?= $trip['id'] ?>" class="btn btn-warning btn-sm flex-grow-1">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <button onclick="deleteTrip(<?= $trip['id'] ?>)" class="btn btn-danger btn-sm flex-grow-1">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteTrip(tripId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce voyage ?')) {
                window.location.href = `delete_trips.php?id=${tripId}`;
            }
        }
    </script>
</body>
</html>