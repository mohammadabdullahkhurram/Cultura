<?php
include 'includes/database.php';
include 'includes/header.php';

// Get search parameters
$user_name = $_GET['user_name'] ?? '';
$user_email = $_GET['user_email'] ?? '';
$user_role = $_GET['user_role'] ?? '';
$user_status = $_GET['user_status'] ?? '';
$major = $_GET['major'] ?? '';

// Build query based on your schema
$sql = "SELECT u.*, 
               s.major, s.class_year,
               rs.college,
               GROUP_CONCAT(r.role_name) as roles,
               COUNT(DISTINCT p.post_id) as post_count,
               COUNT(DISTINCT e.event_id) as event_count
        FROM users u 
        LEFT JOIN students s ON u.user_id = s.user_id
        LEFT JOIN residential_staff rs ON u.user_id = rs.user_id
        LEFT JOIN admins a ON u.user_id = a.user_id
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.role_id
        LEFT JOIN posts p ON u.user_id = p.creator_id
        LEFT JOIN Event e ON u.user_id = e.manager_user_id
        WHERE 1=1";

$params = [];

if (!empty($user_name)) {
    $sql .= " AND u.name LIKE ?";
    $params[] = "%$user_name%";
}

if (!empty($user_email)) {
    $sql .= " AND u.email LIKE ?";
    $params[] = "%$user_email%";
}

if (!empty($user_status)) {
    $sql .= " AND u.status = ?";
    $params[] = $user_status;
}

if (!empty($major)) {
    $sql .= " AND s.major LIKE ?";
    $params[] = "%$major%";
}

// Role-based filtering
if (!empty($user_role)) {
    if ($user_role === 'student') {
        $sql .= " AND s.user_id IS NOT NULL";
    } elseif ($user_role === 'residential_staff') {
        $sql .= " AND rs.user_id IS NOT NULL";
    } elseif ($user_role === 'admin') {
        $sql .= " AND a.user_id IS NOT NULL";
    }
}

$sql .= " GROUP BY u.user_id";
$sql .= " ORDER BY u.name ASC";

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<article class="paper">
    <h1>User Search Results</h1>

    <!-- Display search criteria -->
    <div class="callout" style="margin-bottom: 30px;">
        <div>
            <strong>Search Criteria</strong>
            <div class="meta">
                <?php
                $criteria = [];
                if (!empty($user_name)) $criteria[] = "Name: '$user_name'";
                if (!empty($user_email)) $criteria[] = "Email: '$user_email'";
                if (!empty($user_role)) $criteria[] = "Role: " . ucfirst(str_replace('_', ' ', $user_role));
                if (!empty($user_status)) $criteria[] = "Status: " . ucfirst($user_status);
                if (!empty($major)) $criteria[] = "Major: '$major'";
                
                echo implode(" • ", $criteria) ?: "All users";
                ?>
            </div>
        </div>
        <div style="text-align: right;">
            <strong>Found</strong>
            <div class="meta"><?= count($users) ?> users</div>
        </div>
    </div>

    <?php if (empty($users)): ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--muted);">
            <p style="font-size: 18px; margin-bottom: 20px;">No users found matching your criteria.</p>
            <a href="user_search.php" class="cta alt">Try another search</a>
        </div>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($users as $user): ?>
                <article class="card">
                    <div class="card-image">
                        <?= substr(htmlspecialchars($user['name']), 0, 2) ?>
                    </div>
                    <div class="body">
                        <h3>
                            <?= htmlspecialchars($user['name']) ?>
                            <span style="background: <?= $user['status'] === 'active' ? 'var(--brand)' : '#dc3545'; ?>; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </h3>
                        
                        <div class="meta">
                            📧 <?= htmlspecialchars($user['email']) ?>
                        </div>
                        
                        <div class="meta">
                            🏷️ 
                            <?php
                            if ($user['major']) {
                                echo 'Student • ' . htmlspecialchars($user['major']);
                            } elseif ($user['college']) {
                                echo 'Residential Staff • ' . htmlspecialchars($user['college']);
                            } else {
                                echo 'Administrator';
                            }
                            ?>
                        </div>
                        
                        <?php if ($user['roles']): ?>
                            <div class="meta">
                                👤 Roles: <?= htmlspecialchars($user['roles']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="meta">
                            📊 <?= $user['post_count'] ?> posts • <?= $user['event_count'] ?> events managed
                        </div>
                        
                        <div class="meta">
                            📅 Joined: <?= date('M j, Y', strtotime($user['created_at'])) ?>
                        </div>
                        
                        <a href="user_detail.php?user_id=<?= $user['user_id'] ?>" class="cta" style="display: inline-block; padding: 8px 16px; font-size: 14px; text-align: center;">
                            View Profile
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="user_search.php" class="cta alt">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>