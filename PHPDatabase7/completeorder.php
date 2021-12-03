<?php
	require_once "header.php";
	require_once "connect.php";
if ($visible == 1)
{	
	$formfield['fforderid'] = $_POST['orderid'];
	
	$sqlselecto = "SELECT orderitems.*, products.dbprodname
			FROM orderitems, products
			WHERE products.dbprodid = orderitems.dbprodid
			AND orderitems.dborderid = :bvorderid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvorderid', $formfield['fforderid']);
	$resulto->execute();
	
	$ordertotal = 0;
	
	$sqlinsert = 'update orderinfo set dborderopen = :bvorderopen
								  where dborderid = :bvorderid';
	$stmtinsert = $db->prepare($sqlinsert);
	$stmtinsert->bindvalue(':bvorderopen', 0);
	$stmtinsert->bindvalue(':bvorderid', $formfield['fforderid']);
	$stmtinsert->execute();
?>
<h2>Your order has been submitted.  Thank you!</h2>
<table border>
		<tr>
			<th>Item</th>
			<th>Price</th>
			<th>Notes</th>
		</tr>
		<?php
		while ($rowo = $resulto->fetch() )
			{
			$ordertotal = $ordertotal + $rowo['dborderitemprice'];
			
			echo '<tr><td>' . $rowo['dbprodname'] . '</td><td>' . $rowo['dborderitemprice'] . '</td>';
			echo '<td>' . $rowo['dborderitemnotes'] . '</td></tr>';
			}
		echo '<tr><th>Total</th>';
		echo '<th>' . $ordertotal . '</th><td></td></tr>';
		?>
</table>
	
<?php			
}
	include_once 'footer.php';
?>