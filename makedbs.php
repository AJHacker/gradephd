
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
	
	pg_query($db,"DROP TABLE USERS");

	$sqltest =<<<EOF
        CREATE TABLE USERS
        (
        EMAIL            TEXT    NOT NULL PRIMARY KEY,
        PASSWORD         TEXT    NOT NULL,
        CLASS1           TEXT,
        CLASS2           TEXT,
        CLASS3           TEXT,
        CLASS4           TEXT,
        CLASS5           TEXT,
        CLASS6		   TEXT,
        CLASS7		   TEXT,
        CLASS8		   TEXT
        );
EOF;

	$sqltest1 =<<<EOF
        CREATE TABLE ALL_CLASSES
        (
        NAME		TEXT	NOT NULL 	PRIMARY KEY,
        HWINFO		TEXT,
        LABINFO 	TEXT,
        QUIZINO		TEXT,
        MT1INFO     TEXT,
        MT2INFO     TEXT,
        MT3INFO     TEXT,
        FINALINFO   TEXT,
        MISC1       TEXT,
        MISC2       TEXT,
        MISC3       TEXT
        );
EOF;
	$result=pg_query($db,$sqltest);
	echo pg_last_error();
	$result=pg_query($db,$sqltest1);
	echo pg_last_error();
	echo "SUCCESS BIIIIIIITCH";
?>
