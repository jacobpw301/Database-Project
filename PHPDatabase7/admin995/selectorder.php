<?php
$pagetitle = 'Select Order';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	$sqlselectc = "SELECT * from customers";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
	
	$sqlselectt = "SELECT * from technicians";
	$resultt = $db->prepare($sqlselectt);
	$resultt->execute();
	
	if( isset($_POST['thesubmit']) )
		{
			$stringclause = "";
			$formfield['ffordercust'] = trim($_POST['ordercust']);
			$formfield['ffordertech'] = trim($_POST['ordertech']);
			
			if ($_POST['orderdate'] != '') {
				$formfield['fforderdate'] = date_create(trim($_POST['orderdate']));
				$formfield['fforderdate']  = date_format($formfield['fforderdate'], 'Y-m-d');
				$stringclause = " AND orderinfo.dborderdate like CONCAT('%', :bvorderdate, '%')";
			} 
			
			echo $formfield['fforderdate'];
			$sqlselect = "select orderinfo.*, customers.dbcustfirstname, customers.dbcustlastname, technicians.dbtechname
							from orderinfo, customers, technicians
							where orderinfo.dbordercust = customers.dbcustid
							AND orderinfo.dbordertech = technicians.dbtechid
							AND orderinfo.dbordercust like CONCAT('%', :bvordercust, '%')
							AND orderinfo.dbordertech like CONCAT('%', :bvordertech, '%')"
							. $stringclause;
							
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvordercust', $formfield['ffordercust']);
			$result->bindValue(':bvordertech', $formfield['ffordertech']);
			if ($formfield['fforderdate'] != '') {
				$result->bindValue(':bvorderdate', $formfield['fforderdate']);
			}
			
			$result->execute();
			
		}
	else
		{
			$sqlselect = "select orderinfo.*, customers.dbcustfirstname, customers.dbcustlastname, technicians.dbtechname
							from orderinfo, customers, technicians
							where orderinfo.dbordercust = customers.dbcustid
							AND orderinfo.dbordertech = technicians.dbtechid";
			$result = $db->query($sqlselect);
			
		}

if ($visible == 1)
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Order Information</legend>
				<table border>
					<tr>
						<th><label for="ordercust">Customer:</label></th>
						<td><select name="ordercust" id="ordercust">
						<option value = "">Please Select a Customer</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							echo '<option value="'. $rowc['dbcustid'] . '">' . $rowc['dbcustfirstname'] . ' ' . $rowc['dbcustlastname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th><label for="tech">Technician:</label></th>
						<td><select name="ordertech" id="ordertech">
						<option value = "">Please Select a Technician</option>
						<?php while ($rowt = $resultt->fetch() )
							{
							echo '<option value="'. $rowt['dbtechid'] . '">' . $rowt['dbtechname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Date of Order</th>
						<td><input type="date" name="orderdate" id="orderdate"></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Order ID</th>
		<th>Customer</th>
		<th>Technician</th>
		<th>Date and Time</th>
		<th>Order Open</th>
		<th>Total</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				if($row['dborderopen'] == 1) {
					$openorder = "YES";
				} else {
					$openorder = "NO";
				}
				
				$sqlselectoi = "SELECT orderitems.*
					FROM orderitems
					WHERE orderitems.dborderid = :bvorderid";
					$resultoi = $db->prepare($sqlselectoi);
					$resultoi->bindValue(':bvorderid', $row['dborderid']);
					$resultoi->execute();
				
				$ordertotal = 0;
				while ( $rowoi = $resultoi-> fetch() ) {
					$ordertotal = $ordertotal + $rowoi['dborderitemprice'];
				}
				
				
				echo '<tr><td>' . $row['dborderid'] . '</td><td> ' . $row['dbcustfirstname'] . ' ' . $row['dbcustlastname'] .  '</td><td> ' 
				. $row['dbtechname'] . '</td><td> ' . $row['dborderdate'] . '</td><td> '  . $openorder . 
				'</td><td> ' . $ordertotal . '</td><td> ' .
				'<form action = "updateorder.php" method = "post">
						<input type = "hidden" name = "orderid" value = "'
						. $row['dborderid'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>' .
				'</td><td> ' .
				'<form action="insertorderitem.php" method = "post">
				<input type = "hidden" name = "orderid" value = "'
						. $row['dborderid'] .
				'"><input type="submit" name="thesubmit" value="Edit Order Items">
				</form>'
				
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>