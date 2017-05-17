<html>
<body>

<?php
  $name = $_POST["name"];
  $pass = $_POST["password"];
  if(file_exists ("/users/"+$name+strlen($pass))){
    echo 'USER FOUND';
  } else {
    header("Location: https://gradephd.herokuapp.com/?error=USER NOT FOUND!"); /* Redirect browser */
    exit();
  }
?>


</body>
</html>
