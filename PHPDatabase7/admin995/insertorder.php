<?php
	$pagetitle = 'Insert Order';
	require_once "header.php";
	require_once "connect.php";
	$showform = 1;
	
	$sqlselectt = "SELECT * from technicians";
	$resultt = $db->prepare($sqlselectt);
	$resultt->execute();
	
	$sqlselectc = "SELECT * from customers";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
	
		//This code will only occur after the enter button
	//has been clicked
	if (isset($_POST['thesubmit']) )
	{
		//This code will place the entered data into an
		//Associative array after cleansing
		$formfield['ffordercust'] = trim($_POST['ordercust']);
		$formfield['ffordertech'] = $_POST['ordertech'];
		
		//Validates that the fields were entered
		if(empty($formfield['ffordercust'])) {
			$errormsg .= "<p>Your Have not Selected a Customer</p>";
		}
		if(empty($formfield['ffordertech'])) {
			$errormsg .= "<p>You Have not Selected an Employee</p>";
		}
	
		
		if ($errormsg != "") {
			echo "YOU HAVE ERRORS!!!!";
			echo $errormsg;
		}		
		else {
			
			$sqlmax = "SELECT MAX(dborderid) AS maxid from orderinfo";
			$resultmax = $db->prepare($sqlmax);
			$resultmax->execute();
			$rowmax = $resultmax->fetch();
			$maxid = $rowmax["maxid"];	
			$maxid = $maxid + 1;

			//Creates the sql query
			$sqlinsert = 'INSERT INTO orderinfo (dborderid, dbordercust,
				dbordertech, dborderopen, dborderdate) VALUES (:bvorderid, :bvordercust, :bvordertech, 1, now())';
			
			//Prepares the SQL Statement for execution
			$stmtinsert = $db->prepare($sqlinsert);
			//Binds our associative array variables to the bound
			//variables in the sql statement
			$stmtinsert->bindvalue(':bvorderid', $maxid);
			$stmtinsert->bindvalue(':bvordercust', $formfield['ffordercust']);
			$stmtinsert->bindvalue(':bvordertech', $formfield['ffordertech']);

			//Runs the insert statement and query
			$stmtinsert->execute();
			
			echo "Order Number: " . $maxid;
			echo '<br><br><form action="insertorderitem.php" method = "post">';
			echo '<input type = "hidden" name = "orderid" value = "'. $maxid .'">';
			echo '<input type="submit" name="thesubmit" value="Enter Order Items">';
			echo "</form>";
			$showform = 0;
		}
	}
	

if ($visible == 1 && $showform == 1)
{
?>


	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<fieldset><legend>Order Info</legend>
		
		<table border>
			<tr>
			<th><label for="customer">Customer:</label></th>
				<td><select name="ordercust" id="ordercust">
					<option value = "">Please Select a Customer</option>
					<?php while ($rowc = $resultc->fetch() )
						{
						echo '<option value="'. $rowc['dbcustid'] . '">' . $rowc['dbcustfirstname'] . ' ' . $rowc['dbcustlastname'] .  '</option>';
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
			<th><label for="employee">Technician:</label></th>
				<td><select name="ordertech" id="ordertech">
						<option value = "">Please Select a Technician</option>
						<? while ($rowt = $resultt->fetch() )
							{
								if ($_SESSION['userid'] == $rowt['dbtechid']) {
									$selected = 'selected'; 
								} else {
									$selected = '';
								}
								echo '<option value="'. $rowt['dbtechid'] . '" ' . $selected . '>' 
								. $rowt['dbtechname'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="thesubmit" value="Enter">
		</fieldset>
	</form>

<?php
}//visible
include_once 'footer.php';
?>