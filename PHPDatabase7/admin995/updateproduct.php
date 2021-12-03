<?php
$pagetitle = 'Update Product';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$sqlselectc = "SELECT * from categories";
$resultc = $db->prepare($sqlselectc);
$resultc->execute();
	
		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['ffprodid'] = $_POST['prodid'];
			$sqlselect = 'SELECT * from products where dbprodid = :bvprodid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvprodid', $formfield['ffprodid']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['ffprodid'] = $_POST['prodid'];
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffprodname'] = trim($_POST['prodname']);
			$formfield['ffproddescr'] = trim($_POST['proddescr']);
			$formfield['ffprodprice'] = trim(strtolower($_POST['prodprice']));
			$formfield['ffprodcat'] = trim($_POST['prodcat']);
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffprodname'])){$errormsg .= "<p>Your product name field is empty.</p>";}
			if(empty($formfield['ffproddescr'])){$errormsg .= "<p>Your description is empty.</p>";}
			if(empty($formfield['ffprodprice'])){$errormsg .= "<p>Your price is empty.</p>";}
			if(empty($formfield['ffprodcat'])){$errormsg .= "<p>Your category is empty.</p>";}
			
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
					$sqlinsert = 'update products set dbprodname = :bvprodname,
								  dbproddescr = :bvproddescr,
								  dbprodprice = :bvprodprice,
								  dbprodcat = :bvprodcat
								  where dbprodid = :bvprodid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvprodname', $formfield['ffprodname']);
					$stmtinsert->bindvalue(':bvproddescr', $formfield['ffproddescr']);
					$stmtinsert->bindvalue(':bvprodprice', $formfield['ffprodprice']);
					$stmtinsert->bindvalue(':bvprodcat', $formfield['ffprodcat']);
					$stmtinsert->bindvalue(':bvprodid', $formfield['ffprodid']);
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
			<fieldset><legend>Product Information</legend>
				<table border>
					<tr>
						<th>Product Name</th>
						<td><input type="text" name="prodname" id="prodname"
						value = "<?php echo $row['dbprodname']; ?>"></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><input type="text" name="proddescr" id="proddescr"
						value = "<?php echo $row['dbproddescr']; ?>"></td>
					</tr>
					<tr>
						<th>Price</th>
						<td><input type="text" name="prodprice" id="prodprice"
						value = "<?php echo $row['dbprodprice']; ?>"></td>
					</tr>
					<tr>
						<th><label>Category:</label></th>
						<td><select name="prodcat" id="prodcat">
						<option value = "">Please Select a Category</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['dbcatid'] == $row['dbprodcat'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['dbcatid'] . '" ' . $checker . '>' . $rowc['dbcatname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
				</table>
				<input type="hidden" name = "prodid" value=<?php echo $formfield['ffprodid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && $visible == 1) {
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Product Information</legend>
				<table border>
					<tr>
						<th>Product Name</th>
						<td><input type="text" name="prodname" id="prodname"
						value = "<?php echo $formfield['ffprodname']; ?>"></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><input type="text" name="proddescr" id="proddescr"
						value = "<?php echo $formfield['ffproddescr']; ?>"></td>
					</tr>
					<tr>
						<th>Price</th>
						<td><input type="text" name="prodprice" id="prodprice"
						value = "<?php echo $formfield['ffprodprice']; ?>"></td>
					</tr>
					<tr>
						<th><label>Category:</label></th>
						<td><select name="prodcat" id="prodcat">
						<option value = "">Please Select a Category</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['dbcatid'] == $formfield['ffprodcat'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['dbcatid'] . '" ' . $checker . '>' . $rowc['dbcatname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
				</table>
				<input type="hidden" name = "prodid" value=<?php echo $formfield['ffprodid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>