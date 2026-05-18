<?php
if (isset ( $_POST['button'] )) {
header('location:main.php');
}
?>
<html>

<head>
    <title>Hostel Management System</title>
    <link rel="stylesheet" href="css/home.css">

</head>

<body>
    <form method = "post" action="">

    <div class="container">

        <h1>
            Welcome <br>
            Hostel Management System
        </h1>

        <button id="button" name="button" >Get Start</button>

    </div>
</form>
</body>

</html>
