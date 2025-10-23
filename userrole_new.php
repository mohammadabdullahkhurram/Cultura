<?php require __DIR__ . '/db.php';
// Fetch users and roles for the selects
$users = $pdo->query("SELECT user_id, name, email FROM users ORDER BY created_at DESC")->fetchAll();
$roles = $pdo->query("SELECT role_id, role_name FROM roles ORDER BY role_name")->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Assign role • Cultura</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .paper { max-width: 720px; }
    form label { display: block; font-weight: 600; margin: 12px 0 6px; color: var(--brand-ink); }
    select, input[type="datetime-local"] {
      width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 10px;
    }
    .inline { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .actions { margin-top: 18px; display: flex; gap: 12px; }
  </style>
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
        <a href="maintain.html" aria-current="page">Maintain</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <article class="paper">
      <h1>Assign a role to a user</h1>

      <form method="post" action="userrole_create.php">
        <label for="user_id">User</label>
        <div class="inline">
          <select id="user_id" name="user_id" required>
            <option value="">Choose a user</option>
            <?php foreach ($users as $u): ?>
              <option value="<?php echo (int)$u['user_id']; ?>">
                <?php echo h($u['name'] . " • " . $u['email']); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <a class="cta alt" href="list_users.php" target="_blank" rel="noopener">Open full user list</a>
        </div>

        <label for="role_id">Role</label>
        <div class="inline">
          <select id="role_id" name="role_id" required>
            <option value="">Choose a role</option>
            <?php foreach ($roles as $r): ?>
              <option value="<?php echo (int)$r['role_id']; ?>">
                <?php echo h($r['role_name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <a class="cta alt" href="list_roles.php" target="_blank" rel="noopener">Open full role list</a>
        </div>

        <label for="assigned_at">Assigned at</label>
        <input type="datetime-local" id="assigned_at" name="assigned_at" />

        <div class="actions">
          <button class="cta" type="submit">Assign role</button>
          <a class="cta alt" href="maintain.html">Back to maintenance</a>
        </div>
      </form>
    </article>
  </main>

  <footer class="site-footer">
    <div class="inner">
      <div>© <span id="yr"></span> Cultura</div>
      <div><a href="imprint.html">Imprint and disclaimer</a></div>
    </div>
  </footer>
  <script>document.getElementById('yr').textContent = new Date().getFullYear();</script>
</body>
</html>
