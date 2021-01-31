<?php
include("php/dbconnect.php");

if(isset($_POST['req']) && $_POST['req']=='1') 
{

$sid = (isset($_POST['student']))?mysqli_real_escape_string($conn,$_POST['student']):'';

 $sql = "select s.id,s.sname,s.balance,s.fees,s.contact,b.branch,s.joindate from student as s,branch as b where b.id=s.branch and  s.delete_status='0' and s.id='".$sid."'";
$q = $conn->query($sql);
if($q->num_rows>0)
{

$res = $q->fetch_assoc();
?>
<script>
function paymentfun(id)
{
	if(id > 0)
	{
		$(".paymentvia").show();
	}
	else
	{
		$(".paymentvia").hide();
		$(".paymentval").val('');
	}
}
</script>
<style>
.error
{
	color:red !important;
}
</style>
<form class="form-horizontal" id ="signupForm1" action="fees.php" method="post">
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Name:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" disabled  value=<?php echo $res['sname']; ?>  >
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Contact:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" disabled  value=<?php echo $res['contact']; ?>  />
    </div>
  </div>
  
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Total Fee:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="totalfee" id="totalfee"   value=<?php echo $res['fees']; ?>  disabled />
    </div>
  </div>
  
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Balance:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="balance"  id="balance" value=<?php echo $res['balance']; ?>  disabled />
	  <input type="hidden" value=<?php echo $res['id']; ?> name="sid">
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Via:</label>
    <div class="col-sm-10">
     <select name="via" id="via" onChange="paymentfun(this.value)"  class="form-control">
       <option value="0">Select payment method</option>
        <option value="1">ATM</option>
        <option value="2">Credit Card</option>
        <option value="3">eBanking</option>
    </select>
    </div>
  </div>
  
  <div class="form-group paymentvia" style=" display: none">
    <label class="control-label col-sm-2" for="accountno">A/c No:</label>
    <div class="col-sm-10">
      <input type="number" class="form-control paymentval" name="accountno"  id="accountno"  />
    </div>
    </select>
    </div>
  </div>
  <div class="form-group paymentvia" style=" display: none">
    <label class="control-label col-sm-2" for="accountpass">Password:</label>
    <div class="col-sm-10">
      <input type="number" class="form-control paymentval" name="accountpass"  id="accountpass"  />
    </div>
    </select>
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Paid:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control " name="paid"  id="paid"  />
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Date:</label>
    <div class="col-sm-10">
	
      <input type="text" class="form-control" name="submitdate"  id="submitdate" style="background:#fff;"  readonly />
    </div>
  </div>
  
  
   <div class="form-group">
    <label class="control-label col-sm-2" for="email">Remark:</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="transcation_remark" id="transcation_remark"></textarea>
    </div>
  </div>
 
 
 
 
 
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary" name="save">Save</button>
      <span id="feeerror" style="color:red"></span>
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready( function() {
$("#submitdate").datepicker( {
        changeMonth: true,
        changeYear: true,
       
        dateFormat: "yy-mm-dd",
      
    });
	
	
///////////////////////////

$( "#signupForm1" ).validate( {
				rules: {
					submitdate: "required",
					
					paid: {
						required: true,
						digits: true,
						max:<?php echo $res['balance'];?>
					},
					via:{
						required:true,min:1
					},	
					accountno: {
						required: true,
						digits: true,
						min:1
					},
					accountpass: {
						required: true,
						digits: true,
						min:1
					},
					
				},
				submitHandler: function(){
					 $('#feeerror').html('');
					 //alert("Submitted!") ;
					 var  accountno		=	$('#accountno').val();
					 var  accountpass	=	$('#accountpass').val();
					 var  paid			=	$('#paid').val();
					 $.post("checkbalance.php",{accountno:accountno,accountpass:accountpass},function(response){
						 //alert(response);
						 if(response	==	'incorrect')
						 {
							 $('#feeerror').html('Incorrect a/c no or password .');
						 }
						 else
						 {
							 response	=	parseInt(response);
							 if(paid > response)
							 {
							 $('#feeerror').html('Unable to pay.Your balance is Rs.'+response);
							 }
							 else
							 {
     							$.post("payfee.php",$("#signupForm1").serialize()).done(function(msg){
																		window.location="fees.php?act=1";
																	});
							 }
						 }
					});
				}
				/*errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					// Add `has-feedback` class to the parent div.form-group
					// in order to add icons to inputs
					element.parents( ".col-sm-10" ).addClass( "has-feedback" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}

					
					if ( !element.next( "span" )[ 0 ] ) {
						$( "<span class=\'glyphicon glyphicon-remove form-control-feedback\'></span>" ).insertAfter( element );
					}
				},
				success: function ( label, element ) {
					if ( !$( element ).next( "span" )[ 0 ] ) {
						$( "<span class=\'glyphicon glyphicon-ok form-control-feedback\'></span>" ).insertAfter( $( element ) );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
					$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
				},
				unhighlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
					$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
				}*/
			} );


//////////////////////////	
	
	
	
});

</script>
<?php

}else
{
echo "Something Goes Wrong! Try After sometime.";
}


}

if(isset($_POST['req']) && $_POST['req']=='2') 
{

$sid = (isset($_POST['student']))?mysqli_real_escape_string($conn,$_POST['student']):'';
$sql = "select * from fees_transaction  where stdid='".$sid."'";
$fq = $conn->query($sql);
if($fq->num_rows>0)
{


 $sql = "select s.id,s.sname,s.balance,s.fees,s.contact,b.branch,s.joindate from student as s,branch as b where b.id=s.branch  and s.id='".$sid."'";
$sq = $conn->query($sql);
$sr = $sq->fetch_assoc();

echo '
<h4>Student Info</h4>
<div class="table-responsive">
<table class="table table-bordered">
<tr>
<th>Name</th>
<td>'.$sr['sname'].'</td>
<th>Branch</th>
<td>'.$sr['branch'].'</td>
</tr>
<tr>
<th>Contact</th>
<td>'.$sr['contact'].'</td>
<th>Joining Date</th>
<td>'.date("d-m-Y", strtotime($sr['joindate'])).'</td>
</tr>


</table>
</div>
';


echo '
<h4>Fee Info</h4>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
      <tr>
        <th>Date</th>
        <th>Paid</th>
        <th>Payment Method</th>
        <th>Remark</th>
      </tr>
    </thead>
    <tbody>';
	$totapaid = 0;
while($res = $fq->fetch_assoc())
{
$totapaid+=$res['paid'];
?>
        <td><?php echo date("d-m-Y", strtotime($res['submitdate'])) ?></td>
        <td><?php echo  $res['paid']?></td>
        <td><?php if($res['via']==1) echo  "ATM"; else if($res['via']==2) echo  "Credit Card"; else if($res['via']==3) echo  "eBanking"; else echo "Manual";?></td>
        <td><?php echo  $res['transcation_remark'] ?></td>
      </tr>'
       <?php
}
      
echo '	  
    </tbody>
  </table>
 </div> 
 
<table style="width:150px;" >
<tr>
<th>Total Fees: 
</th>
<td>'.$sr['fees'].'
</td>
</tr>

<tr>
<th>Total Paid: 
</th>
<td>'.$totapaid.'
</td>
</tr>

<tr>
<th>Balance: 
</th>
<td>'.$sr['balance'].'
</td>
</tr>
</table>
 ';


 }
else
{
echo 'No fees submit.';
}
 
}
		
		 
			
			
	

?>