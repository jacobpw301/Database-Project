<?php
$pagetitle = 'Select Technician';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

$sqlselecttt = "SELECT * from techtitle";
$resulttt = $db->prepare($sqlselecttt);
$resulttt->execute();

	
	if( isset($_POST['thesubmit']) )
		{
			$addedclause = '';
			$formfield['fftechname'] = trim($_POST['techname']);
			$formfield['fftechtitle'] = trim($_POST['techtitle']);
			$formfield['fftechschedule'] = trim(strtolower($_POST['techschedule']));
			$formfield['fftechpay'] = $_POST['techpay'];
			
			if ($formfield['fftechpay'] != '') {
				$addedclause .= " AND dbtechpay = :bvtechpay";
			}
			
			$sqlselect = "SELECT technicians.*, techtitle.dbtechtitlename
							from technicians, techtitle 
							where technicians.dbtechtitle = techtitle.dbtechtitleid
							AND dbtechname like CONCAT('%', :bvtechname, '%')
							AND dbtechtitle like CONCAT('%', :bvtechtitle, '%')
							AND dbtechschedule like CONCAT('%', :bvtechschedule, '%')"	. $addedclause;
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvtechname', $formfield['fftechname']);
			$result->bindValue(':bvtechtitle', $formfield['fftechtitle']);
			$result->bindValue(':bvtechschedule', $formfield['fftechschedule']);
			if ($formfield['fftechpay'] != '') {
				$result->bindValue(':bvtechpay', $formfield['fftechpay']);
			}
			$result->execute();
		}
	else
		{
			$sqlselect = "SELECT technicians.*, techtitle.dbtechtitlename
							from technicians, techtitle
							where technicians.dbtechtitle = techtitle.dbtechtitleid";
			$result = $db-> query($sqlselect);
		}

if ($visible == 1)
{
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Technician Information</legend>
				<table border>
					<tr>
						<th>Technician Name</th>
						<td><input type="text" name="techname" id="techname"
						value = <?php echo $formfield['fftechname']; ?>	></td>
					</tr>
					<tr>
						<th><label>Title:</label></th>
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
						<th>Schedule</th>
						<td><input type="text" name="techschedule" id="techschedule"
						value = <?php echo $formfield['fftechschedule']; ?>></td>
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
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Technician Name</th>
		<th>Title</th>
		<th>Schedule</th>
		<th>Pay</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbtechname'] . '</td><td> ' . $row['dbtechtitlename'] . 
				'</td><td> ' . $row['dbtechschedule'] . 
				'</td><td>$ ' . $row['dbtechpay'] . '</td><td>' .
								
				'<form action = "updatetechnicians.php" method = "post">
						<input type = "hidden" name = "techid" value = "'
						. $row['dbtechid'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>'  . '</td></tr>' ;
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>