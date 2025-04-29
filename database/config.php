<?php 

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '1234';
$dbName = 'doctorAppointment';

$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName)
  or die("Connection failed: " . mysqli_connect_error());
 
//  $con = mysqli_connect("localhost","root","1234","doctorDako") or die("Couldn't connect");

?>