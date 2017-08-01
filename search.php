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
    $new_class  = $_SESSION['new_class'];


    //Get Post Variables for Course Information
    $coursenum  = $_POST['coursenum'];
    $semester   = $_POST['semester'];
    $prof       = $_POST['prof'];
    $porp       = $_POST['porp'];

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
    if (!$coursenum && !$semester && !$prof && !$porp && !$new_class) {
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
    } elseif (($coursenum && $semester && $prof && $porp && !$new_class) || ($class_exists && $skip)) {
        if (!$skip) {
            $class_name=$prof."_".$semester."_".$coursenum;
            $class_name=str_replace("-","0xDEADBEEF",$class_name);
            $_SESSION['new_class']=$class_name;


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

            //Check if the class already exists
            $check_dupl="SELECT NAME FROM ALL_CLASSES WHERE NAME LIKE '$class_name%';";
            $result=pg_query($db,$check_dupl);
            $numfound=pg_num_rows($result);
            if ($numfound>0) {
                $_SESSION['numfound']=$numfound;
                $_SESSION['class_exists']=true;
                $_SESSION['porp']=$porp;
                $_SESSION['dupl_classes']=pg_fetch_all($result);
                print_r($_SESSION['dupl_classes']);
                echo "<a href='https://gradephd.herokuapp.com/class.php'>next</a>";
                header("Location: https://gradephd.herokuapp.com/class.php");
                exit();
            } else {
                $_SESSION['class_exists']=false;
            }
        } else $porp=$_SESSION['porp'];

?>
</center>

</body>
</html>
   