<?php
    session_start();

    if(isset($_POST['email'])) {

        //validation succeeded? yes
        $all_ok=true;

        //check the nickname
        $nick = $_POST['nick'];

        //check the length of the nick
        if((strlen($nick)<3) || (strlen($nick)>20)) {
            $all_ok=false;
            $_SESSION['e_nick']="Your nickname must be between 3 and 20 characters long!";
        }

        if(ctype_alnum($nick)==false) {
            $all_ok=false;
            $_SESSION['e_nick']="Your nickname must contain only letters, numbers, spaces, or the underscore character (no polish characters)";
        }
        //Check if e-mail is correct
        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
       
        if((filter_var($emailB, FILTER_SANITIZE_EMAIL)==false) || ($emailB!=$email)) {
            $all_ok=false;
            $_SESSION['e_email']="Please enter a valid email address.";
        }

        //Check if password is correct

		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if ((strlen($password1)<8) || (strlen($password1)>20))
		{
			$all_ok=false;
			$_SESSION['e_password']="Your password must be between 8 and 20 characters long!";
		}
		
		if ($password1!=$password2)
		{
			$all_ok=false;
			$_SESSION['e_password']="Two passwords do not match";
		}	

        $password_hash = password_hash($password1, PASSWORD_DEFAULT);
        
        //Terms accepted?
        if(!isset($_POST['terms'])) {
            $all_ok=false;
            $_SESSION['e_terms']="Please accept Terms and Conditions.";
        }
        //echo $password_hash; exit();

        //Bot or not?
        $secret = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";

        $verif = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

        $response = json_decode($verif);

        if($response->success==false) {
            $all_ok=false;
            $_SESSION['e_bot']="Please confirm you are not a robot.";
        }

        //Remember me
        $_SESSION['fr_nick'] = $nick;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_password1'] = $password1;
        $_SESSION['fr_password2'] = $password2;
        if(isset($_POST['terms'])) $_SESSION['fr_terms'] = true;


        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            if($connection->connect_errno!=0) {
                throw new Exception(mysql_connect_errno());
        } else {
            //Email already exist?
            $result = $connection->query("SELECT id FROM uzytkownicy WHERE email='$email'");
            if(!$result) throw new Exception($connection->error);
            $how_many_mails = $result->num_rows;
            if($how_many_mails>0) {
                $all_ok=false;
                $_SESSION['e_email']="This email already exist.";
            }

            //Nick already exist?
            $result = $connection->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
            if(!$result) throw new Exception($connection->error);
            $how_many_nicks = $result->num_rows;
            if($how_many_nicks>0) {
                $all_ok=false;
                $_SESSION['e_nick']="This nickname already exist.";
            }

            if($all_ok==true) {
                //hurray, all is OK, we add a new gamer into our database
                if($connection->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$password_hash', '$email', 100, 100, 100, 14)")) 
                {
                    $_SESSION['registrationsuccessful']=true;
                    header('Location: welcome.php');
                }
                else
                {
                    throw new Exception($connection->error);
                }

            }


            $connection->close();
        }
    }
        catch(Exception $e) {
            echo '<span style="color:red;">Error of the server, please try later.</span>';
            echo '<br/>Developer information: '.$e;
        }       
    }
?>

<!DOCTYPE html>
<html lang="ang">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Create a free account</title>
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <style>
        .error {
            color:red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>

</head>

<body>
    <form method="post">
        Nickname:<br> <input type="text" value="<?php
            if(isset($_SESSION['fr_nick']))
            {
                echo $_SESSION['fr_nick'];
                unset($_SESSION['fr_nick']);
            }
        ?>" name="nick"/><br>
        <?php 
            if(isset($_SESSION['e_nick'])) {
                echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
                unset($_SESSION['e_nick']);
            } 
        ?>

        E-mail:<br> <input type="text" value ="<?php
            if(isset($_SESSION['fr_email']))
            {
                echo $_SESSION['fr_email'];
                unset($_SESSION['fr_email']);
            }
            ?>" name="email"/><br>
        <?php 
            if(isset($_SESSION['e_email'])) {
                echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                unset($_SESSION['e_email']);
            } 
        ?>

        Your password: <br> <input type="password" value="<?php
             if(isset($_SESSION['fr_password1']))
             {
                 echo $_SESSION['fr_password1'];
                 unset($_SESSION['fr_password1']);
             }
            ?>" name="password1"/><br>
        <?php 
            if(isset($_SESSION['e_password'])) {
                echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                unset($_SESSION['e_password']);
            } 
        ?>

        Repeat your password: <br> <input type="password" value="<?php
            if(isset($_SESSION['fr_password2']))
            {
                echo $_SESSION['fr_password2'];
                unset($_SESSION['fr_password2']);
            }
            ?>" name="password2"/><br>

        <label>
            <input type="checkbox" name="terms" <?php
            if(isset($_SESSION['fr_terms']))
            {
                echo "checked";
                unset($_SESSION['fr_terms']);
            }
            ?>/>I agree to the terms of service
        </label>
        <?php 
            if(isset($_SESSION['e_terms'])) {
                echo '<div class="error">'.$_SESSION['e_terms'].'</div>';
                unset($_SESSION['e_terms']);
            } 
        ?>

        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

        <?php 
            if(isset($_SESSION['e_bot'])) {
                echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                unset($_SESSION['e_bot']);
            } 
        ?>
        <br>

        <input type="submit" value="Sign Up"/>
    </form>
</body>
</html>