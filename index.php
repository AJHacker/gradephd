<?php
    session_start();
    if ($_SESSION['verifiedUser']) header("Location: https://gradephd.herokuapp.com/user.php");
?>
<html>
<body>
<link rel="stylesheet" href="styles/global.css">

<div class='topbar'>
<button class="button" style="float:right;" onclick="window.location.href='login.php'">Sign In</button>
<button class="button" style="float:right;" onclick="window.location.href='login.php'">Sign Up</button>
</div>
<div class='container'>
<center>
<h1>Welcome to GradePhD</h1>
<h3>Search for a class:</h3>
        <form action='/search.php' method='post'>
            <div class="left">Course Number: <br>
                Semester: <br>
                Professor: <br>
            </div>
            <div class="right">
                <input class = 'textinput' type='text' name='coursenum' placeholder='18-100' pattern="[a-zA-Z0-9!@#$%^*_|]{1,25}" required>
                <br>
                
                <select class='textinput' name='semester'>
                    <option value='F17'>F17</option>
                    <option value='S18'>S18</option>
                    <option value='F18'>F18</option>
                    <option value='S19'>S19</option>
                    <option value='F19'>F19</option>
                    <option value='S20'>S20</option>
                </select>
                <br>
                <input type='text' class='textinput' name='prof' placeholder='Sullivan' pattern="[a-zA-Z0-9!@#$%^*_|]{1,25}" required>
                <br>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <input class='button' type='submit' value='Search'>
            
        </form> 
        
</center>
</div>
</body>
<?php 
  echo "<center>".$_GET["error"]."</center>";
?>

</html>

