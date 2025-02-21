<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM trips WHERE id = ?");
$stmt->execute([$id]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trip) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $image = $trip['image']; // Garde l'ancienne image par défaut

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                // Supprime l'ancienne image si elle existe
                if ($trip['image'] && file_exists('uploads/' . $trip['image'])) {
                    unlink('uploads/' . $trip['image']);
                }
                
                $newName = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $newName);
                $image = $newName;
            }
        }

        $stmt = $pdo->prepare("
            UPDATE trips 
            SET title = ?, description = ?, price = ?, 
                available_places = ?,
                image = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['price'],
            $_POST['available_places'],
            $image,
            $id
        ]);

        header('Location: dashboard.php');
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de la modification du voyage: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un voyage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .current-image {
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="form-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Modifier le voyage</h1>
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
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($trip['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($trip['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prix (USD)</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="<?= $trip['price'] ?>" required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Places disponibles</label>
                            <input type="number" name="available_places" class="form-control" value="<?= $trip['available_places'] ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Image actuelle</label>
                            <img src="uploads/<?= htmlspecialchars($trip['image']) ?>" alt="Image actuelle" class="d-block mb-2 current-image">
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Laissez vide pour garder l'image actuelle</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Modifier le voyage</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>