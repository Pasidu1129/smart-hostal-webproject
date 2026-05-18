<?php session_start(); ?>
<?php require_once('php/connection.php'); ?>

<?php 

	// check for form submission
	if (isset($_POST['submit'])) {

        $errors=array();

        //check
        if (!isset($_POST['UN']) || strlen(trim($_POST['UN']))<1 ){
            $errors[]="User name is missing/Invalid";
        }

        if (!isset($_POST['PW']) || strlen(trim($_POST['PW']))<1 ){
            $errors[]="Password is missing/Invalid";
        }

         if (empty($errors)){
                //save user name and password in a var
                $user_name= mysqli_real_escape_string($connection,$_POST['UN']);
                $password= mysqli_real_escape_string($connection,$_POST['PW']);

                //prepare Query
                $query= "SELECT * FROM sub_warden WHERE user_name= '{$user_name}' AND passwords = '{$password}' LIMIT 1";

                $result= mysqli_query($connection,$query);

                if  ($result){

                    //query succesfull

                    if (mysqli_num_rows($result) == 1){

                        //valid user and redirect to file
                        header('location:s_warden.php');
                        //exit();

                    }
                    else {
                        $errors[]='Invalid Username/Password';
                    }

                }else{
                        $errors[]='Database Query failed';
                    }
            }                                                                                                                                                                                                                 

    }	
?>



<html>
<head><link rel="stylesheet" href="css/s_login.css"></head>
<title>Admin page</title>
<body>

<form action="" method="post">

<h2>Admin Login</h2>

<?php
if (isset($_POST['submit'])) {  
	if (isset($errors) && !empty($errors)) {
	echo '<p class="error">Invalid Username / Password</p>';
	}
}				
?>

User name <br> <input type="text" placeHolder="User name" id="UN" name="UN">
   <br> Password <br> <input type="password" placeHolder="password" id="PW" name="PW">
    <br><button name="submit">Submit</button>
</body>

</html>
