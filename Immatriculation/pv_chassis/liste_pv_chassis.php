<?php
// Connexion à la base de données
try {
    // Récupération des enregistrements avec une requête préparée PDO
    $stmt = $conn->query("
        SELECT 
            pvchassis.id, 
            pvchassis.nom, 
            marques.nom AS marque, 
            genres.libelle AS genre, 
            carrosseries.type AS carrosserie, 
            energies.nom AS energie, 
            pvchassis.immat, 
            pvchassis.pv, 
            pvchassis.date_pv 
        FROM pvchassis
        LEFT JOIN marques ON pvchassis.marque = marques.id
        LEFT JOIN genres ON pvchassis.genre = genres.id
        LEFT JOIN carrosseries ON pvchassis.carrosserie = carrosseries.id
        LEFT JOIN energies ON pvchassis.energie = energies.id
    ");

    $enregistrements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</div>");
}

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $stmt = $conn->prepare("DELETE FROM pvchassis WHERE id = :id");
    $stmt->bindParam(':id', $id);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Redirection avec un message de succès
        header('Location: ?route=liste_pv_chassis&&success=3');
        exit();
    } else {
        // Redirection avec un message d'erreur
        header('Location: ?route=liste_pv_chassis&&error=1');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des PV Châssis</title>
</head>

<body>
    <div class="container mt-4">
        <!-- Card principale -->
        <div class="card">
            <!-- En-tête de la carte -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Liste des PV Châssis</h5>
                <a href="?route=create_pv_chassis" class="btn btn-primary">Ajouter un PV Châssis</a>
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
                    <div class="alert alert-danger">Erreur lors de la suppression de l'enregistrement.</div>
                <?php endif; ?>

                <!-- Table des enregistrements -->
                <?php if (!empty($enregistrements)): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nom</th>
                                <th>Marque</th>
                                <th>Genre</th>
                                <th>Immatriculation</th>
                                <th>Carrosserie</th>
                                <th>Energie</th>
                                <th>PV</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enregistrements as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nom']) ?></td>
                                    <td><?= htmlspecialchars($row['immat']) ?></td>
                                    <td><?= htmlspecialchars($row['marque']) ?></td>
                                    <td><?= htmlspecialchars($row['genre']) ?></td>
                                    <td><?= htmlspecialchars($row['carrosserie']) ?></td>
                                    <td><?= htmlspecialchars($row['energie']) ?></td>
                                    <td><?= htmlspecialchars($row['pv']) ?></td>
                                    <td><?= htmlspecialchars($row['date_pv']) ?></td>
                                    <td style="display: flex; align-items: center;">
                                        <a href="pv_chassis/pv_chassis.php?id=<?= urlencode($row['id']) ?>" class="p-1">
                                            <img src="svg/printer.svg" alt="" width="20">
                                        </a>
                                        <a href="?route=edit_pv_chassis&&id=<?= urlencode($row['id']) ?>" class="p-1">
                                        <img src="svg/edit.svg" alt="" width="20">
                                        </a>
                                        <a href="?route=liste_pv_chassis&&id=<?= urlencode($row['id']) ?>" 
                                        class="p-1" 
                                           onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">
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
