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
    //Initiate Session User Variable and Class Variables
    $user       = $_SESSION["verifiedUser"];
    $new_class  = $_SESSION['new_class'];

    //Make Sure User is Logged in
    if (!$user) {
        header("Location: https://gradephd.herokuapp.com/?error=Please Login First"); 
        exit();
    }    

    //Get Post Variables for Course Information
    $coursenum  = $_POST['coursenum'];
    $semester   = $_POST['semester'];
    $prof       = $_POST['prof'];
    $porp       = $_POST['porp'];
    
    // TODO SEARCH FOR COURSE BY NUMBER TO SEE IF ALREADY IN DB


    //

    $hw_diff=($_POST['hwweight']=='different');
    $l_diff=($_POST['lweight']=='different');
    $q_diff=($_POST['qweight']=='different');
    $t_diff=($_POST['tweight']=='different');
    $misc1_diff=($_POST['misc1weight']=='different');
    $misc2_diff=($_POST['misc2weight']=='different');
    $misc3_diff=($_POST['misc3weight']=='different');
    
    if (!$coursenum && !$semester && !$prof && !$porp && !$new_class) {
        echo "1";    
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
    } elseif ($coursenum && $semester && $prof && $porp && !$new_class) {
        echo "2";
        $class_name=$coursenum."_".$semester."_".$prof;
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
                Is there a final? <input type='radio' name='fnum' value = '1'>Yes
                                  <input type='radio' name='fnum' value = '0'>No
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
    
    } elseif ($new_class && !$_SESSION['form_finished']){
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
        echo "WHY THE FUCK YOU GOTTA HAVE DIFFERENT WEIGHTS";
        if (count($diff)>0) {
            echo "<form action='/class.php' method='post'>";
            for ($i=0;$i<count($diff);$i++) {
                $n=$_POST[$diff[$i]."num"];
                $type=$diff[$i];
                $counts[$type]=$n;
                $word=$arr[$i];
                echo "<fieldset>";
                echo "<legend>$word</legend>";
                echo "<ol>";
                for ($x=1;$x<$n+1;$x++) {
                    echo "<li><input type=number name='$type$x' required></li>";
                }    
                echo "</ol>";
                echo "</fieldset>";
            }
            $_SESSION+=$_POST;
            $_SESSION['diff']=$diff;
            $_SESSION['arr']=$arr;
            $_SESSION['counts']=$counts;
    
            $_SESSION['form_finished']=true;
            echo "<input type='submit' value='Submit'>";
            echo "</form>";
        } else {
            $_SESSION+=$_POST;
            $_SESSION['diff']=$diff;
            $_SESSION['arr']=$arr;
            $_SESSION['counts']=$counts;
    
            $_SESSION['form_finished']=true;
            header("Location: https://gradephd.herokuapp.com/class.php"); 
            exit();
        }
    }else{
        echo "DATABASE ENTRY<br>";
        
        function pg_connection_string_from_database_url() {
            extract(parse_url($_ENV["DATABASE_URL"]));
            return "user=$user password=$pass host=$host dbname=" . substr($path, 1); 
        }

        $db = pg_connect(pg_connection_string_from_database_url());
        
        //HOMEWORK
        $hwnum = $_SESSION['hwnum'];
        $hwpercent = $_SESSION['hwpercent'];
        $hwweight = $_SESSION['hwweight'];
        $hwnumwc = $_SESSION['hwnumwc'];
        $hwwcp = $_SESSION['hwwcp'];
       
        $HWINFO = "$hwnum|$hwpercent|$hwweight";
        if ($hwweight=="same") {
            $HWINFO.=",$hwnumwc,$hwwcp";
        } else {
            for ($x=1;$x<$hwnum+1;$x++) {
                $w=$_SESSION["hw$x"];
                $HWINFO.=",$w";
            }
        }

        //LAB
        $lnum = $_SESSION['lnum'];
        $lpercent = $_SESSION['lpercent'];
        $lweight = $_SESSION['lweight'];
        $lnumwc = $_SESSION['lnumwc'];
        $lwcp = $_SESSION['lwcp'];
       
        $LABINFO = "$lnum|$lpercent|$lweight";
        if ($lweight=="same") {
            $LABINFO.=",$lnumwc,$lwcp";
        } else {
            for ($x=1;$x<$lnum+1;$x++) {
                $w=$_SESSION["l$x"];
                $LABINFO.=",$w";
            }
        }
        
        //QUIZ
        $qnum = $_SESSION['qnum'];
        $qpercent = $_SESSION['qpercent'];
        $qweight = $_SESSION['qweight'];
        $qnumwc = $_SESSION['qnumwc'];
        $qwcp = $_SESSION['qwcp'];
       
        $QUIZINFO = "$qnum|$qpercent|$qweight";
        if ($qweight=="same") {
            $QUIZINFO.=",$qnumwc,$qwcp";
        } else {
            for ($x=1;$x<$qnum+1;$x++) {
                $w=$_SESSION["q$x"];
                $QUIZINFO.=",$w";
            }
        }

        //TEST
        $tnum = $_SESSION['tnum'];
        $tpercent = $_SESSION['tpercent'];
        $tweight = $_SESSION['tweight'];
        $tnumwc = $_SESSION['tnumwc'];
        $twcp = $_SESSION['twcp'];
       
        $TESTINFO = "$tnum|$tpercent|$tweight";
        if ($tweight=="same") {
            $TESTINFO.=",$tnumwc,$twcp";
        } else {
            for ($x=1;$x<$tnum+1;$x++) {
                $w=$_SESSION["t$x"];
                $TESTINFO.=",$w";
            }
        }
        
        //FINAL
        $fnum = $_SESSION['fnum'];
        $fpercent = $_SESSION['fpercent'];
        $FINALINFO = "$fnum|$fpercent|same,0,0";
        
        
        //MISC1
        $misc1num = $_SESSION['misc1num'];
        $misc1percent = $_SESSION['misc1percent'];
        $misc1weight = $_SESSION['misc1weight'];
        $misc1numwc = $_SESSION['misc1numwc'];
        $misw1cp = $_SESSION['misc1wcp'];
       
        $MISC1INFO = "$misc1num|$misc1percent|$misc1weight";
        if ($misc1weight=="same") {
            $MISC1INFO.=",$misc1numwc,$misc1wcp";
        } else {
            for ($x=1;$x<$misc1num+1;$x++) {
                $w=$_SESSION["misc1$x"];
                $MISC1.=",$w";
            }
        }

        //MISC 2
        $misc2num = $_SESSION['misc2num'];
        $misc2percent = $_SESSION['misc2percent'];
        $misc2weight = $_SESSION['misc2weight'];
        $misc2numwc = $_SESSION['misc2numwc'];
        $misc2wcp = $_SESSION['misc2wcp'];
       
        $MISC2INFO = "$misc2num|$misc2percent|$misc2weight";
        if ($misc2weight=="same") {
            $MISC2INFO.=",$misc2numwc,$misc2wcp";
        } else {
            for ($x=1;$x<$misc2num+1;$x++) {
                $w=$_SESSION["misc2$x"];
                $MISC2INFO.=",$w";
            }
        }

        //MISC 3 
        $misc3num = $_SESSION['misc3num'];
        $misc3percent = $_SESSION['misc3percent'];
        $misc3weight = $_SESSION['misc3weight'];
        $misc3numwc = $_SESSION['misc3numwc'];
        $misc3wcp = $_SESSION['misc3wcp'];
       
        $MISC3INFO = "$misc3num|$misc3percent|$misc3weight";
        if ($misc3weight=="same") {
            $MISC3INFO.=",$misc3numwc,$misc3wcp";
        } else {
            for ($x=1;$x<$misc3num+1;$x++) {
                $w=$_SESSION["misc3$x"];
                $MISC3INFO.=",$w";
            }
        }

        $sql="INSERT INTO TABLE ALL_CLASSES VALUES ( 
            '$new_class', 
            '$HWINFO',
            '$LABINFO',
            '$QUIZINFO',
            '$TESTINFO',
            '$FINALINFO',
            '$MISC1INFO',
            '$MISC2INFO',
            '$MISC3sINFO'
            );";
            
        pg_query($db,$sql);
        
        $class_sql="CREATE TABLE '$new_class' (
            USER     TEXT    NOT NULL    PRIMARY KEY,
            ";
          
        for($x=1;$x<$hwnum+1;$x++){
            $class_sql.="hw$x INTEGER,";
        }

        for ($x=1;$x<$lnum+1;$x++) {
            $class_sql.="l$x INTEGER,";
        }

        for ($x=1;$x<$qnum+1;$x++) {
            $class_sql.="q$x INTEGER,";
        }

        for($x=1;$x<$tnum+1;$x++){
            $class_sql.="t$x INTEGER,";
        }
        
        $class_sql.="f INTEGER,";

        for ($x=1;$x<$misc1num+1;$x++) {
            $class_sql.="misc1$x INTEGER,";
        }

        for ($x=1;$x<$misc2num+1;$x++) {
            $class_sql.="misc2$x INTEGER,";
        }

        for ($x=1;$x<$misc3num+1;$x++) {
            $class_sql.="misc3$x INTEGER,";
        }

        $class_sql = substr($class_sql, 0, -1);
        $class_sql.=");";
        pg_query($db,$class_sql);
        echo "make class table:";
        echo pg_last_error();
        
        pg_query($db,"INSERT INTO '$new_class' (USER) VALUES ($user);");
        echo "insert user:";
        echo pg_last_error();

        session_unset();
        $_SESSION['verifiedUser']=$user;
        
        // header("Location: https://gradephd.herokuapp.com/user.php?message=Class Added");
        // exit();
    }
    
    ?>
    </center>
    </p>
    
    </body>
    </html>
   