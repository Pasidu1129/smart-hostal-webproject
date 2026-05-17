<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost", "root", "", "smart_hostal");

if (!$conn) {
    die("Connection Failed : " . mysqli_connect_error());
}

if (isset($_POST['save_out'])) {
    $TG_no = $_POST['TG_no_out'];
    $room = $_POST['room_out'];
    $place = $_POST['place_out'];

    $sql_out = "INSERT INTO outgoing (TG_no, room, place, Odate_time, Idate_time) 
                VALUES ('$TG_no', '$room', '$place', NOW(), NULL)";

    if (mysqli_query($conn, $sql_out)) {
        echo "<script>alert('Student Outgoing Record Added Successfully!'); window.location.href=window.location.href;</script>";
    } else {
        echo "Error : " . mysqli_error($conn);
    }
}

if (isset($_POST['save_in'])) {
    $IOid = $_POST['record_id_in'];

    $sql_in = "UPDATE outgoing SET Idate_time = NOW() WHERE IOid = '$IOid'";

    if (mysqli_query($conn, $sql_in)) {
        echo "<script>alert('Student Arrival Marked Successfully!'); window.location.href=window.location.href;</script>";
    } else {
        echo "Error : " . mysqli_error($conn);
    }
}

$all_students = mysqli_query($conn, "SELECT TG_no, room FROM student");
$outside_students = mysqli_query($conn, "SELECT IOid, TG_no, room, place, Odate_time FROM outgoing WHERE Idate_time IS NULL");
?>

!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Hostel - In/Out Management</title>
    <link rel="stylesheet" href="css/security.css">

</head>
<body>

    <h1>Security</h1>
    <div class="tab-container">
        <h2>Student Going OUT / Coming IN</h2>
    </div>

    <div id="out-panel" class="form-box active">
        <h3 style="color: white; margin-top:0; text-align:center;">Log Outgoing Movement</h3>
        <form method="POST" action="">
            <label>Select TG Number</label>
            <select name="TG_no_out" id="TG_no_out" onchange="setOutRoom()" required>
                <option value="">-- Select TG Number --</option>
                <?php
                if ($all_students && mysqli_num_rows($all_students) > 0) {
                    while($row = mysqli_fetch_assoc($all_students)){
                        echo "<option value='".$row['TG_no']."' data-room='".$row['room']."'>".$row['TG_no']."</option>";
                    }
                } else {
                    echo "<option value=''>No students found in DB</option>";
                }
                ?>
            </select>
            
            
            <br><br>
            <label>Room Number</label>
            <input type="text" name="room_out" id="room_out" >

            <br><br>
            <label>Destination/Place</label>
            <input type="text" name="place_out">

            <br><br>
            <button type="submit" name="save_out" class="btn-out">Log Exit (OUT)</button>
            
            

        </form>
    </div>

    <div id="in-panel" class="form-box">
        <br><br>
        <h3 style="color: white; margin-top:0; text-align:center;">Log Incoming Arrival</h3>
        <form method="POST" action="">
            <label>Select TG Number (Currently Outside)</label>
            <select name="record_id_in" id="record_id_in" onchange="setInDetails()" required>
                <option value="">-- Select TG Number --</option>
                <?php
                if ($outside_students && mysqli_num_rows($outside_students) > 0) {
                    while($row = mysqli_fetch_assoc($outside_students)){
                        echo "<option value='".$row['IOid']."' data-room='".$row['room']."' data-place='".$row['place']."' data-otime='".$row['Odate_time']."'>".$row['TG_no']."</option>";
                    }
                } else {
                    echo "<option value=''>No students are outside right now</option>";
                }
                ?>
            </select>

            <br><br>
            <label>Room Number</label>
            <input type="text" id="room_in" readonly placeholder="Auto-filled">

            <br><br>
            <label>Went To</label>
            <input type="text" id="place_in" readonly placeholder="Auto-filled">

            <br><br>
            <label>Exit Time</label>
            <input type="text" id="otime_in" readonly placeholder="Auto-filled">

            <br><br>
            <button type="submit" name="save_in" class="btn-in">Mark Returned (IN)</button>
            
        </form>
    </div>

<script>
    function switchTab(panelId, button) {
        document.getElementById('out-panel').classList.remove('active');
        document.getElementById('in-panel').classList.remove('active');
        
        var buttons = document.getElementsByClassName('tab-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove('active');
        }
        
        document.getElementById(panelId).classList.add('active');
        button.classList.add('active');
    }

    function setOutRoom(){
        var tg = document.getElementById("TG_no_out");
        var selectedOption = tg.options[tg.selectedIndex];
        var room = selectedOption.getAttribute("data-room");
        document.getElementById("room_out").value = room ? room : "";
    }

    function setInDetails(){
        var tg = document.getElementById("record_id_in");
        var selectedOption = tg.options[tg.selectedIndex];
        
        var room = selectedOption.getAttribute("data-room");
        var place = selectedOption.getAttribute("data-place");
        var otime = selectedOption.getAttribute("data-otime");
        
        document.getElementById("room_in").value = room ? room : "";
        document.getElementById("place_in").value = place ? place : "";
        document.getElementById("otime_in").value = otime ? otime : "";
    }
    </script>

</body>
</html>
