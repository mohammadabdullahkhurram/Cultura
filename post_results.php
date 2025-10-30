<?php
include 'includes/database.php';
include 'includes/header.php';

// Get search parameter
$q = trim($_GET['q'] ?? '');
$posts = [];

if (!empty($q)) {
    $sql = "SELECT p.post_id, p.title, p.content, p.country, p.theme, p.attachments_url,
                   u.name AS creator_name, pt.name AS type_name
            FROM posts p
            LEFT JOIN users u ON u.user_id = p.creator_id
            LEFT JOIN post_types pt ON pt.type_id = p.type_id
            WHERE p.title LIKE ? OR p.content LIKE ? 
            ORDER BY p.title ASC";
    
    $stmt = $pdo->prepare($sql);
    $searchTerm = "%$q%";
    $stmt->execute([$searchTerm, $searchTerm]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<article class="paper">
    <h1>Post Search Results</h1>

    <!-- Display search criteria -->
    <div class="callout" style="margin-bottom: 30px;">
        <div>
            <strong>Search Criteria</strong>
            <div class="meta">
                <?php
                if (!empty($q)) {
                    echo "Keyword: '$q'";
                } else {
                    echo "No search term provided";
                }
                ?>
            </div>
        </div>
        <div style="text-align: right;">
            <strong>Found</strong>
            <div class="meta"><?= count($posts) ?> posts</div>
        </div>
    </div>

    <?php if (empty($q)): ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--muted);">
            <p style="font-size: 18px; margin-bottom: 20px;">Please enter a search term.</p>
            <a href="post_search.php" class="cta alt">Back to Search</a>
        </div>
    <?php elseif (empty($posts)): ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--muted);">
            <p style="font-size: 18px; margin-bottom: 20px;">No posts found matching your criteria.</p>
            <a href="post_search.php" class="cta alt">Try another search</a>
        </div>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($posts as $post): ?>
                <article class="card">
                    <?php if ($post['attachments_url']): ?>
                        <div class="card-image">
                            <img src="<?= htmlspecialchars($post['attachments_url']) ?>" alt="Post image" 
                                 style="width: 100%; height: 120px; object-fit: cover; border-radius: var(--radius) var(--radius) 0 0;">
                        </div>
                    <?php else: ?>
                        <div class="card-image">
                            <?= substr(htmlspecialchars($post['title']), 0, 2) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="body">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        
                        <div class="meta">
                            👤 <?= htmlspecialchars($post['creator_name'] ?? 'Unknown') ?>
                        </div>
                        
                        <div class="meta">
                            🏷️ <?= htmlspecialchars($post['type_name'] ?? 'General') ?>
                        </div>
                        
                        <?php if ($post['country']): ?>
                            <div class="meta">
                                🌍 <?= htmlspecialchars($post['country']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($post['theme']): ?>
                            <div class="meta">
                                💬 <?= htmlspecialchars($post['theme']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <p style="margin: 12px 0; font-size: 14px; line-height: 1.5;">
                            <?= nl2br(htmlspecialchars(substr($post['content'], 0, 120))) ?><?= strlen($post['content']) > 120 ? '...' : '' ?>
                        </p>
                        
                        <a href="post_detail.php?id=<?= $post['post_id'] ?>" class="cta" style="display: inline-block; padding: 8px 16px; font-size: 14px; text-align: center;">
                            Read More
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="post_search.php" class="cta alt">New Search</a>
    </div>
</article>

<?php include 'includes/footer.php'; ?>
