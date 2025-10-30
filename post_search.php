<?php include 'includes/header.php'; ?>

<article class="paper">
    <h1>Search Posts</h1>
    
    <form action="post_results.php" method="GET" class="search-form">
        <div style="margin-bottom: 24px;">
            <div>
                <label for="q" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Search Posts</label>
                <input type="text" name="q" id="q" placeholder="Enter keyword to search posts" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
        </div>
        
        <button type="submit" class="cta" style="width: 100%; text-align: center; justify-content: center;">
            Search Posts
        </button>
    </form>
</article>

<?php include 'includes/footer.php'; ?>
