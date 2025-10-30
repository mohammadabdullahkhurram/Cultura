<?php require_once 'db.php';
$q = trim($_GET['q'] ?? '');
$rows = [];
if ($q !== '') {
  $sql = "SELECT post_id, title FROM posts WHERE title LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%') ORDER BY title";
  $st = $conn->prepare($sql);
  $st->bind_param("ss", $q, $q);
  $st->execute();
  $rows = $st->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Post Results</title>
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
    <h1>Results for “<?= htmlspecialchars($q) ?>”</h1>
    <?php if ($q === ''): ?>
      <p>No query.</p>
    <?php elseif (!$rows): ?>
      <p>No matches found.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($rows as $r): ?>
          <li>
            <a href="post_detail.php?id=<?= (int)$r['post_id'] ?>">
              <?= htmlspecialchars($r['title']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <p><a href="post_search.php">New search</a></p>
  </article>
</main>

<footer class="site-footer">
  <p>&copy; Cultura</p>
</footer>
</body>
</html>