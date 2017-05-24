<html>
<head>
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
    
    if (!$prof && !$coursenum && !$semester) {
    
        echo "
        <form action='/create_class.php' method='post'>
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
        
            <input type='submit' value='Submit'>
        </form> 
        ");
    } elseif ($coursenum && $semester &&  {
        
        //initialize new class table
    }

?>
</center>
</p>

</body>
</html>

<!-- 15-122(name,meta,h1,h2,h3..) -> (hw:20|quz:50|,dlh,dlq,hwlt,hwlm,)


users(name, pass, class1-8) -> (dzy,123,15122,21-127)
all_classes(coursenum, semester, professor) -> (15-122, s17, iliiano)
class(hw weight, hw count, midterm weight, midterm count,....) -> 15-122s17iliano(numbers)

user(classes 1-7) -> dzy(15-122s17iliano,...)
user_grades(hw,midterm,final,lab)

 -->
