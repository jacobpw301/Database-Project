<?php
if(isset($_SESSION['userid'])) {

	if ($_SESSION['userpermit'] == 1 || $_SESSION['userpermit'] == 2)
	{
		echo "<a href='insertcustomer.php'>Insert Customer</a>  |  ";
		echo "<a href='selectcustomer.php'>Select Customer</a>  |  ";
		echo "<a href='insertappt.php'>Insert Appointment</a>  |  ";
		echo "<a href='selectappt.php'>Select Appointment</a>  |  ";
		echo "<a href='inserttech.php'>Insert Technician</a>  |  ";
		echo "<a href='selecttech.php'>Select Technician</a>  |  ";
		echo "<a href='inserttechtitle.php'>Insert Tech Title</a>  |  ";
		echo "<a href='selecttechtitle.php'>Select Tech Title</a>  |  ";
		echo "<a href='insertcategory.php'>Insert Category</a>  |  ";
		echo "<a href='selectcategory.php'>Select Category</a>  |  ";
		echo "<a href='insertproduct.php'>Insert Product</a>  |  ";
		echo "<a href='selectproduct.php'>Select Product</a>  |  ";
		echo "<a href='insertorder.php'>Insert Order</a>  |  ";
		echo "<a href='selectorder.php'>Select Order</a>  |  ";
	} else if ($_SESSION['userpermit'] == 3 || $_SESSION['userpermit'] == 4) {
		echo "<a href='insertcustomer.php'>Insert Customer</a>  |  ";
		echo "<a href='selectcustomer.php'>Select Customer</a>  |  ";
		echo "<a href='insertappt.php'>Insert Appointment</a>  |  ";
		echo "<a href='selectappt.php'>Select Appointment</a>  |  ";
		echo "<a href='insertorder.php'>Insert Order</a>  |  ";
		echo "<a href='selectorder.php'>Select Order</a>  |  ";
	} else {
		echo "<a href='selectcustomer.php'>Select Customer</a>  |  ";
		echo "<a href='selectappt.php'>Select Appointment</a>  |  ";	
	}
	
	
	
	echo "<a href='logout.php'>Log Out</a>";
	echo ' [Welcome, ' . $_SESSION['username'] . ']';
	$visible = 1;
}
else
{
	echo "<a href='login.php'>Log In</a>";
	$visible = 0;
}	
?>