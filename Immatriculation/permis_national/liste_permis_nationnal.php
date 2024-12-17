<?php
try {
    // Récupération des enregistrements de la table `permis`
    $stmt = $conn->query("SELECT id, nom, prenom, categorie, telephone FROM permis where copie_permis IS NULL");
    $permis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</div>");
}

// Suppression d'un permis si un ID est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $stmt = $conn->prepare("DELETE FROM permis WHERE id = :id");
    $stmt->bindParam(':id', $id);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Redirection avec message de succès
        header('Location: ?route=liste_permis_nationnal&&success=3');
        exit();
    } else {
        // Redirection avec message d'erreur
        header('Location: ?route=liste_permis_nationnal&&error=1');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Permis</title>
    <link rel="stylesheet" href="chemin/vers/bootstrap.css">
    <script src="chemin/vers/bootstrap.bundle.js"></script>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Liste des Permis</h5>
            <a href="../service document/formulaire.php?page=permis_national" class="btn btn-primary">Ajouter un Permis</a>
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
            <!-- Table des permis -->
            <?php if (!empty($permis)): ?>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Catégorie</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($permis as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['prenom']) ?></td>
                            <td><?= htmlspecialchars($row['categorie']) ?></td>
                            <td><?= htmlspecialchars($row['telephone']) ?></td>
                            <td>
                                <a type="button" class="p-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailsModal<?= $row['id'] ?>">
                                        <img src="svg/folder-open.svg" alt="" width="20">
                                </a>
                                <a href="?route=liste_permis_nationnal&&id=<?= urlencode($row['id']) ?>"
                                   class="p-1"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">
                                   <img src="svg/delete.svg" alt="" width="20">
                                </a>
                            </td>
                        </tr>

                        <!-- Modal des détails -->
                        <div class="modal fade" id="detailsModal<?= $row['id'] ?>" tabindex="-1"
                             aria-labelledby="modalLabel<?= $row['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel<?= $row['id'] ?>">Détails du Permis</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        try {
                                            $stmt = $conn->prepare("SELECT * FROM permis WHERE id = :id");
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
                                                    <?php if ($key === 'photo' || $key === 'copie_pc'): ?>
                                                        <li class="list-group-item">
                                                            <strong><?= htmlspecialchars(ucfirst($key)) ?>:</strong>
                                                            <?php if (!empty($value)): ?>
                                                                <a href="../service document/<?= htmlspecialchars($value) ?>" target="_blank" class="btn btn-link">Voir <?= htmlspecialchars($key) ?></a>
                                                            <?php else: ?>
                                                                <span class="text-muted">Non disponible</span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php else: ?>
                                                        <li class="list-group-item">
                                                            <strong><?= htmlspecialchars(ucfirst($key)) ?>:</strong> <?= htmlspecialchars($value) ?>
                                                        </li>
                                                    <?php endif; ?>
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
                <div class="alert alert-warning">Aucun permis trouvé.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
