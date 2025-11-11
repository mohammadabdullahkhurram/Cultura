<?php
require_once 'auth_guard.php';
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cultura";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

// fetch users
$users = $conn->query("SELECT user_id, name FROM users ORDER BY name");

// fetch posts
$posts = $conn->query("SELECT post_id, title FROM posts ORDER BY title");

// fetch rsvp statuses
$statuses = $conn->query("SELECT status_id, name FROM rsvp_status ORDER BY name");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add RSVP • Cultura</title>
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
      <h1>Add RSVP</h1>

      <form action="rsvpfeedback.php" method="post">
        <label>User<br>
          <select name="user_id" required>
            <option disabled selected>Select a user</option>
            <?php while($u = $users->fetch_assoc()): ?>
              <option value="<?= (int)$u['user_id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </label><br><br>

        <label>Post<br>
          <select name="post_id" required>
            <option disabled selected>Select a post</option>
            <?php while($p = $posts->fetch_assoc()): ?>
              <option value="<?= (int)$p['post_id'] ?>"><?= htmlspecialchars($p['title']) ?></option>
            <?php endwhile; ?>
          </select>
        </label><br><br>

        <label>Status<br>
          <select name="status_id" required>
            <option disabled selected>Select status</option>
            <?php while($s = $statuses->fetch_assoc()): ?>
              <option value="<?= (int)$s['status_id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </label><br><br>

        <button class="cta" type="submit">Submit RSVP</button>
      </form>

      <p><a href="maintenance.html">← Back to Maintenance</a></p>
    </article>
  </main>
</body>
</html>
