<html>
<head>
<script type="text/javascript">
    

    
</script>
<link rel='stylesheet' href='styles/mainpage.css'>
</head>
<body>
<center>
<?php
    $user=$_SESSION["verifiedUser"];

    if(!$user){
        header("Location: https://gradephd.herokuapp.com/?error=Please Login First"); 
		exit();
    }
    
    $coursenum=$_POST['coursenum'];
    $semester=$_POST['semester'];
    $prof=$_POST['prof'];
    
    if (!$coursenum && !$semester && !$prof) {
    
        echo "
        <form action='/class.php' method='post'>
            Course Number: <input type='text' name='coursenum' placeholder='18-100'><br> 
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
            Professor: <input type='text' name='prof' placeholder='Sullivan'><br> 
            Points or Percentages?: 
            <input type='radio' name='porp' value='percentage'>Percent
            <input type='radio' name='porp' value='points'>Points<br>

            <input type='submit' value='Submit'>
        </form> 
        ";
    }
    else{
        echo "fuck everything";
    }
    // } elseif ($coursenum && $semester && $prof) {
    //     $class_name=$coursenum . $semester . $prof;
    //     $sql="CREATE TABLE IF NOT EXISTS '" . $class_name . "' (
    //         );";
    //     //initialize new class table
    // }

?>
</center>
</p>

</body>
</html>

