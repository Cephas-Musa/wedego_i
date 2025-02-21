<?php
$current_page = basename($_SERVER['PHP_SELF']);

$is_logged_in = isset($_SESSION['username']);
$profile_picture = $is_logged_in && !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : null;
?>

<style>
    .nav-link.active {
        color: #047857 !important;
        font-weight: semi-bold;
        border-bottom: 1px solid #047857;
    }

    .profile-initial {
        background-color: #047857;
        color: white;
        font-weight: bold;
    }

    .rounded-circle {
        border-radius: 50%;
    }

    .profile-icon {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
</style>

<!-- Spinner Start -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<!-- Spinner End -->

<!-- Topbar Start -->
<div class="container-fluid topbar bg-secondary d-none d-xl-block w-100">
    <div class="container">
        <div class="row gx-0 align-items-center" style="height: 45px;">
            <div class="col-lg-6 text-center text-lg-start mb-lg-0">
                <div class="d-flex flex-wrap">
                    <a href="#map" class="text-muted me-4"><i class="fas fa-map-marker-alt text-primary me-2"></i>Location</a>
                    <a href="#" class="text-muted me-4"><i class="fas fa-phone-alt text-primary me-2"></i>+257 66309039</a>
                    <a href="mailto:wedegootravel@gmail.com" class="text-muted me-0"><i class="fas fa-envelope text-primary me-2"></i>wedegootravel@gmail.com</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-end">
                <div class="d-flex align-items-center justify-content-end">
                    <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/w.e.d.e.g.o.o" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-light btn-sm-square rounded-circle me-0"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->

<div class="container-fluid nav-bar sticky-top px-0 px-lg-4 py-2 py-lg-0">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <!-- Logo -->
            <a href="#" class="navbar-brand p-0">
                <h1 class="display-6 text-primary">Wedegoo<span style="color: #1f2e4e;">Travel</span></h1>
            </a>

            <!-- Bouton pour le menu responsive -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>

            <!-- Liens de navigation -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto py-0" style="padding-left: 51%;">

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="index.php" class="nav-item nav-link <?= $current_page === 'index.php' ? 'active' : ''; ?>" id="accueil">Accueil</a>

                        <a href="dashboard.php" class="nav-item nav-link <?= $current_page === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                        <a href="gallery.php" class="nav-item nav-link <?= $current_page === 'gallery.php' ? 'active' : ''; ?>">Galerie</a>
                    <?php endif; ?>
                </div>

                <!-- Icône de contact / Profil -->
                <div id="icon-contact" class="profile-icon rounded-circle" style="<?= $profile_picture ? '' : 'background-color: #047857;'; ?>">
                    <?php if ($profile_picture): ?>
                        <img src="uploads/<?= filter_var($profile_picture); ?>" alt="Profile" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span class="profile-initial">
                            <?= isset($_SESSION['username']) && !empty($_SESSION['username']) ? strtoupper($_SESSION['username'][0]) : ''; ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Menu déroulant -->
                <div id="menu-dropdown" class="dropdown-menu" style="display: none; position: absolute; right: 50px; top: 65px;">
    <?php if ($is_logged_in): ?>
        <a href="account.php" class="dropdown-item">
            <i class="bi bi-person"></i> Account management
        </a>
        <a href="logout.php" class="dropdown-item">
            <i class="bi bi-box-arrow-right"></i> Deconnection
        </a>
    <?php else: ?>
        <a href="login.php" class="dropdown-item">
            <i class="bi bi-box-arrow-in-right"></i> Connection
        </a>
        <a href="register.php" class="dropdown-item">
            <i class="bi bi-person-plus"></i> Inscription
        </a>
    <?php endif; ?>
</div>

            </div>
        </nav>
    </div>
</div>

<script>
    // Masque le lien 'Accueil' si l'utilisateur n'est pas connecté
    const isLoggedIn = <?= json_encode($is_logged_in); ?>;
    const accueilLink = document.getElementById('accueil');
    if (accueilLink && !isLoggedIn) {
        accueilLink.style.display = 'none';
    }

    // Gestion du menu déroulant sur l'icône de contact/profil
    const contactIcon = document.getElementById('icon-contact');
    const menuDropdown = document.getElementById('menu-dropdown');

    contactIcon.addEventListener('click', () => {
        menuDropdown.style.display = menuDropdown.style.display === 'none' ? 'block' : 'none';
    });

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', (event) => {
        if (!contactIcon.contains(event.target) && !menuDropdown.contains(event.target)) {
            menuDropdown.style.display = 'none';
        }
    });





</script>