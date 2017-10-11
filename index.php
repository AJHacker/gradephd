<html>
<body>
<link rel="stylesheet" href="styles/index.css">

<div id='topbar'>
<a id="button" style="float:right" onclick="window.location.href='login.php'">Sign In</button>
<a id="button" style="float:right" onclick="window.location.href='login.php'">Sign Up</button>
</div>
<div id='container'>
<center>
<h1>Welcome to GradePHD</h1>
<h2>SEARCH FOR A CLASS:</h2>
        <form action='/search.php' method='post'>
            Course Number: <input id = 'textinput' type='text' name='coursenum' placeholder='18-100' pattern="[a-zA-Z0-9!@#$%^*_|]{6,25}" required>
            <br>
            Semester: 
            <select id = 'textinput' name='semester'>
                <option value='F17'>F17</option>
                <option value='S18'>S18</option>
                <option value='F18'>F18</option>
                <option value='S19'>S19</option>
                <option value='F19'>F19</option>
                <option value='S20'>S20</option>
            </select>
            <br>
            Professor: <input type='text' id='textinput' name='prof' placeholder='Sullivan' pattern="[a-zA-Z0-9!@#$%^*_|]{6,25}" required>
            <br>
            <br>
            <input id = 'button' type='submit' value='Search'>
            
        </form> 
        
</center>
</div>
</body>
</html>