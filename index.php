<html>
<body>
<link rel="stylesheet" href="styles/global.css">

<div id='topbar'>
<button id="button" style="float:right;" onclick="window.location.href='login.php'">Sign In</button>
<button id="button" style="float:right;" onclick="window.location.href='login.php'">Sign Up</button>
</div>
<div id='container'>
<center>
<h1>Welcome to GradePhD</h1>
<h3>Search for a class:</h3>
        <form action='/search.php' method='post'>
            <div class="left">Course Number: <br>
                Semester: <br>
                Professor: <br>
            </div>
            <div class="right">
                <input id = 'textinput' type='text' name='coursenum' placeholder='18-100' pattern="[a-zA-Z0-9!@#$%^*_|]{6,25}" required>
                <br>
                
                <select id = 'textinput' name='semester'>
                    <option value='F17'>F17</option>
                    <option value='S18'>S18</option>
                    <option value='F18'>F18</option>
                    <option value='S19'>S19</option>
                    <option value='F19'>F19</option>
                    <option value='S20'>S20</option>
                </select>
                <br>
                <input type='text' id='textinput' name='prof' placeholder='Sullivan' pattern="[a-zA-Z0-9!@#$%^*_|]{6,25}" required>
                <br>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <input id = 'button' type='submit' value='Search'>
            
        </form> 
        
</center>
</div>
</body>
<?php 
  echo "<center>".$_GET["error"]."</center>";
?>

</html>

