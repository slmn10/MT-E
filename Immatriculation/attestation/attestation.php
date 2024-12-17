<?php
// Inclure la connexion PDO
include '../connect_db.php';

// Récupérer les informations de l'utilisateur en fonction de l'ID ou de l'immatriculation
$id = $_GET['id']; // Par exemple, ID passé en paramètre d'URL
$stmt = $conn->prepare("SELECT * FROM attestations WHERE id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    echo "Aucun enregistrement trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation Provisoire - Certificat d'Immatriculation W</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .attestation {
            padding: 0px 20px;
        }

        .no-print {
            margin-top: 20px;
        }

        .header-text {
            text-align: start;
            font-weight: bold;
        }

        .title-box {
            text-align: center;
            border: 2px solid black;
            padding: 10px;
            margin: 20px 0;
            font-size: 18px;
        }

        .outlined-text {
            font-weight: bold;
            font-size: 22px;
            color: white;
            text-shadow:
                -1px -1px 0 #000,
                1px -1px 0 #000,
                -1px 1px 0 #000,
                1px 1px 0 #000;
        }

        .barre {
            background-color: black;
            border: 2px solid black;
            padding: 3px;
        }

        .header-text p,
        .info p,
        .immat {
            margin: 0;
            padding: 0;
        }

        /* Cache les éléments avec la classe "no-print" lors de l'impression */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="attestation">
        <!-- En-tête du document -->
        <div class="header-text">
            <p>REPUBLIQUE DU NIGER</p>
            <p>******************************</p>
            <p>MINISTERE DES TRANSPORTS ET DE L'EQUIPEMENT</p>
            <p>******************************</p>
            <p>SECRETARIAT GENERAL</p>
            <p>******************************</p>
            <p>DIRECTION GENERALE DES TRANSPORTS</p>
            <p>******************************</p>
            <p>DIRECTION DE LA CIRCULATION ET DE LA SECURITE ROUTIERE</p>
            <p>******************************</p>
            <p>Division de la Circulation Routière</p>
            <p class="barre"></p>
        </div>

        <!-- Titre principal -->
        <div class="title-box">
            <span class="outlined-text">ATTESTATION PROVISOIRE TENANT LIEU DE CERTIFICAT D'IMMATRICULATION W</span>
        </div>

        <!-- Informations principales -->
        <div class="info">
            <p>Je soussigné, le Directeur de la Circulation et de la Sécurité Routières, atteste que :</p>
            <p>Mr/Mlle/Mme : <strong><?= htmlspecialchars($record['nom']) ?></strong></p>
            <p>Adresse : <?= htmlspecialchars($record['adresse']) ?>, Tel. <?= htmlspecialchars($record['telephone']) ?></p>
            <p>A déposé auprès de nos services, une demande d'immatriculation comprenant :</p>
            <ol>
                <li>) une ancienne carte grise ;</li>
                <li>) Autorisation d'ouverture d'entrepôt privé, numéro 000121/DGD/DFP ;</li>
                <li>) Le reçu du paiement de l'impôt de l'année en cours ;</li>
                <li>) La police d'assurance d'un an liée au numéro du certificat d'immatriculation W ;</li>
                <li>) Timbre fiscal (17000).</li>
            </ol>
        </div>

        <p>Déclare mettre en circulation à titre provisoire, UNIQUEMENT aux fins d'ESSAIS ou de VENTE, un véhicule avant son immatriculation définitive ou en réparation.</p>

        <p class="immat">Il lui a été attribué le numéro : <strong><?= htmlspecialchars($record['immatriculation']) ?></strong></p>
        <p>La présente attestation délivrée en attendant l'établissement du certificat d'immatriculation est valable pour une période d'un (01) mois à compter du <strong><?= htmlspecialchars($record ['date']) ?></strong>.</p>

        <p><strong>NB :</strong> Le certificat d'immatriculation W n'est pas rattaché à un véhicule en particulier. Il est interdit de faire circuler simultanément plusieurs véhicules avec le même numéro.</p>
        <p>En foi de quoi la présente attestation est délivrée pour servir et valoir ce que de droit.</p>
        <p class="text-center"><u><strong>Le Directeur de la Circulation et de la Sécurité Routière</strong></u></p>
        <p class="text-center"><u><strong>ADAM ELH GANGAMA</strong></u></p>
    </div>

    <!-- Bouton d'impression -->
    <div class="container text-end no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">Imprimer</button>
        <a href="../index.php?route=liste_attestation" class="btn btn-secondary">Retour</a>
    </div>
</body>

</html>
