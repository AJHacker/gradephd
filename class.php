<?php
    session_start();
    
?>
<html>
<head>
<link rel="stylesheet" href="styles/global.css">
<script type="text/javascript">


    
</script>
</head>
<body>
<div class='topbar'><a class="button topRight" href="/user.php">Cancel</a></div>
<div class='container'>
<center>
<?php

    //Initiate Session User Variable and Class Variables
    $user       = $_SESSION["verifiedUser"];
    $new_class  = $_SESSION['new_class'];

    //Make Sure User is Logged in
    if (!$user) {
        header("Location: https://gradephd.herokuapp.com/login.php?error=Please Login First"); 
        exit();
    }    

    //Get Post Variables for Course Information
    $coursenum  = $_POST['coursenum'];
    $semester   = $_POST['semester'];
    $prof       = $_POST['prof'];

    $hw_diff=($_POST['hwweight']=='different');
    $l_diff=($_POST['lweight']=='different');
    $q_diff=($_POST['qweight']=='different');
    $t_diff=($_POST['tweight']=='different');
    $misc1_diff=($_POST['misc1weight']=='different');
    $misc2_diff=($_POST['misc2weight']=='different');
    $misc3_diff=($_POST['misc3weight']=='different');

    $class_exists=$_SESSION['class_exists']; //Class table exists
    $class_correct=$_GET['class_correct']; //Existing class is correct. Set by plot.php or other display page

    $skip=false; //Skip to middle of form because existing class is incorrect
    
    function pg_connection_string_from_database_url() {
        extract(parse_url($_ENV["DATABASE_URL"]));
        return "user=$user password=$pass host=$host dbname=" . substr($path, 1);
    }

    $db = pg_connect(pg_connection_string_from_database_url());

    if ($_SESSION['class_exists']) {
        if ($class_correct===null) {
            //Display class info by redirecting to plot.php or something
            $_SESSION['new_class']=array_pop($_SESSION['dupl_classes'])['name'];
            $new_class=$_SESSION['new_class'];
            $s=str_replace("0xDEADBEEF","-",$new_class);
            header("Location: https://gradephd.herokuapp.com/plot.php?class=$s&syl_only=1");
            exit();

        } elseif ($class_correct) {
            //Add user to class and class to USERS
            add_class($db,$user,$new_class);
            //Unset
            session_unset();
            $_SESSION['verifiedUser']=$user;
            header("Location: https://gradephd.herokuapp.com/user.php?message=Class Added");
            exit();
        } elseif (!empty($_SESSION['dupl_classes'])) {
            print_r($_SESSION['dupl_classes']);
            echo "<a href='https://gradephd.herokuapp.com/class.php'>next</a>";
            header("Location: https://gradephd.herokuapp.com/class.php");
            exit();
        } else {
            $skip=true;
            $_SESSION['class_exists']=null;
        }

    }
    if (!$coursenum && !$semester && !$prof && !$new_class) {
        echo "
        <form action='/class.php' method='post'>
            <div class='left'>Course Number: <br>
                Semester: <br>
                Professor: <br>
            </div>
            <div class='right'>
                <input class = 'textinput' type='text' name='coursenum' placeholder='18-100' pattern='[a-zA-Z0-9!@#$%^*_|]{1,25}' required>
                <br>
                
                <select class='textinput' name='semester'>
                    <option value='F17'>F17</option>
                    <option value='S18'>S18</option>
                    <option value='F18'>F18</option>
                    <option value='S19'>S19</option>
                    <option value='F19'>F19</option>
                    <option value='S20'>S20</option>
                </select>
                <br>
                <input type='text' class='textinput' name='prof' placeholder='Sullivan' pattern='[a-zA-Z0-9!@#$%^*_|]{1,25}' required>
                <br>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <input class='button' type='submit' value='Submit'>
        </form> 
        ";
    } elseif (($coursenum && $semester && $prof && !$new_class) || ($class_exists && $skip)) {
        if (!$skip) {
            $class_name=$prof."_".$semester."_".$coursenum;
            $class_name=str_replace("-","0xDEADBEEF",$class_name);
            $_SESSION['new_class']=$class_name;

            /* Basically Dead Code since duplicate classes have different table names

            //Check if user already in class
            $check_user="SELECT NAME FROM $class_name WHERE NAME='$user';";
            $result=pg_query($db,$check_user);
            if (pg_num_rows($result)>0) {
                //Unset
                session_unset();
                $_SESSION['verifiedUser']=$user;
                header("Location: https://gradephd.herokuapp.com/user.php?message=You are already enrolled in that class.");
                exit();
            }
            */

            //Check if the class already exists
            $check_dupl="SELECT NAME FROM ALL_CLASSES WHERE NAME LIKE '$class_name%';";
            $result=pg_query($db,$check_dupl);
            $numfound=pg_num_rows($result);
            if ($numfound>0) {
                $_SESSION['numfound']=$numfound;
                $_SESSION['class_exists']=true;
                $_SESSION['dupl_classes']=pg_fetch_all($result);
                print_r($_SESSION['dupl_classes']);
                echo "<a href='https://gradephd.herokuapp.com/class.php'>next</a>";
                header("Location: https://gradephd.herokuapp.com/class.php");
                exit();
            } else {
                $_SESSION['class_exists']=false;
            }
        }

        function echoFieldset($legend,$abbrev,$misc) {
            echo "<fieldset>
                <legend>$legend</legend>";
            if ($misc) echo "Name of category: <input type=text name='${abbrev}name'><br>";
            echo "
                How many? <input type=number name='${abbrev}num'  value = '0'><br>
                Total percentage of final grade?  <input type=number name='${abbrev}percent' value = '0'>%<br>
                <input value='same' name = '${abbrev}weight' type='radio' checked>
                All same weight and Lowest <input type=number name='${abbrev}numwc' value='0'> are <input type=number name='${abbrev}wcp' value='100'>% normal weight.<br>
                <input value='different' name = '${abbrev}weight' type='radio'>Different weights for all
              
            </fieldset>";
        }

        //Class doesn't exist
        echo "
        <form action='/class.php' method='post'>";
        echoFieldset("Homeworks", "hw");
        echoFieldset("Labs", "l");
        echoFieldset("Quizzes", "q");
        echoFieldset("Tests", "t");
        echo "
            <fieldset>
                <legend>Final</legend>
                Is there a final?<br>
                <input type='radio' name='fnum' value = '1'>Yes
                <input type='radio' name='fnum' value = '0' checked>No<br>
                Total percentage of final grade?  <input type=number name='fpercent' value = '0'>%
            </fieldset>";
        echoFieldset("Other 1 (optional)", "misc1", true);
        echoFieldset("Other 2 (optional)", "misc2", true);
        echoFieldset("Other 3 (optional)", "misc3", true);
        echo "
        <input class='button' type='submit' value='Submit'>
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
        $misc1name=$_SESSION['misc1name'];
        $misc1num = $_SESSION['misc1num'];
        $misc1percent = $_SESSION['misc1percent'];
        $misc1weight = $_SESSION['misc1weight'];
        $misc1numwc = $_SESSION['misc1numwc'];
        $misc1wcp = $_SESSION['misc1wcp'];
       
        $MISC1INFO = "$misc1num|$misc1percent|$misc1weight";
        if ($misc1weight=="same") {
            $MISC1INFO.=",$misc1numwc,$misc1wcp";
        } else {
            for ($x=1;$x<$misc1num+1;$x++) {
                $w=$_SESSION["misc1$x"];
                $MISC1.=",$w";
            }
        }
        $MISC1INFO.="|$misc1name";

        //MISC 2
        $misc2name=$_SESSION['misc2name'];
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
        $MISC2INFO.="|$misc2name";

        //MISC 3 
        $misc3name=$_SESSION['misc3name'];
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
        $MISC3INFO.="|$misc3name";

        //Add to all_classes
        echo "Adding $user to class\n";
        $nf=$_SESSION['numfound'];
        echo "$nf\n";
        if ($nf) $new_class.="_v$nf";
        echo $new_class;
        $sql="INSERT INTO all_classes VALUES ( 
            '$new_class', 
            '$HWINFO',
            '$LABINFO',
            '$QUIZINFO',
            '$TESTINFO',
            '$FINALINFO',
            '$MISC1INFO',
            '$MISC2INFO',
            '$MISC3INFO'
            );";
        
        echo "insert class<br>";
        pg_query($db,$sql);
        echo pg_last_error();
        echo "<br>";
        
        $class_sql="CREATE TABLE $new_class (NAME     TEXT    NOT NULL    PRIMARY KEY,";
          
        for($x=1;$x<$hwnum+1;$x++){
            $class_sql.=" hw$x INTEGER,";
        }

        for ($x=1;$x<$lnum+1;$x++) {
            $class_sql.=" l$x INTEGER,";
        }

        for ($x=1;$x<$qnum+1;$x++) {
            $class_sql.=" q$x INTEGER,";
        }

        for($x=1;$x<$tnum+1;$x++){
            $class_sql.=" t$x INTEGER,";
        }
        
        for($x=1;$x<$fnum+1;$x++) {
            $class_sql.=" f INTEGER,";
        }

        for ($x=1;$x<$misc1num+1;$x++) {
            $class_sql.=" misc1$x INTEGER,";
        }

        for ($x=1;$x<$misc2num+1;$x++) {
            $class_sql.=" misc2$x INTEGER,";
        }

        for ($x=1;$x<$misc3num+1;$x++) {
            $class_sql.=" misc3$x INTEGER,";
        }
        
        //Make Class Table
        $class_sql = substr($class_sql, 0, -1);
        $class_sql.=");";
        echo $class_sql."<br>";
        pg_query($db,$class_sql);
        echo "make class table:";
        echo pg_last_error();

        add_class($db,$user,$new_class);

        session_unset();
        $_SESSION['verifiedUser']=$user;

        
        header("Location: https://gradephd.herokuapp.com/user.php?message=Class Added");
        exit();
    }

    function add_class($db,$user,$new_class) {
        //Add the user as a student in the class
        pg_query($db,"INSERT INTO $new_class (NAME) VALUES ('$user');");
        echo "insert user:";
        echo pg_last_error();

        //Add the class to the user's entry in the users table
        $get_user="SELECT * FROM users WHERE email='$user';";
        echo $get_user."<br>";
        $result=pg_query($db,$get_user);
        echo "get row:";
        $classes=pg_fetch_row($result,0);
        echo pg_last_error() . "<br>";
        $n=1;
        for ($n;$n<9;$n++) {
            if (!$classes[$n+1]) break;
        }
        $add_class_to_user="UPDATE users SET class$n='$new_class' WHERE email='$user';";
        echo $add_class_to_user."<br>";
        pg_query($db,$add_class_to_user);
        echo "set class:";
        echo pg_last_error();
    }

    ?>
    </center>
    </div>
</p>

</body>
</html>
