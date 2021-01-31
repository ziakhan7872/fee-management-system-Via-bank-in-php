<?php
include("php/dbconnect.php");
$accountno		=	$_POST['accountno'];
$accountpass	=	$_POST['accountpass'];
$id				=	$_SESSION['rainbow_uid'];
$sql 			=   "SELECT * FROM `user` WHERE `id` = '$id' AND `accountno` = '$accountno' AND `accountpass` = '$accountpass'";
$q = $conn->query($sql);
if($q->num_rows>0)
{
 $res 			= 	$q->fetch_assoc();
 echo 				$res['balance'];
}
else
{
	echo "incorrect";
}