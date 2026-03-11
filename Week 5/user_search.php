<?php include 'includes/header.php'; ?>

<article class="paper">
    <h1>Search Users</h1>
    
    <form action="user_results.php" method="GET" class="search-form">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label for="user_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">User Name</label>
                <input type="text" name="user_name" id="user_name" placeholder="Enter user name" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
            
            <div>
                <label for="user_email" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Email</label>
                <input type="email" name="user_email" id="user_email" placeholder="Enter email address" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label for="user_role" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">User Role</label>
                <select name="user_role" id="user_role" 
                        style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
                    <option value="">All Roles</option>
                    <option value="student">Student</option>
                    <option value="residential_staff">Residential Staff</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            
            <div>
                <label for="user_status" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Status</label>
                <select name="user_status" id="user_status" 
                        style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                    <option value="deactivated">Deactivated</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <div>
                <label for="major" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--brand-ink);">Major (Students Only)</label>
                <input type="text" name="major" id="major" placeholder="Enter major" 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px;">
            </div>
        </div>
        
        <button type="submit" class="cta" style="width: 100%; text-align: center; justify-content: center;">
            Search Users
        </button>
    </form>
</article>

<?php include 'includes/footer.php'; ?>