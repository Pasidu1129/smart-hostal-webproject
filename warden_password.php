
<?php
$pass = $_POST['pass'];

if($pass == "12345"){
    header("Location: warden.php");
    exit();
} else {
    echo " Wrong Password";
    echo "<br><a href='warden_login.php'>Try Again</a>";
}
?>