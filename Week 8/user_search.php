<?php
//DB CONNECTION
$host = "localhost";
$user = "malgatay";
$pass = "b3CMxxaKEi8hSuAa";
$db   = "db_malgatay";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { 
    die("DB connection failed: " . $conn->connect_error); 
}

// Get search parameters
$name = trim($_GET['name'] ?? '');
$email = trim($_GET['email'] ?? '');
$status = trim($_GET['status'] ?? '');

// Build search query
$sql = "SELECT user_id, name, email, status, created_at FROM users WHERE 1=1";
$params = [];
$types = '';

if (!empty($name)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%$name%";
    $types .= 's';
}

if (!empty($email)) {
    $sql .= " AND email LIKE ?";
    $params[] = "%$email%";
    $types .= 's';
}

if (!empty($status)) {
    $sql .= " AND status = ?";
    $params[] = $status;
    $types .= 's';
}

$sql .= " ORDER BY created_at DESC";

// Execute search
$users = [];
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // If no search criteria, show all users
    $result = $conn->query($sql);
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>User Search • Cultura</title>
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
        <a href="event_search.php">Events</a>
        <a href="post_search.php">Posts</a>
        <a href="rsvp_search.php">My RSVPs</a>
        <a href="maintenance.html">Maintenance</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <article class="paper">
      <h1>User Search</h1>
      
      <form method="get" style="margin-bottom: 32px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600;">Name</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>"
                   style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px;"
                   placeholder="Search by name">
          </div>
          <div>
            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600;">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>"
                   style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px;"
                   placeholder="Search by email">
          </div>
          <div>
            <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600;">Status</label>
            <select name="status" id="status" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px;">
              <option value="">All Statuses</option>
              <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="suspended" <?= $status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
              <option value="deactivated" <?= $status === 'deactivated' ? 'selected' : '' ?>>Deactivated</option>
            </select>
          </div>
        </div>
        <button type="submit" class="cta" style="width: 100%;">🔍 Search Users</button>
      </form>

      <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        <p><strong><?= count($users) ?> user(s) found</strong></p>
        <?php if (!empty($name)): ?>
          <p>Name: "<?= htmlspecialchars($name) ?>"</p>
        <?php endif; ?>
        <?php if (!empty($email)): ?>
          <p>Email: "<?= htmlspecialchars($email) ?>"</p>
        <?php endif; ?>
        <?php if (!empty($status)): ?>
          <p>Status: <?= ucfirst($status) ?></p>
        <?php endif; ?>
      </div>

      <?php if (count($users) > 0): ?>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
          <thead>
            <tr style="background: #f8f9fa;">
              <th style="padding: 12px; border-bottom: 2px solid var(--border); text-align: left;">Name</th>
              <th style="padding: 12px; border-bottom: 2px solid var(--border); text-align: left;">Email</th>
              <th style="padding: 12px; border-bottom: 2px solid var(--border); text-align: left;">Status</th>
              <th style="padding: 12px; border-bottom: 2px solid var(--border); text-align: left;">Joined</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);">
                  <strong><?= htmlspecialchars($user['name']) ?></strong>
                </td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);">
                  <?= htmlspecialchars($user['email']) ?>
                </td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);">
                  <span style="padding: 4px 8px; border-radius: 4px; font-weight: 600;
                        background: <?= $user['status'] == 'active' ? '#d1fae5' : '#fef3c7' ?>;
                        color: <?= $user['status'] == 'active' ? '#065f46' : '#92400e' ?>;">
                    <?= ucfirst($user['status']) ?>
                  </span>
                </td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);">
                  <?= date('M j, Y', strtotime($user['created_at'])) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 8px;">
          <h3 style="color: #666; margin-bottom: 16px;">No Users Found</h3>
          <p>No users match your search criteria.</p>
          <p>Try adjusting your search terms or <a href="input_users.php">add new users</a>.</p>
          <div style="margin-top: 20px;">
            <a href="input_users.php" class="cta">➕ Add New User</a>
            <a href="list_users.php" class="cta alt">👥 View All Users</a>
          </div>
        </div>
      <?php endif; ?>

      <div style="display: flex; gap: 12px; justify-content: center; margin-top: 32px;">
        <a href="input_users.php" class="cta">➕ Add New User</a>
        <a href="list_users.php" class="cta alt">👥 View All Users</a>
        <a href="maintenance.html" class="cta alt">⚙️ Maintenance</a>
      </div>
    </article>
  </main>
</body>
</html>
