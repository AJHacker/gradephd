<html>
<body>

<?php
  $name = $_POST["name"];
  $pass = $_POST["password"];
  if(file_exists ("/users/"+$name+strlen($pass))){
    echo 'Classes:';
  } else {
    if (mkdir("/users/" + $name+strlen($pass), 0700,true)){
       echo 'User Created With Name ' + $name + 'and password length of ' + strlen($pass);
    
    } else{
      echo 'error!'; 
    }
   
  }
?>


</body>
</html>
