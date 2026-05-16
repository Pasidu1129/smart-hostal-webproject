<!DOCTYPE html>
<html>
<head>
    <title>Maintenance - Smart Hostal</title>
</head>
    
<body>

<h1>🔧 Maintenance</h1>

<?php if($msg != ""): ?>
    <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>


<!-- ====== DEFAULT: Choose Role ====== -->
<?php if($mode == ""): ?>

<div class="card">
    <h2 style="text-align:center;">Who are you?</h2>
    <div class="choice-btns">
        <form method="post" action="">
            <input type="hidden" name="mode" value="student">
            <button class="btn" type="submit">🎓 Student</button>
        </form>
        <form method="post" action="">
            <input type="hidden" name="mode" value="admin_login_form">
            <button class="btn btn-purple" type="submit">🛡️ Admin</button>
        </form>
    </div>
</div>


<!-- ====== STUDENT LOGIN ====== -->
<?php elseif($mode == "student"): ?>

<div class="card">
    <h2>Student Login</h2>
    <form method="post" action="">
        <input type="hidden" name="mode" value="student">
        <label>TG Number</label>
        <input type="text" name="tg" placeholder="TGxxxx" required>
        <label>Password</label>
        <input type="password" name="pass" placeholder="........" required>
        <button class="btn" type="submit" name="verify_student">Login</button>
    </form>
</div>


<!-- ====== STUDENT DASHBOARD: Submit + My Status ====== -->
<?php elseif($mode == "student_dashboard"): ?>

<!-- Tabs -->
<div class="tabs">
    <button class="tab-btn active" onclick="showTab('tab-submit', this)">📝 Submit Complaint</button>
    <button class="tab-btn" onclick="showTab('tab-status', this)">📋 My Complaints</button>
</div>

<!-- Tab 1: Submit -->
<div class="tab-pane active" id="tab-submit">
    <div class="card" style="margin-bottom:0;">
        <form method="post" action="">
            <input type="hidden" name="mode" value="student_dashboard">
            <input type="hidden" name="tg_verified" value="<?php echo htmlspecialchars($tg_verified); ?>">
            <input type="hidden" name="room" value="<?php echo htmlspecialchars($room); ?>">

            <label>Your Room (Auto)</label>
            <div class="room-display">🏠 Room <?php echo htmlspecialchars($room); ?></div>

            <label>Complaint Type</label>
            <select name="complaint_type">
                <option value="Common issue">Common issue</option>
                <option value="Special">Special (Specific issue)</option>
            </select>

            <label>Complaint Details</label>
            <textarea name="complaint" placeholder="Describe the issue clearly..." required></textarea>

            <label class="anon-box" for="anon_check">
                <input type="checkbox" name="anonymous" id="anon_check" value="1">
                <div>
                    <div class="anon-label">🕵️ Submit Anonymously (ANN)</div>
                    <div class="anon-note">Your TG number will be hidden from admin</div>
                </div>
            </label>

            <button class="btn" type="submit" name="student_submit">Submit Complaint</button>
        </form>
    </div>
</div>

<!-- Tab 2: My Complaints Status -->
<div class="tab-pane" id="tab-status">
    <?php if(count($my_complaints) == 0): ?>
        <div class="c-card">
            <p class="no-c">You have no complaints submitted yet.</p>
        </div>
    <?php else: ?>
        <?php foreach($my_complaints as $c): ?>
        <div class="c-card <?php echo $c['status']=='Fixed' ? 'fixed' : ''; ?>">
            <div class="c-header">
                <!-- Show "You (ANN)" if anonymous, else show TG -->
                <?php if($c['TG_no'] == 'Anonymous'): ?>
                    <span class="badge badge-yellow">🕵️ You (ANN)</span>
                <?php else: ?>
                    <span class="badge badge-blue"><?php echo htmlspecialchars($c['TG_no']); ?></span>
                <?php endif; ?>
                <span class="badge badge-gray"><?php echo htmlspecialchars($c['complaint_type']); ?></span>
                <span style="color:#aaa; font-size:13px;">Room <?php echo htmlspecialchars($c['room']); ?></span>
            </div>

            <p class="c-text">💬 <?php echo htmlspecialchars($c['complaint']); ?></p>

            <div class="c-footer">
                <span class="date-text"><?php echo $c['submitted_at']; ?></span>
                <?php if($c['status'] == 'Pending'): ?>
                    <span class="pill-pending">⏳ Pending</span>
                <?php else: ?>
                    <span class="pill-fixed">✅ Fixed</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<!-- ====== ADMIN LOGIN ====== -->
