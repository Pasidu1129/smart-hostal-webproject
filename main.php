<?php
if (isset ( $_POST['str'] )) {
header('location:student_reg.php');
}
if (isset ( $_POST['log'] )) {
header('location:login.php');
}
if (isset ( $_POST['admin'] )) {
header('location:admin.php');
}
?>
<html>
<head>
    <title> main page</title>
     <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <h1>smart hostal</h1>

    <form method= "post" action="">
    
    <input type="submit" id="str" name="str" value="student registation" class="submit"><br>
    <input type="submit" id="log" name="log" value="student Login" class="submit"><br>
    <input type="submit" id="admin" name="admin" value="admin" class="submit"><br>
    <input type="submit" id="help" name="help" value="help" class="submit"><br>

</form>
</body>



</html>