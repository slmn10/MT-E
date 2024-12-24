<?php
// Inclure la connexion PDO
include 'connect_db.php';

// Requêtes pour récupérer les données
$marques = $conn->query("SELECT id, nom FROM marques")->fetchAll(PDO::FETCH_ASSOC);
$genres = $conn->query("SELECT id, libelle FROM genres")->fetchAll(PDO::FETCH_ASSOC);
$carrosseries = $conn->query("SELECT id, type FROM carrosseries")->fetchAll(PDO::FETCH_ASSOC);
$energies = $conn->query("SELECT id, nom FROM energies")->fetchAll(PDO::FETCH_ASSOC);


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupération des données existantes
    try {
        $sql = "SELECT * FROM pvchassis WHERE id = :id";
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
    $immat = htmlspecialchars(trim($_POST['immat']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $marque = $_POST['marque'];
    $genre = $_POST['genre'];
    $carrosserie = $_POST['carrosserie'];
    $type = htmlspecialchars(trim($_POST['type']));
    $energie = $_POST['energie'];
    $chassis = htmlspecialchars(trim($_POST['chassis']));
    $pv = $_POST['pv'];
    $cu = htmlspecialchars(trim($_POST['cu']));
    $ptac = $_POST['ptac'];
    $date = $_POST['date'];

    // Mise à jour de l'enregistrement
    try {
        $update_sql = "UPDATE pvchassis 
            SET nom = :nom, marque = :marque, genre = :genre, immat = :immat, carrosserie = :carrosserie, type = :type, energie = :energie, chassis = :chassis, pv = :pv, cu = :cu, ptac = :ptac, date_pv = :date_pv 
            WHERE id = :id";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':marque', $marque);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':immat', $immat);
        $stmt->bindParam(':carrosserie', $carrosserie);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':energie', $energie);
        $stmt->bindParam(':chassis', $chassis);
        $stmt->bindParam(':pv', $pv);
        $stmt->bindParam(':cu', $cu);
        $stmt->bindParam(':ptac', $ptac);
        $stmt->bindParam(':date_pv', $date);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection vers la liste des enregistrements avec message de succès
        header("Location: ?route=liste_pv_chassis&&success=2");
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
                <form action="" method="POST">
                    <div class="row">
                        <!-- Immatriculation -->
                        <div class="col-md-6 mb-3">
                            <label for="immat" class="form-label">Immatriculation</label>
                            <input type="text" class="form-control" id="immat" name="immat" value="<?= htmlspecialchars($record['immat']) ?>" required>
                        </div>

                        <!-- Nom complet -->
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($record['nom']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Marque -->
                        <div class="col-md-6 mb-3">
                            <label for="marque" class="form-label">Marque</label>
                            <select class="form-control form-select" name="marque" id="marque" required>
                                <option value="" disabled>Sélectionnez une marque</option>
                                <?php foreach ($marques as $marque): ?>
                                    <option value="<?= $marque['id'] ?>" <?php if ($marque['id'] == $record['marque']) echo 'selected'; ?>><?= htmlspecialchars($marque['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Genre -->
                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <select class="form-control form-select" name="genre" id="genre" required>
                                <option value="" disabled>Sélectionnez un genre</option>
                                <?php foreach ($genres as $genre): ?>
                                <option value="<?= $genre['id'] ?> <?php if ($genre['id'] == $record['genre']) echo 'selected'; ?>"><?= htmlspecialchars($genre['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Carrosserie -->
                        <div class="col-md-6 mb-3">
                            <label for="carrosserie" class="form-label">Carrosserie</label>
                            <select class="form-control form-select" name="carrosserie" id="carrosserie" required>
                                <option value="" disabled>Sélectionnez une carrosserie</option>
                                <?php foreach ($carrosseries as $carrosserie): ?>
                                <option value="<?= $carrosserie['id'] ?> <?php if ($carrosserie['id'] == $record['carrosserie']) echo 'selected'; ?>"><?= htmlspecialchars($carrosserie['type']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Energie -->
                        <div class="col-md-6 mb-3">
                            <label for="energie" class="form-label">Energie</label>
                            <select class="form-control form-select" name="energie" id="energie" required>
                                <option value="" disabled>Sélectionnez une énergie</option>
                                <?php foreach ($energies as $energie): ?>
                                    <option value="<?= $energie['id'] ?> <?php if ($energie['id'] == $record['energie']) echo 'selected'; ?>"><?= htmlspecialchars($energie['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- type -->
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type" name="type" value="<?= htmlspecialchars($record['type']) ?>" required>
                        </div>

                        <!-- PV -->
                        <div class="col-md-6 mb-3">
                            <label for="pv" class="form-label">Poids à vide</label>
                            <input type="number" class="form-control" id="pv" name="pv" value="<?= htmlspecialchars($record['pv']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- cu -->
                        <div class="col-md-6 mb-3">
                            <label for="cu" class="form-label">Charge utile</label>
                            <input type="number" class="form-control" id="cu" name="cu" value="<?= htmlspecialchars($record['cu']) ?>" required>
                        </div>

                        <!-- ptac -->
                        <div class="col-md-6 mb-3">
                            <label for="ptac" class="form-label">Ptac</label>
                            <input type="number" class="form-control" id="ptac" name="ptac" value="<?= htmlspecialchars($record['ptac']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- puissance -->
                        <div class="col-md-6 mb-3">
                            <label for="puissance" class="form-label">Puissance</label>
                            <input type="number" class="form-control" id="puissance" value="<?= htmlspecialchars($record['puissance']) ?>" name="puissance"
                                required>
                        </div>

                        <!-- nbrEssieux -->
                        <div class="col-md-6 mb-3">
                            <label for="nbrEssieux" class="form-label">Nombre d'essieux</label>
                            <input type="number" class="form-control" id="nbrEssieux" value="<?= htmlspecialchars($record['nbrEssieux']) ?>" name="nbrEssieux" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- chassis -->
                        <div class="col-md-6 mb-3">
                            <label for="chassis" class="form-label">Chassis</label>
                            <input type="text" class="form-control" id="chassis" name="chassis" value="<?= htmlspecialchars($record['chassis']) ?>" required>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>"
                                required>
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
