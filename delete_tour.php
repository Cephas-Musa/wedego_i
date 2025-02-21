<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    try {
        // Préparation de la requête de suppression
        $stmt = $pdo->prepare("DELETE FROM tours WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Rediriger après la suppression
            header("Location: view_tour.php?success=Programme supprimé avec succès.");
            exit;
        } else {
            echo "Erreur lors de la suppression du programme.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
