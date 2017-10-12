<?php
    session_start();
?>
<html>
<head>
<link rel="stylesheet" href="styles/global.css">

</head>
<body>
<?php
	$message = $_GET["message"];
    debug_to_console($message);
    $action = $_POST["action"];
    debug_to_console($action);
	$email = $_POST["email"];
    debug_to_console($email);
	$pass = $_POST["pass"];
    debug_to_console($pass);
	$repass = $_POST["repass"];
    debug_to_console($repass);

	function pg_connection_string_from_database_url() {
	    extract(parse_url($_ENV["DATABASE_URL"]));
	    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
	}
	# Here we establish the connection. Yes, that's all.
	$db = pg_connect(pg_connection_string_from_database_url());
?>
<div class="topbar"><h1>
    <?php
	if($action == "signup"){ //NEW USER SIGN UP
		if($pass!=$repass){ //Passwords Don't match
			header("Location: https://gradephd.herokuapp.com/login.php?error=Password Does Not Match"); /* Redirect browser */
			exit();
		}
		$query= "SELECT * FROM users WHERE email = '".$email."';";
        debug_to_console($query);
		$result=pg_query($db,$query);
        debug_to_console(pg_last_error());
		if (0!=pg_num_rows($result)){ // CHECK IF USER EXISTS
            debug_to_console("user exists");
			header("Location: https://gradephd.herokuapp.com/login.php?error=User Exists With Email"); /* Redirect browser */
			exit();
		}
	 	$query= "INSERT INTO USERS (EMAIL, PASSWORD) VALUES ('".$email ."','".$pass."')"; //CREATE USER
	 	$result=pg_query($db,$query);
    	echo pg_last_error();
        $_SESSION["verifiedUser"] = $email;
	 	// echo "<center>Welcome to GradePHD ".$email."</center>";
	 	// header("Location: https://gradephd.herokuapp.com/user.php");

	} elseif($action == "signin") { //SIGN IN
		$query= "SELECT * FROM users WHERE email = '".$email."';";
		$result=pg_query($db,$query);
		if (0==pg_num_rows($result)){//NO USER FOUND
			header("Location: https://gradephd.herokuapp.com/?error=User Not Found"); /* Redirect browser */
			exit();
		}
		$query= "SELECT password FROM users WHERE email ='".$email."';";
        $result=pg_query($db,$query);
		$correct_pass=pg_fetch_row($result)[0];
        if($pass!=$correct_pass) { //Verify password
            header("Location: https://gradephd.herokuapp.com/login.php?error=Invalid Password ");
            exit();
        }
        $_SESSION["verifiedUser"] = $email;
		// echo "<center>Welcome Back to GradePHD ".$email."</center>";
		// header("Location: https://gradephd.herokuapp.com/user.php");

	} elseif($action == "reset") {
		echo "will make this shit later";
	}
	
    $email = $_SESSION['verifiedUser'];
	echo "<center>Welcome to GradePHD, ".$email."</center>";
    ?></h1>
    <a class='button topRight' style='position: absolute; top: 0; right:0;' href='/index.php?logout=1'>Sign Out</a>
</div>
<div class="container">
<?php
	echo $message;
	echo "<h2>Enrolled Classes:</h2>";
	session_unset();
    $_SESSION['verifiedUser']=$email;

	$query= "SELECT * FROM users WHERE email = '$email';";
	$result=pg_query($db,$query);
	$A=pg_fetch_row($result,0);
    echo "
    <table>
        <thead>
        <tr>
            <th>Course Number</th>
            <th>Semester</th>
            <th>Professor</th>
        </tr>
        </thead>";

	for($i = 2;$i<10;$i++) {
	    echo "<tr>";
	    $class=$A[$i];
	    if ($class) {
	    	$class=str_replace("0xDEADBEEF","-",$class);
	    	$c=explode("_",$class);
	    	echo "<td>".$c[2]."</td>";
	    	echo "<td>".$c[1]."</td>";
	    	echo "<td>".$c[0]."</td>";
	    	echo "<td><a class='tableButton' style='padding: 2px;' href='/plot.php?class=$class'>View</a></td>";
	    	echo "<td><a class='tableButton' style='padding: 2px;' href='/remove.php?class=$class'>Remove</a></td>";
	    }
	    echo "</tr>";
	}
	echo "</table>";
    echo "<h3><a class='button' href='/class.php' style='position: relative; top: 100px;'>Add a Class</a></h3>";


function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log('$output');</script>";
}
?>
</div>

</body>
</html>
