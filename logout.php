<?php
// Démarre la session pour accéder aux variables de session
session_start();

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page index.php après la déconnexion
header("Location: index.php");
exit();
?>
