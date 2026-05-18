<?php

$conn = mysqli_connect("localhost","root","","smart_hostal");

if(!$conn){
    die("Connection Failed");
}

$msg = "";

if(isset($_POST['submit'])){

    $tg = $_POST['tg'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $confp = $_POST['confp'];
    $room = $_POST['rq'];
    $gender = $_POST['gender'];

    // Passwords check
    if($pass == $confp){

        // Gender + Room validation (NEW ADDED)
        if($gender == "Male"){

            if($room < 100 || $room > 300){
                $msg = "Male students can only choose rooms 100–300";
            }

        }
        else if($gender == "Female"){

            if($room < 301 || $room > 500){
                $msg = "Female students can only choose rooms 301–500";
            }

        }
        else{
            $msg = "Please select gender";
        }

        // Only continue if no error
        if($msg == ""){

            // TG number check
            $check = "SELECT * FROM student WHERE TG_no='$tg'";
            $result = mysqli_query($conn,$check);

            if(mysqli_num_rows($result) > 0){

                $msg = "This TG number already exists";

            }
            else{

                // Room count check
                $roomcheck = "SELECT * FROM student WHERE room='$room'";
                $roomresult = mysqli_query($conn,$roomcheck);

                if(mysqli_num_rows($roomresult) >= 4){

                    $msg = "Room is Full. Apply another room";

                }
                else{

                    // Insert data
                    $sql = "INSERT INTO student(TG_no,email,passwords,room)
                            VALUES('$tg','$email','$pass','$room')";

                    if(mysqli_query($conn,$sql)){

                        echo "
                        <script>
                            alert('Registration Successfully');
                            window.location='main.php';
                        </script>
                        ";
                        exit();

                    }
                    else{
                        $msg = "Error inserting data";
                    }

                }

            }

        }

    }
    else{
        $msg = "Passwords do not match";
    }

}

?>

<html>

<head>

<title>Student Registration</title>

<link rel="stylesheet" href="css/student_reg1.css">

</head>

<body>

<h1>Student Registration</h1>

<h3 style="color:red;">
<?php echo $msg; ?>
</h3>

<form method="post" action="">

TG NO
<input type="text" name="tg" required><br><br>

E-mail
<input type="text" name="email" required><br><br>

Passwords
<input type="password" name="pass" required><br><br>

Confirm Passwords
<input type="password" name="confp" required><br><br>

Gender
<input type="radio" name="gender" value="Male">Male
<input type="radio" name="gender" value="Female">Female
<br><br>

Request Room
<p style="font-size:12px;">
  Male (100–300 only) Female (301–500 only)
</p>

<input type="text" name="rq" required><br><br>

<button type="submit" name="submit">
SUBMIT
</button>

</form>

</body>

</html>