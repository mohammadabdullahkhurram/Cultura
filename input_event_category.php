<?php
require 'db.php';

// fetch events and categories
try {
  $events = $db->query("SELECT id, title FROM events ORDER BY title")->fetchAll(PDO::FETCH_ASSOC);
  $categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database error: " . htmlspecialchars($e->getMessage()));
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Input Event Category • Cultura</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <main class="container">
    <article class="paper">
      <h1>Link Event to Category</h1>
      <form method="post" action="eventcategoryfeedback.php">
        <label for="event_id">Select Event:</label><br>
        <select id="event_id" name="event_id" required>
          <option value="">-- choose an event --</option>
          <?php foreach ($events as $e): ?>
            <option value="<?= htmlspecialchars($e['id']) ?>">
              <?= htmlspecialchars($e['title']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <a href="list_events.php" onclick="window.open('list_events.php','events','width=600,height=600');return false;">View events</a>
        <br><br>

        <label for="category_id">Select Category:</label><br>
        <select id="category_id" name="category_id" required>
          <option value="">-- choose a category --</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= htmlspecialchars($c['id']) ?>">
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <a href="list_categories.php" onclick="window.open('list_categories.php','categories','width=600,height=600');return false;">View categories</a>
        <br><br>

        <button type="submit">Save Link</button>
      </form>
      <p><a href="maintenance.html">Back to Maintenance</a></p>
    </article>
  </main>
</body>
</html>
