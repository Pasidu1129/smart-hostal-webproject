<?php
if (isset ( $_POST['wad'] )) {
header('location:warden.php');
}
?>
<html>
<head>
    <title>admin</title>
    <link rel="stylesheet" href="css/admin.css">

<body>
    <form method="post" action="">

        <input type="submit" id="wad" name="wad" value="warden" class="submit"><br>
        <input type="submit" id="subwad" name="subwad" value="sub warden" class="submit"><br>
        <input type="submit" id="sec" name="sec" value="security" class="submit"><br>

        </form>

</body>

</html>