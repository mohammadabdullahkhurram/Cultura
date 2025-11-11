<?php
require_once 'auth_guard.php';
//DB CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cultura";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

//read post data
$creator_id = isset($_POST['creator_id']) ? (int)$_POST['creator_id'] : 0;
$type_id    = isset($_POST['type_id']) ? (int)$_POST['type_id'] : 0;
$title      = trim($_POST['title'] ?? '');
$content    = trim($_POST['content'] ?? '');
$country    = trim($_POST['country'] ?? '');
$theme      = trim($_POST['theme'] ?? '');
$attachments_url = null;

//image upload  
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['image'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Upload error: " . $file['error']);
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        die("Image too large (max 2MB)");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];
    if (!isset($allowed[$mime])) {
        die("Invalid image type");
    }

    $uploadsDir = __DIR__ . '/uploads';
    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

    $ext = $allowed[$mime];
    $safeBase = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
    $newName = $safeBase . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destPath = $uploadsDir . '/' . $newName;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        die("Failed to save uploaded file.");
    }

    $attachments_url = 'uploads/' . $newName;
}

//insert into DB 
$sql = "INSERT INTO posts (creator_id, type_id, title, content, country, theme, attachments_url)
        VALUES (?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssss", $creator_id, $type_id, $title, $content, $country, $theme, $attachments_url);
$ok = $stmt->execute();
$err = $conn->error;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Post Result • Cultura</title>
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
      <?php if ($ok): ?>
        <h1>✅ Post Added Successfully</h1>
        <p><strong>Title:</strong> <?= htmlspecialchars($title) ?></p>
        <p><strong>ID:</strong> <?= (int)$conn->insert_id ?></p>
        <?php if ($attachments_url): ?>
          <p><strong>Image:</strong></p>
          <img src="<?= htmlspecialchars($attachments_url) ?>" alt="Post image" style="max-width:300px;border:1px solid #eee;border-radius:6px;">
        <?php endif; ?>
      <?php else: ?>
        <h1>Error Adding Post</h1>
        <p><?= htmlspecialchars($err) ?></p>
      <?php endif; ?>
      <p><a href="maintenance.html">← Back to Maintenance</a></p>
    </article>
  </main>
</body>
</html>
