<?php
$pagetitle = 'Select Customer';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	if( isset($_POST['thesubmit']) )
		{
			$addedclause = '';
			$formfield['ffcustfirstname'] = trim($_POST['custfirstname']);
			$formfield['ffcustlastname'] = trim($_POST['custlastname']);
			$formfield['ffcustaddress'] = trim($_POST['custaddress']);
			$formfield['ffcustphone'] = trim(strtolower($_POST['custphone']));
			$formfield['ffcustplan'] = trim($_POST['custplan']);
			
			if ($formfield['ffcustplan'] != '') {
				$addedclause .= " AND dbcustplan = :bvcustplan";
			}
			
			$sqlselect = "SELECT * from customers where dbcustfirstname like CONCAT('%', :bvcustfirstname, '%')
							AND dbcustlastname like CONCAT('%', :bvcustlastname, '%')
							AND dbcustaddress like CONCAT('%', :bvcustaddress, '%')
							AND dbcustphone like CONCAT('%', :bvcustphone, '%')"
							. $addedclause;
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvcustfirstname', $formfield['ffcustfirstname']);
			$result->bindValue(':bvcustlastname', $formfield['ffcustlastname']);
			$result->bindValue(':bvcustaddress', $formfield['ffcustaddress']);
			$result->bindValue(':bvcustphone', $formfield['ffcustphone']);
			if ($formfield['ffcustplan'] != '') {
				$result->bindValue(':bvcustplan', $formfield['ffcustplan']);
			}
			$result->execute();
		}
	else
		{
			$sqlselect = "SELECT * from customers";
			$result = $db-> query($sqlselect);
		}

if ($visible == 1)
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Customer Information</legend>
				<table border>
					<tr>
						<th>Customer First Name</th>
						<td><input type="text" name="custfirstname" id="custfirstname"
						value = "<?php echo $formfield['ffcustfirstname']; ?>"	></td>
					</tr>
					<tr>
						<th>Customer Last Name</th>
						<td><input type="text" name="custlastname" id="custlastname"
						value = "<?php echo $formfield['ffcustlastname']; ?>"	></td>
					</tr>
					<tr>
						<th>Address</th>
						<td><input type="text" name="custaddress" id="custaddress"
						value = "<?php echo $formfield['ffcustaddress']; ?>"></td>
					</tr>
					<tr>
						<th>Phone</th>
						<td><input type="text" name="custphone" id="custphone"
						value = "<?php echo $formfield['ffcustphone']; ?>"></td>
					</tr>
					<tr>
						<th><label for="custplan">Customer Plan:</label></th>
						<td><select name="custplan" id="custplan">
								<option value="" <?php if( isset($_POST['custplan']) && $formfield['ffcustplan'] == "" ){echo ' selected';}?>>SELECT ONE</option>
								<option value="1" <?php if( isset($_POST['custplan']) && $formfield['ffcustplan'] == "1" ){echo ' selected';}?>>Standard</option>
								<option value="2" <?php if( isset($_POST['custplan']) && $formfield['ffcustplan'] == "2" ){echo ' selected';}?>>Gold</option>
								<option value="3" <?php if( isset($_POST['custplan']) && $formfield['ffcustplan'] == "3" ){echo ' selected';}?>>Platinum</option>
								
							</select>
						</td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Customer</th>
		<th>Address</th>
		<th>Phone</th>
		<th>Plan</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				if ($row['dbcustplan'] == 1) {
					$planholder = "Standard";
				} else if ($row['dbcustplan'] == 2) {
					$planholder = "Gold";
				} else if ($row['dbcustplan'] == 3) {
					$planholder = "Platinum";
				}
				echo '<tr><td>' . $row['dbcustfirstname'] . ' ' . $row['dbcustlastname'] . '</td><td> ' . $row['dbcustaddress'] . 
				'</td><td> ' . $row['dbcustphone'] . 
				'</td><td> ' . $planholder . 
				'</td><td> ' .
				
				'<form action = "updatecustomer.php" method = "post">
						<input type = "hidden" name = "custid" value = "'
						. $row['dbcustid'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>'
				
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>