
<?php

	function pg_connection_string_from_database_url() {
	    extract(parse_url($_ENV["DATABASE_URL"]));
	    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
	}
	# Here we establish the connection. Yes, that's all.
	$db = pg_connect(pg_connection_string_from_database_url());
	//if(!$db)echo "ERROR";
	//if($db)echo "WORKS";
	//pg_close($db);
	//exit();

	$sqltest =<<<EOF
      CREATE TABLE USERS
      (
      EMAIL            TEXT    NOT NULL PRIMARY KEY,
      PASSWORD         TEXT    NOT NULL,
      CLASS1           TEXT,
      CLASS2           TEXT,
      CLASS3           TEXT,
      CLASS4           TEXT,
      CLASS5           TEXT
      );
EOF;

	$sql = "ALTER TABLE USERS ADD PRIMARY KEY (EMAIL);"

	$result=pg_query($db,$sql);
	echo pg_last_error();
	//echo $result;

?>
