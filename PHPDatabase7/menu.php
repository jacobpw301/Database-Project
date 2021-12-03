<?php
if(isset($_SESSION['customerid'])) {

		echo "<a href='updatecustomer.php'>Update Customer Info</a>  |  ";
		echo "<a href='insertorder.php'>Insert Order</a>  |  ";
		echo "<a href='selectorder.php'>Select Order</a>  |  ";
		
	echo "<a href='logout.php'>Log Out</a>";
	echo ' [Welcome, ' . $_SESSION['customername'] . ']';
	$visible = 1;
}
else
{
	echo "<a href='login.php'>Log In</a>";
	$visible = 0;
}	
?>