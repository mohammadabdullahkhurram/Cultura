<?php
//db connectio
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cultura";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

//fetch managers (users) for the Foreign Key select. Displayinng name and email (meaningful attributes)
$managers = $conn->query("SELECT user_id, name, email FROM users ORDER BY name");
if (!$managers) { die("Error fetching users: " . $conn->error); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Event • Cultura</title>
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
      <h1>Add New Event</h1>

      <form action="eventfeedback.php" method="post">

        <label>Event Name<br>
          <input type="text" name="name" required>
        </label><br><br>
        
        <label>Manager (FK)<br>
          <select name="manager_user_id" required>
            <option disabled selected>Select an Event Manager (User Name)</option>
            <?php while($m = $managers->fetch_assoc()): ?>
              <option value="<?= (int)$m['user_id'] ?>">
                <?= htmlspecialchars($m['name']) ?> (<?= htmlspecialchars($m['email']) ?>)
              </option>
            <?php endwhile; ?>
          </select>
        </label><br><br>

        <label>Description<br>
          <textarea name="description" rows="5" required></textarea>
        </label><br><br>

        <label>Location<br>
          <input type="text" name="location" required>
        </label><br><br>

        <label>Date<br>
          <input type="date" name="date" required>
        </label><br><br>

        <label>Start Time<br>
          <input type="time" name="start_time" required>
        </label><br><br>

        <label>End Time<br>
          <input type="time" name="end_time" required>
        </label><br><br>

        <label>Capacity<br>
          <input type="number" name="capacity" min="1" required>
        </label><br><br>

        <label>Poster URL (optional)<br>
          <input type="url" name="event_poster_url">
        </label><br><br>

        <label>
          <input type="checkbox" name="is_published" value="1"> Is Published (Mark to make event visible)
        </label><br><br>
        
        <button class="cta" type="submit">Submit Event</button>
      </form>

      <p><a href="maintenance.html">← Back to Maintenance</a></p>
    </article>
  </main>
</body>
</html>