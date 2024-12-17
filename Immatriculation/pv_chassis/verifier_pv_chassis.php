<?php
include 'connect_db.php';

$input = json_decode(file_get_contents('php://input'), true);
$immat = $input['immat'] ?? '';

if ($immat) {
    $stmt = $conn->prepare("SELECT * FROM pvchassis WHERE immat = :immat");
    $stmt->bindParam(':immat', $immat);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}

$conn = null;
?>
