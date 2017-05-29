<html>
<?php
	$user=$_SESSION["verifiedUser"];

    if(!$user){
        header("Location: https://gradephd.herokuapp.com/?error=Please Login First"); 
		exit();
    }
?>

</html>