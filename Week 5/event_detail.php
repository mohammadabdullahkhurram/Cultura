<?php
include 'includes/database.php';
include 'includes/header.php';

$event_id = $_GET['event_id'] ?? 0;

// Get single event details
$sql = "SELECT e.*, u.name as manager_name, u.email as manager_email,
               GROUP_CONCAT(DISTINCT c.name) as category_names,
               COUNT(DISTINCT r.rsvp_id) as current_rsvp_count,
               w.topic as workshop_topic, w.duration,
               s.dress_code
        FROM Event e 
        LEFT JOIN users u ON e.manager_user_id = u.user_id 
        LEFT JOIN EventCategory ec ON e.event_id = ec.event_id
        LEFT JOIN Category c ON ec.category_id = c.category_id
        LEFT JOIN rsvp r ON e.event_id = r.event_id 
        LEFT JOIN Workshop w ON e.event_id = w.event_id
        LEFT JOIN SocialEvent s ON e.event_id = s.event_id
        WHERE e.event_id = ?
        GROUP BY e.event_id";

$stmt = $pdo->prepare($sql);
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo '<article class="paper" style="text-align: center; padding: 60px 20px;">
            <h2>Event Not Found</h2>
            <p style="color: var(--muted); margin-bottom: 30px;">The event you\'re looking for doesn\'t exist.</p>
            <a href="event_results.php" class="cta alt">Back to Event Search</a>
          </article>';
    include 'includes/footer.php';
    exit;
}
?>

<article class="paper">
    <h1><?= htmlspecialchars($event['name']) ?></h1>

    <?php if (!$event['is_published']): ?>
        <div style="background: rgba(255,193,7,0.1); color: #856404; padding: 12px 16px; border: 1px solid rgba(255,193,7,0.3); border-radius: 8px; margin: 20px 0;">
            <strong>Note:</strong> This event is not published yet and may not be visible to all users.
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px; margin: 30px 0;">
        <?php if ($event['event_poster_url']): ?>
            <div>
                <img src="<?= $event['event_poster_url'] ?>" alt="Event Poster" 
                     style="width: 100%; border-radius: var(--radius); border: 1px solid var(--border);">
            </div>
        <?php else: ?>
            <div style="background: linear-gradient(45deg, var(--brand), var(--accent)); color: white; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 600; min-height: 200px;">
                <?= substr(htmlspecialchars($event['name']), 0, 2) ?>
            </div>
        <?php endif; ?>
        
        <div>
            <?php if ($event['description']): ?>
                <div style="margin-bottom: 30px;">
                    <h2 style="color: var(--brand); margin-bottom: 12px;">Description</h2>
                    <p style="line-height: 1.6; color: var(--ink);"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                </div>
            <?php endif; ?>
            
            <div class="features" style="grid-template-columns: 1fr; gap: 16px;">
                <?php if ($event['start_time']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">📅</div>
                        <div>
                            <strong>Start Time</strong>
                            <div style="color: var(--muted);"><?= date('F j, Y g:i A', strtotime($event['start_time'])) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($event['end_time']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">⏰</div>
                        <div>
                            <strong>End Time</strong>
                            <div style="color: var(--muted);"><?= date('F j, Y g:i A', strtotime($event['end_time'])) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">📍</div>
                    <div>
                        <strong>Location</strong>
                        <div style="color: var(--muted);"><?= htmlspecialchars($event['location']) ?></div>
                    </div>
                </div>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">🎯</div>
                    <div>
                        <strong>Event Type</strong>
                        <div style="color: var(--muted);">
                            <?php
                            if ($event['workshop_topic']) {
                                echo 'Workshop - ' . htmlspecialchars($event['workshop_topic']);
                            } elseif ($event['dress_code']) {
                                echo 'Social Event - Dress: ' . htmlspecialchars($event['dress_code']);
                            } else {
                                echo 'General Event';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <?php if ($event['category_names']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">🏷️</div>
                        <div>
                            <strong>Categories</strong>
                            <div style="color: var(--muted);"><?= htmlspecialchars($event['category_names']) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">👥</div>
                    <div>
                        <strong>Attendance</strong>
                        <div style="color: var(--muted);"><?= $event['current_rsvp_count'] ?>/<?= $event['capacity'] ?> registered</div>
                    </div>
                </div>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">👤</div>
                    <div>
                        <strong>Event Manager</strong>
                        <div style="color: var(--muted);"><?= htmlspecialchars($event['manager_name']) ?></div>
                        <?php if ($event['manager_email']): ?>
                            <div style="color: var(--brand); font-size: 14px;"><?= htmlspecialchars($event['manager_email']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RSVP Section -->
    <div class="callout" style="margin: 40px 0;">
        <div>
            <strong>RSVP for this Event</strong>
            <div class="meta">
                <?php if ($event['current_rsvp_count'] < $event['capacity']): ?>
                    Reserve your spot now
                <?php else: ?>
                    This event is at full capacity
                <?php endif; ?>
            </div>
        </div>
        <div>
            <?php if ($event['current_rsvp_count'] < $event['capacity']): ?>
                <form action="rsvp_handler.php" method="POST" style="display: flex; gap: 12px; align-items: center;">
                    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                    <select name="rsvp_status" required 
                            style="padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; background: white; min-width: 150px;">
                        <option value="">Select Status</option>
                        <?php
                        $status_stmt = $pdo->query("SELECT status_id, code FROM rsvp_status ORDER BY status_id");
                        $statuses = $status_stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($statuses as $status) {
                            echo "<option value='{$status['status_id']}'>{$status['code']}</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="cta" style="padding: 10px 20px;">Submit RSVP</button>
                </form>
            <?php else: ?>
                <div style="background: #dc3545; color: white; padding: 12px 16px; border-radius: 8px; text-align: center;">
                    This event is at full capacity. No more RSVPs can be accepted.
                </div>
            <?php endif; ?>
            </div>
    </div>

    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 30px;">
        <a href="event_results.php" class="cta alt">← Back to Results</a>
        <a href="event_search.php" class="cta">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>