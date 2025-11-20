<?php
include 'includes/database.php';

header('Content-Type: application/json');

$term = isset($_GET['term']) ? $_GET['term'] : '';

try {
    if (empty($term)) {
        $stmt = $pdo->prepare("SELECT DISTINCT title FROM posts ORDER BY title LIMIT 50");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT DISTINCT title FROM posts WHERE title LIKE :term ORDER BY title LIMIT 20");
        $stmt->execute(['term' => '%' . $term . '%']);
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $titles = array_column($results, 'title');
    
    echo json_encode($titles);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
