<?php
include 'includes/database.php';
include 'includes/header.php';

$user_id = $_GET['user_id'] ?? 0;

// Get single user details
$sql = "SELECT u.*, 
               s.major, s.class_year,
               rs.college,
               GROUP_CONCAT(DISTINCT r.role_name) as roles,
               COUNT(DISTINCT p.post_id) as post_count,
               COUNT(DISTINCT e.event_id) as event_count,
               COUNT(DISTINCT rsvp.rsvp_id) as rsvp_count
        FROM users u 
        LEFT JOIN students s ON u.user_id = s.user_id
        LEFT JOIN residential_staff rs ON u.user_id = rs.user_id
        LEFT JOIN admins a ON u.user_id = a.user_id
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.role_id
        LEFT JOIN posts p ON u.user_id = p.creator_id
        LEFT JOIN Event e ON u.user_id = e.manager_user_id
        LEFT JOIN rsvp ON u.user_id = rsvp.user_id
        WHERE u.user_id = ?
        GROUP BY u.user_id";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<article class="paper" style="text-align: center; padding: 60px 20px;">
            <h2>User Not Found</h2>
            <p style="color: var(--muted); margin-bottom: 30px;">The user you\'re looking for doesn\'t exist.</p>
            <a href="user_results.php" class="cta alt">Back to User Search</a>
          </article>';
    include 'includes/footer.php';
    exit;
}
?>

<article class="paper">
    <h1><?= htmlspecialchars($user['name']) ?></h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px; margin: 30px 0;">
        <div>
            <div style="background: linear-gradient(45deg, var(--brand), var(--accent)); color: white; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 600; min-height: 200px;">
                <?= substr(htmlspecialchars($user['name']), 0, 2) ?>
            </div>
        </div>
        
        <div>
            <div class="features" style="grid-template-columns: 1fr; gap: 16px;">
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">📧</div>
                    <div>
                        <strong>Email</strong>
                        <div style="color: var(--muted);"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                </div>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">🟢</div>
                    <div>
                        <strong>Status</strong>
                        <div style="color: var(--muted);">
                            <span style="color: <?= $user['status'] === 'active' ? 'var(--brand)' : '#dc3545'; ?>">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <?php if ($user['major']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">🎓</div>
                        <div>
                            <strong>Student Information</strong>
                            <div style="color: var(--muted);">
                                <?= htmlspecialchars($user['major']) ?>
                                <?php if ($user['class_year']): ?>
                                    • Class of <?= $user['class_year'] ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($user['college']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">🏠</div>
                        <div>
                            <strong>Residential Staff</strong>
                            <div style="color: var(--muted);"><?= htmlspecialchars($user['college']) ?> College</div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($user['roles']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">👤</div>
                        <div>
                            <strong>Roles</strong>
                            <div style="color: var(--muted);"><?= htmlspecialchars($user['roles']) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">📊</div>
                    <div>
                        <strong>Activity Summary</strong>
                        <div style="color: var(--muted);">
                            <?= $user['post_count'] ?> posts • 
                            <?= $user['event_count'] ?> events managed • 
                            <?= $user['rsvp_count'] ?> event RSVPs
                        </div>
                    </div>
                </div>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">📅</div>
                    <div>
                        <strong>Member Since</strong>
                        <div style="color: var(--muted);"><?= date('F j, Y', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="callout" style="margin: 40px 0;">
        <div>
            <strong>Recent Activity</strong>
            <div class="meta">User posts and event management</div>
        </div>
        <div>
            <a href="user_posts.php?user_id=<?= $user['user_id'] ?>" class="cta">View Posts</a>
            <a href="user_events.php?user_id=<?= $user['user_id'] ?>" class="cta alt" style="margin-left: 10px;">View Managed Events</a>
        </div>
    </div>

    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 30px;">
        <a href="user_results.php" class="cta alt">← Back to Results</a>
        <a href="user_search.php" class="cta">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>