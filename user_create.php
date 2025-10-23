<?php
require __DIR__ . '/db.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$status = $_POST['status'] ?? 'active';

$errors = [];
if ($name === '') $errors[] = "Name is required.";
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";
if ($password === '') $errors[] = "Password is required.";
if (!in_array($status, ['active','suspended','deactivated'], true)) $errors[] = "Invalid status.";

$ok = false;
$userId = null;
if (!$errors) {
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $status]);
        $userId = $pdo->lastInsertId();
        $ok = true;
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add user • Result</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <main class="container">
    <article class="paper">
      <h1>User input feedback</h1>
      <?php if ($ok): ?>
        <p>The user has been created successfully.</p>
        <p><strong>User id:</strong> <?php echo h($userId); ?></p>
        <p><strong>Name:</strong> <?php echo h($name); ?></p>
        <p><strong>Email:</strong> <?php echo h($email); ?></p>
        <p><a class="cta" href="maintain.html">Return to maintenance</a></p>
      <?php else: ?>
        <p>There was a problem creating the user.</p>
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?php echo h($err); ?></li>
          <?php endforeach; ?>
        </ul>
        <p><a class="cta alt" href="user_new.php">Back to the form</a></p>
        <p><a class="cta" href="maintain.html">Return to maintenance</a></p>
      <?php endif; ?>
    </article>
  </main>
</body>
</html>
