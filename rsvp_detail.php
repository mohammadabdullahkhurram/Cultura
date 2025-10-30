<?php
include 'includes/database.php';
include 'includes/header.php';

$rsvp_id = $_GET['rsvp_id'] ?? 0;

// Get single RSVP details for EVENT (not post)
$sql = "SELECT r.*,
               u.user_id, u.name as user_name, u.email as user_email, u.status as user_status,
               e.event_id, e.name as event_name, e.description, e.location, e.start_time, e.end_time, e.capacity,
               rs.code as status_code, rs.name as status_name
        FROM rsvp r
        LEFT JOIN users u ON r.user_id = u.user_id
        LEFT JOIN Event e ON r.event_id = e.event_id
        LEFT JOIN rsvp_status rs ON r.status_id = rs.status_id
        WHERE r.rsvp_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$rsvp_id]);
$rsvp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rsvp) {
    echo '<article class="paper" style="text-align: center; padding: 60px 20px;">
            <h2>RSVP Not Found</h2>
            <p style="color: var(--muted); margin-bottom: 30px;">The RSVP you\'re looking for doesn\'t exist.</p>
            <a href="rsvp_results.php" class="cta alt">Back to RSVP Search</a>
          </article>';
    include 'includes/footer.php';
    exit;
}
?>

<article class="paper">
    <h1>RSVP Details</h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px; margin: 30px 0;">
        <div>
            <div style="background: linear-gradient(45deg, var(--brand), var(--accent)); color: white; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 600; min-height: 200px;">
                <?= substr(htmlspecialchars($rsvp['user_name']), 0, 2) ?>
            </div>
        </div>
        
        <div>
            <div class="features" style="grid-template-columns: 1fr; gap: 16px;">
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">👤</div>
                    <div>
                        <strong>User</strong>
                        <div style="color: var(--muted);"><?= htmlspecialchars($rsvp['user_name']) ?></div>
                        <div style="color: var(--brand); font-size: 14px;"><?= htmlspecialchars($rsvp['user_email']) ?></div>
                    </div>
                </div>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">🎯</div>
                    <div>
                        <strong>Event</strong>
                        <div style="color: var(--muted);"><?= htmlspecialchars($rsvp['event_name']) ?></div>
                        <div style="color: var(--brand); font-size: 14px;"><?= htmlspecialchars($rsvp['location']) ?></div>
                    </div>
                </div>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">🟢</div>
                    <div>
                        <strong>RSVP Status</strong>
                        <div style="color: var(--muted);">
                            <span style="color: 
                                <?= $rsvp['status_code'] === 'confirmed' ? 'var(--brand)' : 
                                   ($rsvp['status_code'] === 'maybe' ? 'var(--accent)' : '#6c757d'); ?>">
                                <?= ucfirst($rsvp['status_name']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <?php if ($rsvp['start_time']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">📅</div>
                        <div>
                            <strong>Event Time</strong>
                            <div style="color: var(--muted);">
                                <?= date('F j, Y g:i A', strtotime($rsvp['start_time'])) ?>
                                <?php if ($rsvp['end_time']): ?>
                                    <br>to <?= date('g:i A', strtotime($rsvp['end_time'])) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">⏰</div>
                    <div>
                        <strong>RSVP Created</strong>
                        <div style="color: var(--muted);"><?= date('F j, Y g:i A', strtotime($rsvp['created_at'])) ?></div>
                    </div>
                </div>
                
                <?php if ($rsvp['notes']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">📝</div>
                        <div>
                            <strong>Notes</strong>
                            <div style="color: var(--muted);"><?= nl2br(htmlspecialchars($rsvp['notes'])) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="callout" style="margin: 40px 0;">
        <div>
            <strong>Quick Actions</strong>
            <div class="meta">Manage this RSVP</div>
        </div>
        <div>
            <a href="user_detail.php?user_id=<?= $rsvp['user_id'] ?>" class="cta">View User Profile</a>
            <a href="event_detail.php?event_id=<?= $rsvp['event_id'] ?>" class="cta alt" style="margin-left: 10px;">View Event Details</a>
        </div>
    </div>

    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 30px;">
        <a href="rsvp_results.php" class="cta alt">← Back to Results</a>
        <a href="rsvp_search.php" class="cta">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>