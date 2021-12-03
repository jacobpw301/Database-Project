<?php
	require_once "header.php";
	require_once "connect.php";

	//NECESSARY VARIABLES
	$errormsg = "";
	$showform = 0;
	//DATABASE CONNECTION
	require_once "connect.php";

		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['fftechtitleid'] = $_POST['techtitleid'];
			$sqlselect = 'SELECT * from techtitle where dbtechtitleid = :bvtechtitleid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvtechtitleid', $formfield['fftechtitleid']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['fftechtitleid'] = $_POST['techtitleid'];
			$formfield['fftechtitlename'] = $_POST['techtitlename'];
			echo '<p>The form was submitted.</p>';
	
			
			if(empty($formfield['fftechtitlename'])){$errormsg .= "<p>Your title is empty.</p>";}
			
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
					$sqlinsert = 'update techtitle set dbtechtitlename = :bvtechtitlename
								  where dbtechtitleid = :bvtechtitleid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvtechtitlename', $formfield['fftechtitlename']);
					$stmtinsert->bindvalue(':bvtechtitleid', $formfield['fftechtitleid']);
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
			<fieldset><legend>Technician Title Information</legend>
				<table border>
					<tr>
						<th>Technician Title</th>
						<td><input type="text" name="techtitlename" id="techtitlename"
						value = "<?php echo $row['dbtechtitlename']; ?>"	></td>
					</tr>
					
				</table>
				<input type="hidden" name = "techtitleid" value=<?php echo $formfield['fftechtitleid'] ?>>
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
						<th>Technician Title</th>
						<td><input type="text" name="techtitlename" id="techtitlename"
						value = "<?php echo $formfield['fftechtitlename']; ?>"	></td>
					</tr>
					
				</table>
				<input type="hidden" name = "techtitleid" value=<?php echo $formfield['fftechtitleid'] ?>>
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