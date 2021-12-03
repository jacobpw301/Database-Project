<?php
	include_once "header.php";
	require_once "connect.php";
?>
<head>
		<link type="text/css" href="styles/register.css" rel="stylesheet">
	</head>
	<?php
	
$errormsg = "";

		$showform = 1;
		$formfield['ffcustid'] = $_POST['custid'];
		$sqlselect = 'SELECT * from customer where customerkey = :bvcustid';
		$result = $db->prepare($sqlselect);
		$result->bindValue(':bvcustid', $_SESSION['customerkey']);
		$result->execute();
		$row = $result->fetch(); 
		
	
		if( isset($_POST['thesubmit']) )
		{	
			$hasemail = true;
			$hasfirst = true;
			$haslast = true;
			$hasaddress = true;
			$hascity = true;
			$hasstate = true;
			$haszip = true;
			$haspass = true;
			$haspass2 = true;
			$hasphone = true;
			

			//Data Cleansing
			$formfield['ffemail'] = trim($_POST['customeremail']);
			$formfield['fffirstname'] = trim($_POST['customerfirstname']);
			$formfield['fflastname'] = trim($_POST['customerlastname']);
			$formfield['ffaddress'] = trim($_POST['customeraddress']);
			$formfield['ffcity'] = trim($_POST['customercity']);
			$formfield['ffstate'] = trim($_POST['customerstate']);
			$formfield['ffzip'] = trim($_POST['customerzip']);
			$formfield['ffpass'] = trim($_POST['customerpassword']);
			$formfield['ffpass2'] = trim($_POST['customerpassword2']);
			$formfield['ffphone'] = trim(strtolower($_POST['custphone']));
		
		
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
			if(empty($formfield['ffemail']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$hasemail = false;
			}
			if(empty($formfield['fffirstname']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$hasfirst = false;
			}
			if(empty($formfield['fflastname']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$haslast = false;
			}
			if(empty($formfield['ffaddress']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$hasaddress = false;
			}
			if(empty($formfield['ffcity']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$hascity = false;
			}
			if(empty($formfield['ffstate']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$hasstate = false;
			}
			if(empty($formfield['ffzip']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$haszip = false;
			}
			if(empty($formfield['ffpass']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$haspass = false;
			}
			if(empty($formfield['ffpass2']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$haspass2 = false;
			}		
			if(empty($formfield['ffphone']))
			{
				$errormsg = "<p>* One or more required fields are empty.</p>";
				$hasphone = false;
			}
			
			//CHECK FOR MATCHING PASSWORDS
			if($formfield['ffpass'] != $formfield['ffpass2'])
			{
				$errormsg .= "<p>Your passwords do not match.</p>";
			}
			
			if (!filter_var($formfield['ffemail'], FILTER_VALIDATE_EMAIL))
			{
				$errormsg .= "<p>Your email is not valid.</p>";
			}
			
			if($errormsg != "")
			{
				echo $errormsg;

			}
			else
			{
				$options = [
					'cost' => 12,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
						];
					$encpass = password_hash($formfield['ffcustpass'], PASSWORD_BCRYPT, $options);
				try
				{
					//enter data into database
					$sqlinsert = 'update customer set customeremail = :bvemail,
								  customerfirstname = :bvfirstname,
								  customerlastname = :bvlastname,
								  customeraddress = :bvaddress,
								  customercity = :bvcity,
								  customertate = :bvstate,
								  customerzip = :bvzip,
								  customerphone = :bvphone,
								  customerpassword = :bvpass
								  where customerkey = :bvcustid';
								  
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvemail', $formfield['ffemail']);
					$stmtinsert->bindvalue(':bvfirstname', $formfield['fffirstname']);
					$stmtinsert->bindvalue(':bvlastname', $formfield['fflastname']);
					$stmtinsert->bindvalue(':bvaddress', $formfield['ffaddress']);
					$stmtinsert->bindvalue(':bvcity', $formfield['ffcity']);
					$stmtinsert->bindvalue(':bvstate', $formfield['ffstate']);
					$stmtinsert->bindvalue(':bvzip', $formfield['ffzip']);
					$stmtinsert->bindvalue(':bvpass', $encpass);
					$stmtinsert->bindvalue(':bvphone', $formfield['ffphone']);
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
		if(isset($_SESSION['userid']))
	{
	?>
	<?php
	else 
	{
	?>
	<div class="container">
	<div class="signin-container">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
				<table  class="form-signin">
					<tr>
						<th><label class="label"  for="XXemail">Email<?php if($hasemail == false){echo '*';} ?></label></th>
						<td><input type="text" name="XXemail" id="XXemail" value="<?php if( isset($formfield['ffemail'])){echo $formfield['ffemail'];}?>" /></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXfirstname">First Name<?php if($hasfirst == false){echo '*';} ?></label></th>
						<td><input type="text" name="XXfirstname" id="XXfirstname" value="<?php if( isset($formfield['fffirstname'])){echo $formfield['fffirstname'];}?>"/></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXlastname">Last Name<?php if($haslast == false){echo '*';} ?></label></th>
						<td><input type="text" name="XXlastname" id="XXlastname" value="<?php if( isset($formfield['fflastname'])){echo $formfield['fflastname'];}?>"/></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXaddress">Address<?php if($hasaddress == false){echo '*';} ?></label></th>
						<td><input type="text" name="XXaddress" id="XXaddress" value="<?php if( isset($formfield['ffaddress'])){echo $formfield['ffaddress'];}?>" /></td>
					</tr>
					<tr>
						<th><label  class="label" for="XXcity">City<?php if($hascity == false){echo '*';} ?></label></th>
						<td><input type="text" name="XXcity" id="XXcity" value="<?php if( isset($formfield['ffcity'])){echo $formfield['ffcity'];}?>" /></td>
					</tr>
					<tr>
					<th><label class="label"  for="XXstate">State<?php if($hasstate == false){echo '*';} ?></label></th>
					<td><select name="XXstate" id="XXstate">
							<option value="AL" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "AL" ){echo ' selected';}?>>AL</option>
							<option value="AK" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "AK" ){echo ' selected';}?>>AK</option>
							<option value="AZ" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "AZ" ){echo ' selected';}?>>AZ</option>
							<option value="AR" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "AR" ){echo ' selected';}?>>AR</option>
							<option value="CA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "CA" ){echo ' selected';}?>>CA</option>
							<option value="CO" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "CO" ){echo ' selected';}?>>CO</option>
							<option value="CT" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "CT" ){echo ' selected';}?>>CT</option>
							<option value="DE" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "DE" ){echo ' selected';}?>>DE</option>
							<option value="FL" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "FL" ){echo ' selected';}?>>FL</option>
							<option value="GA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "GA" ){echo ' selected';}?>>GA</option>
							<option value="HI" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "HI" ){echo ' selected';}?>>HI</option>
							<option value="ID" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "ID" ){echo ' selected';}?>>ID</option>
							<option value="IL" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "IL" ){echo ' selected';}?>>IL</option>
							<option value="IN" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "IN" ){echo ' selected';}?>>IN</option>
							<option value="IA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "IA" ){echo ' selected';}?>>IA</option>
							<option value="KS" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "KS" ){echo ' selected';}?>>KS</option>
							<option value="KY" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "KY" ){echo ' selected';}?>>KY</option>
							<option value="LA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "LA" ){echo ' selected';}?>>LA</option>
							<option value="ME" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "ME" ){echo ' selected';}?>>ME</option>
							<option value="MD" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "MD" ){echo ' selected';}?>>MD</option>
							<option value="MA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "MA" ){echo ' selected';}?>>MA</option>
							<option value="MI" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "MI" ){echo ' selected';}?>>MI</option>
							<option value="MN" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "MN" ){echo ' selected';}?>>MN</option>
							<option value="MS" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "MS" ){echo ' selected';}?>>MS</option>
							<option value="MO" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "MO" ){echo ' selected';}?>>MO</option>
							<option value="MT" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "MT" ){echo ' selected';}?>>MT</option>
							<option value="NE" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "NE" ){echo ' selected';}?>>NE</option>
							<option value="NV" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "NV" ){echo ' selected';}?>>NV</option>
							<option value="NH" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "NH" ){echo ' selected';}?>>NH</option>
							<option value="NJ" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "NJ" ){echo ' selected';}?>>NJ</option>
							<option value="NM" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "NM" ){echo ' selected';}?>>NM</option>
							<option value="NY" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "NY" ){echo ' selected';}?>>NY</option>
							<option value="NC" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "NC" ){echo ' selected';}?>>NC</option>
							<option value="ND" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "ND" ){echo ' selected';}?>>ND</option>
							<option value="OH" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "OH" ){echo ' selected';}?>>OH</option>
							<option value="OK" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "OK" ){echo ' selected';}?>>OK</option>
							<option value="OR" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "OR" ){echo ' selected';}?>>OR</option>
							<option value="PA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "PA" ){echo ' selected';}?>>PA</option>
							<option value="RI" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "RI" ){echo ' selected';}?>>RI</option>
							<option value="SC" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "SC" ){echo ' selected';}?>>SC</option>
							<option value="SD" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "SD" ){echo ' selected';}?>>SD</option>
							<option value="TN" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "TN" ){echo ' selected';}?>>TN</option>
							<option value="TX" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "TX" ){echo ' selected';}?>>TX</option>
							<option value="UT" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "UT" ){echo ' selected';}?>>UT</option>
							<option value="VT" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "VT" ){echo ' selected';}?>>VT</option>
							<option value="VA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "VA" ){echo ' selected';}?>>VA</option>
							<option value="WA" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "WA" ){echo ' selected';}?>>WA</option>
							<option value="WV" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "WV" ){echo ' selected';}?>>WV</option>
							<option value="WI" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "WI" ){echo ' selected';}?>>WI</option>
							<option value="WY" <?php if( isset($formfield['ffstate']) && $formfield['ffstate'] == "WY" ){echo ' selected';}?>>WY</option>
						</select>
					</td>
					</tr>
					<tr>
						<th><label class="label"  for="XXzip">Zip<?php if($haszip == false){echo '*';} ?></label></th>
						<td><input type="text" name="XXzip" id="XXcity" value="<?php if( isset($formfield['ffcity'])){echo $formfield['ffcity'];}?>" /></td>
					</tr>
					<tr>
						<th><label class="label" for="XXpass">Password<?php if($haspass == false){echo '*';} ?></label></th>
						<td><input type="password" name="XXpass" id="XXpass" value="<?php if( isset($formfield['ffpass'])){echo $formfield['ffpass'];}?>" /></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXpass2">Confirm Password<?php if($haspass2 == false){echo '*';} ?></label></th>
						<td><input type="password" name="XXpass2" id="XXpass2" value="<?php if( isset($formfield['ffpass2'])){echo $formfield['ffpass2'];}?>" /></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXphone">Phone<?php if($hasphone == false){echo '*';} ?></label></th>
						<td><input type="text" name="XXphone" id="XXphone" value="<?php if( isset($formfield['ffphone'])){echo $formfield['ffphone'];}?>" /></td>
					</tr>
					<tr>
						<td colspan="2"><input style="margin-top: 12px;" type="submit" name="XXsubmit" value="Register" id="submit"/></td>
					</tr>
				</table>
		</form>
	</div>
	</div>
	<?php
	}
	include_once "footer.php";
	?>