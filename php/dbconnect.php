<?php
//error_reporting(0);
ob_start();
session_start();
date_default_timezone_set('Asia/Karachi'); 
$conn =  new mysqli('localhost','root','','feedb');
if($conn->connect_error)
die("Failed to connect database ".$conn->connect_error );