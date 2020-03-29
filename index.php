<?php
    session_start();

    if(isset(($_SESSION['loggedin'])) && ($_SESSION['loggedin']==true)) {
        header('Location: game.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ang">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Browser_Game_Example</title>
</head>

<body>
    
    Hello!<br><br>

    <a href="registration.php">Create a free account</a>
    <br><br>

    <form action="login.php" method="POST">

        Username: <br><input type="text" name="login"/><br><br>
        Password: <br><input type="password" name="password"/><br><br>
        <input type="submit" value="Log In"/>

    </form>
<?php
    if(isset($_SESSION['error']))
    echo $_SESSION['error'];
?>

</body>
</html>