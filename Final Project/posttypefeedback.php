<?php
require_once 'auth_guard.php';
//DB CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cultura";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');

$sql = "INSERT INTO post_types (name, description) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $description);
$ok = $stmt->execute();
$err = $conn->error;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Post Type Feedback • Cultura</title>
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
        <h1>✅ Post Type Added</h1>
        <p><strong><?= htmlspecialchars($name) ?></strong> has been added.</p>
      <?php else: ?>
        <h1>❌ Error</h1>
        <p><?= htmlspecialchars($err) ?></p>
      <?php endif; ?>
      <p><a href="maintenance.html">← Back to Maintenance</a></p>
    </article>
  </main>
</body>
</html>
