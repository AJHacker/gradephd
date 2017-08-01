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

    $hw_diff=($_POST['hwweight']=='different');
    $l_diff=($_POST['lweight']=='different');
    $q_diff=($_POST['qweight']=='different');
    $t_diff=($_POST['tweight']=='different');
    $misc1_diff=($_POST['misc1weight']=='different');
    $misc2_diff=($_POST['misc2weight']=='different');
    $misc3_diff=($_POST['misc3weight']=='different');

    $class_exists=$_SESSION['class_exists']; //Class table exists
    $class_correct=$_GET['class_correct']; //Existing class is correct. Set by plot.php or other display page


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
            header("Location: https://gradephd.herokuapp.com/plot.php?class=$s&search=1");
            exit();
        } elseif ($class_correct) {
            $s=str_replace("0xDEADBEEF","-",$new_class);
            session_unset();
            header("Location: https://gradephd.herokuapp.com/plot.php?class=$s");
        } elseif (!empty($_SESSION['dupl_classes'])) {
            print_r($_SESSION['dupl_classes']);
            echo "<a href='https://gradephd.herokuapp.com/search.php'>next</a>";
            header("Location: https://gradephd.herokuapp.com/search.php");
            exit();
        } else {
            $_SESSION['class_exists']=null;
            echo "No Matching Classes Found";
        }

    }
    if ($coursenum && $semester && $prof && !$new_class) {
        if (!$skip) {
            $class_name=$prof."_".$semester."_".$coursenum;
            $class_name=str_replace("-","0xDEADBEEF",$class_name);
            $_SESSION['new_class']=$class_name;

            //Check if the class already exists
            $check_dupl="SELECT NAME FROM ALL_CLASSES WHERE NAME LIKE '$class_name%';";
            $result=pg_query($db,$check_dupl);
            $numfound=pg_num_rows($result);
            if ($numfound>0) {
                $_SESSION['numfound']=$numfound;
                $_SESSION['class_exists']=true;
                $_SESSION['dupl_classes']=pg_fetch_all($result);
                print_r($_SESSION['dupl_classes']);
                echo "<a href='https://gradephd.herokuapp.com/search.php'>next</a>";
                header("Location: https://gradephd.herokuapp.com/search.php");
                exit();
            } else {
                echo "No Matching Classes Found";
            }
        }
    }
?>
</center>

</body>
</html>
   