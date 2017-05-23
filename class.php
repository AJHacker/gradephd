<html>
<head>
<link rel="stylesheet" href="styles/mainpage.css">

</head>
<body>
<h2>
	Search for a new class:
</h2>
<p>

<?php

	$verifiedUser = $_POST["verifiedUser"];
	
	function pg_connection_string_from_database_url() {
	    extract(parse_url($_ENV["DATABASE_URL"]));
	    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
	}
	# Here we establish the connection. Yes, that's all.
	$db = pg_connect(pg_connection_string_from_database_url());

	$query= "SELECT * FROM users WHERE email = '".$email."';";
	$result=pg_query($db,$query);
		


?>

</p>

</body>
</html>
