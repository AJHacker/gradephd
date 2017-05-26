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
                <fieldset>
                    <legend>Homeworks</legend>
                    How many? <input type=number name='hwnum'><br>
                    Total percentage of final grade?  <input type=number name='hwpercent'>%<br>
                    Lowest <input type=number name='hwnumwc'> are <input type=number name='hwwcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Labs</legend>
                    How many? <input type=number name='lnum'><br>
                    Total percentage of final grade?  <input type=number name='lpercent'>%<br>
                    Lowest <input type=number name='lnumwc'> are <input type=number name='lwcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Quizzes</legend>
                    How many? <input type=number name='qnum'><br>
                    Total percentage of final grade?  <input type=number name='qpercent'>%<br>
                    Lowest <input type=number name='qnumwc'> are <input type=number name='qwcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Midterms</legend>
                    Midterm 1:<br>
                    Total percentage of final grade?  <input type=number name='m1percent'>%<br>
                    Midterm 2:<br>
                    Total percentage of final grade?  <input type=number name='m2percent'>%<br>
                    Midterm 3:<br>
                    Total percentage of final grade?  <input type=number name='m3percent'>%<br>
                </fieldset>
                <fieldset>
                    <legend>Final</legend>
                    Total percentage of final grade?  <input type=number name='fpercent'>%<br>
                </fieldset>
                <fieldset>
                    <legend>Other 1(optional)</legend>
                    Name of category: <input type=text name='misc1name'>
                    How many? <input type=number name='misc1num'>
                    Total percentage of final grade?  <input type=number name='misc1percent'>%<br>
                    Lowest <input type=number name='misc1numwc'> are <input type=number name='misc1wcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Other 2(optional)</legend>
                    Name of category: <input type=text name='misc2name'>
                    How many? <input type=number name='misc2num'>
                    Total percentage of final grade?  <input type=number name='misc2percent'>%<br>
                    Lowest <input type=number name='misc2numwc'> are <input type=number name='misc2wcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Other 1(optional)</legend>
                    Name of category: <input type=text name='misc3name'>
                    How many? <input type=number name='misc3num'>
                    Total percentage of final grade?  <input type=number name='misc3percent'>%<br>
                    Lowest <input type=number name='misc3numwc'> are <input type=number name='misc3wcp'>% normal weight.
                </fieldset>
            <input type='submit' value='Submit'>
            </form>
            ";
        } elseif ($porp=='points') {
            echo "            
            <form action='/class.php' method='post'>
                <fieldset>
                    <legend>Homeworks</legend>
                    How many? <input type=number name='hwnum'><br>
                    Total points?  <input type=number name='hwpercent'>%<br>
                    Lowest <input type=number name='hwnumwc'> are <input type=number name='hwwcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Labs</legend>
                    How many? <input type=number name='lnum'><br>
                    Total percentage of final grade?  <input type=number name='lpercent'>%<br>
                    Lowest <input type=number name='lnumwc'> are <input type=number name='lwcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Quizzes</legend>
                    How many? <input type=number name='qnum'><br>
                    Total percentage of final grade?  <input type=number name='qpercent'>%<br>
                    Lowest <input type=number name='qnumwc'> are <input type=number name='qwcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Midterms</legend>
                    Midterm 1:<br>
                    Total percentage of final grade?  <input type=number name='m1percent'>%<br>
                    Midterm 2:<br>
                    Total percentage of final grade?  <input type=number name='m2percent'>%<br>
                    Midterm 3:<br>
                    Total percentage of final grade?  <input type=number name='m3percent'>%<br>
                </fieldset>
                <fieldset>
                    <legend>Final</legend>
                    Total percentage of final grade?  <input type=number name='fpercent'>%<br>
                </fieldset>
                <fieldset>
                    <legend>Other 1(optional)</legend>
                    Name of category: <input type=text name='misc1name'>
                    How many? <input type=number name='misc1num'>
                    Total percentage of final grade?  <input type=number name='misc1percent'>%<br>
                    Lowest <input type=number name='misc1numwc'> are <input type=number name='misc1wcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Other 2(optional)</legend>
                    Name of category: <input type=text name='misc2name'>
                    How many? <input type=number name='misc2num'>
                    Total percentage of final grade?  <input type=number name='misc2percent'>%<br>
                    Lowest <input type=number name='misc2numwc'> are <input type=number name='misc2wcp'>% normal weight.
                </fieldset>
                <fieldset>
                    <legend>Other 1(optional)</legend>
                    Name of category: <input type=text name='misc3name'>
                    How many? <input type=number name='misc3num'>
                    Total percentage of final grade?  <input type=number name='misc3percent'>%<br>
                    Lowest <input type=number name='misc3numwc'> are <input type=number name='misc3wcp'>% normal weight.
                </fieldset>
            <input type='submit' value='Submit'>
            </form>";
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

