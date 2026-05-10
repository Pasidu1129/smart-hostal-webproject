<?php
if (isset ( $_POST['button'] )) {
header('location:main.php');
}
?>
<html>

<head>
    <title>Hostel Management System</title>
    <link rel="stylesheet" href="css/home.css">
    <style>

        body{
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;

            background: linear-gradient(135deg, #1e3c72, #2a5298);

            font-family: Arial, sans-serif;
        }

        .container{
            text-align: center;
            color: white;
        }

        h1{
            font-size: 45px;
            margin-bottom: 30px;
        }

        #button{
            padding: 15px 40px;
            font-size: 18px;
            border: none;
            border-radius: 30px;

            background-color: #00c6ff;
            color: white;

            cursor: pointer;

            transition: 0.4s;
        }

        #button:hover{
            background-color: #ffffff;
            color: #1e3c72;

            transform: scale(1.1);

            box-shadow: 0px 0px 20px #ffffff;
        }

    </style>

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
