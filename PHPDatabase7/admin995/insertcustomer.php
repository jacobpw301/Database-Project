<?php
$pagetitle = 'Insert Customer';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

		if( isset($_POST['thesubmit']) )
		{
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffcustfirstname'] = trim($_POST['custfirstname']);
			$formfield['ffcustlastname'] = trim($_POST['custlastname']);
			$formfield['ffcustaddress'] = trim($_POST['custaddress']);
			$formfield['ffcustphone'] = trim(strtolower($_POST['custphone']));
			$formfield['ffcustplan'] = trim($_POST['custplan']);
			$formfield['ffcustpass'] = trim($_POST['custpass']);
			$formfield['ffcustpass2'] = trim($_POST['custpass2']);
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffcustfirstname'])){$errormsg .= "<p>Your customer name field is empty.</p>";}
			if(empty($formfield['ffcustlastname'])){$errormsg .= "<p>Your customer last field is empty.</p>";}
			if(empty($formfield['ffcustaddress'])){$errormsg .= "<p>Your address is empty.</p>";}
			if(empty($formfield['ffcustphone'])){$errormsg .= "<p>Your phone is empty.</p>";}
			if(empty($formfield['ffcustplan'])){$errormsg .= "<p>Your plan is empty.</p>";}
			if(empty($formfield['ffcustpass'])){$errormsg .= "<p>Your password is empty.</p>";}
			if(empty($formfield['ffcustpass2'])){$errormsg .= "<p>Your confirm password is empty.</p>";}
			
			//CHECK FOR MATCHING PASSWORDS
			if($formfield['ffcustpass'] != $formfield['ffcustpass2'])
			{
				$errormsg .= "<p>Your passwords do not match.</p>";
			}
			
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
				$options = [
					'cost' => 12,
						];
				$encpass = password_hash($formfield['ffcustpass'], PASSWORD_BCRYPT, $options);
				
				
				//Create user name
				$basecustusername = strtolower($formfield['ffcustfirstname'].$formfield['ffcustlastname']);
				$checker = 1;
				$usernameaddon = 0;
				$finalcustusername = $basecustusername;
				
				while ($checker == 1) {
					$sqluncount = "SELECT count(*) as dbuncounter from customers
							where dbcustusername = :bvcustusername";
					$countunresult = $db-> prepare($sqluncount);
					$countunresult->bindValue(':bvcustusername', $finalcustusername);
					$countunresult->execute();
					$rowuncount = $countunresult-> fetch();
					$countun = $rowuncount['dbuncounter'];
					
					if ($countun > 0) {
						$usernameaddon += 1;
						$finalcustusername = $basecustusername.$usernameaddon;
					} else {
						$checker = 0;
					}
				}
				
				
				try
				{
					
					//enter data into database
					$sqlinsert = 'INSERT INTO customers (dbcustfirstname, dbcustlastname, dbcustaddress, dbcustphone, dbcustplan, dbcustusername, dbcustpass)
								  VALUES (:bvcustfirstname, :bvcustlastname,:bvcustaddress, :bvcustphone, :bvcustplan, :bvcustusername, :bvcustpass)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvcustfirstname', $formfield['ffcustfirstname']);
					$stmtinsert->bindvalue(':bvcustlastname', $formfield['ffcustlastname']);
					$stmtinsert->bindvalue(':bvcustaddress', $formfield['ffcustaddress']);
					$stmtinsert->bindvalue(':bvcustphone', $formfield['ffcustphone']);
					$stmtinsert->bindvalue(':bvcustplan', $formfield['ffcustplan']);
					$stmtinsert->bindvalue(':bvcustusername', $formfield['ffcustusername']);
					$stmtinsert->bindvalue(':bvcustusername', $finalcustusername);
					$stmtinsert->bindvalue(':bvcustpass', $encpass);
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


	$sqlselect = 'SELECT * from customers';

	$result = $db-> query($sqlselect);

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
					<tr>
						<th>Password</th>
						<td><input type="password" name="custpass" id="custpass"></td>
					</tr>
					<tr>
						<th>Confirm Password</th>
						<td><input type="password" name="custpass2" id="custpass2"></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Customer Name</th>
		<th>Address</th>
		<th>Phone</th>
		<th>Plan</th>
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
				'</td><td> ' . $planholder . '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>