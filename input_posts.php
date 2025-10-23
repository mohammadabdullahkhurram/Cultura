<?php
// ---- DB CONNECTION (adjust if needed) ----
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cultura";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

// fetch creators (users)
$users = $conn->query("SELECT user_id, name FROM users ORDER BY name");

// fetch post types
$types = $conn->query("SELECT type_id, name FROM post_types ORDER BY name");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Post • Cultura</title>
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
      <h1>Add Post</h1>

      <form action="postsfeedback.php" method="post" enctype="multipart/form-data">
        <label>Creator<br>
          <select name="creator_id" required>
            <option value="" disabled selected>Select a user</option>
            <?php while($u = $users->fetch_assoc()): ?>
              <option value="<?= (int)$u['user_id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </label><br><br>

        <label>Post Type<br>
          <select name="type_id" required>
            <option value="" disabled selected>Select a post type</option>
            <?php while($t = $types->fetch_assoc()): ?>
              <option value="<?= (int)$t['type_id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </label><br><br>

        <label>Title<br>
          <input type="text" name="title" required>
        </label><br><br>

        <label>Content<br>
          <textarea name="content" rows="5" required></textarea>
        </label><br><br>

        <label>Country (optional)<br>
          <input type="text" name="country">
        </label><br><br>

        <label>Theme (optional)<br>
          <input type="text" name="theme">
        </label><br><br>

        <label>Image (optional)<br>
          <input type="file" name="image" accept="image/*" id="imgInput">
        </label>
        <img id="preview" style="display:none;max-width:200px;margin-top:10px;border:1px solid #eee;border-radius:6px;"><br><br>

        <button class="cta" type="submit">Submit Post</button>
      </form>

      <p><a href="maintenance.html">← Back to Maintenance</a></p>
    </article>
  </main>

  <script>
    const inp = document.getElementById('imgInput');
    const prev = document.getElementById('preview');
    inp.addEventListener('change', () => {
      const [file] = inp.files || [];
      if (!file) return;
      prev.src = URL.createObjectURL(file);
      prev.style.display = 'block';
    });
  </script>
</body>
</html>