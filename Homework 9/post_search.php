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

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
.ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
$(function() {
    $('#q').autocomplete({
        source: 'autocomplete_posts.php',
        minLength: 2,
        delay: 300
    });
});
</script>

<?php include 'includes/footer.php'; ?>
