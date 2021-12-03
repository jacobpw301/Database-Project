<?php
$pagetitle = 'Login Confirmation';
require_once 'header.php';
require_once 'connect.php';

if(isset($_SESSION['userid'])) {
	echo '<p>You are already logged in</p>';
	include_once 'footer.php';
	exit();
}

$showform = 1;
$errormsg = '';

if(isset ($_POST['submit'])) {
	
	$formfield['fftechusername'] = strtolower(htmlspecialchars(stripslashes(trim($_POST['techusername']))));
	$formfield['fftechpass'] = trim($_POST['techpass']);
	
	if(empty($formfield['fftechusername'])) { $errormsg .= '<p>USERNAME IS MISSING</p>';}
	if(empty($formfield['fftechpass'])) { $errormsg .= '<p>PASSWORD IS MISSING</p>';}
	
	if($errormsg != '') {
		echo "<p>THERE ARE ERRORS</p>" . $errormsg;
	}
	else
	{
		try
		{
			$sql = 'SELECT * FROM technicians WHERE dbtechusername = :bvtechusername';
			$s = $db->prepare($sql);
			$s->bindValue(':bvtechusername', $formfield['fftechusername']);
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
			$confirmeduname = $row['dbtechusername'];
			$confirmedpw = $row['dbtechpass'];
			
			echo '<p>Logging in for user: </p>' . $confirmeduname;
			
			if (password_verify($formfield['fftechpass'], $confirmedpw))
			{
				$_SESSION['userid'] = $row['dbtechid'];
				
				$_SESSION['username'] = $row['dbtechname'];
				$_SESSION['userpermit'] = $row['dbtechtitle'];
				echo '<p>Logged in Successfully</p>';
				echo '<br><br>';
				echo '<a href="inserttech.php">Continue</a>';
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
			<td><input type="text" name="techusername" id = "techusername" size="20" required></td>
		</tr><tr>	
			<td>Password</td>	
			<td><input type="password" name="techpass" id = "techpass" size="30" required></td>
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