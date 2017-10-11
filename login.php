<head>
<link rel="stylesheet" href="styles/login.css">
<script>
function yesorno(){
    if (document.getElementById('signin').checked) {
        document.getElementById('repass').style.visibility = 'hidden';
        document.getElementById('pass').style.visibility = 'visible';

    }
    if (document.getElementById('signup').checked) {
        document.getElementById('repass').style.visibility = 'visible';
        document.getElementById('pass').style.visibility = 'visible';

    }
    if (document.getElementById('reset').checked) {
        document.getElementById('repass').style.visibility = 'hidden';
        document.getElementById('pass').style.visibility = 'hidden';

    }

}
</script>
</head>

<form action="/user.php" method="post">

  <input checked = "checked" id="signin" name="action" type="radio" value="signin" onclick="javascript:yesorno();" >Sign In</input><br>
  <input id="signup" name="action" type="radio" value="signup" onclick="javascript:yesorno();" >Sign Up</input><br>
  <input id="reset" name="action" type="radio" value="reset" onclick="javascript:yesorno();" >Reset</input><br>

  <input name = "email" id="email" placeholder="Email" type="text" pattern="[a-zA-Z0-9!@#$%^*_|]{6,25}"></input><br>
  <input name = "pass" id="pass" placeholder="Password" type="password" pattern="[a-zA-Z0-9!@#$%^*_|]{6,25}"></input></br>
  <input name = "repass" id="repass" placeholder="Repeat password" type="password" pattern="[a-zA-Z0-9!@#$%^*_|]{6,25}" style="visibility: hidden;"></input></br>
  <input type="submit" value="Hello Sir?">
</form>

<?php 
  echo "<center>".$_GET["error"]."</center>";
?>
