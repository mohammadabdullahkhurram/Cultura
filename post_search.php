<?php require_once 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Search Posts</title>
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
    <h1>Search Posts</h1>
    <form action="post_results.php" method="get">
      <label>Keyword<br>
        <input type="text" name="q" required>
      </label><br><br>
      <button class="cta" type="submit">Search</button>
    </form>
  </article>
</main>

<footer class="site-footer">
  <p>&copy; Cultura</p>
</footer>
</body>
</html>