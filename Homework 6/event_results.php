<?php
include 'includes/database.php';
include 'includes/header.php';

// Get search parameters
$event_name = $_GET['event_name'] ?? '';
$event_type = $_GET['event_type'] ?? '';
$location = $_GET['location'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$category_id = $_GET['category_id'] ?? '';
$published_only = isset($_GET['published_only']);

// Build query
$sql = "SELECT e.*, u.name as manager_name, 
               GROUP_CONCAT(DISTINCT c.name) as category_names,
               COUNT(DISTINCT r.rsvp_id) as rsvp_count,
               w.topic as workshop_topic,
               s.dress_code
        FROM Event e 
        LEFT JOIN users u ON e.manager_user_id = u.user_id 
        LEFT JOIN EventCategory ec ON e.event_id = ec.event_id
        LEFT JOIN Category c ON ec.category_id = c.category_id
        LEFT JOIN rsvp r ON e.event_id = r.event_id
        LEFT JOIN Workshop w ON e.event_id = w.event_id
        LEFT JOIN SocialEvent s ON e.event_id = s.event_id
        WHERE 1=1";

$params = [];

if (!empty($event_name)) {
    $sql .= " AND e.name LIKE ?";
    $params[] = "%$event_name%";
}

if (!empty($location)) {
    $sql .= " AND e.location LIKE ?";
    $params[] = "%$location%";
}

if (!empty($date_from)) {
    $sql .= " AND (e.start_time IS NULL OR DATE(e.start_time) >= ?)";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $sql .= " AND (e.start_time IS NULL OR DATE(e.start_time) <= ?)";
    $params[] = $date_to;
}

if (!empty($category_id)) {
    $sql .= " AND ec.category_id = ?";
    $params[] = $category_id;
}

if ($published_only) {
    $sql .= " AND e.is_published = 1";
}

// Event type filtering
if (!empty($event_type)) {
    if ($event_type === 'workshop') {
        $sql .= " AND w.event_id IS NOT NULL";
    } elseif ($event_type === 'social') {
        $sql .= " AND s.event_id IS NOT NULL";
    } elseif ($event_type === 'general') {
        $sql .= " AND w.event_id IS NULL AND s.event_id IS NULL";
    }
}

$sql .= " GROUP BY e.event_id";
$sql .= " ORDER BY e.start_time ASC, e.name ASC";

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<article class="paper">
    <h1>Event Search Results</h1>

    <!-- Display search criteria -->
    <div class="callout" style="margin-bottom: 30px;">
        <div>
            <strong>Search Criteria</strong>
            <div class="meta">
                <?php
                $criteria = [];
                if (!empty($event_name)) $criteria[] = "Name: '$event_name'";
                if (!empty($event_type)) $criteria[] = "Type: " . ucfirst($event_type);
                if (!empty($location)) $criteria[] = "Location: '$location'";
                if (!empty($date_from)) $criteria[] = "From: $date_from";
                if (!empty($date_to)) $criteria[] = "To: $date_to";
                if (!empty($category_id)) {
                    $cat_stmt = $pdo->prepare("SELECT name FROM Category WHERE category_id = ?");
                    $cat_stmt->execute([$category_id]);
                    $category = $cat_stmt->fetch();
                    $criteria[] = "Category: " . $category['name'];
                }
                if ($published_only) $criteria[] = "Published only";
                
                echo implode(" • ", $criteria) ?: "All published events";
                ?>
            </div>
        </div>
        <div style="text-align: right;">
            <strong>Found</strong>
            <div class="meta"><?= count($events) ?> events</div>
        </div>
    </div>

    <?php if (empty($events)): ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--muted);">
            <p style="font-size: 18px; margin-bottom: 20px;">No events found matching your criteria.</p>
            <a href="event_search.php" class="cta alt">Try another search</a>
        </div>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($events as $event): ?>
                <article class="card">
                    <div class="card-image">
                        <?= substr(htmlspecialchars($event['name']), 0, 2) ?>
                    </div>
                    <div class="body">
                        <h3>
                            <?= htmlspecialchars($event['name']) ?>
                            <?php if (!$event['is_published']): ?>
                                <span style="background: var(--accent); color: #1b1f24; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;">Draft</span>
                            <?php endif; ?>
                        </h3>
                        
                        <div class="meta">
                            <?php
                            if ($event['workshop_topic']) {
                                echo 'Workshop';
                            } elseif ($event['dress_code']) {
                                echo 'Social Event';
                            } else {
                                echo 'General Event';
                            }
                            ?>
                            • <?= htmlspecialchars($event['location']) ?>
                        </div>
                        
                        <?php if ($event['start_time']): ?>
                            <div class="meta">
                                📅 <?= date('M j, Y g:i A', strtotime($event['start_time'])) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($event['category_names']): ?>
                            <div class="meta">
                                🏷️ <?= htmlspecialchars($event['category_names']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="meta">
                            👤 <?= htmlspecialchars($event['manager_name']) ?> • 
                            📊 <?= $event['rsvp_count'] ?>/<?= $event['capacity'] ?> registered
                        </div>
                        
                        <?php if ($event['description']): ?>
                            <p style="margin: 12px 0; font-size: 14px; line-height: 1.5;">
                                <?= nl2br(htmlspecialchars(substr($event['description'], 0, 120))) ?><?= strlen($event['description']) > 120 ? '...' : '' ?>
                            </p>
                        <?php endif; ?>
                        
                        <a href="event_detail.php?event_id=<?= $event['event_id'] ?>" class="cta" style="display: inline-block; padding: 8px 16px; font-size: 14px; text-align: center;">
                            View Details & RSVP
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="event_search.php" class="cta alt">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>