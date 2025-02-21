<?php
session_start();
require 'db.php';

// Récupérer l'ID du programme
if (isset($_GET['id'])) {
    $tour_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - <?= htmlspecialchars($tour['title']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            color: #198754;
        }
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-success:hover {
            background-color: #166c43;
            border-color: #166c43;
            box-shadow: 0 3px 5px 0 #1f2e4e;
        }
        .card-img-top {
            border-radius: 10px;
        }
        h2 {
            color: #198754;
            font-weight: bold;
        }
        .btn2 {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Book for<span style="color: #1f2e4e;"> <?= htmlspecialchars($tour['title']); ?></span></h2>
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($tour['title']); ?></h5>
                        <img src="uploads/<?= htmlspecialchars($tour['image']) ?>" alt="<?= htmlspecialchars($tour['title']) ?>" class="img-fluid w-100 mb-4" style="height: 200px; object-fit:cover; width:100%; border-radius:10px">
                        <p class="card-text"><?= htmlspecialchars($tour['description']); ?></p>
                        <p class="card-text"><strong>price:</strong> <?= number_format((float)$tour['price'], 2); ?> USD</p>
                        <label for="people">Number of people:</label>
                        <input type="number" name="people" id="people" class="form-control mb-3" min="1" max="<?= $tour['available_places']; ?>" required>
                        <p class="card-text"><strong>Availability:</strong> <?= htmlspecialchars($tour['available_places']); ?> Remaining Places</p>
                        <label for="preferred-date">Prefered Date:</label>
                        <input type="date" name="preferred-date" id="preferred-date" class="form-control mb-3" required>

                        <div class="btn2">
                             <form id="reservationForm" onsubmit="redirectToWhatsApp(event)">
                                    <button type="submit" class="btn btn-success w-100">Book now by WhatsApp</button>
                             </form>
                             <form id="reservationForm1" onsubmit="redirectToEmail(event)">
                                    <button type="submit" class="btn btn-success w-100">Book now by Email</button>
                             </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function redirectToWhatsApp(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement

        // Récupération des informations saisies dans le formulaire
        const people = document.getElementById('people').value;
        const preferredDate = document.getElementById('preferred-date').value; // Récupérer la date préférée
        const tourTitre = <?= json_encode($tour['title']) ?>; // Titre de la sortie

        // Message à envoyer sur WhatsApp
        let message = `Hi Wedegoo, we come from your website, there are ${people} of us and we would like to book a place(s) for "${tourTitre}".`;

        // Ajouter la date préférée si elle est remplie
        if (preferredDate) {
            message += ` And we would like to travel on ${preferredDate}.`;
        }

        // Numéro WhatsApp Wedegoo
        const whatsappNumber = "+243995083589";

        // URL de redirection vers WhatsApp
        const whatsappURL = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;

        // Redirection vers WhatsApp
        window.location.href = whatsappURL;
    }

    function redirectToEmail(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement

        // Récupération des informations saisies dans le formulaire
        const people = document.getElementById('people').value;
        const preferredDate = document.getElementById('preferred-date').value; // Récupérer la date préférée
        const tourTitre = <?= json_encode($tour['title']) ?>; // Titre de la sortie

        // Objet et corps du mail
        let subject = `Réservation pour "${tourTitre}"`;
        let body = `Hi Wedegoo, we come from your website, there are ${people} of us and we would like to book a place(s) for "${tourTitre}".`;

        // Ajouter la date préférée si elle est remplie
        if (preferredDate) {
            body += ` And we would like to travel on ${preferredDate}.`;
        }

        // Adresse email de Wedegoo
        const email = "wedegootravel@gmail.com";

        // URL de redirection vers le client de messagerie
        const emailURL = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

        // Redirection vers le client de messagerie
        window.location.href = emailURL;
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
