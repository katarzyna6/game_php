<?php
    
	session_start();
	
	if (!isset($_SESSION['registrationsuccessful']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['registrationsuccessful']);
	}

//Delete variables used in the form of registration
if(isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
if(isset($_SESSION['fr_password1'])) unset($_SESSION['fr_password1']);
if(isset($_SESSION['fr_password2'])) unset($_SESSION['fr_password2']);
if(isset($_SESSION['fr_terms'])) unset($_SESSION['fr_terms']);

//Delete errors e_
if(isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
if(isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
if(isset($_SESSION['e_terms'])) unset($_SESSION['e_terms']);
if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
?>

<!DOCTYPE html>
<html lang="ang">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Browser_Game_Example</title>
</head>

<body>
    
    Thanks for registration! You can log in now!<br><br>

    <a href="index.php">Log In!</a>
	<br /><br />

</body>
</html>