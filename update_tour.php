<?php
session_start();
require 'db.php';

// Vérifier si l'ID du programme est fourni
if (isset($_GET['id'])) {
    $tour_id = $_GET['id'];

    // Récupérer les détails du programme à partir de la base de données
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le programme n'existe pas
    if (!$tour) {
        die("Programme non trouvé.");
    }
} else {
    die("ID non fourni.");
}

// Traiter le formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $available_places = $_POST['available_places'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Gestion du fichier image
    if ($_FILES['image']['name']) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . $image_name;

        move_uploaded_file($image_tmp_name, $image_path);
    } else {
        $image_name = $tour['image']; // Garder l'image existante
    }

    // Mise à jour dans la base de données
    $stmt = $pdo->prepare("UPDATE tours SET title = ?, description = ?, image = ?, price = ?, available_places = ?, WHERE id = ?");
    $stmt->execute([$title, $description, $image_name, $price, $available_places,$tour_id]);

    // Rediriger l'utilisateur après la mise à jour
    header("Location: view_tour.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Programme de Voyage</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles personnalisés */
        body {
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #198754;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-primary:hover {
            background-color: #166c43;
            border-color: #166c43;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center">Modify the Program</h2>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title :</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($tour['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($tour['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image (leave empty to keep the current image) :</label>
                <input type="file" name="image" id="image" class="form-control">
                <?php if ($tour['image']): ?>
                    <img src="uploads/<?= htmlspecialchars($tour['image']); ?>" alt="Image actuelle" class="img-thumbnail mt-3" width="200">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" class="form-control" value="<?= htmlspecialchars($tour['price']); ?>" required>
            </div>

            <div class="form-group">
                <label for="available_places">Number of places available:</label>
                <input type="number" name="available_places" id="available_places" class="form-control" value="<?= htmlspecialchars($tour['available_places']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