<?php elseif($mode == "admin_login_form"): ?>

<div class="card">
    <h2>Admin Login</h2>
    <form method="post" action="">
        <label>Admin Password</label>
        <input type="password" name="admin_pass" placeholder="Enter admin password" required>
        <button class="btn btn-purple" type="submit" name="admin_login">Login</button>
    </form>
</div>


<!-- ====== ADMIN VIEW ====== -->
<?php elseif($mode == "admin"): ?>

<?php
    $total   = count($all_complaints);
    $pending = count(array_filter($all_complaints, fn($c) => $c['status'] == 'Pending'));
    $fixed   = $total - $pending;
?>

<div class="admin-wrap">

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-num"><?php echo $total; ?></div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-box" style="border: 1px solid rgba(255,165,0,0.3);">
            <div class="stat-num" style="color:#ffa500;"><?php echo $pending; ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-box" style="border: 1px solid rgba(0,230,118,0.3);">
            <div class="stat-num" style="color:#00e676;"><?php echo $fixed; ?></div>
            <div class="stat-label">Fixed</div>
        </div>
    </div>

    <h2>All Complaints</h2>

    <?php if($total == 0): ?>
        <div class="c-card"><p class="no-c">No complaints yet.</p></div>
    <?php else: ?>
        <?php foreach($all_complaints as $c): ?>
        <div class="c-card <?php echo $c['status']=='Fixed' ? 'fixed' : ''; ?>">
            <div class="c-header">
                <?php if($c['TG_no'] == 'Anonymous'): ?>
                    <span class="badge badge-yellow">🕵️ Anonymous</span>
                <?php else: ?>
                    <span class="badge badge-blue"><?php echo htmlspecialchars($c['TG_no']); ?></span>
                <?php endif; ?>
                <span class="badge badge-gray"><?php echo htmlspecialchars($c['complaint_type']); ?></span>
                <span style="color:#aaa; font-size:13px;">Room <?php echo htmlspecialchars($c['room']); ?></span>
            </div>

            <p class="c-text">💬 <?php echo htmlspecialchars($c['complaint']); ?></p>

            <div class="c-footer">
                <span class="date-text"><?php echo $c['submitted_at']; ?></span>

                <?php if($c['status'] == 'Pending'): ?>
                    <span class="pill-pending">⏳ Pending</span>
                    <form method="post" action="">
                        <input type="hidden" name="complaint_id" value="<?php echo $c['id']; ?>">
                        <input type="hidden" name="admin_login" value="1">
                        <input type="hidden" name="admin_pass" value="admin123">
                        <button class="btn-fix" type="submit" name="mark_fixed">✔ Mark as Fixed</button>
                    </form>
                <?php else: ?>
                    <span class="pill-fixed">✅ Fixed</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?php endif; ?>

<br>
<a href="main.php" class="back-link">← Back to Main Menu</a>

<script>
function showTab(tabId, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}

// Auto-switch to status tab if coming back after submit
<?php if(isset($_POST['student_submit'])): ?>
window.onload = function(){ 
    var btn = document.querySelectorAll('.tab-btn')[1];
    if(btn) showTab('tab-status', btn);
};
<?php endif; ?>
</script>

</body>
</html>
