<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Inclure la connexion PDO
include '../connect_db.php';


if (isset($_POST['immatriculation'])) {
    $immatriculation = $_POST['immatriculation'];

    try {
        // Requête pour récupérer les données
        $sql = "SELECT id, nom, adresse, telephone FROM attestations WHERE immatriculation = :immatriculation";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':immatriculation', $immatriculation, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Renvoyer les résultats en JSON
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } else {
            // Aucun résultat trouvé
            echo json_encode(['error' => 'Aucun enregistrement trouvé pour cette immatriculation.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Paramètre manquant.']);
}
?>
