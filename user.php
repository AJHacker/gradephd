<html>
<body>

<?php
  $name = $_POST["name"];
  $pass = $_POST["password"];
  if(file_exists ( "/users/"+$name )){
    echo 'USER FOUND';
  } else {
    echo 'USER CREATED'; 
  }
?>


</body>
</html>
