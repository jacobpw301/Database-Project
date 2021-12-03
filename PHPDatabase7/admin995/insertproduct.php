<?php
$pagetitle = 'Insert Product';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
$sqlselectc = "SELECT * from categories";
$resultc = $db->prepare($sqlselectc);
$resultc->execute();

		if( isset($_POST['thesubmit']) )
		{
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
					$sqlinsert = 'INSERT INTO products (dbprodname, dbproddescr, dbprodprice, dbprodcat)
								  VALUES (:bvprodname, :bvproddescr, :bvprodprice, :bvprodcat)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvprodname', $formfield['ffprodname']);
					$stmtinsert->bindvalue(':bvproddescr', $formfield['ffproddescr']);
					$stmtinsert->bindvalue(':bvprodprice', $formfield['ffprodprice']);
					$stmtinsert->bindvalue(':bvprodcat', $formfield['ffprodcat']);
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


	$sqlselect = "SELECT products.*, categories.dbcatname
							FROM products, categories
							WHERE products.dbprodcat = categories.dbcatid";

	$result = $db-> query($sqlselect);

if ($visible == 1)
{
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
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
		<table border>
	<tr>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
		<th>Category</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbprodname'] . '</td><td> ' . $row['dbproddescr'] . 
				'</td><td> ' . $row['dbprodprice'] . 
				'</td><td> ' . $row['dbcatname'] . 
				'</td><td> ' .
				
				'<form action = "updateproduct.php" method = "post">
						<input type = "hidden" name = "prodid" value = "'
						. $row['dbprodid'] . 
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