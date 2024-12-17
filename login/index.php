<?php
// Paramètres de connexion à la base de données
include '../Immatriculation/connect_db.php';

// Début de la gestion de la connexion de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['username'];  // Nom d'utilisateur (e-mail)
    $password = $_POST['password'];  // Mot de passe

    // Validation des champs
    $errors = [];

    // Vérifier si l'email est valide
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Veuillez entrer un e-mail valide.";
    }

    // Vérifier si le mot de passe est vide
    if (empty($password)) {
        $errors[] = "Le mot de passe ne peut pas être vide.";
    }

    // Si aucune erreur, vérifier les identifiants
    if (empty($errors)) {
        // Requête pour vérifier l'utilisateur dans la base de données
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($password, $user['password'])) {
            // Mot de passe correct, démarrer une session et rediriger
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: ../Immatriculation/index.php');  // Rediriger vers le tableau de bord
            exit;
        } else {
            // Mot de passe incorrect ou utilisateur non trouvé
            $error_message = "Identifiants incorrects.";
        }
    } else {
        // Si des erreurs existent, les afficher
        $error_message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | E-service</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-theme19.css">
</head>
<body>
    <div class="form-body without-side">
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="images/graphic3.svg" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3 class="text-center">Connexion</h3>
                        <p class="text-center">Veillez entrer vos identifiant.</p>

                        <!-- Affichage de l'erreur si les identifiants sont incorrects -->
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger text-center"><?= $error_message ?></div>
                        <?php endif; ?>

                        <form action="" method="post">
                            <input class="form-control" type="text" name="username" placeholder="Adresse e-mail" >
                            <input class="form-control" type="password" name="password" placeholder="Mot de passe" >
                            <div class="form-button">
                                <center>
                                <button id="submit" type="submit" class="ibtn">Connectez-vous</button>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
