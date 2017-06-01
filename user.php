<?php
    session_start();
?>
<html>
<head>
<link rel="stylesheet" href="styles/mainpage.css">

</head>
<body>
<p>
<?php
	$message = $_GET["message"];
	$action = $_POST["action"];
	$email = $_POST["email"];
	$pass = $_POST["pass"];
	$repass = $_POST["repass"];

	function pg_connection_string_from_database_url() {
	    extract(parse_url($_ENV["DATABASE_URL"]));
	    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
	}
	# Here we establish the connection. Yes, that's all.
	$db = pg_connect(pg_connection_string_from_database_url());

	if($action == "signup"){ //NEW USER SIGN UP
		if($pass!=$repass){ //Passwords Don't match
			header("Location: https://gradephd.herokuapp.com/?error=Password Does Not Match"); /* Redirect browser */
			exit();
		}
		$query= "SELECT * FROM users WHERE email IS '".$email.";";
		$result=pg_query($db,$query);
		if (0!=pg_num_rows($result)){ // CHECK IF USER EXISTS
			header("Location: https://gradephd.herokuapp.com/?error=User Exists With Email"); /* Redirect browser */
			exit();
		}
	 	$query= "INSERT INTO USERS (EMAIL, PASSWORD) VALUES ('".$email ."','".$pass."')"; //CREATE USER
	 	$result=pg_query($db,$query);
    	echo pg_last_error();
        $_SESSION["verifiedUser"] = $email;
	 	echo "<center>Welcome to GradePHD ".$email."</center>";
		

	} elseif($action == "signin") { //SIGN IN
		$query= "SELECT * FROM users WHERE email = '".$email."';";
		$result=pg_query($db,$query);
		if (0==pg_num_rows($result)){//NO USER FOUND
			header("Location: https://gradephd.herokuapp.com/?error=User Not Found"); /* Redirect browser */
			exit();
		}
		$query= "SELECT password FROM users WHERE email='".$email."';";
        $result=pg_query($db,$query);
		$correct_pass=pg_fetch_row($result)[0];
        if($pass!=$correct_pass) { //Verify password
            header("Location: https://gradephd.herokuapp.com/?error=Invalid Password ");
            exit();
        }
        $_SESSION["verifiedUser"] = $email;
		echo "<center>Welcome Back to GradePHD ".$email."</center>";
		


	} elseif($action == "reset") {
		echo "will make this shit later";

	} elseif(!$action && !$message) {
		header("Location: https://gradephd.herokuapp.com/?error=Please Login First");
        exit();
	} else{

	}
	echo $message;
	echo "<h3><a href='/class.php'>Add a Class</a></h3>";
	echo "<h2>Enrolled Classes:</h2><ul>";
	
		
	$email = $_SESSION['verifiedUser'];
	$query= "SELECT * FROM users WHERE email = '$email';";
	$result=pg_query($db,$query);
	$A=pg_fetch_row($result,0);
	for($i = 2;$i<10;$i++) {
	    $class=$A[$i];
	    if ($class) echo "<li><a href='/plot.php?class=$class>$class</a></li>";
	}
	echo "</ul>";
	
	
	
?>
</p>

</body>
</html>
