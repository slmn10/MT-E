<?php
// Inclure la connexion PDO
include 'connect_db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    // Récupération des données du formulaire
    $id = $_POST['id'];
    $nom = htmlspecialchars(strip_tags($_POST['nom']));
    $adresse = htmlspecialchars(strip_tags($_POST['adresse']));
    $telephone = htmlspecialchars(strip_tags($_POST['telephone']));
    $immatriculation = htmlspecialchars(strip_tags($_POST['immatriculation']));
    $date = htmlspecialchars(strip_tags($_POST['date']));
    $duplicata = 'Duplicata';

    if (empty($id) || empty($nom) || empty($adresse) || empty($telephone) || empty($immatriculation) || empty($date)) {
        header("Location: ?route=duplicata_attestation&&error=1&&msg=Champs obligatoires manquants.");
        exit;
    } else {
        // Insertion des données dans la base
        try {
            $update_sql = "UPDATE attestations 
                           SET nom = :nom, 
                               adresse = :adresse, 
                               telephone = :telephone, 
                               immatriculation = :immatriculation, 
                               date = :date,
                               status = :status 
                           WHERE id = :id";
            
            $stmt = $conn->prepare($update_sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':immatriculation', $immatriculation);
            $stmt->bindParam(':date', $date);
            $stmt->bindValue(':status', $duplicata, PDO::PARAM_STR);
            $stmt->execute();
    
            // Redirection vers la liste avec message de succès
            header("Location: ?route=liste_attestation&&success=1");
        } catch (PDOException $e) {
            // En cas d'erreur, redirection avec un message d'erreur
            header("Location: ?route=duplicata_attestation&&error=1");
        }
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
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="alert alert-danger">
        Une erreur est survenue, veillez réessayer plus tard.
        </div>
    <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="text-center">Duplicata des Attestations Provisoires des Immatriculations W</h5>
            </div>
            <div class="card-body">
                <form id="immatriculationForm" action="" method="POST">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <!-- Immatriculation -->
                        <div class="col-md-6 mb-3">
                            <label for="immatriculation" class="form-label">Immatriculation</label>
                            <input type="text" class="form-control" id="immatriculation" name="immatriculation"
                                required>
                        </div>

                        <!-- Nom complet -->
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Adresse -->
                        <div class="col-md-6 mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required>
                        </div>

                        <!-- Numéro de téléphone -->
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                 required>
                        </div>
                    </div>

                    <div class="row">
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
    document.addEventListener("DOMContentLoaded", function () {
        const immatriculationInput = document.getElementById('immatriculation');

        immatriculationInput.addEventListener('change', () => {
            const immatriculation = immatriculationInput.value;

            if (immatriculation) {
                fetch('attestation/get_data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'immatriculation=' + encodeURIComponent(immatriculation)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    
                    if (!data.error) {
                        // Remplir les champs avec les données récupérées
                        document.getElementById('id').value = data.id;
                        document.getElementById('nom').value = data.nom;
                        document.getElementById('adresse').value = data.adresse;
                        document.getElementById('telephone').value = data.telephone;
                    } else {
                        alert(data.error);
                        // Réinitialiser les champs si aucune donnée trouvée
                        document.getElementById('id').value = '';
                        document.getElementById('nom').value = '';
                        document.getElementById('adresse').value = '';
                        document.getElementById('telephone').value = '';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données:', error);
                    alert("Une erreur s'est produite. Veuillez réessayer.");
                });
            }
        });
    });
</script>


</body>

</html>
