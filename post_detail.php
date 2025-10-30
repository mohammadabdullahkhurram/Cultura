<?php require_once 'db.php';
$id = (int)($_GET['id'] ?? 0);
$post = null;
if ($id > 0) {
  $sql = "SELECT p.post_id, p.title, p.content, p.country, p.theme, p.attachments_url,
                 u.name AS creator_name, pt.name AS type_name
          FROM posts p
          LEFT JOIN users u ON u.user_id = p.creator_id
          LEFT JOIN post_types pt ON pt.type_id = p.type_id
          WHERE p.post_id = ?";
  $st = $conn->prepare($sql);
  $st->bind_param("i", $id);
  $st->execute();
  $post = $st->get_result()->fetch_assoc();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Post Details</title>
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
    <?php if (!$post): ?>
      <h1>Not found</h1>
      <p>The post doesn’t exist.</p>
    <?php else: ?>
      <h1><?= htmlspecialchars($post['title']) ?></h1>
      <?php if (!empty($post['attachments_url'])): ?>
        <p><img src="<?= htmlspecialchars($post['attachments_url']) ?>" alt="Post image" 
		style="max-width:420px;height:auto;border:1px solid #eee;border-radius:6px;"></p>
      <?php endif; ?>
      <p><strong>Creator:</strong> <?= htmlspecialchars($post['creator_name'] ?? '—') ?></p>
      <p><strong>Type:</strong> <?= htmlspecialchars($post['type_name'] ?? '—') ?></p>
      <?php if (!empty($post['country'])): ?><p><strong>Country:</strong> <?= htmlspecialchars($post['country']) ?>
	</p><?php endif; ?>
      <?php if (!empty($post['theme'])): ?><p><strong>Theme:</strong> <?= htmlspecialchars($post['theme']) ?>
	</p><?php endif; ?>
      <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <?php endif; ?>
    <p><a href="post_search.php">Back to search</a></p>
  </article>
</main>

<footer class="site-footer">
  <p>&copy; Cultura</p>
</footer>
</body>
</html>