<?php
    session_start();
    
?>
<link rel="stylesheet" href="styles/index.css">

<html>
<body>
<button class="button button1" value='Sign In'>Sign In</button>
<button class="button button1" value='Sign Up'>Sign Up</button>
<center><h3>Search for a class:</h3>
        <form action='/search.php' method='post'>
            Course Number: <input type='text' name='coursenum' placeholder='18-100' required>
            Semester: 
            <select name='semester'>
                <option value='F17'>F17</option>
                <option value='S18'>S18</option>
                <option value='F18'>F18</option>
                <option value='S19'>S19</option>
                <option value='F19'>F19</option>
                <option value='S20'>S20</option>
            </select>
            Professor: <input type='text' name='prof' placeholder='Sullivan' required>
            Points or Percentages?: 
            <input type='radio' name='porp' value='percentage' required>Percent
            <input type='radio' name='porp' value='points'>Points<br>

            <input type='submit' value='Submit'>
        </form> 
</center>
</body>
</html>