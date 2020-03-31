<?php

session_start();

if(!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}

?>

<!DOCTYPE HTML>
<html lang="ang">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Browser_Game_Example</title>
</head>

<body>
 
<?php

echo "<p>Hello ".$_SESSION['user'].'! [ <a href="logout.php">Log out!</a> ]</p>';
echo "<p><b>Option1</b>: ".$_SESSION['option1'];
echo " | <b>Option2</b>: ".$_SESSION['option2'];
echo " | <b>Option3</b>: ".$_SESSION['option3']."</p>";

echo "<p><b>E-mail</b>: ".$_SESSION['email'];
echo "<br /><b>premiumdays</b>: ".$_SESSION['premiumdays']."</p>";
	
$datetime = new DateTime('2020-03-31 21:02:33');
echo "Date and Time of Server: ".$datetime->format('Y-m-d H:i:s')."<br>";

$end = DateTime::createFromFormat('Y-m-d H:i:s', $_SESSION['dnipremium']);

$difference = $datetime->diff($end);

if($datetime<$end)
echo "You have : days of Premium".$difference->format('%y years, %m months, %d days, %h hours, %i min, %s sec');
else 
echo "Premium inactive since ".$difference->format('%y years, %m months, %d days, %h hours, %i min, %s sec');

?>
</body>
</html>
