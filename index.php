<html>
<div id='top_bar'>
<button value='Sign In'>Sign In</button>
<button value='Sign Up'>Sign Up</button>
</div>
<h3>Search for a class:</h3>

<?php
echo "
        <form action='/class.php' method='post'>
            Course Number: <input type='text' name='coursenum' placeholder='18-100' required><br> 
            Semester: 
            <select name='semester'>
                <option value='F17'>F17</option>
                <option value='S18'>S18</option>
                <option value='F18'>F18</option>
                <option value='S19'>S19</option>
                <option value='F19'>F19</option>
                <option value='S20'>S20</option>
            </select>
            <br>
            Professor: <input type='text' name='prof' placeholder='Sullivan' required><br> 
            Points or Percentages?: 
            <input type='radio' name='porp' value='percentage' required>Percent
            <input type='radio' name='porp' value='points'>Points<br>

            <input type='submit' value='Submit'>
        </form> 
        ";
?>

</html>