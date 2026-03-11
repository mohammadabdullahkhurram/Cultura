<?php include 'includes/header.php'; ?>

<article class="paper">
    <h1>Search RSVPs</h1>
    
    <form action="rsvp_results.php" method="GET" class="search-form">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label for="user_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">User Name</label>
                <input type="text" name="user_name" id="user_name" placeholder="Enter user name" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
            
            <div>
                <label for="event_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Event Name</label>
                <input type="text" name="event_name" id="event_name" placeholder="Enter event name" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <label for="status_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">RSVP Status</label>
            <select name="status_id" id="status_id" 
                    style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
                <option value="">All Statuses</option>
                <?php
                include 'includes/database.php';
                $stmt = $pdo->query("SELECT status_id, code FROM rsvp_status ORDER BY code");
                $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($statuses as $status) {
                    echo "<option value='{$status['status_id']}'>{$status['code']}</option>";
                }
                ?>
            </select>
        </div>
        
        <button type="submit" class="cta" style="width: 100%; text-align: center; justify-content: center;">
            Search RSVPs
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
    $('#user_name').autocomplete({
        source: 'autocomplete_users.php',
        minLength: 2,
        delay: 300
    });
    
    $('#event_name').autocomplete({
        source: 'autocomplete_events.php',
        minLength: 2,
        delay: 300
    });
});
</script>

<?php include 'includes/footer.php'; ?>
