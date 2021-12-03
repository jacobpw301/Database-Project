<?php
$pagetitle = 'Select Technician Title';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

$sqlselecttt = "SELECT * from techtitle";
$resulttt = $db->prepare($sqlselecttt);
$resulttt->execute();

if ($visible == 1)
{
	?>
	<table border>
	<tr>
		<th>Title</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $resulttt-> fetch() )
			{
				echo '<tr><td>' . $row['dbtechtitlename'] . '</td><td>' .
								
				'<form action = "updatetechtitle.php" method = "post">
						<input type = "hidden" name = "techtitleid" value = "'
						. $row['dbtechtitleid'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>'  . '</td></tr>' ;
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>