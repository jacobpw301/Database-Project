<?php
	require_once "header.php";
	require_once "connect.php";

	//NECESSARY VARIABLES
	$errormsg = "";
	$showform = 0;
	//DATABASE CONNECTION
	require_once "connect.php";

	$sqlselecttt = "SELECT * from techtitle";
	$resulttt = $db->prepare($sqlselecttt);
	$resulttt->execute();

		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['fftechid'] = $_POST['techid'];
			$sqlselect = 'SELECT * from technicians where dbtechid = :bvtechid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvtechid', $formfield['fftechid']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['fftechid'] = $_POST['techid'];
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['fftechname'] = trim($_POST['techname']);
			$formfield['fftechtitle'] = trim($_POST['techtitle']);
			$formfield['fftechpay'] = $_POST['techpay'];
			$formfield['fftechusername'] = trim($_POST['techusername']);
			$formfield['fftechpass'] = trim($_POST['techpass']);
			$formfield['fftechpass2'] = trim($_POST['techpass2']);
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['fftechname'])){$errormsg .= "<p>Your customer name field is empty.</p>";}
			if(empty($formfield['fftechtitle'])){$errormsg .= "<p>Your title is empty.</p>";}
			if(empty($formfield['fftechpay'])){$errormsg .= "<p>Your pay is empty.</p>";}
			if(empty($formfield['fftechusername'])){$errormsg .= "<p>Your username is empty.</p>";}
			if(empty($formfield['fftechpass'])){$errormsg .= "<p>Your password is empty.</p>";}
			if(empty($formfield['fftechpass2'])){$errormsg .= "<p>Your confirm password is empty.</p>";}
			
			//CHECK FOR MATCHING PASSWORDS
			if($formfield['fftechpass'] != $formfield['fftechpass2'])
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
				$encpass = password_hash($formfield['fftechpass'], PASSWORD_BCRYPT, $options);
				
				try
				{
					//enter data into database
					$sqlinsert = 'update technicians set dbtechname = :bvtechname,
								  dbtechtitle = :bvtechtitle,
								  dbtechpay = :bvtechpay,
								  dbtechusername = :bvtechusername,
								  dbtechpass = :bvtechpass,
								  dbtechsalt = :bvtechsalt
								  where dbtechid = :bvtechid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvtechname', $formfield['fftechname']);
					$stmtinsert->bindvalue(':bvtechtitle', $formfield['fftechtitle']);
					$stmtinsert->bindvalue(':bvtechpay', $formfield['fftechpay']);
					$stmtinsert->bindvalue(':bvtechusername', $formfield['fftechusername']);
					$stmtinsert->bindvalue(':bvtechpass', $encpass);
					$stmtinsert->bindvalue(':bvtechsalt', $salt);
					$stmtinsert->bindvalue(':bvtechid', $formfield['fftechid']);
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
	if ($visible == 1)
	{

	if ($showform == 1)
	{
	?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Technician Information</legend>
				<table border>
					<tr>
						<th>Technician Name</th>
						<td><input type="text" name="techname" id="techname"
						value = "<?php echo $row['dbtechname']; ?>"	></td>
					</tr>
					<tr>
						<th><label for="tech">Technician Title:</label></th>
						<td><select name="techtitle" id="techtitle">
						<option value = "">Please Select a Title</option>
						<?php while ($rowtt = $resulttt->fetch() )
							{
							if ($rowtt['dbtechtitleid'] == $row['dbtechtitle'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowtt['dbtechtitleid'] . '" ' . $checker . '>' . $rowtt['dbtechtitlename'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Pay:</th>
						<td><input type="radio" name="techpay" id="12.50" 
									value="12.50" <?php if( $row['dbtechpay'] == "12.50" ){echo ' checked';}?> />
							<label for="12.50">$12.50</label>
							<input type="radio" name="techpay" id="14.50" 
									value="14.50" <?php if( $row['dbtechpay'] == "14.50" ){echo ' checked';}?>/><label for="colorgreen">$14.50</label>
							<input type="radio" name="techpay" id="15.50" 
									value="15.50" <?php if( $row['dbtechpay'] == "15.50" ){echo ' checked';}?>/><label for="colorblue">$15.50</label>
							<input type="radio" name="techpay" id="19.75" 
									value="19.75" <?php if( $row['dbtechpay'] == "19.75" ){echo ' checked';}?>/><label for="colorblue">$19.75</label>
						</td>
					</tr>
					<tr>
						<th><label for="techusername">User Name:</label></th>
						<td><input type="text" name="techusername" id="techusername"
								value = <?php echo $row['dbtechusername']; ?>	></td>
					</tr>
					<tr>
						<th><label for="techpass">Password:</label></th>
						<td><input type="password" name="techpass" id="techpass" /></td>
					</tr>
					<tr>
						<th><label for="techpass2">Confirm Password:</label></th>
						<td><input type="password" name="techpass2" id="techpass2" /></td>
					</tr>
					</tr>
				</table>
				
				
				<input type="hidden" name = "techid" value=<?php echo $formfield['fftechid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2) {
	?>

				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Technician Information</legend>
				<table border>
					<tr>
						<th>Technician Name</th>
						<td><input type="text" name="techname" id="techname"
						value = "<?php echo $formfield['fftechname']; ?>"	></td>
					</tr>
					<tr>
						<th><label for="tech">Technician Title:</label></th>
						<td><select name="techtitle" id="techtitle">
						<option value = "">Please Select a Title</option>
						<?php while ($rowtt = $resulttt->fetch() )
							{
							if ($rowtt['dbtechtitleid'] == $formfield['fftechtitle'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowtt['dbtechtitleid'] . '" ' . $checker . '>' . $rowtt['dbtechtitlename'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Pay:</th>
						<td><input type="radio" name="techpay" id="12.50" 
									value="12.50" <?php if( isset($_POST['techpay']) && $formfield['fftechpay'] == "12.50" ){echo ' checked';}?> />
							<label for="12.50">$12.50</label>
							<input type="radio" name="techpay" id="14.50" 
									value="14.50" <?php if( isset($_POST['techpay']) && $formfield['fftechpay'] == "14.50" ){echo ' checked';}?>/><label for="colorgreen">$14.50</label>
							<input type="radio" name="techpay" id="15.50" 
									value="15.50" <?php if( isset($_POST['techpay']) && $formfield['fftechpay'] == "15.50" ){echo ' checked';}?>/><label for="colorblue">$15.50</label>
							<input type="radio" name="techpay" id="19.75" 
									value="19.75" <?php if( isset($_POST['techpay']) && $formfield['fftechpay'] == "19.75" ){echo ' checked';}?>/><label for="colorblue">$19.75</label>
						</td>
					</tr>
					<tr>
						<th><label for="techusername">User Name:</label></th>
						<td><input type="text" name="techusername" id="techusername" 
							value = <?php echo $formfield['fftechusername']; ?> ></td>
					</tr>
					<tr>
						<th><label for="techpass">Password:</label></th>
						<td><input type="password" name="techpass" id="techpass" /></td>
					</tr>
					<tr>
						<th><label for="techpass2">Confirm Password:</label></th>
						<td><input type="password" name="techpass2" id="techpass2" /></td>
					</tr>
					</tr>
				</table>
				<input type="hidden" name = "techid" value=<?php echo $formfield['fftechid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
		}
		else {
		echo "You do not have permission to update";
		}
	?>	
	
<?php
}//visible
include_once 'footer.php';
?>