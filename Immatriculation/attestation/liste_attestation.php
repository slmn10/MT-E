<?php

try {
    // Récupération des enregistrements avec une requête préparée PDO
    $stmt = $conn->query("SELECT * FROM attestations");
    $enregistrements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</div>");
}

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $stmt = $conn->prepare("DELETE FROM attestations WHERE id = :id");
    $stmt->bindParam(':id', $id);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Redirection avec un message de succès
        header('Location: ?route=liste_attestation&&success=3');
        exit();
    } else {
        // Redirection avec un message d'erreur
        header('Location: ?route=liste_attestation&&error=1');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Mettre à jour la date avec la date actuelle
        $stmt = $conn->prepare("UPDATE attestations SET date = NOW(), status = 'Renouvelé' WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection avec message de succès
        header("Location: ?route=liste_attestation&&success=2");
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur
        header("Location: ?route=liste_attestation&&error=1");
        // header("Location: ?route=liste_attestation&&error=1&&msg=" . urlencode($e->getMessage()));
        exit();
    }
}


$conn = null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Attestations</title>
</head>

<body>
    <div class="container mt-4">
        <!-- Card principale -->
        <div class="card">
            <!-- En-tête de la carte -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Liste des Attestations</h5>
                <div>
                    <a href="?route=duplicata_attestation" class="btn btn-primary">Rechercher un duplicata</a>
                    <a href="?route=create_attestation" class="btn btn-primary">Ajouter une Attestation</a>
                </div>
            </div>

            <!-- Corps de la carte -->
            <div class="card-body">
                <!-- Notifications -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <?php if ($_GET['success'] == 1): ?>
                            Enregistrement effectué avec succès.
                        <?php elseif ($_GET['success'] == 2): ?>
                            Modification effectuée avec succès.
                        <?php elseif ($_GET['success'] == 3): ?>
                            Enregistrement supprimé avec succès.
                        <?php endif; ?>
                    </div>
                <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
                    <div class="alert alert-danger">Une erreur est survenue, veillez réessayer plus tard.</div>
                <?php endif; ?>

                <!-- Table des enregistrements -->
                <?php if (!empty($enregistrements)): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Téléphone</th>
                                <th>Immatriculation</th>
                                <th>statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enregistrements as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nom']) ?></td>
                                    <td><?= htmlspecialchars($row['adresse']) ?></td>
                                    <td><?= htmlspecialchars($row['telephone']) ?></td>
                                    <td><?= htmlspecialchars($row['immatriculation']) ?></td>
                                    <td>
                                        <?php
                                        $enregistrementAnnee = date('Y', strtotime($row['date'])); // Extraire l'année de l'enregistrement
                                        $anneeActuelle = date('Y');

                                        if ($enregistrementAnnee == ($anneeActuelle - 1)): ?>
                                            <form method="POST" action="">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                                <button type="submit" class="btn btn-warning btn-sm">Renouveler</button>
                                            </form>
                                        <?php else: ?>
                                            <?= htmlspecialchars($row['status']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td style="display: flex; align-items: center;">
                                        <a href="attestation/attestation.php?id=<?= urlencode($row['id']) ?>" class="p-1" title="Imprimer">
                                            <img src="svg/printer.svg" alt="" width="20">
                                        </a>
                                        <a href="?route=edit_attestation&&id=<?= urlencode($row['id']) ?>" class="p-1" title="Modifier">
                                            <img src="svg/edit.svg" alt="" width="20">
                                        </a>
                                        <a href="?route=liste_attestation&&id=<?= urlencode($row['id']) ?>" class="p-1" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">
                                            <img src="svg/delete.svg" alt="" width="20">
                                        </a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">Aucun enregistrement trouvé.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>
