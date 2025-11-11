<?php
require_once 'auth_guard.php';
//DB CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cultura";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Post Type • Cultura</title>
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
      <h1>Add Post Type</h1>

      <form action="posttypefeedback.php" method="post">
        <label>Post Type Name<br>
          <input type="text" name="name" required>
        </label><br><br>

        <label>Description (optional)<br>
          <textarea name="description" rows="4"></textarea>
        </label><br><br>

        <button class="cta" type="submit">Submit</button>
      </form>

      <p><a href="maintenance.html">← Back to Maintenance</a></p>
    </article>
  </main>
</body>
</html>
