<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>SQL Insert</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
	<?php
	//NECESSARY VARIABLES
	$errormsg = "";
	$showform = 1;
	//DATABASE CONNECTION
	require_once "connect.php";

		if( isset($_POST['thesubmit']) )
		{
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffname'] = trim($_POST['fullname']);
			$formfield['ffaddress'] = trim($_POST['address']);
			$formfield['ffemail'] = trim(strtolower($_POST['email']));
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffname'])){$errormsg .= "<p>Your name is empty.</p>";}
			if(empty($formfield['ffaddress'])){$errormsg .= "<p>Your address is empty.</p>";}
			if(empty($formfield['ffemail'])){$errormsg .= "<p>Your email is empty.</p>";}
			
			//VALIDATE THE EMAIL
    		if (!filter_var($formfield['ffemail'], FILTER_VALIDATE_EMAIL))
			{
				$errormsg .= "<p>Your email is not valid.</p>";
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
				try
				{
					//enter data into database
					$sqlinsert = 'INSERT INTO theinfo (dbname, dbaddress, dbemail)
								  VALUES (:bvname, :bvaddress, :bvemail)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvname', $formfield['ffname']);
					$stmtinsert->bindvalue(':bvaddress', $formfield['ffaddress']);
					$stmtinsert->bindvalue(':bvemail', $formfield['ffemail']);
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


	$sqlselect = 'SELECT dbname, dbaddress, dbemail from theinfo';

	$result = $db-> query($sqlselect);


	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Personal Information</legend>
				<table border>
					<tr>
						<th>Name</th>
						<td><input type="text" name="fullname" id="fullname"
						value = <?php echo $formfield['ffname']; ?>	></td>
					</tr>
					<tr>
						<th>Address</th>
						<td><input type="text" name="address" id="address"
						value = <?php echo $formfield['ffaddress']; ?>></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type="text" name="email" id="email"
						value = <?php echo $formfield['ffemail']; ?>></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Name</th>
		<th>Address</th>
		<th>E-Mail</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbname'] . '</td><td> ' . $row['dbaddress'] . 
				'</td><td> ' . $row['dbemail'] . '</td></tr>';
			}
		?>
	</table>
	</body>
</html>