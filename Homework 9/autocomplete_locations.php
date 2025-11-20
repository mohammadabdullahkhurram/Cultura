<?php
include 'includes/database.php';

header('Content-Type: application/json');

$term = isset($_GET['term']) ? $_GET['term'] : '';

try {
    if (empty($term)) {
        $stmt = $pdo->prepare("SELECT DISTINCT location FROM Event WHERE location IS NOT NULL AND location != '' ORDER BY location LIMIT 50");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT DISTINCT location FROM Event WHERE location LIKE :term AND location IS NOT NULL AND location != '' ORDER BY location LIMIT 20");
        $stmt->execute(['term' => '%' . $term . '%']);
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $locations = array_column($results, 'location');
    
    echo json_encode($locations);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
