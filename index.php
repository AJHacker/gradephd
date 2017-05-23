<head>
<link rel="stylesheet" href="styles/mainpage.css">
</head>

<form action="/user.php" method="post">
  
  <input checked="<%= true %>" id="signin" name="action" type="radio" value="signin"></input>
  <label for="signin">Sign in</label>
  <input id="signup" name="action" type="radio" value="signup"></input>
  <label for="signup">Sign up</label>
  <input id="reset" name="action" type="radio" value="reset"></input>
  <label for="reset">Reset</label>
  <div id="wrapper">
    <div id="arrow"></div>
    <input name = "email" id="email" placeholder="Email" type="text"></input>
    <input name = "pass" id="pass" placeholder="Password" type="password"></input>
    <input name = "repass" id="repass" placeholder="Repeat password" type="password"></input>
  </div>
  <button type="submit">
    <span>
      Reset password
      <br/>
      Sign in
      <br/>
      Sign up
    </span>
  </button>
</form>
<?php 
  echo "<center>".$_GET["error"]."</center>";
  ?>
<div id="hint">Click on the tabs</div>
