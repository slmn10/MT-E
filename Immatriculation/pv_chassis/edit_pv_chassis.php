<?php
// Inclure la connexion PDO
include 'connect_db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupération des données existantes
    try {
        $sql = "SELECT * FROM attestations WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            echo "<div class='alert alert-warning'>Enregistrement non trouvé.</div>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-warning'>ID manquant dans l'URL.</div>";
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $immatriculation = $_POST['immatriculation'];
    $date = $_POST['date'];

    // Mise à jour de l'enregistrement
    try {
        $update_sql = "UPDATE attestations SET nom = :nom, adresse = :adresse, telephone = :telephone, immatriculation = :immatriculation, date = :date WHERE id = :id";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':immatriculation', $immatriculation);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection vers la liste des enregistrements avec message de succès
        header("Location: index.php?success=2");
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Enregistrement</title>
</head>
<body>
<div class="container mt-5">
    <!-- Card principale -->
    <div class="card">
        <!-- En-tête de la carte -->
        <div class="card-header">
            <h5 class="card-title mb-0">Modifier l'Enregistrement</h5>
        </div>

        <!-- Corps de la carte -->
        <div class="card-body">
            <form action="edit.php?id=<?= $id ?>" method="POST">
                <div class="row">
                    <!-- Champ Nom complet -->
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($record['nom']) ?>" required>
                    </div>

                    <!-- Champ Adresse -->
                    <div class="col-md-6 mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($record['adresse']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Champ Numéro de téléphone -->
                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($record['telephone']) ?>" required pattern="^(\+?\d{1,3}[-.\s]?)?\d{10}$">
                    </div>

                    <!-- Champ Immatriculation -->
                    <div class="col-md-6 mb-3">
                        <label for="immatriculation" class="form-label">Immatriculation</label>
                        <input type="text" class="form-control" id="immatriculation" name="immatriculation" value="<?= htmlspecialchars($record['immatriculation']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Champ Date -->
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($record['date']) ?>" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">Enregistrer les modifications</button>
                    <a href="?route=liste_attestation" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
