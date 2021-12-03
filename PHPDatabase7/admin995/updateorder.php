<?php
$pagetitle = 'Update Order';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
	$sqlselectc = "SELECT * from customers";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
	
	$sqlselectt = "SELECT * from technicians";
	$resultt = $db->prepare($sqlselectt);
	$resultt->execute();
	
		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['fforderid'] = $_POST['orderid'];
			$sqlselect = 'SELECT * from orderinfo where dborderid = :bvorderid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvorderid', $formfield['fforderid']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['fforderid'] = $_POST['orderid'];
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffordercust'] = trim($_POST['ordercust']);
			$formfield['ffordertech'] = trim($_POST['ordertech']);
			
			if ($_POST['orderdate'] != '') {
				$formfield['fforderdate'] = date_create(trim($_POST['orderdate']));
				$formfield['fforderdate']  = date_format($formfield['fforderdate'], 'Y-m-d');
			} 
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffordercust'])){$errormsg .= "<p>Your customer name field is empty.</p>";}
			if(empty($formfield['ffordertech'])){$errormsg .= "<p>Your technician is empty.</p>";}
			if(empty($formfield['fforderdate'])){$errormsg .= "<p>Your order date is empty.</p>";}
			
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
					$sqlinsert = 'update orderinfo set dbordercust = :bvordercust,
								  dbordertech = :bvordertech,
								  dborderdate = :bvorderdate
								  where dborderid = :bvorderid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvordercust', $formfield['ffordercust']);
					$stmtinsert->bindvalue(':bvordertech', $formfield['ffordertech']);
					$stmtinsert->bindvalue(':bvorderdate', $formfield['fforderdate']);
					$stmtinsert->bindvalue(':bvorderid', $formfield['fforderid']);
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

	if ($showform == 1  && $visible == 1)
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Order Information</legend>
				<table border>
					<tr>
						<th><label for="ordercust">Customer:</label></th>
						<td><select name="ordercust" id="ordercust">
						<option value = "">Please Select a Customer</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['dbcustid'] == $row['dbordercust'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['dbcustid'] . '" ' . $checker . '>' . $rowc['dbcustfirstname'] . ' ' . $rowc['dbcustlastname'] .  '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th><label for="tech">Technician:</label></th>
						<td><select name="ordertech" id="ordertech">
						<option value = "">Please Select a Technician</option>
						<?php while ($rowt = $resultt->fetch() )
							{
							if ($rowt['dbtechid'] == $row['dbordertech'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowt['dbtechid'] . '" ' . $checker . '>' . $rowt['dbtechname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<?php
						$dateholder = date_create($row['dborderdate']);
						$dateholder = date_format($dateholder, 'Y-m-d');
						?>
						<th>Date of Order</th>
						<td><input type="date" name="orderdate" id="orderdate" 
							value="<?php echo $dateholder ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "orderid" value=<?php echo $formfield['fforderid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && $visible == 1) {
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Order Information</legend>
					<table>
					<tr>
						<th><label for="ordercust">Customer:</label></th>
						<td><select name="ordercust" id="ordercust">
						<option value = "">Please Select a Customer</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['dbcustid'] == $formfield['ffordercust'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['dbcustid'] . '" ' . $checker . '>' . $rowc['dbcustfirstname'] . ' ' . $rowc['dbcustlastname'] .  '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th><label for="tech">Technician:</label></th>
						<td><select name="ordertech" id="ordertech">
						<option value = "">Please Select a Technician</option>
						<?php while ($rowt = $resultt->fetch() )
							{
							if ($rowt['dbtechid'] == $formfield['ffordertech'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowt['dbtechid'] . '" ' . $checker . '>' . $rowt['dbtechname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Date of Order</th>
						<td><input type="date" name="orderdate" id="orderdate" 
							value="<?php echo $formfield['fforderdate'] ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "orderid" value=<?php echo $formfield['fforderid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>