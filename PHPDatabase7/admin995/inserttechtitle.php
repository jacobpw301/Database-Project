<?php
$pagetitle = 'Insert Tech Title';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

		if( isset($_POST['thesubmit']) )
		{
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['fftechtitlename'] = trim($_POST['techtitlename']);
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
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
					$sqlinsert = 'INSERT INTO techtitle (dbtechtitlename)
								  VALUES (:bvtechtitlename)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvtechtitlename', $formfield['fftechtitlename']);
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


	$sqlselect = 'SELECT * from techtitle';

	$result = $db-> query($sqlselect);

if ($visible == 1)
{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Tech Title Information</legend>
				<table border>
					<tr>
						<th>Tech Title</th>
						<td><input type="text" name="techtitlename" id="techtitlename"
						value = <?php echo $formfield['fftechtitlename']; ?>></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Tech Title ID</th>
		<th>Tech Title Name</th>		
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbtechtitleid'] . '</td><td> ' . $row['dbtechtitlename'] . '</td></tr> ';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>