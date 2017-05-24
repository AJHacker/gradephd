<html>
<head>
<link rel="stylesheet" href="styles/mainpage.css">
</head>
<h1>Welcome!</h1>
<?php
$user = $_POST['user'];
$pass = $_POST['pass'];

if($user == "admin"
&& $pass == "admin")
{
        
# This function reads your DATABASE_URL config var and returns a connection
# string suitable for pg_connect. Put this in your app.
function pg_connection_string_from_database_url() {
  extract(parse_url($_ENV["DATABASE_URL"]));
  return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
}
# Here we establish the connection. Yes, that's all.
$db = pg_connect(pg_connection_string_from_database_url());
   $result1 = pg_query($db, "SELECT relname FROM pg_stat_user_tables WHERE schemaname='public'");
    print "<pre>\n";
    if (!pg_num_rows($result1)) {
      print("Your connection is working, but your database is empty.\nFret not. This is expected for new apps.\n");
    } else {
     // OLD CODE FROM WOFM
     while ($row = pg_fetch_row($result1)) { 
       if (!($row[0]=="users" || $row[0]=="all_classes")) {
         pg_query($db, "DROP TABLE " . $row[0] . ";");
         echo $row[0];
       }
      }
      pg_query($db, "TRUNCATE TABLE USERS;");
      pg_query($db, "TRUNCATE TABLE ALL_CLASSES");
    }

   pg_close($db);
}
else
{
    if(isset($_POST))
    {?>

            <form method="POST" action="purge.php">
            User <input type="text" name="user"></input><br/>
            Pass <input type="password" name="pass"></input><br/>
            <input type="submit" name="submit" value="Purge"></input>
            </form>
    <?}
}

?>
  
</html>
