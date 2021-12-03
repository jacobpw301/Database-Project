<?php
	$pagetitle = 'Insert Order';
	require_once "header.php";
	require_once "connect.php";
	$showform = 1;
if ($visible == 1)
{	
	$sqlopen = "select orderinfo.*, customers.dbcustfirstname, customers.dbcustlastname, technicians.dbtechname
			from orderinfo, customers, technicians
			where orderinfo.dbordercust = customers.dbcustid
			AND orderinfo.dbordertech = technicians.dbtechid
			AND orderinfo.dbordercust = :bvordercust
			AND dborderopen = :bvorderopen";
	$resultopen = $db->prepare($sqlopen);
	$resultopen->bindValue(':bvordercust', $_SESSION['customerid']);
	$resultopen->bindValue(':bvorderopen', 1);
	$resultopen->execute();
	
	$countopen = $resultopen->rowCount();
	
	if ($countopen > 0)
	{
	?>
	<h3>Incomplete Orders Exist, Please Complete Before Starting a New Order</h3>
	<table border>
	<tr>
		<th>Order ID</th>
		<th>Technician</th>
		<th>Date and Time</th>
		<th>Order Open</th>
		<th>Total</th>
	</tr>
	<?php 
		while ( $row = $resultopen-> fetch() )
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
				
				
				echo '<tr><td>' . $row['dborderid'] . '</td><td> ' 
				.  $row['dbtechname'] . '</td><td> ' . $row['dborderdate'] . '</td><td> '  . $openorder . 
				'</td><td> ' . $ordertotal . '</td><td> ' .
				'<form action="insertorderitem.php" method = "post">
				<input type = "hidden" name = "orderid" value = "'
						. $row['dborderid'] .
				'"><input type="submit" name="thesubmit" value="Finalize Order">
				</form>'
				
				. '</td></tr>';
			}
		
		?>
	</table>
	
	<?php
	} else {
	
	$sqlmax = "SELECT MAX(dborderid) AS maxid from orderinfo";
	$resultmax = $db->prepare($sqlmax);
	$resultmax->execute();
	$rowmax = $resultmax->fetch();
	$maxid = $rowmax["maxid"];	
	$maxid = $maxid + 1;

	$sqlinsert = 'INSERT INTO orderinfo (dborderid, dbordercust,
		dbordertech, dborderopen, dborderdate) VALUES (:bvorderid, :bvordercust, :bvordertech, 1, now())';
	
	//Prepares the SQL Statement for execution
	$stmtinsert = $db->prepare($sqlinsert);
	//Binds our associative array variables to the bound
	//variables in the sql statement
	$stmtinsert->bindvalue(':bvorderid', $maxid);
	$stmtinsert->bindvalue(':bvordercust', $_SESSION['customerid']);
	$stmtinsert->bindvalue(':bvordertech', 35);
		//Runs the insert statement and query
	$stmtinsert->execute();
	
	echo "Order Number: " . $maxid;
	echo '<br><br><form action="insertorderitem.php" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $maxid .'">';
	echo '<input type="submit" name="thesubmit" value="Enter Order Items">';
	echo "</form>";
	$showform = 0;
	}
}
	include_once 'footer.php';
?>