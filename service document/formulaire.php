<?php
// Inclure la connexion PDO
include '../Immatriculation/connect_db.php';

try {
  // Récupération des options pour chaque champ
  $marques = $conn->query("SELECT * FROM marques")->fetchAll(PDO::FETCH_ASSOC);
  $genres = $conn->query("SELECT * FROM genres")->fetchAll(PDO::FETCH_ASSOC);
  $carrosseries = $conn->query("SELECT * FROM carrosseries")->fetchAll(PDO::FETCH_ASSOC);
  $energies = $conn->query("SELECT * FROM energies")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}
$errors = [];
$success = '';
// Vérification si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action']) && $_POST['action'] == "carte_grise") {
    // Récupération et validation des données
    $immat = htmlspecialchars(trim($_POST['immat']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $chassis = htmlspecialchars(trim($_POST['chassis']));
    $marque = htmlspecialchars(trim($_POST['marque']));
    $genre = htmlspecialchars(trim($_POST['genre']));
    $carrosserie = htmlspecialchars(trim($_POST['carrosserie']));
    $energie = htmlspecialchars(trim($_POST['energie']));
    $model = htmlspecialchars(trim($_POST['model']));
    $couleur = htmlspecialchars(trim($_POST['couleur']));
    $place = (int) $_POST['place'];
    $ptac = (int) $_POST['ptac'];
    $puissance = (int) $_POST['puissance'];
    $amc = $_POST['amc'];
    $pc = (int) $_POST['pc'];
    $cu = (int) $_POST['cu'];
    $date_cg = $_POST['date_cg'];
  
    // Vérification des champs obligatoires
    if (empty($immat)) $errors[] = "L'immatriculation est obligatoire.";
    if (empty($nom)) $errors[] = "Le nom est obligatoire.";
    if (empty($prenom)) $errors[] = "Le prénom est obligatoire.";
    if (empty($telephone)) $errors[] = "Le numéro de téléphone est obligatoire.";
    if (empty($chassis)) $errors[] = "Le numéro de châssis est obligatoire.";
    if (empty($marque)) $errors[] = "La marque est obligatoire.";
    if (empty($genre)) $errors[] = "Le genre est obligatoire.";
    if (empty($carrosserie)) $errors[] = "La carrosserie est obligatoire.";
    if (empty($energie)) $errors[] = "L'énergie est obligatoire.";
    if (empty($model)) $errors[] = "Le modèle est obligatoire.";
    if (empty($couleur)) $errors[] = "La couleur est obligatoire.";
    if ($place <= 0) $errors[] = "Le nombre de places doit être supérieur à 0.";
    if ($ptac <= 0) $errors[] = "Le PTAC doit être supérieur à 0.";
    if ($puissance <= 0) $errors[] = "La puissance doit être supérieure à 0.";
    if ($pc <= 0) $errors[] = "Le PC doit être supérieur à 0.";
    if ($cu <= 0) $errors[] = "Le CU doit être supérieur à 0.";
  
    if((empty($errors))) {
        // Insertion dans la base de données
        try {
            $stmt = $conn->prepare("INSERT INTO carte_grise (immat, nom, prenom, telephone, chassis, marque, genre, carrosserie, energie, model, couleur, place, ptac, puissance, amc, pc, cu, date_cg)
                                    VALUES (:immat, :nom, :prenom, :telephone, :chassis, :marque, :genre, :carrosserie, :energie, :model, :couleur, :place, :ptac, :puissance, :amc, :pc, :cu, :date_cg)");
            $stmt->execute([
                ':immat' => $immat,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':telephone' => $telephone,
                ':chassis' => $chassis,
                ':marque' => $marque,
                ':genre' => $genre,
                ':carrosserie' => $carrosserie,
                ':energie' => $energie,
                ':model' => $model,
                ':couleur' => $couleur,
                ':place' => $place,
                ':ptac' => $ptac,
                ':puissance' => $puissance,
                ':amc' => $amc,
                ':pc' => $pc,
                ':cu' => $cu,
                ':date_cg' => $date_cg,
            ]);
            $errors = [];
            $success = "La demande de carte grise a été enregistrée avec succès.";
            header('location:formulaire.php?page=carte_grise&&success='. $success);
        } catch (PDOException $e) {
          $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
  }

  if (isset($_POST['action']) && $_POST['action'] == "permis_national") {
    // Dossier pour l'upload des fichiers
    $upload_dir = "uploads/permis_national/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Récupération des données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $date_naissance = $_POST['date_naissance'];
    $lieu_naissance = htmlspecialchars(trim($_POST['lieu_naissance']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $domicile = htmlspecialchars(trim($_POST['domicile']));
    $categorie = $_POST['categorie'];
    $date_pc = $_POST['date_pc'];

    // Gestion des fichiers
    $photo = $_FILES['photo'];
    $copie_pc = $_FILES['copie_pc'];

    $errors = [];

    // Validation des champs texte
    if (empty($nom) || !preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $nom)) {
        $errors[] = "Le nom est invalide. Veuillez entrer un nom valide.";
    }
    if (empty($prenom) || !preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $prenom)) {
        $errors[] = "Le prénom est invalide. Veuillez entrer un prénom valide.";
    }
    if (empty($lieu_naissance) || !preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $lieu_naissance)) {
        $errors[] = "Le lieu de naissance est invalide. Veuillez entrer un lieu valide.";
    }
    if (empty($domicile)) {
        $errors[] = "Le domicile est obligatoire.";
    }
    if (empty($categorie)) {
        $errors[] = "La catégorie du permis est obligatoire.";
    }

    // Validation de la date de naissance
    if (empty($date_naissance) || !strtotime($date_naissance)) {
        $errors[] = "La date de naissance est invalide.";
    }

    // Validation du numéro de téléphone
    if (empty($telephone) || !preg_match("/^\+227\s\d{8}$/", $telephone)) {
        $errors[] = "Le numéro de téléphone est invalide. Format attendu : +227 97000000.";
    }

    // Validation de la date de la pièce d'identité
    if (empty($date_pc) || !strtotime($date_pc)) {
        $errors[] = "La date de la pièce d'identité est invalide.";
    }

    // Validation des fichiers (photo)
    if ($photo['size'] > 2 * 1024 * 1024) {
        $errors[] = "La photo ne doit pas dépasser 2MB.";
    } elseif (!in_array(strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
        $errors[] = "La photo doit être au format JPG ou PNG.";
    }

    // Validation des fichiers (copie pièce d'identité)
    if ($copie_pc['size'] > 2 * 1024 * 1024) {
        $errors[] = "La copie de la pièce d'identité ne doit pas dépasser 2MB.";
    } elseif (!in_array(strtolower(pathinfo($copie_pc['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'pdf'])) {
        $errors[] = "La copie de la pièce doit être au format PDF ou JPG.";
    }

    if (empty($errors)) {
        // Déplacement des fichiers uploadés
        $photo_path = $upload_dir . uniqid() . "_" . basename($photo['name']);
        $copie_pc_path = $upload_dir . uniqid() . "_" . basename($copie_pc['name']);

        move_uploaded_file($photo['tmp_name'], $photo_path);
        move_uploaded_file($copie_pc['tmp_name'], $copie_pc_path);

        try {
            // Préparation de la requête SQL
            $stmt = $conn->prepare("INSERT INTO permis (nom, prenom, date_naissance, lieu_naissance, domicile, categorie, telephone, photo, copie_pc, date_pc) 
                                    VALUES (:nom, :prenom, :date_naissance, :lieu_naissance, :domicile, :categorie, :telephone, :photo, :copie_pc, :date_pc)");

            // Liaison des paramètres
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':lieu_naissance', $lieu_naissance, PDO::PARAM_STR);
            $stmt->bindParam(':domicile', $domicile, PDO::PARAM_STR);
            $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photo_path, PDO::PARAM_STR);
            $stmt->bindParam(':copie_pc', $copie_pc_path, PDO::PARAM_STR);
            $stmt->bindParam(':date_pc', $date_pc);

            // Exécution de la requête
            $stmt->execute();

            $errors = [];
            // Message de succès
            $success = "Votre demande de prmis de conduite national a été enregistrée avec succès.";
            header('location:formulaire.php?page=permis_national&&success='. $success);
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
  }

  if (isset($_POST['action']) && $_POST['action'] == "permis_international") {
    // Dossier pour l'upload des fichiers
    $upload_dir = "uploads/permis_international/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Récupération des données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $date_naissance = $_POST['date_naissance'];
    $lieu_naissance = htmlspecialchars(trim($_POST['lieu_naissance']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $domicile = htmlspecialchars(trim($_POST['domicile']));
    $categorie = $_POST['categorie'];
    $date_pc = $_POST['date_pc'];

    // Gestion des fichiers
    $photo = $_FILES['photo'];
    $copie_pc = $_FILES['copie_pc'];
    $copie_permis = $_FILES['copie_permis'];

    $errors = [];

    // Validation des champs texte
    if (empty($nom) || !preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $nom)) {
        $errors[] = "Le nom est invalide. Veuillez entrer un nom valide.";
    }
    if (empty($prenom) || !preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $prenom)) {
        $errors[] = "Le prénom est invalide. Veuillez entrer un prénom valide.";
    }
    if (empty($lieu_naissance) || !preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $lieu_naissance)) {
        $errors[] = "Le lieu de naissance est invalide. Veuillez entrer un lieu valide.";
    }
    if (empty($domicile)) {
        $errors[] = "Le domicile est obligatoire.";
    }
    if (empty($categorie)) {
        $errors[] = "La catégorie du permis est obligatoire.";
    }

    // Validation de la date de naissance
    if (empty($date_naissance) || !strtotime($date_naissance)) {
        $errors[] = "La date de naissance est invalide.";
    }

    // Validation du numéro de téléphone
    if (empty($telephone) || !preg_match("/^\+227\s\d{8}$/", $telephone)) {
        $errors[] = "Le numéro de téléphone est invalide. Format attendu : +227 97000000.";
    }

    // Validation de la date de la pièce d'identité
    if (empty($date_pc) || !strtotime($date_pc)) {
        $errors[] = "La date de la pièce d'identité est invalide.";
    }

    // Validation des fichiers (photo)
    if ($photo['size'] > 2 * 1024 * 1024) {
        $errors[] = "La photo ne doit pas dépasser 2MB.";
    } elseif (!in_array(strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
        $errors[] = "La photo doit être au format JPG ou PNG.";
    }

    // Validation des fichiers (copie pièce d'identité)
    if ($copie_pc['size'] > 2 * 1024 * 1024) {
        $errors[] = "La copie de la pièce d'identité ne doit pas dépasser 2MB.";
    } elseif (!in_array(strtolower(pathinfo($copie_pc['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'pdf'])) {
        $errors[] = "La copie de la pièce doit être au format PDF ou JPG.";
    }

    if ($copie_permis['size'] > 2 * 1024 * 1024) {
      $errors[] = "La copie du permis national ne doit pas dépasser 2MB.";
    } elseif (!in_array(strtolower(pathinfo($copie_permis['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'pdf'])) {
        $errors[] = "La copie du permi doit être au format PDF ou JPG.";
    }

    if (empty($errors)) {
        // Déplacement des fichiers uploadés
        $photo_path = $upload_dir . uniqid() . "_" . basename($photo['name']);
        $copie_pc_path = $upload_dir . uniqid() . "_" . basename($copie_pc['name']);
        $copie_permis_path = $upload_dir . uniqid() . "_" . basename($copie_permis['name']);

        move_uploaded_file($photo['tmp_name'], $photo_path);
        move_uploaded_file($copie_pc['tmp_name'], $copie_pc_path);
        move_uploaded_file($copie_permis['tmp_name'], $copie_permis_path);

        try {
            // Préparation de la requête SQL
            $stmt = $conn->prepare("INSERT INTO permis (nom, prenom, date_naissance, lieu_naissance, domicile, categorie, telephone, photo, copie_pc, copie_permis, date_pc) 
                                    VALUES (:nom, :prenom, :date_naissance, :lieu_naissance, :domicile, :categorie, :telephone, :photo, :copie_pc, :copie_permis, :date_pc)");

            // Liaison des paramètres
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':lieu_naissance', $lieu_naissance, PDO::PARAM_STR);
            $stmt->bindParam(':domicile', $domicile, PDO::PARAM_STR);
            $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photo_path, PDO::PARAM_STR);
            $stmt->bindParam(':copie_pc', $copie_pc_path, PDO::PARAM_STR);
            $stmt->bindParam(':copie_permis', $copie_permis_path, PDO::PARAM_STR);
            $stmt->bindParam(':date_pc', $date_pc);

            // Exécution de la requête
            $stmt->execute();

            $errors = [];
            // Message de succès
            $success = "Votre demande de prmis de conduite national a été enregistrée avec succès.";
            header('location:formulaire.php?page=permis_international&&success='. $success);
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
  }

}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>E-service</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

<!-- Template Main CSS File -->
<link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
  <main id="main">
    <section id="breadcrumbs" class="breadcrumbs" style="margin-top: 0px !important;">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <h2>E-services</h2>
          <ol>
            <li><a href="index.php">Accueil</a></li>
            <li><?php if (isset($_GET['page'])) {
              echo $_GET['page'];
            }  ?></li>
          </ol>
        </div>
      </div>
    </section>

    <div class="container mt-5">
      <?php
      if (isset($_GET['page'])) {
        switch ($_GET['page']) {
          case "carte_grise":
            ?>
            <!-- Section Carte Grise -->
            <section id="carte_grise" class="about">
              <div class="container">

                <!-- Information utilisateur -->
                <div class="row content">
                  <div class="col-lg-6">
                    <h2>Demande de Carte Grise</h2>
                    <h3>Veuillez suivre les étapes ci-dessous pour compléter votre demande.</h3>
                  </div>
                  <div class="col-lg-6 pt-4 pt-lg-0">
                    <p>
                      Assurez-vous de fournir des informations exactes pour faciliter le traitement de votre demande.
                    </p>
                    <ul>
                      <li><i class="ri-check-double-line"></i> Fournissez vos informations personnelles complètes.</li>
                      <li><i class="ri-check-double-line"></i> Informations sur le véhicule : immatriculation, modèle, couleur, etc.</li>
                      <li><i class="ri-check-double-line"></i> Vérifiez vos informations avant soumission.</li>
                    </ul>
                    <p class="fst-italic">
                      Note : Toute fausse information peut entraîner le rejet de votre demande.
                    </p>
                  </div>
                </div>

                <!-- Formulaire -->
                <div class="row mt-5 justify-content-center">
                    <div class="col-lg-10 mt-5 mt-lg-0">
                    <?php 
                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                echo '<p class="alert alert-danger">'. $error .'</p>';
                            }
                        }
                        if (isset($_GET['success'])) {
                          echo '<p class="alert alert-success">'. $_GET['success'] .'</p>';
                        }
                      ?>
                      <div class="card">
                        <div class="card-header">
                          <h5 class="text-center">Formulaire de Demande de Carte Grise</h5>
                        </div>
                        <div class="card-body">
                          <form action="" method="post">
                            <input type="hidden" name="action" value="carte_grise">
                            <div class="form-group">
                              <label for="immat">Immatriculation</label>
                              <input type="text" name="immat" class="form-control" id="immat" placeholder="Immatriculation" required>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-6 form-group">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" class="form-control" id="nom" placeholder="Nom" required>
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Prénom" required>
                              </div>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-6 form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="text" name="telephone" class="form-control" id="telephone" placeholder="Téléphone" required>
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="chassis">Numéro de Châssis</label>
                                <input type="text" name="chassis" class="form-control" id="chassis" placeholder="Numéro de Châssis" required>
                              </div>
                            </div>
                            <div class="row">
                              <!-- Exemple pour Marques -->
                              <div class="col-md-6 form-group">
                                <label for="marque">Marque</label>
                                <select name="marque" id="marque" class="form-control" required>
                                  <option value="" disabled selected>Choisissez une marque</option>
                                  <?php foreach ($marques as $marque): ?>
                                    <option value="<?= $marque['id']; ?>"><?= htmlspecialchars($marque['nom']); ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>

                              <!-- Exemple pour Genres -->
                              <div class="col-md-6 form-group">
                                <label for="genre">Genre</label>
                                <select name="genre" id="genre" class="form-control" required>
                                  <option value="" disabled selected>Choisissez un genre</option>
                                  <?php foreach ($genres as $genre): ?>
                                    <option value="<?= $genre['id']; ?>"><?= htmlspecialchars($genre['libelle']); ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                            </div>

                            <!-- Autres listes -->
                            <div class="row mt-3">
                              <div class="col-md-6 form-group">
                                <label for="carrosserie">Carrosserie</label>
                                <select name="carrosserie" id="carrosserie" class="form-control" required>
                                  <option value="" disabled selected>Choisissez une carrosserie</option>
                                  <?php foreach ($carrosseries as $carrosserie): ?>
                                    <option value="<?= $carrosserie['id']; ?>"><?= htmlspecialchars($carrosserie['type']); ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="energie">Énergie</label>
                                <select name="energie" id="energie" class="form-control" required>
                                  <option value="" disabled selected>Choisissez une énergie</option>
                                  <?php foreach ($energies as $energie): ?>
                                    <option value="<?= $energie['id']; ?>"><?= htmlspecialchars($energie['nom']); ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                            </div>

                            <div class="row mt-3">
                              <div class="col-md-6 form-group">
                                <label for="model">Modèle</label>
                                <input type="text" name="model" class="form-control" id="model" placeholder="Modèle" required>
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="couleur">Couleur</label>
                                <input type="text" name="couleur" class="form-control" id="couleur" placeholder="Couleur" required>
                              </div>
                            </div>

                            <div class="row mt-3">
                              <div class="col-md-6 form-group">
                                <label for="place">Nombre de Places</label>
                                <input type="number" name="place" class="form-control" id="place" placeholder="Nombre de Places" required>
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="ptac">PTAC (kg)</label>
                                <input type="number" name="ptac" class="form-control" id="ptac" placeholder="PTAC en kg" required>
                              </div>
                            </div>

                            <div class="row mt-3">
                              <!-- Champ Puissance -->
                              <div class="col-md-6 form-group">
                                <label for="puissance">Puissance</label>
                                <input type="number" name="puissance" id="puissance" class="form-control" placeholder="Entrez la puissance (CV)" required>
                              </div>

                              <!-- Champ AMC (date) -->
                              <div class="col-md-6 form-group">
                                <label for="amc">Date AMC</label>
                                <input type="date" name="amc" id="amc" class="form-control" required>
                              </div>
                            </div>

                            <div class="row mt-3">
                              <!-- Champ PC -->
                              <div class="col-md-6 form-group">
                                <label for="pc">PC</label>
                                <input type="number" name="pc" id="pc" class="form-control" placeholder="Entrez le PC (kg)" required>
                              </div>

                              <!-- Champ CU -->
                              <div class="col-md-6 form-group">
                                <label for="cu">CU</label>
                                <input type="number" name="cu" id="cu" class="form-control" placeholder="Entrez le CU (kg)" required>
                              </div>
                            </div>


                            <div class="form-group mt-3">
                              <label for="date_cg">Date de la Carte Grise</label>
                              <input type="date" name="date_cg" class="form-control" id="date_cg" required>
                            </div>

                            <div class="text-center mt-4">
                              <button type="submit" class="btn btn-primary">Envoyer</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                </div>

              </div>
            </section>
            <?php
            break;

          case "permis_national":
            ?>
            <!-- ======= Permis National Section ======= -->
            <section id="permis_national" class="about">
              <div class="container">

                <!-- Information utilisateur -->
                <div class="row content">
                  <div class="col-lg-6">
                    <h2>Demande de Permis National</h2>
                    <h3>Veuillez suivre les étapes ci-dessous pour compléter votre demande.</h3>
                  </div>
                  <div class="col-lg-6 pt-4 pt-lg-0">
                    <p>
                      Assurez-vous de fournir des informations exactes et de télécharger tous les documents nécessaires pour faciliter le traitement de votre demande.
                    </p>
                    <ul>
                      <li><i class="ri-check-double-line"></i> Fournissez vos informations personnelles complètes.</li>
                      <li><i class="ri-check-double-line"></i> Téléchargez une copie de votre pièce d'identité et votre photo.</li>
                      <li><i class="ri-check-double-line"></i> Vérifiez vos informations avant soumission.</li>
                    </ul>
                    <p class="fst-italic">
                      Note : Toute fausse information peut entraîner le rejet de votre demande.
                    </p>
                  </div>
                </div>

                <!-- Formulaire -->
                <div class="row mt-5 justify-content-center">
                    <div class="col-lg-10 mt-5 mt-lg-0">
                    <?php 
                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                echo '<p class="alert alert-danger">'. $error .'</p>';
                            }
                        }
                        if (isset($_GET['success'])) {
                          echo '<p class="alert alert-success">'. $_GET['success'] .'</p>';
                        }
                      ?>
                      <div class="card">
                        <div class="card-header">
                          <h5 class="text-center">Formulaire de Demande de Permis National</h5>
                        </div>
                        <div class="card-body">
                          <form action="" method="post" enctype="multipart/form-data" role="form">
                            <input type="hidden" name="action" value="permis_national">
                            <div class="row">
                              <div class="col-md-6 form-group">
                              <label for="nom">Nom</label>
                              <input type="text" name="nom" class="form-control" id="nom" placeholder="Entrez votre Nom" required>
                              </div>
                              <div class="col-md-6 form-group mt-3 mt-md-0">
                              <label for="prenom">Prénom</label>
                                <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Entrez votre Prénom" required>
                              </div>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-6 form-group">
                              <label for="date_naissance">Date de naissance</label>
                                <input type="date" name="date_naissance" class="form-control" id="date_naissance" placeholder="Entrez votre Date de Naissance" required>
                              </div>
                              <div class="col-md-6 form-group">
                              <label for="lieu_naissance">Lieu de naissance</label>
                              <input type="text" name="lieu_naissance" class="form-control" id="lieu_naissance" placeholder="Entrez votre Lieu de Naissance" required>
                              </div>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-6 form-group">
                              <label for="telephone">Numéro de téléphone (+227 97000000)</label>
                              <input type="text" name="telephone" class="form-control" id="telephone" placeholder="Entrez votre Numéro de Téléphone" required>
                              </div>
                              <div class="col-md-6 form-group">
                              <label for="domicile">Domicile</label>
                              <input type="text" name="domicile" class="form-control" id="domicile" placeholder="Entrez votre Domicile" required>
                              </div>
                            </div>
                            <div class="form-group mt-3">
                              <label for="categorie">Catégorie du Permis (ex: A, B, C)</label>
                              <select name="categorie" id="categorie" required class="form-control">
                                <option value="" disabled selected>Sélectionnez la Catégorie</option>
                                <option value="A">A - Permis moto</option>
                                <option value="A1">A1 - Moto légère</option>
                                <option value="A2">A2 - Moto intermédiaire</option>
                                <option value="B">B - Permis voiture</option>
                                <option value="B96">B96 - Voiture + remorque</option>
                                <option value="B+E">B+E - Voiture + remorque</option>
                                <option value="C">C - Poids lourd</option>
                                <option value="C1">C1 - Poids lourd (3,5 - 7,5 tonnes)</option>
                                <option value="C1+E">C1+E - Poids lourd + remorque</option>
                                <option value="C+E">C+E - Poids lourd + remorque</option>
                                <option value="D">D - Autobus et autocars</option>
                                <option value="D1">D1 - Minibus (9 - 16 passagers)</option>
                                <option value="D1+E">D1+E - Minibus + remorque</option>
                                <option value="D+E">D+E - Autobus + remorque</option>
                                <option value="E">E - Remorque</option>
                                <option value="F">F - Véhicules agricoles</option>
                                <option value="G">G - Transport de matières dangereuses</option>
                                <option value="H">H - Véhicules spéciaux</option>
                                <option value="I">I - Véhicules industriels</option>
                                <option value="J">J - Véhicules particuliers</option>
                                <option value="K">K - Transport spécifique</option>
                                <option value="L">L - Véhicules légers</option>
                                <option value="M">M - Véhicules à moteur</option>
                                <option value="N">N - Véhicules de grand gabarit</option>
                                <option value="P">P - Permis probatoire (jeune conducteur)</option>
                                <option value="Q">Q - Tramway/Metro</option>
                                <option value="R">R - Véhicules d’urgence</option>
                              </select>
                            </div>

                            <div class="form-group mt-3">
                              <label for="photo">Photo d'identité (format JPG/PNG, max 2MB)</label>
                              <input type="file" name="photo" class="form-control" id="photo" required>
                            </div>
                            <div class="form-group mt-3">
                              <label for="copie_pc">Copie de la Pièce d'Identité (format PDF/JPG, max 2MB)</label>
                              <input type="file" name="copie_pc" class="form-control" id="copie_pc" required>
                            </div>
                            <div class="form-group mt-3">
                              <label for="date_pc">Date de la Pièce d'Identité</label>
                              <input type="date" name="date_pc" class="form-control" id="date_pc" required>
                            </div>
                            <div class="my-3 justify-content-center text-center">
                              <div class="text-center"><button type="submit" class="btn btn-primary">Soumettre</button></div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                </div>

              </div>
            </section><!-- End Permis National Section -->
            <?php
            break;

          case "permis_international":
            ?>
            <!-- ======= Permis International Section ======= -->
            <section id="permis_national" class="about">
              <div class="container">

                <!-- Information utilisateur -->
                <div class="row content">
                  <div class="col-lg-6">
                    <h2>Demande de Permis International</h2>
                    <h3>Veuillez suivre les étapes ci-dessous pour compléter votre demande.</h3>
                  </div>
                  <div class="col-lg-6 pt-4 pt-lg-0">
                    <p>
                      Assurez-vous de fournir des informations exactes et de télécharger tous les documents nécessaires
                      pour faciliter le traitement de votre demande.
                    </p>
                    <ul>
                      <li><i class="ri-check-double-line"></i> Fournissez vos informations personnelles complètes.</li>
                      <li><i class="ri-check-double-line"></i> Téléchargez une copie de votre pièce d'identité et votre
                        photo.</li>
                      <li><i class="ri-check-double-line"></i> Vérifiez vos informations avant soumission.</li>
                    </ul>
                    <p class="fst-italic">
                      Note : Toute fausse information peut entraîner le rejet de votre demande.
                    </p>
                  </div>
                </div>

                <!-- Formulaire -->
                <div class="row mt-5 justify-content-center">
                  <div class="col-lg-10 mt-5 mt-lg-0">
                    <?php 
                      if (!empty($errors)) {
                          foreach ($errors as $error) {
                              echo '<p class="alert alert-danger">'. $error .'</p>';
                          }
                      }
                      if (isset($_GET['success'])) {
                        echo '<p class="alert alert-success">'. $_GET['success'] .'</p>';
                      }
                    ?>
                    <div class="card">
                      <div class="card-header">
                        <h5 class="text-center">Formulaire de Demande de Permis International</h5>
                      </div>
                      <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data" role="form">
                          <input type="hidden" name="action" value="permis_international">
                          <div class="row">
                            <div class="col-md-6 form-group">
                              <label for="nom">Nom</label>
                              <input type="text" name="nom" class="form-control" id="nom" placeholder="Entrez votre Nom"
                                required>
                            </div>
                            <div class="col-md-6 form-group mt-3 mt-md-0">
                              <label for="prenom">Prénom</label>
                              <input type="text" name="prenom" class="form-control" id="prenom"
                                placeholder="Entrez votre Prénom" required>
                            </div>
                          </div>
                          <div class="row mt-3">
                            <div class="col-md-6 form-group">
                              <label for="date_naissance">Date de naissance</label>
                              <input type="date" name="date_naissance" class="form-control" id="date_naissance"
                                placeholder="Entrez votre Date de Naissance" required>
                            </div>
                            <div class="col-md-6 form-group">
                              <label for="lieu_naissance">Lieu de naissance</label>
                              <input type="text" name="lieu_naissance" class="form-control" id="lieu_naissance"
                                placeholder="Entrez votre Lieu de Naissance" required>
                            </div>
                          </div>
                          <div class="row mt-3">
                            <div class="col-md-6 form-group">
                              <label for="telephone">Numéro de téléphone (+227 97000000)</label>
                              <input type="text" name="telephone" class="form-control" id="telephone"
                                placeholder="Entrez votre Numéro de Téléphone" required>
                            </div>
                            <div class="col-md-6 form-group">
                              <label for="domicile">Domicile</label>
                              <input type="text" name="domicile" class="form-control" id="domicile"
                                placeholder="Entrez votre Domicile" required>
                            </div>
                          </div>

                          <div class="row mt-3">
                            <div class="col-md-6 form-group">
                              <label for="categorie">Catégorie du Permis (ex: A, B, C)</label>
                              <select name="categorie" id="categorie" required class="form-control">
                                <option value="" disabled selected>Sélectionnez la Catégorie</option>
                                <option value="A">A - Permis moto</option>
                                <option value="A1">A1 - Moto légère</option>
                                <option value="A2">A2 - Moto intermédiaire</option>
                                <option value="B">B - Permis voiture</option>
                                <option value="B96">B96 - Voiture + remorque</option>
                                <option value="B+E">B+E - Voiture + remorque</option>
                                <option value="C">C - Poids lourd</option>
                                <option value="C1">C1 - Poids lourd (3,5 - 7,5 tonnes)</option>
                                <option value="C1+E">C1+E - Poids lourd + remorque</option>
                                <option value="C+E">C+E - Poids lourd + remorque</option>
                                <option value="D">D - Autobus et autocars</option>
                                <option value="D1">D1 - Minibus (9 - 16 passagers)</option>
                                <option value="D1+E">D1+E - Minibus + remorque</option>
                                <option value="D+E">D+E - Autobus + remorque</option>
                                <option value="E">E - Remorque</option>
                                <option value="F">F - Véhicules agricoles</option>
                                <option value="G">G - Transport de matières dangereuses</option>
                                <option value="H">H - Véhicules spéciaux</option>
                                <option value="I">I - Véhicules industriels</option>
                                <option value="J">J - Véhicules particuliers</option>
                                <option value="K">K - Transport spécifique</option>
                                <option value="L">L - Véhicules légers</option>
                                <option value="M">M - Véhicules à moteur</option>
                                <option value="N">N - Véhicules de grand gabarit</option>
                                <option value="P">P - Permis probatoire (jeune conducteur)</option>
                                <option value="Q">Q - Tramway/Metro</option>
                                <option value="R">R - Véhicules d’urgence</option>
                              </select>
                            </div>
                            <div class="col-md-6 form-group">
                              <label for="photo">Photo d'identité (format JPG/PNG, max 2MB)</label>
                              <input type="file" name="photo" class="form-control" id="photo" required>
                            </div>
                          </div>

                          <div class="row mt-3">
                            <div class="col-md-6 form-group">
                              <label for="copie_pc">Copie de la Pièce d'Identité (format PDF/JPG, max 2MB)</label>
                              <input type="file" name="copie_pc" class="form-control" id="copie_pc" required>
                            </div>
                            <div class="col-md-6 form-group">
                              <label for="copie_permis" class="form-label">Copie Permis National</label>
                              <input type="file" class="form-control" id="copie_permis" name="copie_permis" required>
                            </div>
                          </div>
                          <div class="form-group mt-3">
                            <label for="date_pc">Date de la Pièce d'Identité</label>
                            <input type="date" name="date_pc" class="form-control" id="date_pc" required>
                          </div>
                          <div class="my-3 justify-content-center text-center">
                            <div class="text-center"><button type="submit" class="btn btn-primary">Soumettre</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </section><!-- End Permis International Section -->
            <?php
            break;

          default:
            echo "<p>Section inconnue.</p>";
        }
      } else {
        echo "<p>Veuillez sélectionner une section.</p>";
      }
      ?>
    </div>
  </main>
      <!-- ======= Footer ======= -->
  <footer id="footer">

<div class="container">
  <div class="copyright">
    &copy; Copyright <strong><span>2024</span></strong>. Tous droits réserver
  </div>
</div>
</footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
</body>

</html>
