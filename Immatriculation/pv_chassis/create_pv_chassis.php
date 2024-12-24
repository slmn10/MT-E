<?php
// Inclure la connexion PDO
include 'connect_db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Requêtes pour récupérer les données
$marques = $pdo->query("SELECT id, nom FROM marques")->fetchAll(PDO::FETCH_ASSOC);
$genres = $pdo->query("SELECT id, libelle FROM genres")->fetchAll(PDO::FETCH_ASSOC);
$carrosseries = $pdo->query("SELECT id, type FROM carrosseries")->fetchAll(PDO::FETCH_ASSOC);
$energies = $pdo->query("SELECT id, nom FROM energies")->fetchAll(PDO::FETCH_ASSOC);


if($_SERVER['REQUEST_METHOD']==='POST'){
    // Récupération et validation des données
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
    $puissance = $_POST['puissance'];
    $nbrEssieux = $_POST['nbrEssieux'];
    $date = $_POST['date'];

    // Insertion des données dans la base
    try {
        $insert_sql = "INSERT INTO pvchassis (nom, marque, genre, immat, carrosserie, type, energie, chassis, puissance, nbrEssieux, pv, cu, ptac, date_pv)
                    VALUES (:nom, :marque, :genre, :immat, :carrosserie, :type, :energie, :chassis, :pv, :puissance, :nbrEssieux, :cu, :ptac, :date_pv)";
        $stmt = $conn->prepare($insert_sql);
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
        $stmt->bindParam(':puissance', $puissance);
        $stmt->bindParam(':nbrEssieux', $nbrEssieux);
        $stmt->bindParam(':ptac', $ptac);
        $stmt->bindParam(':date_pv', $date);
        $stmt->execute();

        // Redirection vers la liste avec message de succès
        header("Location: ?route=liste_pv_chassis&&success=1");
    } catch (PDOException $e) {
        error_log("Erreur d'insertion : " . $e->getMessage());
        header("Location: ?route=create_pv_chassis&&error=1");
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
        <div class="card">
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
            <div class="card-header">
                <h5 class="text-center">Saisie et Edition des PV d'Homologation des Chassis</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row">
                        <!-- Immatriculation -->
                        <div class="col-md-6 mb-3">
                            <label for="immat" class="form-label">Immatriculation</label>
                            <input type="text" class="form-control" id="immat" name="immat"
                                required>
                        </div>

                        <!-- Nom complet -->
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Marque -->
                        <div class="col-md-6 mb-3">
                            <label for="marque" class="form-label">Marque</label>
                            <select class="form-control form-select" name="marque" id="marque" required>
                                <option value="" disabled selected>Sélectionnez une marque</option>
                                <?php foreach ($marques as $marque): ?>
                                    <option value="<?= $marque['id'] ?>"><?= htmlspecialchars($marque['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Genre -->
                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <select class="form-control form-select" name="genre" id="genre" required>
                                <option value="" disabled selected>Sélectionnez un genre</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= $genre['id'] ?>"><?= htmlspecialchars($genre['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Carrosserie -->
                        <div class="col-md-6 mb-3">
                            <label for="carrosserie" class="form-label">Carrosserie</label>
                            <select class="form-control form-select" name="carrosserie" id="carrosserie" required>
                                <option value="" disabled selected>Sélectionnez une carrosserie</option>
                                <?php foreach ($carrosseries as $carrosserie): ?>
                                    <option value="<?= $carrosserie['id'] ?>"><?= htmlspecialchars($carrosserie['type']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Energie -->
                        <div class="col-md-6 mb-3">
                            <label for="energie" class="form-label">Energie</label>
                            <select class="form-control form-select" name="energie" id="energie" required>
                                <option value="" disabled selected>Sélectionnez une énergie</option>
                                <?php foreach ($energies as $energie): ?>
                                    <option value="<?= $energie['id'] ?>"><?= htmlspecialchars($energie['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- type -->
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type" name="type"
                                required>
                        </div>

                        <!-- PV -->
                        <div class="col-md-6 mb-3">
                            <label for="pv" class="form-label">Poids à vide</label>
                            <input type="number" class="form-control" id="pv" name="pv" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- cu -->
                        <div class="col-md-6 mb-3">
                            <label for="cu" class="form-label">Charge utile</label>
                            <input type="number" class="form-control" id="cu" name="cu"
                                required>
                        </div>

                        <!-- ptac -->
                        <div class="col-md-6 mb-3">
                            <label for="ptac" class="form-label">Ptac</label>
                            <input type="number" class="form-control" id="ptac" name="ptac" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- puissance -->
                        <div class="col-md-6 mb-3">
                            <label for="puissance" class="form-label">Puissance</label>
                            <input type="number" class="form-control" id="puissance" name="puissance"
                                required>
                        </div>

                        <!-- nbrEssieux -->
                        <div class="col-md-6 mb-3">
                            <label for="nbrEssieux" class="form-label">Nombre d'essieux</label>
                            <input type="number" class="form-control" id="nbrEssieux" name="nbrEssieux" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- chassis -->
                        <div class="col-md-6 mb-3">
                            <label for="chassis" class="form-label">Chassis</label>
                            <input type="text" class="form-control" id="chassis" name="chassis" required>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>"
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
            document.getElementById('immat').addEventListener('blur', function () {
                const immat = this.value;

                fetch('pv_chassis/verifier_pv_chassis.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            immat: immat
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.nom) {
                            // Remplir les champs avec les données reçues
                            document.getElementById('nom').value = data.nom;
                            document.getElementById('marque').value = data.marque;
                            document.getElementById('genre').value = data.genre;
                        } else {
                            // Si l'immatriculation n'existe pas, réinitialiser les champs
                            document.getElementById('nom').value = '';
                            document.getElementById('marque').value = '';
                            document.getElementById('genre').value = '';
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
