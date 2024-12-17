<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si la variable de session existe
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: ../login/index.php');
    exit;
}

// Inclure la connexion PDO
include 'connect_db.php';

// Vérification de la déconnexion si l'action est spécifiée dans l'URL
if (isset($_GET['action']) && $_GET['action'] == "logout") {
    // Déconnexion de l'utilisateur
    session_unset(); // Supprime toutes les variables de session
    session_destroy(); // Détruit la session

    // Rediriger vers la page de connexion après la déconnexion
    header('Location: ../login/index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Enregistrements</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .list-group-item {
            font-size: 16px;
        }
        .list-group-item .dropdown-toggle {
            font-weight: 600;
        }
        .dropdown-menu {
            border-radius: 8px;
        }
        .alert {
            margin-top: 20px;
            font-size: 14px;
        }
        .active {
            color: #ffffff !important;
            background-color: #0d6efd !important;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <!-- Menu de navigation -->
        <div class="col-lg-3">
            <h4 class="mb-3">Menu de Navigation</h4>
            <ul class="list-group">
                <!-- Attestation -->
                <li class="list-group-item <?= isset($_GET['route']) && in_array($_GET['route'], ['liste_attestation', 'create_attestation']) ? 'active' : '' ?>">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-decoration-none <?= isset($_GET['route']) && in_array($_GET['route'], ['liste_attestation', 'create_attestation']) ? 'active' : '' ?>" role="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                            Attestation
                        </a>
                        <ul class="dropdown-menu p-0">
                            <li><a href="?route=liste_attestation" class="dropdown-item <?= isset($_GET['route']) && $_GET['route'] == 'liste_attestation' ? 'active' : '' ?>">Liste</a></li>
                            <li><a href="?route=create_attestation" class="dropdown-item <?= isset($_GET['route']) && $_GET['route'] == 'create_attestation' ? 'active' : '' ?>">Ajouter</a></li>
                            <li><a href="?route=duplicata_attestation" class="dropdown-item <?= isset($_GET['route']) && $_GET['route'] == 'duplicata_attestation' ? 'active' : '' ?>">Duplicata</a></li>
                        </ul>
                    </div>
                </li>
                <!-- Carte Grise -->
                <li class="list-group-item <?= isset($_GET['route']) && $_GET['route'] == 'liste_carte_grise' ? 'active' : '' ?>">
                    <a href="?route=liste_carte_grise" class="dropdown-item">
                        Carte Grise
                    </a>
                </li>
                <!-- Permis National -->
                <li class="list-group-item <?= isset($_GET['route']) && $_GET['route'] == 'liste_permis_nationnal' ? 'active' : '' ?>">
                    <a href="?route=liste_permis_nationnal" class="dropdown-item">
                        Permis National
                    </a>
                </li>
                <!-- Permis International -->
                <li class="list-group-item <?= isset($_GET['route']) && $_GET['route'] == 'liste_permis_international' ? 'active' : '' ?>">
                    <a href="?route=liste_permis_international" class="dropdown-item">
                        Permis International
                    </a>
                </li>
                <!-- PV Châssis -->
                <li class="list-group-item <?= isset($_GET['route']) && in_array($_GET['route'], ['liste_pv_chassis', 'create_pv_chassis']) ? 'active' : '' ?>">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-decoration-none <?= isset($_GET['route']) && in_array($_GET['route'], ['liste_pv_chassis', 'create_pv_chassis']) ? 'active' : '' ?>" data-bs-toggle="dropdown">
                            Homologation
                        </a>
                        <ul class="dropdown-menu p-0">
                            <li><a href="?route=liste_pv_chassis" class="dropdown-item <?= isset($_GET['route']) && $_GET['route'] == 'liste_pv_chassis' ? 'active' : '' ?>">Chassis</a></li>
                            <li><a href="?route=create_pv_chassis" class="dropdown-item <?= isset($_GET['route']) && $_GET['route'] == 'create_pv_chassis' ? 'active' : '' ?>">Caractéristique</a></li>
                        </ul>
                    </div>
                </li>
                <li class="list-group-item">
                    <a href="?action=logout" class="dropdown-item">
                        Se déconnexion
                    </a>
                </li>
            </ul>
        </div>
        <!-- Contenu principal -->
        <div class="col-lg-9">
            <h4 class="mb-3">Contenu Principal</h4>
            <?php
            $routes = [
                'liste_attestation' => './attestation/liste_attestation.php',
                'create_attestation' => './attestation/create_attestation.php',
                'duplicata_attestation' => './attestation/duplicata_attestation.php',
                'edit_attestation' => './attestation/edit_attestation.php',
                'imprimer_attestation' => './attestation/attestation.php',

                'liste_carte_grise' => './carte_grise/liste_carte_grise.php',

                'liste_permis_nationnal' => './permis_national/liste_permis_nationnal.php',

                'liste_permis_international' => './permis_international/liste_permis_international.php',

                'liste_pv_chassis' => './pv_chassis/liste_pv_chassis.php',
                'create_pv_chassis' => './pv_chassis/create_pv_chassis.php',
                'edit_pv_chassis' => './pv_chassis/edit_pv_chassis.php',
            ];

            if (isset($_GET['route']) && array_key_exists($_GET['route'], $routes)) {
                include($routes[$_GET['route']]);
            } else {
                echo '<div class="alert alert-info">Sélectionnez une option dans le menu pour commencer.</div>';
            }
            ?>
        </div>
    </div>
</div>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    });
</script>
</body>
</html>
