<html>
    <body>
    <?php
        session_start();
        $class=$_GET['class'];
        $remove_confirm=$_GET['remove_confirm'];
        $user       = $_SESSION['verifiedUser'];
        //Make Sure User is Logged in
        if (!$user) {
            header("Location: https://gradephd.herokuapp.com/login.php?error=Please Login First"); 
            exit();
        }
        
        if ($remove_confirm===null) {
            $C          = explode("_",$class);
            $class_name = $C[2];
            $semester   = $C[1];
            $prof       = $C[0];
            echo "Removing $class_name taught by $prof for the $semester semester";
            
            echo " <p>Are you sure you want to remove this class from your class list?</p>
            <a href='/remove.php?class=$class&remove_confirm=1'>Yes</a><br>
            <a href='/remove.php?class=$class&remove_confirm=0'>No</a>";
        } elseif ($remove_confirm) {
            function pg_connection_string_from_database_url() {
                extract(parse_url($_ENV["DATABASE_URL"]));
                return "user=$user password=$pass host=$host dbname=" . substr($path, 1); 
            }
            $db = pg_connect(pg_connection_string_from_database_url());
            
            $tname=str_replace("-","0xDEADBEEF",$class);
            
            //Remove user from class
            echo "remove user from class\n";
            $class_sql="DELETE FROM $tname WHERE name='$user';";
            echo "$class_sql\n";
            pg_query($db,$class_sql);
            echo pg_last_error();
            echo "<br>";
            
            //remove class from user
            echo "remove class from user\n";
            $get_user="SELECT * FROM users WHERE email='$user';";
            echo $get_user."<br>";
            $result=pg_query($db,$get_user);
            echo "get row:";
            $classes=pg_fetch_row($result,0);
            echo pg_last_error() . "<br>";
            $n=1;
            for ($n;$n<9;$n++) {
                if ($classes[$n+1]==$tname) break;
            }
            if ($n+1<10) {
                $user_sql="UPDATE users SET class$n=NULL WHERE email='$user';";
                echo "$user_sql\n";
                pg_query($db,$user_sql);
                echo pg_last_error();
                echo "<br>";
            }
            
            echo "Done\n";
            echo "<a href='/user.php'>Back</a>";
        } else {
            header("Location: https://gradephd.herokuapp.com/user.php"); 
            exit();
        }
        
    
    ?>
    </body>
</html>
