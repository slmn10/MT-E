<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Attestation Provisoire</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center">Saisie et Edition des PV d'Homologation des Chassis</h2>
        </div>
        <div class="card-body">
            <form id="immatriculationForm" action="traitement.php" method="POST">
                <div class="mb-3">
                    <label for="immatriculation" class="form-label">Immatriculation</label>
                    <input type="text" class="form-control" id="immatriculation" name="immatriculation" required>
                </div>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom complet</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Numéro de téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" pattern="^(\+?\d{1,3}[-.\s]?)?\d{10}$" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="index.php" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('immatriculation').addEventListener('blur', function() {
        const immatriculation = this.value;

        fetch('verifier_immatriculation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ immatriculation: immatriculation })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.nom) {
                // Remplir les champs avec les données reçues
                document.getElementById('nom').value = data.nom;
                document.getElementById('adresse').value = data.adresse;
                document.getElementById('telephone').value = data.telephone;
                // alert("Cette immatriculation existe déjà. Les informations ont été remplies.");
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
