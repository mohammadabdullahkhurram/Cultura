<?php
require 'db.php';
require_once 'auth_guard.php';
$event_id = $_POST['event_id'] ?? null;
$category_id = $_POST['category_id'] ?? null;

if (!$event_id || !$category_id) {
  echo "<p>Missing event or category.</p>";
  echo '<p><a href="maintenance.html">Back to Maintenance</a></p>';
  exit;
}

try {
  $stmt = $db->prepare("INSERT INTO event_category (event_id, category_id) VALUES (?, ?)");
  $stmt->execute([$event_id, $category_id]);
  echo "<p>Event linked to category successfully.</p>";
} catch (PDOException $e) {
  echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo '<p><a href="maintenance.html">Back to Maintenance</a></p>';
?>

