<?php require __DIR__ . '/db.php';
require_once 'auth_guard.php';

$rows = $pdo->query("SELECT role_id, role_name, description FROM roles ORDER BY role_name")->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Role list • Cultura</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px 10px; border-bottom: 1px solid var(--border); text-align: left; }
    th { color: var(--brand-ink); }
    .hint { color: var(--muted); font-size: 14px; }
  </style>
</head>
<body>
  <main class="container">
    <article class="paper">
      <h1>Roles</h1>
      <p class="hint">This list helps you choose a meaningful reference for relationship input.</p>
      <table>
        <thead><tr><th>Role</th><th>Description</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?php echo h($r['role_name']); ?></td>
              <td><?php echo h($r['description']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p><a class="cta" href="maintain.html" target="_self">Return to maintenance</a></p>
    </article>
  </main>
</body>
</html>
