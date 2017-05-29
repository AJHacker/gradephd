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
    $new_class=$_SESSION['new_class'];

    if (!$user) {
        header("Location: https://gradephd.herokuapp.com/?error=Please Login First"); 
        exit();
    }
    
    $coursenum=$_POST['coursenum'];
    $semester=$_POST['semester'];
    $prof=$_POST['prof'];
    $porp=$_POST['porp'];
    
    $hw_diff=($_POST['hwweight']=='different');
    $l_diff=($_POST['lweight']=='different');
    $q_diff=($_POST['qweight']=='different');
    $t_diff=($_POST['tweight']=='different');
    $misc1_diff=($_POST['misc1weight']=='different');
    $misc2_diff=($_POST['misc2weight']=='different');
    $misc3_diff=($_POST['misc3weight']=='different');
    
    if (!$coursenum && !$semester && !$prof && !$porp && !$new_class) {
    
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
    } elseif ($coursenum && $semester && $prof && $porp && !$new_class) {
        $class_name=$coursenum."|".$semester."|".$prof;
        $_SESSION['new_class']=$class_name;
//        unset($_SESSION['new_class']);
        if ($porp=='percentage') {
            $v = "%";
            $s="percentage of final grade";
        } else {
            $v = "points";
            $s="points";
        }
        echo "
        <form action='/class.php' method='post'>
            <fieldset>
                <legend>Homeworks</legend>
                How many? <input type=number name='hwnum'><br>
                Total " . $s . "?  <input type=number name='hwpercent'>".$v."<br>
                <input value='same' name = 'hwweight' type='radio' checked>
                Lowest <input type=number name='hwnumwc' value='0'> are <input type=number name='hwwcp' value='100'>% normal weight.<br>
                <input value='different' name = 'hwweight' type='radio'>Different weights for all
              
            </fieldset>
            <fieldset>
                <legend>Labs</legend>
                How many? <input type=number name='lnum'><br>
                Total " . $s . "?  <input type=number name='lpercent'>".$v."<br>
                <input value='same' name = 'lweight' type='radio' checked>
                All same weight and Lowest <input type=number name='lnumwc' value='0'> are <input type=number name='lwcp' value='100'>% normal weight.<br>
                <input value='different' name = 'lweight' type='radio'>Different weights for all

            </fieldset>
            <fieldset>
                <legend>Quizzes</legend>
                How many? <input type=number name='qnum'><br>
                Total " . $s . "?  <input type=number name='qpercent'>".$v."<br>
                <input value='same' name = 'qweight' type='radio' checked>
                All same weight and Lowest <input type=number name='qnumwc' value = '0'> are <input type=number name='qwcp' value='100'>% normal weight.<br>
                <input value='different' name = 'qweight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Tests</legend>
                How many? <input type=number name='tnum'><br>
                Total " . $s . "?  <input type=number name='tpercent'>".$v."<br>
                <input value='same' name='tweight' type='radio' checked>
                All same weight and Lowest <input type=number name='tnumwc' value='0'> are <input type=number name='twcp' value='100'>% normal weight.<br>
                <input value='different' name='tweight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Final</legend>
                Total " . $s . "?  <input type=number name='fpercent'>".$v."
            </fieldset>
            <fieldset>
                <legend>Other 1 (optional)</legend>
                Name of category: <input type=text name='misc1name'><br>
                How many? <input type=number name='misc1num'><br>
                Total " . $s . "?  <input type=number name='misc1percent'>".$v."<br>
                <input value='same' name='misc1weight' type='radio' checked>                
                All same weight and Lowest <input type=number name='misc1numwc'> are <input type=number name='misc1wcp'>% normal weight.<br>
                <input value='different' name='misc1weight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Other 2 (optional)</legend>
                Name of category: <input type=text name='misc2name'><br>
                How many? <input type=number name='misc2num'><br>
                Total ?  <input type=number name='misc2percent'>".$v."<br>
                <input value='same' name='misc2weight' type='radio' checked>                
                All same weight and Lowest <input type=number name='misc2numwc'> are <input type=number name='misc2wcp'>% normal weight.<br>
                <input value='different' name='misc2weight' type='radio'>Different weights for all
            </fieldset>
            <fieldset>
                <legend>Other 3 (optional)</legend>
                Name of category: <input type=text name='misc3name'><br>
                How many? <input type=number name='misc3num'><br>
                Total " . $s . "?  <input type=number name='misc3percent'><br>
                <input value='same' name='misc3weight' type='radio' checked>
                All same weight and Lowest <input type=number name='misc3numwc'> are <input type=number name='misc3wcp'>% normal weight.<br>
                <input value='different' name='misc3weight' type='radio'>Different weights for all
            </fieldset>
        <input type='submit' value='Submit'>
        </form>
        ";
    
    } elseif ($new_class){
        $diff=array();
        $arr = array();
        $y=0;
        if($hw_diff){
            $diff[$y]="hw";
            $arr[$y]="Homework";
            $y++;
        }
        if($l_diff){
            $diff[$y]="l";
            $arr[$y]="Lab";
            $y++;
        }
        if($q_diff){
            $diff[$y]="q";
            $arr[$y]="Quiz";
            $y++;
        }
        if($t_diff){
            $diff[$y]="t";
            $arr[$y]="Test";
            $y++;
        }
        if($misc1_diff){
            $diff[$y]="misc1";
            $arr[$y]="Other 1";
            $y++;
        }
        if($misc2_diff){
            $diff[$y]="misc2";
            $arr[$y]="Other 2";
            $y++;
        }
        if($misc3_diff){
            $diff[$y]="misc3";
            $arr[$y]="Other 3";
            $y++;
        }

        $counts = array();
        echo "<form action='/class.php'>";
        for ($i=0;$i<count($diff);$i++) {
            $n=$_POST[$diff[$i]."num"];
            $type=$diff[$i];
            $counts[$type]=$n;
            $word=$arr[$i];
            echo "<fieldset>";
            echo "<legend>$word</legend>";
            echo "<ol>";
            for ($x=0;$x<$n;$x++) {
                echo "<li><input type=number name='$type$x'></li>";
            }
            echo "</ol>";
            echo "</fieldset>";
        }
        echo "<input type='submit' value='Submit'>";
        echo "</form>";
        $_SESSION+=$_POST;
        $_SESSION['diff']=$diff;
        $_SESSION['arr']=$arr;
        $_SESSION['counts']=$counts;
        
    }else{
    	$diff = $_SESSION['diff'];
    	$counts = $_SESSION['counts'];

    	for($x=0;$x<count($diff);$x++){
    		for($i=0;$i<$counts[$diff[$x]];$i++){
    			$str = $diff[$x].$i;
    			echo "database entry? $POST[$str]";
    		}
    	}

    }
    // elseif ($coursenum && $semester && $prof) {
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


