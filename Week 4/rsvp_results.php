<?php
include 'includes/database.php';
include 'includes/header.php';

//get search parameters
$user_name = $_GET['user_name'] ?? '';
$event_name = $_GET['event_name'] ?? '';
$status_id = $_GET['status_id'] ?? '';

//build query for EVENT RSVPs
$sql = "SELECT r.rsvp_id, r.created_at,
               u.user_id, u.name as user_name, u.email as user_email,
               e.event_id, e.name as event_name, e.start_time, e.location,
               rs.code as status_code, rs.status_id
        FROM rsvp r
        LEFT JOIN users u ON r.user_id = u.user_id
        LEFT JOIN Event e ON r.event_id = e.event_id
        LEFT JOIN rsvp_status rs ON r.status_id = rs.status_id
        WHERE 1=1";

$params = [];

if (!empty($user_name)) {
    $sql .= " AND u.name LIKE ?";
    $params[] = "%$user_name%";
}

if (!empty($event_name)) {
    $sql .= " AND e.name LIKE ?";
    $params[] = "%$event_name%";
}

if (!empty($status_id)) {
    $sql .= " AND r.status_id = ?";
    $params[] = $status_id;
}

$sql .= " ORDER BY r.created_at DESC";

//execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rsvps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<article class="paper">
    <h1>RSVP Search Results</h1>

    <!-- Display search criteria -->
    <div class="callout" style="margin-bottom: 30px;">
        <div>
            <strong>Search Criteria</strong>
            <div class="meta">
                <?php
                $criteria = [];
                if (!empty($user_name)) $criteria[] = "User: '$user_name'";
                if (!empty($event_name)) $criteria[] = "Event: '$event_name'";
                if (!empty($status_id)) {
                    $status_stmt = $pdo->prepare("SELECT code FROM rsvp_status WHERE status_id = ?");
                    $status_stmt->execute([$status_id]);
                    $status = $status_stmt->fetch();
                    $criteria[] = "Status: " . ($status['code'] ?? 'Unknown');
                }
                
                echo implode(" • ", $criteria) ?: "All RSVPs";
                ?>
            </div>
        </div>
        <div style="text-align: right;">
            <strong>Found</strong>
            <div class="meta"><?= count($rsvps) ?> RSVPs</div>
        </div>
    </div>

    <?php if (empty($rsvps)): ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--muted);">
            <p style="font-size: 18px; margin-bottom: 20px;">No RSVPs found matching your criteria.</p>
            <a href="rsvp_search.php" class="cta alt">Try another search</a>
        </div>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($rsvps as $rsvp): ?>
                <article class="card">
                    <div class="card-image">
                        <?= substr(htmlspecialchars($rsvp['user_name']), 0, 2) ?>
                    </div>
                    <div class="body">
                        <h3>
                            <?= htmlspecialchars($rsvp['user_name']) ?>
                            <span style="background: 
                                <?= $rsvp['status_code'] === 'confirmed' ? 'var(--brand)' : 
                                   ($rsvp['status_code'] === 'maybe' ? 'var(--accent)' : '#6c757d'); ?>; 
                                color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;">
                                <?= ucfirst($rsvp['status_code']) ?>
                            </span>
                        </h3>
                        
                        <div class="meta">
                            📧 <?= htmlspecialchars($rsvp['user_email']) ?>
                        </div>
                        
                        <div class="meta">
                            🎯 <?= htmlspecialchars($rsvp['event_name']) ?>
                        </div>
                        
                        <?php if ($rsvp['start_time']): ?>
                            <div class="meta">
                                📅 <?= date('M j, Y g:i A', strtotime($rsvp['start_time'])) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="meta">
                            📍 <?= htmlspecialchars($rsvp['location']) ?>
                        </div>
                        
                        <div class="meta">
                            ⏰ RSVP'd: <?= date('M j, Y g:i A', strtotime($rsvp['created_at'])) ?>
                        </div>
                        
                        <div style="display: flex; gap: 8px; margin-top: 12px;">
                            <a href="user_detail.php?user_id=<?= $rsvp['user_id'] ?>" class="cta" style="display: inline-block; padding: 6px 12px; font-size: 12px; text-align: center;">
                                View User
                            </a>
                            <a href="event_detail.php?event_id=<?= $rsvp['event_id'] ?>" class="cta alt" style="display: inline-block; padding: 6px 12px; font-size: 12px; text-align: center;">
                                View Event
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="rsvp_search.php" class="cta alt">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>
