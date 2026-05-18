<?php

$conn = mysqli_connect("localhost","root","","smart_hostal");

if(!$conn){
    die("Connection Failed");
}

$msg = "";
$room = "";
$members = [];
$login = false;

if(isset($_POST['submit'])){

    $tg = $_POST['tg'];
    $pass = $_POST['pass'];

    $sql = "SELECT * FROM rooms
            WHERE TG_no='$tg' AND passwords='$pass'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $login = true;

        $msg = "Login Success";

        $row = mysqli_fetch_assoc($result);

        $room = $row['room'];

        $roomsql = "SELECT TG_no FROM rooms WHERE room='$room'";

        $roomresult = mysqli_query($conn,$roomsql);

        while($r = mysqli_fetch_assoc($roomresult)){
            $members[] = $r['TG_no'];
        }

    }
    else{

        $msg = "Invalid TG Number or Password";

    }

}

?>

<html>

<head>

<link rel="stylesheet" href="css/login.css">

</head>

<body>

<?php if($login == false){ ?>

<div class="form-box">

<h2><?php echo $msg; ?></h2>

<form method="post" action="">

<label>TG Number</label>
<input type="text" name="tg" placeholder="TGxxxx">

<label>Password</label>
<input type="password" name="pass" placeholder="........">

<input type="submit" name="submit" value="Login">

</form>

</div>

<?php } ?>

<?php if($login == true){ ?>

<div class="success-box">

<h1><?php echo $msg; ?></h1>

<h2>Your Room : <?php echo $room; ?></h2>

<h3>Room Members</h3>

<?php

foreach($members as $m){

    echo "<p>$m</p>";

}

?>

</div>

<?php } ?>

</body>

</html>