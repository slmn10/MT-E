<?php
// Inclure la connexion PDO
include '../connect_db.php';

// Récupérer les informations de l'utilisateur en fonction de l'ID ou de l'immatriculation
$id = $_GET['id']; // Par exemple, ID passé en paramètre d'URL
$stmt = $conn->prepare("SELECT 
            pvchassis.id, 
            pvchassis.nom, 
            marques.nom AS marque, 
            genres.libelle AS genre, 
            carrosseries.type AS carrosserie, 
            energies.nom AS energie, 
            pvchassis.immat, 
            pvchassis.pv, 
            pvchassis.type, 
            pvchassis.cu, 
            pvchassis.chassis, 
            pvchassis.ptac, 
            pvchassis.puissance, 
            pvchassis.nbrEssieux, 
            pvchassis.date_pv 
        FROM pvchassis
        LEFT JOIN marques ON pvchassis.marque = marques.id
        LEFT JOIN genres ON pvchassis.genre = genres.id
        LEFT JOIN carrosseries ON pvchassis.carrosserie = carrosseries.id
        LEFT JOIN energies ON pvchassis.energie = energies.id
        WHERE pvchassis.id = ?");
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
            width: 350px;
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
        <div class="header-text text-center">
            <img src="../../images/Coat_of_arms_of_Niger.svg.png" alt="Armoiries du Niger" width="80">
            <p>REPUBLIQUE DU NIGER</p>
            <p>******************************</p>
            <p style="font-style: italic; ">Fraternité-Travail-Progrès</p>
            <p>******************************</p>
            <p>MINISTERE DES TRANSPORTS ET DE L'EQUIPEMENT</p>
            <p>******************************</p>
            <p>SECRETARIAT GENERAL</p>
            <p>******************************</p>
            <p>DIRECTION GENERALE DES TRANSPORTS</p>
            <p>******************************</p>
            <p>DIRECTION DE LA CIRCULATION ET DE LA SECURITE ROUTIERE</p>
            <p>******************************</p>
            <p style="font-size: 30px;">Procès-Verbal de Constat</p>
            <center><p class="barre mb-3"></p></center>
        </div>


        <!-- Informations principales -->
        <div class="info">
            <p style="text-align: justify !important;">Il resulte des constatations effectuées ce jour, <span id="current-date"></span> par 
                Monsieur le Directeur Général de la circulation et de la sécurité routière 
                et le Directeur Général des Transports au Mistère des transports et de 
                l'équipements et suivant le procès -verbal dressé par le chef de service 
                PVR du GUAN, en date du 25 Avril 2024, à la demande de M. <strong><?= htmlspecialchars($record['nom']) ?></strong>,
                que le véhicule ci-dessous désigné : 
            </p>
            <ol class="mb-0">
                <li>) Marque : <?= htmlspecialchars($record['marque']) ?></li>
                <li>) Genre : <?= htmlspecialchars($record['genre']) ?></li>
                <li>) Type : <?= htmlspecialchars($record['type']) ?></li>
                <li>) Carrosserie : POUR <?= htmlspecialchars($record['carrosserie']) ?></li>
                <li>) Immatriculation : <?= htmlspecialchars($record['immat']) ?></li>
                <li>) Energie : <?= htmlspecialchars($record['energie']) ?></li>
                <li>) Puissance : <?= htmlspecialchars($record['puissance']) ?> CV</li>
                <li>) Nombre d'essieur : <?= htmlspecialchars($record['nbrEssieux']) ?></li>
                <li>) Charge utile : <?= htmlspecialchars($record['cu']) ?></li>
                <li>) Poids à vide : <?= htmlspecialchars($record['pv']) ?></li>
                <li>) PTAC : <?= htmlspecialchars($record['ptac']) ?></li>
                <p>
                    a subit une transformation : de <strong>Camio-Benne</strong> en <strong><?= htmlspecialchars($record['genre']) ?></strong>.
                    <br>
                    Le numéro de châssis <strong><?= htmlspecialchars($record['chassis']) ?></strong> est resté intact.
                </p>
            </ol>
            <p>Le dossier de l'intéressé sera transmis au <strong>GUAN</strong> pour la ré-immatriculation.</p>
        </div>
        <p class="mt-2">Fait à Niamey, le <span id="current-date1"></span>.</p>

        <div class="d-flex justify-content-between">
            <div>
                <p>Le Directeur Général des Transports</p>
                <br>
                <br>
                <p><u><strong>HAMA IDE</strong></u></p>
            </div>
            <div>
                <p>Le Directeur de la Circulation <br> et de la Sécurité Routière</p>
                <br>
                <p><u><strong>ABDOU ABDOUL-AZIZ</strong></u></p>
            </div>
        </div>
        
    </div>

    <!-- Bouton d'impression -->
    <div class="container text-end no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">Imprimer</button>
        <a href="../index.php?route=liste_attestation" class="btn btn-secondary">Retour</a>
    </div>
    
    <script>
        // Fonction pour afficher la date au format désiré
        function formatDate() {
            const days = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
            const months = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
            
            const today = new Date();
            const dayName = days[today.getDay()];
            const day = today.getDate();
            const monthName = months[today.getMonth()];
            const year = today.getFullYear();
            
            return `${dayName} ${day} ${monthName} ${year}`;
        }

        // Insérer la date formatée dans l'élément HTML
        document.getElementById("current-date").textContent = formatDate();
        document.getElementById("current-date1").textContent = formatDate();
    </script>
</body>

</html>
