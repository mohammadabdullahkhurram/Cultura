<?php
include 'includes/database.php';
include 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$post = null;

if ($id > 0) {
    $sql = "SELECT p.post_id, p.title, p.content, p.country, p.theme, p.attachments_url,
                   u.name AS creator_name, pt.name AS type_name
            FROM posts p
            LEFT JOIN users u ON u.user_id = p.creator_id
            LEFT JOIN post_types pt ON pt.type_id = p.type_id
            WHERE p.post_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$post) {
    echo '<article class="paper" style="text-align: center; padding: 60px 20px;">
            <h2>Post Not Found</h2>
            <p style="color: var(--muted); margin-bottom: 30px;">The post you\'re looking for doesn\'t exist.</p>
            <a href="post_search.php" class="cta alt">Back to Post Search</a>
          </article>';
    include 'includes/footer.php';
    exit;
}
?>

<article class="paper">
    <h1><?= htmlspecialchars($post['title']) ?></h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px; margin: 30px 0;">
        <?php if ($post['attachments_url']): ?>
            <div>
                <img src="<?= htmlspecialchars($post['attachments_url']) ?>" alt="Post image" 
                     style="width: 100%; border-radius: var(--radius); border: 1px solid var(--border);">
            </div>
        <?php else: ?>
            <div style="background: linear-gradient(45deg, var(--brand), var(--accent)); color: white; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 600; min-height: 200px;">
                <?= substr(htmlspecialchars($post['title']), 0, 2) ?>
            </div>
        <?php endif; ?>
        
        <div>
            <div class="features" style="grid-template-columns: 1fr; gap: 16px;">
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">👤</div>
                    <div>
                        <strong>Author</strong>
                        <div style="color: var(--muted);"><?= htmlspecialchars($post['creator_name'] ?? 'Unknown') ?></div>
                    </div>
                </div>
                
                <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                    <div style="font-size: 24px;">🏷️</div>
                    <div>
                        <strong>Post Type</strong>
                        <div style="color: var(--muted);"><?= htmlspecialchars($post['type_name'] ?? 'General') ?></div>
                    </div>
                </div>
                
                <?php if ($post['country']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">🌍</div>
                        <div>
                            <strong>Country</strong>
                            <div style="color: var(--muted);"><?= htmlspecialchars($post['country']) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($post['theme']): ?>
                    <div class="feature" style="display: flex; align-items: center; gap: 12px; padding: 16px;">
                        <div style="font-size: 24px;">💬</div>
                        <div>
                            <strong>Theme</strong>
                            <div style="color: var(--muted);"><?= htmlspecialchars($post['theme']) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div style="margin: 40px 0;">
        <h2 style="color: var(--brand); margin-bottom: 20px;">Content</h2>
        <div style="background: var(--bg); padding: 24px; border-radius: var(--radius); border: 1px solid var(--border); line-height: 1.6;">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
    </div>

    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 30px;">
        <a href="post_results.php?q=<?= urlencode($post['title']) ?>" class="cta alt">← Back to Results</a>
        <a href="post_search.php" class="cta">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>
