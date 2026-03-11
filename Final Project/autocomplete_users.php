<?php
include 'includes/database.php';

header('Content-Type: application/json');

$term = isset($_GET['term']) ? $_GET['term'] : '';

try {
    if (empty($term)) {
        $stmt = $pdo->prepare("SELECT DISTINCT name FROM users ORDER BY name LIMIT 50");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT DISTINCT name FROM users WHERE name LIKE :term ORDER BY name LIMIT 20");
        $stmt->execute(['term' => '%' . $term . '%']);
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $names = array_column($results, 'name');
    
    echo json_encode($names);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
