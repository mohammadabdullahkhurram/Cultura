<?php require 'db.php'; session_start(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Login • Cultura</title>
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
    <h1>Admin Login</h1>

    <?php if (!empty($_GET['err'])): ?>
      <div class="error" style="background: #fee2e2;
	  border: 1px solid #f38d8dff;
	  color: #991b1b;
      border-radius: 8px;
	  padding: 10px;
	  margin-bottom: 16px;
	  text-align: center;">
        Invalid username or password.
      </div>
    <?php endif; ?>

    <form action="do_login.php" method="post">
      <label>Username<br>
        <input name="username" required>
      </label><br><br>

      <label>Password<br>
        <input type="password" name="password" required>
      </label><br><br>

      <button class="cta" type="submit">Login</button>
    </form>
  </article>
</main>

<footer class="site-footer">
  <p>&copy; <?= date('Y') ?> Cultura</p>
</footer>
</body>
</html>