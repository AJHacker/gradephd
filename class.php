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
            $s="percentage of final grade";
        } else {
            $s="points";
        }
        echo "
        <form action='/class.php' method='post'>
            <fieldset>
                <legend>Homeworks</legend>
                How many? <input type=number name='hwnum'><br>
                Total percentage of final grade?  <input type=number name='hwpercent'>%<br>
                <input value='same' name = 'hwweight' type='radio' checked>
                Lowest <input type=number name='hwnumwc' value='0'> are <input type=number name='hwwcp' value='100'>% normal weight.<br>
                <input value='different' name = 'hwweight' type='radio'>Different weights for all
              
            </fieldset>
            <fieldset>
                <legend>Labs</legend>
                How many? <input type=number name='lnum'><br>
                Total percentage of final grade?  <input type=number name='lpercent'>%<br>
                <input value='same' name = 'lweight' type='radio' checked>
                All same weight and Lowest <input type=number name='lnumwc' value='0'> are <input type=number name='lwcp' value='100'>% normal weight.<br>
                <input value='different' name = 'lweight' type='radio'>Different weights for all

            </fieldset>
            <fieldset>
                <legend>Quizzes</legend>
                How many? <input type=number name='qnum'><br>
                Total percentage of final grade?  <input type=number name='qpercent'>%<br>
                <input value='same' name = 'qweight' type='radio' checked>
                All same weight and Lowest <input type=number name='qnumwc' value = '0'> are <input type=number name='qwcp' value='100'>% normal weight.<br>
                <input value='different' name = 'qweight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Tests</legend>
                How many? <input type=number name='tnum'><br>
                Total percentage of final grade?  <input type=number name='tpercent'>%<br>
                <input value='same' name='tweight' type='radio' checked>
                All same weight and Lowest <input type=number name='tnumwc' value='0'> are <input type=number name='twcp' value='100'>% normal weight.<br>
                <input value='different' name='tweight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Final</legend>
                Total percentage of final grade?  <input type=number name='fpercent'>%
            </fieldset>
            <fieldset>
                <legend>Other 1 (optional)</legend>
                Name of category: <input type=text name='misc1name'><br>
                How many? <input type=number name='misc1num'><br>
                Total percentage of final grade?  <input type=number name='misc1percent'>%<br>
                <input value='same' name='misc1weight' type='radio' checked>                
                All same weight and Lowest <input type=number name='misc1numwc'> are <input type=number name='misc1wcp'>% normal weight.<br>
                <input value='different' name='misc1weight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Other 2 (optional)</legend>
                Name of category: <input type=text name='misc2name'><br>
                How many? <input type=number name='misc2num'><br>
                Total percentage of final grade?  <input type=number name='misc2percent'>%<br>
                <input value='same' name='misc2weight' type='radio' checked>                
                All same weight and Lowest <input type=number name='misc2numwc'> are <input type=number name='misc2wcp'>% normal weight.<br>
                <input value='different' name='misc2weight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Other 3 (optional)</legend>
                Name of category: <input type=text name='misc3name'><br>
                How many? <input type=number name='misc3num'><br>
                Total percentage of final grade?  <input type=number name='misc3percent'>%<br>
                <input value='same' name='misc3weight' type='radio' checked>
                All same weight and Lowest <input type=number name='misc3numwc'> are <input type=number name='misc3wcp'>% normal weight.<br>
                <input value='different' name='misc3weight' type='radio'>Different weights for all
            </fieldset>
        <input type='submit' value='Submit'>
        </form>
        ";
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

l>

