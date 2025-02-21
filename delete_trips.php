<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? 0;

try {
    // Récupère l'image avant la suppression
    $stmt = $pdo->prepare("SELECT image FROM trips WHERE id = ?");
    $stmt->execute([$id]);
    $trip = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($trip) {
        // Supprime l'image du serveur
        if ($trip['image'] && file_exists('uploads/' . $trip['image'])) {
            unlink('uploads/' . $trip['image']);
        }

        // Supprime l'enregistrement de la base de données
        $stmt = $pdo->prepare("DELETE FROM trips WHERE id = ?");
        $stmt->execute([$id]);
    }
} catch (PDOException $e) {
    // Log l'erreur si nécessaire
}

header('Location: view_trips.php');
exit();