<html>
<?php
	$user=$_SESSION["verifiedUser"];

    if(!$user){
        header("Location: https://gradephd.herokuapp.com/login.php?error=Please Login First"); 
		exit();
    }
?>

</html>