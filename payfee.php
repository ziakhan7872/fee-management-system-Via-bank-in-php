<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$uid			=	$_SESSION['rainbow_uid'];
echo $sqlu 		=   "SELECT balance FROM `user` WHERE `id` = '$uid' ";	
$qu 			= 	$conn->query($sqlu);
$resu 			= 	$qu->fetch_assoc();
$rebalance 		=	$resu['balance']-$_POST['paid'];
echo $sqlup 		= 	"update user set balance='$rebalance' where id = '$uid'";
					$conn->query($sqlup);
$paid = mysqli_real_escape_string($conn,$_POST['paid']);
$submitdate = mysqli_real_escape_string($conn,$_POST['submitdate']);
$transcation_remark = mysqli_real_escape_string($conn,$_POST['transcation_remark']);
$sid = mysqli_real_escape_string($conn,$_POST['sid']);
$via = mysqli_real_escape_string($conn,$_POST['via']);
echo $sql = "select fees,balance  from student where id = '$sid'";
$sq = $conn->query($sql);
$sr = $sq->fetch_assoc();
$totalfee = $sr['fees'];
if($sr['balance'] > 0)
{
echo $sql = "insert into fees_transaction(stdid,submitdate,transcation_remark,paid,via) values('$sid','$submitdate','$transcation_remark','$paid','$via') ";
$conn->query($sql);
echo $sql = "SELECT sum(paid) as totalpaid FROM fees_transaction WHERE stdid = '$sid'";
$tpq = $conn->query($sql);
$tpr = $tpq->fetch_assoc();
$totalpaid = $tpr['totalpaid'];
$tbalance = $totalfee - $totalpaid;
$sql = "update student set balance='$tbalance' where id = '$sid'";
$conn->query($sql);
}
