<?php
session_start();
require 'db.php'; // Assurez-vous que db.php initialise une connexion PDO ($conn)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $price = (float)$_POST['price'];
    $available_places = (int)$_POST['available_places'];

    // Gestion de l'image
    $image = $_FILES['image']['name'];
    $imagePath = "uploads/" . $image;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // Préparation de la requête
        $sql = "INSERT INTO tours (title, description, price, available_places, image) 
                VALUES (:title, :description, :price, :available_places,:image)";
        $stmt = $pdo->prepare($sql);

        // Exécution de la requête
        if ($stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':available_places' => $available_places,
            ':image' => $image
        ])) {
            header("Location: view_tour.php");
            exit();
        } else {
            echo "Erreur lors de l'insertion des données.";
        }
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Programme de Voyage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@100;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/galerie.css" rel="stylesheet">
    <style>
        .body-r {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container-r {
            width: 70%;
            max-width: 900px;
            padding: 20px 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #198754;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: grid;
            gap: 15px;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        input, textarea, button {
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input:focus, textarea:focus {
            border-color: #198754;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        .button {
            grid-column: span 2;
            background-color: #198754;
            color: #fff;
            border: none;
            padding: 10px 0;
            font-weight: bold;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #166c43;
        }
    </style>
</head>
<body>
    <?php require 'navbar.php'; ?>
    <section class="body-r">
        <div class="container-r">
            <h2>Add a new travel Program</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Titre" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Description" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" placeholder="Price" required>
            </div>

            <div class="form-group">
                <label for="available_places">Number of places available</label>
                <input type="number" id="available_places" name="available_places" placeholder="Number of places available" required>
            </div>

            <!-- <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" required>
            </div> -->
            
            <button class="button" type="submit" name="add_tour">Add Program</button>
        </form>
    </div>
    </section>
    
    <?php require 'footer.php'; ?>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
