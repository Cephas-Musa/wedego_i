<?php
session_start();
require_once 'db.php';


$country = $_GET['country'] ?? '';
if (!in_array($country, ['rwanda', 'uganda', 'kenya', 'tanzanie'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO trips (title, description, price, available_places, image, country, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        // Gestion de l'upload d'image
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $newName = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $newName);
                $image = $newName;
            }
        }

        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['price'],
            $_POST['available_places'],
            $image,
            $country
        ]);

        header('Location: dashboard.php');
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout du voyage: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un voyage - <?= ucfirst($country) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="form-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Ajouter un voyage - <?= ucfirst($country) ?></h1>
                <a href="dashboard.php" class="btn btn-secondary">Retour</a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prix (USD)</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Places disponibles</label>
                            <input type="number" name="available_places" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Ajouter le voyage</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>