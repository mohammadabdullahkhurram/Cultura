<?php
require __DIR__ . '/db.php';
require_once 'auth_guard.php';

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 0;
$assigned_at = trim($_POST['assigned_at'] ?? '');
if ($assigned_at === '') { $assigned_at = null; }

$errors = [];
if ($user_id <= 0) $errors[] = "User must be selected.";
if ($role_id <= 0) $errors[] = "Role must be selected.";

// check existence to preserve referential integrity
if (!$errors) {
    $existsUser = $pdo->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
    $existsUser->execute([$user_id]);
    if (!$existsUser->fetchColumn()) $errors[] = "Selected user does not exist.";

    $existsRole = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE role_id = ?");
    $existsRole->execute([$role_id]);
    if (!$existsRole->fetchColumn()) $errors[] = "Selected role does not exist.";
}

$ok = false;
if (!$errors) {
    try {
        if ($assigned_at) {
            $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id, assigned_at) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $role_id, $assigned_at]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $role_id]);
        }
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
  <title>Assign role • Result</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <main class="container">
    <article class="paper">
      <h1>User role input feedback</h1>
      <?php if ($ok): ?>
        <p>The role has been assigned to the user successfully.</p>
        <p><a class="cta" href="maintain.html">Return to maintenance</a></p>
      <?php else: ?>
        <p>There was a problem assigning the role.</p>
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?php echo h($err); ?></li>
          <?php endforeach; ?>
        </ul>
        <p><a class="cta alt" href="userrole_new.php">Back to the form</a></p>
        <p><a class="cta" href="maintain.html">Return to maintenance</a></p>
      <?php endif; ?>
    </article>
  </main>
</body>
</html>
