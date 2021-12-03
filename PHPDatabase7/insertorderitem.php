<?php
	require_once "header.php";
	require_once "connect.php";

	$formfield['fforderid'] = $_POST['orderid'];
	$formfield['ffprodid'] = $_POST['prodid'];
	$formfield['fforderitemprice'] = $_POST['orderitemprice'];
	
	$sqlselectc = "SELECT * from categories";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
	
	if (isset($_POST['OIEnter']))
	{
		$sqlinsert = 'INSERT INTO orderitems (dborderid, dbprodid,
				dborderitemprice) VALUES (:bvorderid, :bvprodid, :bvorderitemprice)';
			
			//Prepares the SQL Statement for execution
			$stmtinsert = $db->prepare($sqlinsert);
			//Binds our associative array variables to the bound
			//variables in the sql statement
			$stmtinsert->bindvalue(':bvorderid', $formfield['fforderid']);
			$stmtinsert->bindvalue(':bvprodid', $formfield['ffprodid']);
			$stmtinsert->bindvalue(':bvorderitemprice', $formfield['fforderitemprice']);

			//Runs the insert statement and query
			$stmtinsert->execute();
	}

	if (isset($_POST['DeleteItem']))
	{
		$sqldelete = 'DELETE FROM orderitems 
					WHERE dborderitemid = :bvorderitemid';
		$stmtdelete = $db->prepare($sqldelete);
		$stmtdelete->bindvalue(':bvorderitemid', $_POST['orderitemid']);
		$stmtdelete->execute();
	}
	
	if (isset($_POST['UpdateItem']))
	{
		$sqlupdateoi = 'Update orderitems 
					set dborderitemprice = :bvitemprice, dborderitemnotes = :bvitemnotes
					WHERE dborderitemid = :bvorderitemid';
		$stmtupdateoi = $db->prepare($sqlupdateoi);
		$stmtupdateoi->bindvalue(':bvorderitemid', $_POST['orderitemid']);
		$stmtupdateoi->bindvalue(':bvitemprice', $_POST['newprice']);
		$stmtupdateoi->bindvalue(':bvitemnotes', $_POST['newnote']);
		$stmtupdateoi->execute();
	}
	
	$sqlselecto = "SELECT orderitems.*, products.dbprodname
			FROM orderitems, products
			WHERE products.dbprodid = orderitems.dbprodid
			AND orderitems.dborderid = :bvorderid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvorderid', $formfield['fforderid']);
	$resulto->execute();
	
	if ($visible == 1)
	{
?>
<html>
<body>

	<fieldset><legend>Enter Items for Order Number <?php echo $formfield['fforderid'] ;?> </legend>
		<form action = "<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		
		<table border>
			<?php
				echo '<tr>';
				while ($rowc = $resultc->fetch() )
					{
					echo '<td><table border>';
					$sqlselectp = "SELECT * from products where dbprodcat = :bvprodcat";
					$resultp = $db->prepare($sqlselectp);
					$resultp->bindValue(':bvprodcat', $rowc['dbcatid']);
					$resultp->execute();
					while ($rowp = $resultp->fetch() )
						{
						echo '<tr><td>';
						echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
						echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
						echo '<input type = "hidden" name = "prodid" value = "'. $rowp['dbprodid'] .'">';
						echo '<input type = "hidden" name = "orderitemprice" value = "'. $rowp['dbprodprice'] .'">';
						echo '<input type="submit" name="OIEnter" value="'. $rowp['dbprodname'] . ' - $' 
							. $rowp['dbprodprice'] .'">';
						echo '</form>';
						
						echo '</td></tr>';
						}
					echo '</table></td>';	
					}
				echo '</tr>';
			?>
		</table>
	</fieldset>
	<br><br>
	<table>
		<tr>
		<td>
		<table border>
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Notes</th>
				<th></th>
				<th></th>

			</tr>
			<?php
				$ordertotal = 0;
				while ($rowo = $resulto->fetch() )
				{
				$ordertotal = $ordertotal + $rowo['dborderitemprice'];
					
				echo '<tr><td>' . $rowo['dbprodname'] . '</td><td>' . $rowo['dborderitemprice'] . '</td>';
				echo '<td>' . $rowo['dborderitemnotes'] . '</td><td>';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
				echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['dborderitemid'] .'">';
				echo '<input type="submit" name="NoteEntry" value="Update">';
				echo '</form></td><td>';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
				echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['dborderitemid'] .'">';
				echo '<input type="submit" name="DeleteItem" value="Delete">';
				echo '</form></td></tr>';
				}
			?>
		<tr>
			<th>Total:</th>
			<th><?php echo $ordertotal; ?></th>
		</tr>
		</table>
		<?php
			if (isset($_POST['NoteEntry']))
			{
			$sqlselectoi = "SELECT orderitems.*, products.dbprodname 
				from orderitems, products
				WHERE products.dbprodid = orderitems.dbprodid
				AND orderitems.dborderid = :bvorderid
				AND orderitems.dborderitemid = :bvorderitemid";
			$resultoi = $db->prepare($sqlselectoi);
			$resultoi->bindValue(':bvorderid', $formfield['fforderid']);
			$resultoi->bindvalue(':bvorderitemid', $_POST['orderitemid']);
			$resultoi->execute();
			$rowoi = $resultoi->fetch();
			
			echo '</td><td>';
			echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
			echo '<table>';
			echo '<tr><td>Price: <input type = "text" name = "newprice" value = "'. $rowoi['dborderitemprice'] . '"></td></tr>';
			echo '<tr><td>Note: <input type = "text" name = "newnote" value = "'. $rowoi['dborderitemnotes'] . '"></td></tr>';
			echo '<tr><td>';
			echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
			echo '<input type = "hidden" name = "orderitemid" value = "'. $rowoi['dborderitemid'] .'">';
			echo '<input type="submit" name="UpdateItem" value="Update Item"></td></tr></table>';
			}
			?>
		
		</td></tr>
	</table>
	<br><br>
<?php
	echo '<form action = "completeorder.php" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type="submit" name="CompleteOrder" value="Complete Order">';
	echo '</form>';

}//visible
include_once 'footer.php';
?>