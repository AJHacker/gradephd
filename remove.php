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
            $tname=str_replace("0xDEADBEEF","-",$class);
            
        } else {
            header("Location: https://gradephd.herokuapp.com/user.php"); 
            exit();
        }
        
    
    ?>
    </body>
</html>
