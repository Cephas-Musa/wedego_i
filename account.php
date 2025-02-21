<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

// Redirige vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];
    $profileImage = $_FILES['profile_image'];

    // Mise à jour du nom d'utilisateur
    if (!empty($username)) {
        $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $_SESSION['username'] = $username; // Mettez à jour la session avec le nouveau nom d'utilisateur
    }

    // Mise à jour du mot de passe
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }
    // Redirection après toutes les mises à jour
    header('Location: account.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion de compte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@100;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <?php require 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Account management</h1>

        <form method="POST" enctype="multipart/form-data" class="form-group">
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" class="form-control mb-3"
                value="<?= htmlspecialchars($_SESSION['username']); ?>" required>

            <label for="new_password">New PassWord</label>
            <input type="password" id="new_password" name="new_password" class="form-control mb-3">
            <button type="submit" class="btn btn-primary">Updat</button>
        </form>
    </div>
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