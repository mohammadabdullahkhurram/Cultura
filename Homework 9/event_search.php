<?php include 'includes/header.php'; ?>

<article class="paper">
    <h1>Search Events</h1>
    
    <form action="event_results.php" method="GET" class="search-form">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label for="event_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Event Name</label>
                <input type="text" name="event_name" id="event_name" placeholder="Enter event name" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
            
            <div>
                <label for="event_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Event Type</label>
                <select name="event_type" id="event_type" 
                        style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
                    <option value="">All Events</option>
                    <option value="workshop">Workshop</option>
                    <option value="social">Social Event</option>
                    <option value="general">General Event</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label for="location" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Location</label>
                <input type="text" name="location" id="location" placeholder="Enter location" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
            
            <div>
                <label for="category" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Category</label>
                <select name="category_id" id="category" 
                        style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
                    <option value="">All Categories</option>
                    <?php
                    include 'includes/database.php';
                    $stmt = $pdo->query("SELECT category_id, name FROM Category ORDER BY name");
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($categories as $category) {
                        echo "<option value='{$category['category_id']}'>{$category['name']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label for="date_from" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">From Date</label>
                <input type="date" name="date_from" id="date_from" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
            
            <div>
                <label for="date_to" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">To Date</label>
                <input type="date" name="date_to" id="date_to" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="published_only" id="published_only" checked 
                       style="width: 18px; height: 18px;">
                <span style="font-weight: 600; color: var(--brand-ink);">Show only published events</span>
            </label>
        </div>
        
        <button type="submit" class="cta" style="width: 100%; text-align: center; justify-content: center;">
            Search Events
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
    $('#event_name').autocomplete({
        source: 'autocomplete_events.php',
        minLength: 2,
        delay: 300
    });
    
    $('#location').autocomplete({
        source: 'autocomplete_locations.php',
        minLength: 2,
        delay: 300
    });
});
</script>

<?php include 'includes/footer.php'; ?>
