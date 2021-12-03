<?php
$pagetitle = 'Insert Appointment';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

	$sqlselectc = "SELECT * from customers";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
	
	$sqlselectt = "SELECT * from technicians";
	$resultt = $db->prepare($sqlselectt);
	$resultt->execute();
	
		if( isset($_POST['thesubmit']) )
		{
			echo '<p>The form was submitted.</p>';
			echo $_POST['customer'];
			echo $_POST['tech'];
			//Data Cleansing
			$formfield['ffcustomer'] = $_POST['customer'];
			$formfield['fftech'] = $_POST['tech'];
			$formfield['ffapptdate'] = trim(strtolower($_POST['apptdate']));
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffcustomer'])){$errormsg .= "<p>Your customer field is empty.</p>";}
			if(empty($formfield['fftech'])){$errormsg .= "<p>Your technician field is empty.</p>";}
			if(empty($formfield['ffapptdate'])){$errormsg .= "<p>Your date is empty.</p>";}
			
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
			if($errormsg != "")
			{
				echo "<div class='error'><p>THERE ARE ERRORS!</p>";
				echo $errormsg;
				echo "</div>";
			}
			else
			{
				try
				{
					//enter data into database
					$sqlinsert = 'INSERT INTO appointments (dbapptcustomer, dbappttech, dbapptdate)
								  VALUES (:bvcustomer, :bvtech, :bvapptdate)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvcustomer', $formfield['ffcustomer']);
					$stmtinsert->bindvalue(':bvtech', $formfield['fftech']);
					$stmtinsert->bindvalue(':bvapptdate', $formfield['ffapptdate']);
					$stmtinsert->execute();
					echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit


	$sqlselect = "select appointments.dbapptdate, customers.dbcustname, technicians.dbtechname
				from appointments, customers, technicians
				where appointments.dbapptcustomer = customers.dbcustid
				and appointments.dbappttech = technicians.dbtechid";
	
	$result = $db-> query($sqlselect);

if ($visible == 1)
{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Appointment Information</legend>
				<table border>
					<tr>
						<th><label for="customer">Customer:</label></th>
						<td><select name="customer" id="customer">
						<option value = "">Please Select a Customer</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							echo '<option value="'. $rowc['dbcustid'] . '">' . $rowc['dbcustname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th><label for="tech">Technician:</label></th>
						<td><select name="tech" id="tech">
						<option value = "">Please Select a Position</option>
						<?php while ($rowt = $resultt->fetch() )
							{
							echo '<option value="'. $rowt['dbtechid'] . '">' . $rowt['dbtechname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Date of Appointment</th>
						<td><input type="date" name="apptdate" id="apptdate"
						value = <?php echo $formfield['ffapptdate']; ?>></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Customer</th>
		<th>Technician</th>
		<th>Appointment Date</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbcustname'] . '</td><td> ' . $row['dbtechname'] . 
				'</td><td> ' . $row['dbapptdate'] . '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>