<head>
<link rel="stylesheet" href="styles/index.css">
<script>
    let rect = document.getElementById("rect");
    let username = document.getElementById("username");
    let password = document.getElementById("password");

    function handle1() {
        rect.setAttribute("class", "rect2");
    }

    function handle2() {
        rect.setAttribute("class", "rect1");
    }

    //For codepen header!!!
    setTimeout(() => {
        password.focus();
    }, 500);

    setTimeout(() => {
        username.focus();
    }, 1500);
</script>
</head>



<div class="al"></div>
  <div class="container">
   <div class="header">Sign In</div>
    <div class='info'><?php echo $_POST['message']?></div>
      <form id='login' action='user.php' method='post'>
      <input id='username' class='text' onfocus="handle2()" class='inc2' type="text" name="email" placeholder='Username' value="">
      <!-- Had to remove the type "password" due to the browser user credential's autofill-->
       <input id='password' class='pass' onfocus="handle1()" class='inc1' type="pass" name="pass" placeholder='Password' value="">
       <input type='submit' value = 'Sign In'>
       <input value = 'signin' id = 'action'>
       </form>
       <svg width="390" height="549" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <rect id='rect' class='rect1'   x="45px"  y="300px"   rx="27" ry="27" width="300px" height="50px" style="stroke: #fff; stroke-width: 1px; fill: #000" />
      </svg>
  </div>


<?php 
  echo "<center>".$_GET["error"]."</center>";
?>
