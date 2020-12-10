<?php
session_start();
include("database.php");
extract($_POST);

if(isset($submit))
{
	$rs=mysqli_query($conn,"select * from user where user='$user' and password='$pass'");
	if(mysqli_num_rows($rs)<1)
	{
		$found="N";
	}
	else
	{
		$_SESSION["user"] = $user;
	}
}
if (isset($_SESSION["user"]))
{
echo "<h1 align=center>Hye you are sucessfully login!!!</h1>";
echo $_SESSION["user"];
 header('Location: http://localhost/kripto/dashboard.php');
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link rel="stylesheet" href="style.css"> -->
</head>
<body>
<center>
<div class="floating-box">

<form name="form1" method="post">


   <label for="uname">User Name</label>
   <input type="text" id="loginid2" name="user"><br><br>

   <label for="pwd">Password</label>
   <input type="password" id="pass2" name="pass"><br><br>
   <input name="submit" type="submit" id="submit" value="Login"><br>

<p>New User <a href="signup.php">Register Here</a></p>
<?php
		  if(isset($found))
		  {
		  	echo '<p class="w3-center w3-text-red">Invalid user id or password<br><a href="index.php">Please try again</p>';
		  }
		  ?>
 </center>
</form>

</div>
<center>
</body>
</html>