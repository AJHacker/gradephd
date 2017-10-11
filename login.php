<head>
<link rel="stylesheet" href="styles/global.css">
<script>
function yesorno(a){
    if (a==0) {
        document.getElementById('repass').style.visibility = 'hidden';
        document.getElementById('repassLabel').style.visibility = 'hidden';
        document.getElementById('passLabel').style.visibility = 'visible';
        document.getElementById('pass').style.visibility = 'visible';
    }
    if (a==1) {
        document.getElementById('repass').style.visibility = 'visible';
        document.getElementById('pass').style.visibility = 'visible';
        document.getElementById('repassLabel').style.visibility = 'visible';
        document.getElementById('passLabel').style.visibility = 'visible';

    }
    if (a==2) {
        document.getElementById('repass').style.visibility = 'hidden';
        document.getElementById('pass').style.visibility = 'hidden';
        document.getElementById('repassLabel').style.visibility = 'hidden';
        document.getElementById('passLabel').style.visibility = 'hidden';

    }

}
</script>
</head>
<div class="container">
  <form action="/user.php" method="post">
    <div class='topbar' style="width: 60%; left: 20%;">
    <center>
    <input type = 'radio' class='button hidden' id="signin" name="action" value="signin" onclick="javascript:yesorno(0);" checked></input>
    <label for='signin'>Sign In</label>
    <input type = 'radio' class='button hidden' id="signup" name="action" value="signup" onclick="javascript:yesorno(1);"></input>
    <label for='signup'>Sign Up</label>
    <input type = 'radio' class='button hidden' id="reset" name="action" value="reset" onclick="javascript:yesorno(2);"></input>
    <label for='reset'>Reset</label>
    </center>
    </div>
    <br>
    <br>
    <br>
    <div class="left">
        Email: <br>
        <label id='passLabel'>Password: </label><br>
        <label id='repassLabel' style="visibility: hidden;">Repeat Password: </label><br>
    </div>
    <div class="right">
        <input class = "textinput" name = "email" id="email" type="text" pattern="[a-zA-Z0-9!@#$%^*_|]{1,25}"></input><br>
        <input class = "textinput" name = "pass" id="pass" type="password" pattern="[a-zA-Z0-9!@#$%^*_|]{1,25}"></input></br>
        <input class = "textinput" name = "repass" id="repass" type="password" pattern="[a-zA-Z0-9!@#$%^*_|]{1,25}" style="visibility: hidden;"></input></br>
    </div>
    <center><input class = "button" type="submit" value="Hello Sir?" style="position: relative; top: 150px;"></center>
  </form>
</div>

<?php 
  echo "<center>".$_GET["error"]."</center>";
?>
