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
    <style>
        body{
    margin: 0;
    padding: 0;

    height: 100vh;

    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;

    background: linear-gradient(135deg, #141e30, #243b55);

    font-family: Arial, sans-serif;
}

h1{
    color: white;

    font-size: 50px;

    margin-bottom: 40px;

    text-transform: uppercase;

    letter-spacing: 2px;

    text-shadow: 0px 0px 10px rgba(255,255,255,0.5);
}

form{

    background: rgba(255,255,255,0.1);

    padding: 40px;

    border-radius: 20px;

    backdrop-filter: blur(10px);

    box-shadow: 0px 0px 25px rgba(0,0,0,0.4);

    text-align: center;
}

input[type="submit"]{

    width: 280px;

    padding: 15px;

    margin: 12px 0;

    border: none;

    border-radius: 30px;

    background: #00c6ff;

    color: white;

    font-size: 18px;

    cursor: pointer;

    transition: 0.4s;
}

input[type="submit"]:hover{

    background: white;

    color: #243b55;

    transform: scale(1.08);

    box-shadow: 0px 0px 20px white;
}
    </style>
</head>

