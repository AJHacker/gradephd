<html>
<body>

<?php
  $name = $_POST["name"];
  $pass = $_POST["password"];
  if(file_exists ("/users/"+$name+strlen($pass))){
    echo 'USER FOUND';
  } else {
    header("Location: https://gradephd.herokuapp.com/?error=user_not_found"); /* Redirect browser */
    exit();
  }
?>


</body>
</html>
