<?php
try {
    // Récupération des enregistrements
    $stmt = $conn->query("SELECT id, immat, nom, prenom, marque, model FROM carte_grise");
    $cartes_grises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</div>");
}

// Suppression d'une carte grise si un ID est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $stmt = $conn->prepare("DELETE FROM carte_grise WHERE id = :id");
    $stmt->bindParam(':id', $id);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Redirection avec message de succès
        header('Location: ?route=liste_carte_grise&&success=3');
        exit();
    } else {
        // Redirection avec message d'erreur
        header('Location: ?route=liste_carte_grise&&error=1');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Cartes Grises</title>
    <link rel="stylesheet" href="chemin/vers/bootstrap.css">
    <script src="chemin/vers/bootstrap.bundle.js"></script>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Liste des Cartes Grises</h5>
            <a href="../service document/formulaire.php?page=carte_grise" class="btn btn-primary">Ajouter une Carte Grise</a>
        </div>

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
            <!-- Table des cartes grises -->
            <?php if (!empty($cartes_grises)): ?>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Immatriculation</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cartes_grises as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['immat']) ?></td>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['prenom']) ?></td>
                            <td><?= htmlspecialchars($row['marque']) ?></td>
                            <td><?= htmlspecialchars($row['model']) ?></td>
                            <td style="display: flex; align-items: center;">
                                <a type="button" class="p-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailsModal<?= $row['id'] ?>">
                                    <img src="svg/folder-open.svg" alt="" width="20">
                                </a>
                                <a href="?route=liste_carte_grise&&id=<?= urlencode($row['id']) ?>" 
                                   class="p-1" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">
                                   <img src="svg/delete.svg" alt="" width="20">
                                </a>
                            </td>
                        </tr>

                        <!-- Modal des détails -->
                        <div class="modal fade" id="detailsModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel<?= $row['id'] ?>">Détails de la Carte Grise</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        try {
                                            $stmt = $conn->prepare("SELECT * FROM carte_grise WHERE id = :id");
                                            $stmt->bindParam(':id', $row['id']);
                                            $stmt->execute();
                                            $details = $stmt->fetch(PDO::FETCH_ASSOC);
                                        } catch (PDOException $e) {
                                            echo "<div class='alert alert-danger'>Erreur lors de la récupération des détails : " . htmlspecialchars($e->getMessage()) . "</div>";
                                        }
                                        ?>

                                        <?php if ($details): ?>
                                            <ul class="list-group">
                                                <?php foreach ($details as $key => $value): ?>
                                                    <li class="list-group-item">
                                                        <strong><?= htmlspecialchars(ucfirst($key)) ?>:</strong> <?= htmlspecialchars($value) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <div class="alert alert-warning">Détails introuvables.</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin du modal -->
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">Aucune carte grise trouvée.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
