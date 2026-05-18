<?php
// DB Connection
$conn = mysqli_connect("127.0.0.1", "root", "", "smart_hostal", "3307");
if (!$conn) { 
    die("Connection Failed"); 
}

$msg = "";
$mode = isset($_POST['mode']) ? $_POST['mode'] : "student_login_form";
$room = isset($_POST['room']) ? $_POST['room'] : "";
$st_tg = isset($_POST['tg_verified']) ? $_POST['tg_verified'] : ""; 
$complaints_list = []; 

// Check student login
if (isset($_POST['verify_student'])) {
    $tg = mysqli_real_escape_string($conn, $_POST['tg']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    $query = "SELECT * FROM rooms WHERE TG_no='$tg' AND passwords='$pass'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $room = $row['room']; 
        $st_tg = $tg;
        $mode = "student_dashboard";
    } else {
        $msg = "Invalid TG Number or Password.";
        $mode = "student_login_form";
    }
}

// Save new complaint
if (isset($_POST['student_submit'])) {
    $st_tg = mysqli_real_escape_string($conn, $_POST['tg_verified']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    $complaint = mysqli_real_escape_string($conn, trim($_POST['complaint']));
    $c_type = mysqli_real_escape_string($conn, $_POST['complaint_type']);
    $common_issue = mysqli_real_escape_string($conn, isset($_POST['common_issue']) ? $_POST['common_issue'] : "");
    $special_issue = mysqli_real_escape_string($conn, isset($_POST['special_issue']) ? $_POST['special_issue'] : "");
    
    $anonymous = isset($_POST['anonymous']) ? 1 : 0;
    $store_tg = $anonymous ? "Anonymous" : $st_tg;

    $error = "";
    if ($c_type == "") {
        $error = "Please select a Complaint Type.";
    } elseif ($c_type == "Common" && $common_issue == "") {
        $error = "Please select a Common Issue.";
    } elseif ($c_type == "Special" && $special_issue == "") {
        $error = "Please select a Special Issue.";
    } elseif ($complaint == "") {
        $error = "Please enter Complaint Details.";
    }

    if ($error == "") {
        $sql = "INSERT INTO complaints (TG_no, room, complaint_type, common_issue, special_issue, complaint, anonymous) VALUES ('$store_tg','$room','$c_type','$common_issue','$special_issue','$complaint','$anonymous')";

        if (mysqli_query($conn, $sql)) {
            $msg = "Complaint submitted successfully!";
        } else {
            $msg = "DB Error: " . mysqli_error($conn);
        }
    } else {
        $msg = $error;
    }

    $mode = "student_dashboard";
}

// Load student's complaints if logged in
if ($mode == "student_dashboard" && !empty($st_tg)) {
    $res = mysqli_query($conn, "SELECT * FROM complaints WHERE (TG_no='$st_tg' OR (anonymous=1 AND room='$room')) ORDER BY submitted_at DESC");
    while ($r = mysqli_fetch_assoc($res)) {
        $complaints_list[] = $r;
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
    <title>Student Maintenance - Smart Hostal</title>
    <link rel="stylesheet" href="css/maintain.css">
</head>
<body>

<h1>Student Portal</h1>

<?php if ($msg != ""): ?>
    <div class="msg <?php echo (strpos($msg,'Error')!==false||strpos($msg,'Invalid')!==false||strpos($msg,'Please')!==false)?'err':''; ?>">
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<?php if ($mode == "student_login_form"): ?>
<div class="card">
    <h2>Student Login</h2>
    <form method="post" action="">
        <label>TG Number</label>
        <input type="text" name="tg" placeholder="TGxxxx" required>
        <label>Password</label>
        <input type="password" name="pass" placeholder="........" required>
        <button class="btn" type="submit" name="verify_student">Login</button>
    </form>
</div>

<?php elseif ($mode == "student_dashboard"): ?>
<div class="tabs">
    <button class="tab-btn active" onclick="showTab('tab-submit',this)">Submit Complaint</button>
    <button class="tab-btn" onclick="showTab('tab-status',this)">My Complaints</button>
</div>

<div class="tab-pane active" id="tab-submit">
<div class="card" style="margin-bottom:0;">
<form method="post" action="">
    <input type="hidden" name="mode" value="student_dashboard">
    <input type="hidden" name="tg_verified" value="<?php echo htmlspecialchars($st_tg); ?>">
    <input type="hidden" name="room" value="<?php echo htmlspecialchars($room); ?>">

    <label>Your Room (Auto)</label>
    <div class="room-display">Room <?php echo htmlspecialchars($room); ?></div>

    <label>Complaint Type</label>
    <select name="complaint_type" id="mainType" onchange="toggleIssues(this.value)">
        <option value="">-- Select Type --</option>
        <option value="Common">Common Issue</option>
        <option value="Special">Special Issue</option>
    </select>

    <div class="sub-block" id="commonBlock">
        <label>Select Common Issue</label>
        <select name="common_issue" id="commonSel">
            <option value="">-- Select --</option>
            <option value="Plumbing">Plumbing</option>
            <option value="Electrical">Electrical</option>
            <option value="Furniture">Furniture</option>
            <option value="Cleanliness">Cleanliness</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="sub-block" id="specialBlock">
        <label>Select Special Issue</label>
        <select name="special_issue" id="specialSel">
            <option value="">-- Select --</option>
            <option value="AC not working">AC not working</option>
            <option value="Heater not working">Heater not working</option>
            <option value="WiFi issue">WiFi issue</option>
            <option value="Noise complaint">Noise complaint</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <label>Complaint Details</label>
    <textarea name="complaint" placeholder="Describe the issue clearly..." required></textarea>

    <label class="anon-box" for="anon_check">
        <input type="checkbox" name="anonymous" id="anon_check" value="1">
        <div>
            <div class="anon-label">Submit Anonymously (ANN)</div>
            <div class="anon-note">Your TG number will be hidden from admin</div>
        </div>
    </label>

    <button class="btn" type="submit" name="student_submit">Submit Complaint</button>
</form>
</div>
</div>

<div class="tab-pane" id="tab-status">
    <?php if (count($complaints_list) == 0): ?>
        <div class="c-card"><p class="no-c">You have no complaints submitted yet.</p></div>
    <?php else: ?>
        <?php foreach ($complaints_list as $c): ?>
        <div class="c-card <?php echo $c['status']=='Fixed'?'fixed':''; ?>">
            <div class="c-header">
                <?php if ($c['TG_no'] == 'Anonymous'): ?>
                    <span class="badge badge-yellow">You (ANN)</span>
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
                <?php if ($c['status']=='Pending'): ?>
                    <span class="pill-pending">⏳ Pending</span>
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
function toggleIssues(val) {
    var cb = document.getElementById('commonBlock');
    var sb = document.getElementById('specialBlock');
    var cs = document.getElementById('commonSel');
    var ss = document.getElementById('specialSel');

    cb.style.display = 'none';
    sb.style.display = 'none';
    cs.value = '';
    ss.value = '';

    if (val === 'Common') {
        cb.style.display = 'block';
    } else if (val === 'Special') {
        sb.style.display = 'block';
    }
}

function showTab(tabId, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}

<?php if (isset($_POST['student_submit'])): ?>
window.onload = function() {
    var btn = document.querySelectorAll('.tab-btn')[1];
    if (btn) {
        showTab('tab-status', btn);
    }
};
<?php endif; ?>
</script>
</body>
</html>