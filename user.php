<html>
<head>
<link rel="stylesheet" href="styles/mainpage.css">

</head>
<body>

<?php
	$action = $_POST["action"];
	$email = $_POST["email"];
	$pass = $_POST["password"];
	$repass = $_POST["repass"];

	function pg_connection_string_from_database_url() {
	    extract(parse_url($_ENV["DATABASE_URL"]));
	    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
	}
	# Here we establish the connection. Yes, that's all.
	$db = pg_connect(pg_connection_string_from_database_url());

	if($action == "signup"){
		if(!$pass==$repass){
			header("https://gradephd.herokuapp.com/?error=Password Does Not Match"); /* Redirect browser */
			exit();
		}
		$query= "SELECT email,password,class1,class2,class3,class4,class5 FROM users WHERE email IS '".$email.";";
		$result=pg_query($db,$query);
		if (0!=pg_num_rows($result)){
			header("https://gradephd.herokuapp.com/?error=User Exists With Email"); /* Redirect browser */
			exit();
		}
	 	$query= "INSERT INTO USERS (EMAIL, PASSWORD) VALUES (".$email .",".$pass.")";
	 	$result=pg_query($db,$query);
    	echo pg_last_error();
	 	echo "Welcome to GradePHD ".$email;
		

	} elseif($action == "signin"){
		$query= "SELECT email,password,class1,class2,class3,class4,class5 FROM users WHERE email IS '".$email.";";
		$result=pg_query($db,$query);
		if (0==pg_num_rows($result)){
			header("https://gradephd.herokuapp.com/?error=User Not Found"); /* Redirect browser */
			exit();
		}


	} elseif($action == "reset") {
		echo "will make this shit later";
	} else {
		echo "FUCKING HELL!";
	}
  
  


?>


</body>
</html>
