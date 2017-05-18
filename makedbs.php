
<?php

	function pg_connection_string_from_database_url() {
	    extract(parse_url($_ENV["DATABASE_URL"]));
	    return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
	}
	# Here we establish the connection. Yes, that's all.
	$db = pg_connect(pg_connection_string_from_database_url());
	if(!$db)echo "ERROR";
	if($db)echo "WORKS";
	pg_close($db);
	//exit();

	$sql =<<<EOF
      CREATE TABLE USERS
      (
      EMAIL            TEXT    NOT NULL,
      PASSWORD         TEXT    NOT NULL,
      CLASS1           TEXT    IS NULL,
      CLASS2           TEXT    IS NULL,
      CLASS3           TEXT    IS NULL,
      CLASS4           TEXT    IS NULL,
      CLASS5           TEXT    IS NULL
      );
EOF;


	$result=pg_query($db,$sql);
	echo pg_last_error();
	echo $result;

?>
