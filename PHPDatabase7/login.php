<?php
$pagetitle = 'Login Confirmation';
require_once 'header.php';
require_once 'connect.php';

if(isset($_SESSION['customerid'])) {
	echo '<p>You are already logged in</p>';
	include_once 'footer.php';
	exit();
}

$showform = 1;
$errormsg = '';

if(isset ($_POST['submit'])) {
	
	$formfield['ffcustusername'] = strtolower(htmlspecialchars(stripslashes(trim($_POST['custusername']))));
	$formfield['ffcustpass'] = trim($_POST['custpass']);
	
	if(empty($formfield['ffcustusername'])) { $errormsg .= '<p>USERNAME IS MISSING</p>';}
	if(empty($formfield['ffcustpass'])) { $errormsg .= '<p>PASSWORD IS MISSING</p>';}
	
	if($errormsg != '') {
		echo "<p>THERE ARE ERRORS</p>" . $errormsg;
	}
	else
	{
		try
		{
			$sql = 'SELECT * FROM customers WHERE dbcustusername = :bvcustusername';
			$s = $db->prepare($sql);
			$s->bindValue(':bvcustusername', $formfield['ffcustusername']);
			$s->execute();
			$count = $s->rowCount();
		}
		catch (PDOException $e)
		{
			echo "ERROR!!!" . $e->getMessage();
			exit();
		}
		
		if($count < 1)
		{
			echo '<p>The user name or password is incorrect</p>';
		}
		else 
		{
			$row = $s->fetch();
			$confirmeduname = $row['dbcustusername'];
			$confirmedpw = $row['dbcustpass'];
			
			echo '<p>Logging in for user: </p>' . $confirmeduname;
			
			if (password_verify($formfield['ffcustpass'], $confirmedpw))
			{
				$_SESSION['customerid'] = $row['dbcustid'];
				
				$_SESSION['customername'] = $row['dbcustfirstname'] . ' ' . $row['dbcustlastname'];
				
				echo '<p>Logged in Successfully</p>';
				echo '<br><br>';
				echo '<a href="insertorder.php">Continue</a>';
				echo '<br><br>';
				
				$showform = 0;
			} 
			else
			{
				echo '<p>The user name or password is incorrect</p>';
			}
			
			
		}
		
		
	}
	
	
}
if($showform == 1)
{
?>

<p>You are not logged in.  Please log in</p>

<form name = "loginForm" id = "loginForm" method = "post" action = "login.php">
	<table>
		<tr>
			<td>Username</td>
			<td><input type="text" name="custusername" id = "custusername" size="20" required></td>
		</tr><tr>	
			<td>Password</td>	
			<td><input type="password" name="custpass" id = "custpass" size="30" required></td>
		</tr><tr>	
			<td>Submit:</td>
			<td><input type ="submit" name= "submit" value = "submit"></td>
		</tr>
	</table>
</form>
<?php
}
include_once 'footer.php';
?>