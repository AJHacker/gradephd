<?php
    session_start();
?>
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

    if (!$user) {
        header("Location: https://gradephd.herokuapp.com/?error=Please Login First"); 
		exit();
    }
    
    $coursenum=$_POST['coursenum'];
    $semester=$_POST['semester'];
    $prof=$_POST['prof'];
    $porp=$_POST['porp'];
    
    if (!$coursenum && !$semester && !$prof && !$porp) {
    
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
    } elseif ($coursenum && $semester && $prof && $porp) {
        if ($porp=='percentage') {
            echo "
            <form action='/class.php' method='post'>
                <div>
                    Homeworks:<br>
                    How many? <input type=number name='hwnum'><br>
                    Total percentage of final grade?  <input type=number name='hwpercent'>%<br>
                    Lowest <input type=number name='hwnumwc'> are <input type=number name='hwwcp'>% normal weight.
                </div>
                <div>
                    Labs:<br>
                    How many? <input type=number name='lnum'><br>
                    Total percentage of final grade?  <input type=number name='lpercent'>%<br>
                    Lowest <input type=number name='lnumwc'> are <input type=number name='lwcp'>% normal weight.
                </div>
                <div>
                    Quizzes:<br>
                    How many? <input type=number name='qnum'><br>
                    Total percentage of final grade?  <input type=number name='qpercent'>%<br>
                    Lowest <input type=number name='qnumwc'> are <input type=number name='qwcp'>% normal weight.
                </div>
                <div>
                    Midterm 1:<br>
                    
                
            ";
        } elseif ($porp=='points') {
            echo "";
        }
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

