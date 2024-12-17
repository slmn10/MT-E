<?php
// Inclure la connexion PDO
include 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $immatriculation = $_POST['immatriculation'];
    $date = $_POST['date'];
    $status = 'Original';

    try {
        // Vérification de l'existence de l'immatriculation
        $check_sql = "SELECT COUNT(*) FROM attestations WHERE immatriculation = :immatriculation";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bindParam(':immatriculation', $immatriculation);
        $stmt_check->execute();
        $exists = $stmt_check->fetchColumn();

        if ($exists > 0) {
            // Immatriculation déjà existante, renvoyer avec les anciennes valeurs
            $params = http_build_query([
                'error' => 2,
                'nom' => $nom,
                'adresse' => $adresse,
                'telephone' => $telephone,
                'immatriculation' => $immatriculation,
                'date' => $date
            ]);
            header("Location: ?route=create_attestation&$params");
            exit();
        }

        // Si l'immatriculation est unique, procéder à l'insertion
        $insert_sql = "INSERT INTO attestations (nom, adresse, telephone, immatriculation, date, status)
                       VALUES (:nom, :adresse, :telephone, :immatriculation, :date, :status)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':immatriculation', $immatriculation);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        // Redirection avec un message de succès
        header("Location: ?route=liste_attestation&success=1");
        exit();
    } catch (PDOException $e) {
        // Erreur serveur, renvoyer avec les anciennes valeurs
        $params = http_build_query([
            'error' => 1,
            'nom' => $nom,
            'adresse' => $adresse,
            'telephone' => $telephone,
            'immatriculation' => $immatriculation,
            'date' => $date
        ]);
        header("Location: ?route=create_attestation&$params");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Attestation Provisoire</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5 mb-5">
        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php if ($_GET['error'] == 1): ?>
            Une erreur est survenue lors de l'enregistrement. Veuillez réessayer plus tard.
            <?php elseif ($_GET['error'] == 2): ?>
            L'immatriculation existe déjà. Veuillez utiliser une autre immatriculation.
            <?php elseif (isset($_GET['msg'])): ?>
            <?= htmlspecialchars($_GET['msg']) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <h5 class="text-center">Saisie et Edition des Attestations Provisoires des Immatriculations W</h5>
            </div>
            <div class="card-body">
                <form id="immatriculationForm" action="" method="POST">
                    <div class="row">
                        <!-- Immatriculation -->
                        <div class="col-md-6 mb-3">
                            <label for="immatriculation" class="form-label">Immatriculation</label>
                            <input type="text" class="form-control" id="immatriculation" name="immatriculation"
                                value="<?= isset($_GET['immatriculation']) ? htmlspecialchars($_GET['immatriculation']) : '' ?>"
                                required>
                        </div>

                        <!-- Nom complet -->
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                value="<?= isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '' ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Adresse -->
                        <div class="col-md-6 mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse"
                                value="<?= isset($_GET['adresse']) ? htmlspecialchars($_GET['adresse']) : '' ?>"
                                required>
                        </div>

                        <!-- Numéro de téléphone -->
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                value="<?= isset($_GET['telephone']) ? htmlspecialchars($_GET['telephone']) : '' ?>"
                                pattern="^(\+?\d{1,3}[-.\s]?)?\d{10}$" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : date('Y-m-d') ?>"
                                required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Enregistrer</button>
                        <a href="index.php" class="btn btn-secondary">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('immatriculation').addEventListener('blur', function () {
                const immatriculation = this.value;

                fetch('attestation/verifier_immatriculation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            immatriculation: immatriculation
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.nom) {
                            // Remplir les champs avec les données reçues
                            document.getElementById('nom').value = data.nom;
                            document.getElementById('adresse').value = data.adresse;
                            document.getElementById('telephone').value = data.telephone;
                        } else {
                            // Si l'immatriculation n'existe pas, réinitialiser les champs
                            document.getElementById('nom').value = '';
                            document.getElementById('adresse').value = '';
                            document.getElementById('telephone').value = '';
                        }
                    })
                    .catch(error => {
                        console.error("Erreur lors de la requête :", error);
                    });
            });
        });
    </script>

</body>

</html>
