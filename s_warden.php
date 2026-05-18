<?php
require_once('php/connectoinStudent.php');
session_start();

// Load pending students
$query0 = "SELECT TG_no, room FROM student";
$result0 = mysqli_query($connection, $query0);

$std_data = "";
if ($result0) {
    while ($req = mysqli_fetch_assoc($result0)) {
        $std_data .= "<tr>";
        $std_data .= "<td>{$req['TG_no']}</td>";
        $std_data .= "<td>{$req['room']}</td>";
        $std_data .= "</tr>";
    }
}

// Handle selection
$tg = "";
$room = "";
$pass="";
$req_pass="";

if (isset($_POST['c_but'])) {
    $tg = "TG" . $_POST['SUID'];
    $tg = mysqli_real_escape_string($connection, $tg);
    $que = "SELECT passwords FROM student WHERE TG_no='$tg'";
    $pass = mysqli_query($connection, $que);
    $req_p = mysqli_fetch_assoc($pass);
    $req_pass=$req_p['passwords'];
    //echo "$req_pass";


    $query2 = "SELECT * FROM student WHERE TG_no='$tg'";
    $result2 = mysqli_query($connection, $query2);

    if ($row = mysqli_fetch_assoc($result2)) {
        $room = $row['room'];
    }
}

// Handle Accept
if (isset($_POST['acc'])) {
    $tg = $_POST['hidden_user'];
    $room = $_POST['hidden_room'];
    $req_pass = $_POST['hidden_pass'];

    // Check if already assigned
    $check = "SELECT * FROM rooms WHERE TG_no='$tg'";
    $check_result = mysqli_query($connection, $check);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('User already has a room');</script>";
    } else {
        // Insert
        $query4 = "INSERT INTO rooms(TG_no,room,passwords) VALUES ('$tg','$room','$req_pass')";
        //$query4 = "INSERT INTO rooms(TG_no,room) VALUES ('$tg','$room')";
        mysqli_query($connection, $query4);

        // Remove student
        $delete = "DELETE FROM student WHERE TG_no='$tg'";
        mysqli_query($connection, $delete);

        echo "<script>alert('Accepted successfully');
        window.location.href='users.php';</script>";
    }
}

// Handle Decline
if (isset($_POST['dec'])) {
    $tg = $_POST['hidden_user'];

    $delete = "DELETE FROM student WHERE TG_no='$tg'";
    mysqli_query($connection, $delete);
    
    echo "<script>alert('student declined');
        window.location.href='users.php';</script>";

}
?>

<html>
    <head>
        <link rel="stylesheet" href="css/users1.css">
    </head>
<body>

<h2>Accept or Decline student</h2>

<form method="post">
    <p>Select User ID TG/
    <input type="number" name="SUID">
    <button name="c_but">Choose</button></p>
</form>

<?php if (!empty($tg)) { ?>
<form method="post">
    <p>Selected User: <?php echo $tg; ?></p>

    <input type="hidden" name="hidden_user" value="<?php echo $tg; ?>">
    <input type="hidden" name="hidden_room" value="<?php echo $room; ?>">
    <input type="hidden" name="hidden_pass" value="<?php echo $req_pass; ?>">

    <button name="acc">Accept</button>
    <button name="dec">Decline</button>
</form>
<?php } ?>
<br>
<h3>Pending List</h3>
<table border="1px">
<tr>
    <th>User name</th>
    <th>Room Number</th>
</tr>
<?php echo $std_data; ?>
</table>

</body>
</html>
