<?php
// DB Connection
$conn = mysqli_connect("127.0.0.1", "root", "", "smart_hostal", "3307");
if (!$conn) { 
    die("Connection Failed"); 
}

$msg = "";
$mode = isset($_POST['mode']) ? $_POST['mode'] : "admin_login_form";
$admin_user = isset($_POST['admin_user']) ? $_POST['admin_user'] : ""; 

// Admin login
if (isset($_POST['admin_login'])) {
    $admin_user_input = mysqli_real_escape_string($conn, $_POST['admin_user']); 
    $admin_pass = mysqli_real_escape_string($conn, $_POST['admin_pass']);

    $sql = "SELECT * FROM sub_warden WHERE user_name='$admin_user_input' AND passwords='$admin_pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $admin_user = $row['user_name']; 
        $mode = "admin_dashboard";
    } else {
        $msg = "Wrong Username or Password.";
        $mode = "admin_login_form";
    }
}

// Update status to fixed
if (isset($_POST['mark_fixed'])) {
    $cid = (int)$_POST['complaint_id'];
    mysqli_query($conn, "UPDATE complaints SET status='Fixed' WHERE id='$cid'");
    $msg = "Marked as Fixed!";
    
    $admin_user = mysqli_real_escape_string($conn, $_POST['admin_user']);
    $mode = "admin_dashboard";
}

// Admin view data loading
$all_complaints = [];
if ($mode == "admin_dashboard") {
    $res = mysqli_query($conn, "SELECT * FROM complaints ORDER BY status ASC, submitted_at DESC");
    while ($r = mysqli_fetch_assoc($res)) {
        $all_complaints[] = $r;
    }
}

function issueLabel($c) {
    if ($c['complaint_type'] == 'Common') {
        return "Common › " . $c['common_issue'];
    } elseif ($c['complaint_type'] == 'Special') {
        return "Special › " . $c['special_issue'];
    }
    return $c['complaint_type'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Maintenance - Smart Hostal</title>
    <link rel="stylesheet" href="css/maintain.css">
</head>
<body>

<h1>Admin Portal</h1>

<?php if ($msg != ""): ?>
    <div class="msg <?php echo (strpos($msg,'Wrong')!==false)?'err':''; ?>">
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<?php if ($mode == "admin_login_form"): ?>
<div class="card">
    <h2>Admin Login</h2>
    <form method="post" action="">
        <input type="hidden" name="mode" value="admin_login_form">
        <label>Username</label>
        <input type="text" name="admin_user" placeholder="Enter username" required>
        <label>Password</label>
        <input type="password" name="admin_pass" placeholder="Enter password" required>
        <button class="btn btn-purple" type="submit" name="admin_login">Login</button>
    </form>
</div>

<?php elseif ($mode == "admin_dashboard"): 
    $total = count($all_complaints);
    $pending = count(array_filter($all_complaints, fn($c) => $c['status'] == 'Pending'));
    $fixed = $total - $pending;
?>
<div class="admin-wrap">
    <div style="margin-bottom: 15px; font-weight: bold; color: #555;">
        Welcome Admin: <span style="color: #673ab7;"><?php echo htmlspecialchars($admin_user); ?></span>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-num"><?php echo $total; ?></div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-box" style="border-color:rgba(255,165,0,0.3);">
            <div class="stat-num" style="color:#ffa500;"><?php echo $pending; ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-box" style="border-color:rgba(0,230,118,0.3);">
            <div class="stat-num" style="color:#00e676;"><?php echo $fixed; ?></div>
            <div class="stat-label">Fixed</div>
        </div>
    </div>

    <h2>All Complaints</h2>

    <?php if ($total == 0): ?>
        <div class="c-card"><p class="no-c">No complaints yet.</p></div>
    <?php else: ?>
        <?php foreach ($all_complaints as $c): ?>
        <div class="c-card <?php echo $c['status']=='Fixed'?'fixed':''; ?>">
            <div class="c-header">
                <?php if ($c['TG_no'] == 'Anonymous'): ?>
                    <span class="badge badge-yellow">Anonymous</span>
                <?php else: ?>
                    <span class="badge badge-blue"><?php echo htmlspecialchars($c['TG_no']); ?></span>
                <?php endif; ?>

                <span class="badge <?php echo $c['complaint_type']=='Common'?'badge-common':'badge-special'; ?>">
                    <?php echo htmlspecialchars(issueLabel($c)); ?>
                </span>
                <span style="color:#777;font-size:12px;">Room <?php echo htmlspecialchars($c['room']); ?></span>
            </div>

            <p class="c-text"><?php echo htmlspecialchars($c['complaint']); ?></p>

            <div class="c-footer">
                <span class="date-text"><?php echo $c['submitted_at']; ?></span>
                <?php if ($c['status'] == 'Pending'): ?>
                    <span class="pill-pending">Pending</span>
                    <form method="post" action="">
                        <input type="hidden" name="complaint_id" value="<?php echo $c['id']; ?>">
                        <input type="hidden" name="mode" value="admin_dashboard">
                        <input type="hidden" name="admin_user" value="<?php echo htmlspecialchars($admin_user); ?>">
                        <button class="btn-fix" type="submit" name="mark_fixed">✔ Mark as Fixed</button>
                    </form>
                <?php else: ?>
                    <span class="pill-fixed">Fixed</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<br>
<a href="main.php" class="back-link">← Back to Main Menu</a>

</body>
</html>