<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>SQL Insert</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
	<?php
	$errormsg = "";
	//DATABASE CONNECTION
	require_once "connect.php";
	if( isset($_POST['thesubmit']) )
		{
			$formfield['fffullname'] = trim($_POST['fullname']);
			$formfield['ffaddress'] = trim($_POST['address']);
			$formfield['ffemail'] = trim(strtolower($_POST['email']));
			
			$sqlselect = "SELECT * from theinfo where dbname like CONCAT('%', :bvname, '%')
							AND dbaddress like CONCAT('%', :bvaddress, '%')
							AND dbemail like CONCAT('%', :bvemail, '%')";
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvname', $formfield['fffullname']);
			$result->bindValue(':bvaddress', $formfield['ffaddress']);
			$result->bindValue(':bvemail', $formfield['ffemail']);
			$result->execute();
		}
	else
		{
			$sqlselect = "SELECT * from theinfo";
			$result = $db-> query($sqlselect);
		}
	
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Personal Information</legend>
				<table border>
					<tr>
						<th>Name</th>
						<td><input type="text" name="fullname" id="fullname"
						value = <?php echo $formfield['fffullname']; ?>	></td>
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