<?php
$pagetitle = 'Select Appointment';
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
			$formfield['ffcustomer'] = trim($_POST['customer']);
			$formfield['fftech'] = trim($_POST['tech']);
			$formfield['ffapptdate'] = trim(strtolower($_POST['apptdate']));
			
			$sqlselect = "select appointments.dbapptdate, customers.dbcustname, technicians.dbtechname
							from appointments, customers, technicians
							where appointments.dbapptcustomer = customers.dbcustid
							AND appointments.dbappttech = technicians.dbtechid
							AND appointments.dbapptcustomer like CONCAT('%', :bvcustomer, '%')
							AND appointments.dbappttech like CONCAT('%', :bvtech, '%')
							AND appointments.dbapptdate like CONCAT('%', :bvapptdate, '%')";
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvcustomer', $formfield['ffcustomer']);
			$result->bindValue(':bvtech', $formfield['fftech']);
			$result->bindValue(':bvapptdate', $formfield['ffapptdate']);
			$result->execute();
			
		}
	else
		{
			$sqlselect = "select appointments.dbapptdate, customers.dbcustname, technicians.dbtechname
				from appointments, customers, technicians
				where appointments.dbapptcustomer = customers.dbcustid
				and appointments.dbappttech = technicians.dbtechid";
			$result = $db-> query($sqlselect);
		}

if ($visible == 1)
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Appointment Information</legend>
				<table border>
					<tr>
						<th><label for="customer">Customer:</label></th>
						<td><select name="customer" id="customer">
						<option value = "">Please Select a Position</option>
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