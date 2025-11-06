<?php
require_once 'auth_guard.php';
// DB CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cultura";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

// Read and sanitize input
$manager_user_id  = (int)($_POST['manager_user_id'] ?? 0);
$name             = trim($_POST['name'] ?? '');
$description      = trim($_POST['description'] ?? '');
$location         = trim($_POST['location'] ?? '');
$date             = trim($_POST['date'] ?? '');
$start_time       = trim($_POST['start_time'] ?? '');
$end_time         = trim($_POST['end_time'] ?? '');
$capacity         = (int)($_POST['capacity'] ?? 0);
$event_poster_url = trim($_POST['event_poster_url'] ?? null);
$is_published     = isset($_POST['is_published']) ? 1 : 0;

// Insert query
$sql = "INSERT INTO Event (manager_user_id, name, description, location, date, start_time, end_time, capacity, event_poster_url, is_published) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssssiis", $manager_user_id, $name, $description, $location, $date, $start_time, $end_time, $capacity, $event_poster_url, $is_published);
$ok = $stmt->execute();
$new_id = (int)$conn->insert_id;
$err = $conn->error;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Event Feedback • Cultura</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <div class="bar">
      <a class="brand" href="index.html">
        <span class="word">CULTURA</span>
        <span class="tag">Constructor University Community</span>
      </a>
      <nav class="nav">
        <a href="index.html">Home</a>
        <a href="maintenance.html">Maintenance</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <article class="paper">
      <?php if ($ok): ?>
        <h1>✅ Event Added</h1>
        <p>Event <strong><?= htmlspecialchars($name) ?></strong> was successfully created with ID: <strong><?= $new_id ?></strong>.</p>
        <p>Date: <?= htmlspecialchars($date) ?> at <?= htmlspecialchars($start_time) ?></p>
      <?php else: ?>
        <h1>❌ Error</h1>
        <p>The event could not be added.</p>
        <p>Error details: <em><?= htmlspecialchars($err) ?></em></p>
      <?php endif; ?>

      <p><a href="maintenance.html">← Back to Maintenance</a></p>
    </article>
  </main>
</body>

</html>

