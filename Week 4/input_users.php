<?php require __DIR__ . '/db.php';
require_once 'auth_guard.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add user • Cultura</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .paper { max-width: 720px; }
    form .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    form label { display: block; font-weight: 600; margin: 12px 0 6px; color: var(--brand-ink); }
    input[type="text"], input[type="email"], input[type="password"], select {
      width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 10px;
    }
    .actions { margin-top: 18px; display: flex; gap: 12px; }
    button, .cta { border: 0; }
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
      <h1>Add a user</h1>
      <form method="post" action="user_create.php" novalidate>
        <label for="name">Full name</label>
        <input id="name" name="name" type="text" required />

        <label for="email">Email address</label>
        <input id="email" name="email" type="email" required />

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required />

        <label for="status">Status</label>
        <select id="status" name="status" required>
          <option value="active" selected>active</option>
          <option value="suspended">suspended</option>
          <option value="deactivated">deactivated</option>
        </select>

        <div class="actions">
          <button class="cta" type="submit">Create user</button>
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
