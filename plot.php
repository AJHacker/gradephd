<?php
    session_start();
?>

<html>


<head>

    <script src="plotly-latest.min.js"></script>

</head>


<body>
 

    <?php
        $class      = $_GET['class'];
        $user       = $_SESSION['verifiedUser'];
        $user_sql   = "SELECT * FROM $class WHERE user='$user';";
        $result     = pg_query($db,$user_sql);
        $A          = pg_fetch_row($result);
        
        $class_sql  = "SELECT * FROM all_classes WHERE name='$class';";
        $result     = pg_query($db,$class_sql);
        $B          = pg_fetch_row($result);
        
        $C          = explode("|",$class);
        $class_name = $C[0];
        $semester   = $C[1];
        $prof       = $C[2];
        
        
        //Homework
        $HWINFO     = explode("|",$B[1]);
        $hwnum      = $HWINFO[0];
        $hwpercent  = $HWINFO[1];
        $hwexploded = explode(",",$HWINFO[2]);
        $hwweight   = $hwexploded[0];
        if ($hwweight == "same") {
            $hwdrop     = $hwexploded[1];
            $hwdroppc   = $hwexploded[2];
        } else {
            $i          = strpos($hwexploded,",");
            $hwweights  = substr($hwexploded,$i+1);
        }
        
        
        //Lab
        $LABINFO    = explode("|",$B[2]);
        $lnum       = $LABINFO[0];
        $lpercent   = $LABINFO[1];
        $lexploded  = explode(",",$LABINFO[2]);
        $lweight    = $lexploded[0];
        if ($lweight == "same") {
            $ldrop      = $lexploded[1];
            $ldroppc    = $lexploded[2];
        } else {
            $i          = strpos($lexploded,",");
            $lweights   = substr($lexploded,$i+1);
        }
        
        //Quiz
        $QUIZINFO   = explode("|",$B[3]);
        $qnum       = $QUIZINFO[0];
        $qpercent   = $QUIZINFO[1];
        $qexploded  = explode(",",$QUIZINFO[2]);
        $qweight    = $qexploded[0];
        if ($qweight == "same") {
            $qdrop      = $qexploded[1];
            $qdroppc    = $qexploded[2];
        } else {
            $i          = strpos($qexploded,",");
            $qweights   = substr($qexploded,$i+1);
        }
        
        //Test
        $TESTINFO   = explode("|",$B[4]);
        $tnum       = $TESTINFO[0];
        $tpercent   = $TESTINFO[1];
        $texploded  = explode(",",$TESTINFO[2]);
        $tweight    = $texploded[0];
        if ($tweight == "same") {
            $tdrop      = $texploded[1];
            $tdroppc    = $texploded[2];
        } else {
            $i          = strpos($texploded,",");
            $tweights   = substr($texploded,$i+1);
        
        //Final
        $FINALINFO  = explode("|",$B[5]);        
        $fnum       = $FINALINFO[0];
        $fpercent   = $FINALINFO[1];

        //Misc1
        $MISC1INFO      = explode("|",$B[6]);
        $misc1num       = $MISC1INFO[0];
        $misc1percent   = $MISC1INFO[1];
        $misc1exploded  = explode(",",$MISC1INFO[2]);
        $misc1weight    = $misc1exploded[0];
        if ($misc1weight == "same") {
            $misc1drop      = $misc1exploded[1];
            $misc1droppc    = $misc1exploded[2];
        } else {
            $i              = strpos($misc1exploded,",");
            $misc1weights   = substr($misc1exploded,$i+1);
        }

        //Misc2
        $MISC2INFO      = explode("|",$B[6]);
        $misc2num       = $MISC2INFO[0];
        $misc2percent   = $MISC2INFO[1];
        $misc2exploded  = explode(",",$MISC2INFO[2]);
        $misc2weight    = $misc2exploded[0];
        if ($misc2weight == "same") {
            $misc2drop      = $misc2exploded[1];
            $misc2droppc    = $misc2exploded[2];
        } else {
            $i              = strpos($misc2exploded,",");
            $misc2weights   = substr($misc2exploded,$i+1);
        }
        
        //Misc3
        $MISC3INFO      = explode("|",$B[8]);
        $misc3num       = $MISC3INFO[0];
        $misc3percent   = $MISC3INFO[1];
        $misc3exploded  = explode(",",$MISC3INFO[2]);
        $misc3weight    = $misc3exploded[0];
        if ($misc3weight == "same") {
            $misc3drop      = $misc3exploded[1];
            $misc3droppc    = $misc3exploded[2];
        } else {
            $i              = strpos($misc3exploded,",");
            $misc3weights   = substr($mic3exploded,$i+1);
        }
        
        //User Scores
        $i      = 1;
        $hw     = "[".implode(",",array_slice($A,$i,$i+$hwnum))."]";
        $i      +=$hwnum;
        $lab    = "[".implode(",",array_slice($A,$i,$i+$lnum))."]";
        $i      +=$lnum;
        $quiz   = "[".implode(",",array_slice($A,$i,$i+$qnum))."]";
        $i      +=$qnum;
        $mid    = "[".implode(",",array_slice($A,$i,$i+$tnum))."]";
        $i      +=$tnum;
        $f      = "[".implode(",",array_slice($A,$i,$i+$fnum))."]";
        $i      +=$fnum;
        $misc1  = "[".implode(",",array_slice($A,$i,$i+$misc1num))."]";
        $i      +=$misc1num;
        $misc2  = "[".implode(",",array_slice($A,$i,$i+$misc2num))."]";
        $i      +=$misc2num;
        $misc3  = "[".implode(",",array_slice($A,$i,$i+$misc3num))."]";
        
    ?>

    <div id="predictor" style="width:600px;height:250px;"></div>


    <script>


    // Floating Point Error allowed in Grade Calculations (Out of 100).

    var Epsilon = 0.01;

    
    // Number of items in each category
    
    var no_hw    = 8;
    var no_quiz  = 8;
    var no_lab   = 0;
    var no_mid   = 4;
    var no_fin   = 1;
    var no_misc1 = 0;
    var no_misc2 = 0;
    var no_misc3 = 0;

    
    // Percent weight of each category contributing towards total grade
    
    var hw_perc    = 35;
    var quiz_perc  = 15;
    var lab_perc   = 0;
    var mid_perc   = 37.5;
    var fin_perc   = 12.5;
    var misc1_perc = 0;
    var misc2_perc = 0;
    var misc3_perc = 0;


    // Boolean variables to denote the mode of grading:
    // Either Variable Weights, or Drop Lowest.
    // By default, this is set to Drop Lowest.

    var variable_weights = false;
    var drop_lowest = true;


    // As a design decision, users are allowed to choose only one of the two modes:
    // Either Variable Weights, or Drop Lowest, Not Both for now.
    // The assert statement below checks this.

    Console.assert( (drop_lowest && !variable_weights) || (variable_weights && !drop_lowest) );


    // Creates new boolean variables for each category, which are set to true
    // if entries in that category have variable weights, false otherwise.

    var hw_vw    = false;
    var quiz_vw  = false;
    var lab_vw   = false;
    var mid_vw   = false;
    var fin_vw   = false;
    var misc1_vw = false;
    var misc2_vw = false;
    var misc3_vw = false;


    // Number of lowest items of each category that are 'dropped'
    
    var hw_drop    = 1;
    var quiz_drop  = 2;
    var lab_drop   = 0;
    var mid_drop   = 1;
    var fin_drop   = 0;
    var misc1_drop = 0;
    var misc2_drop = 0;
    var misc3_drop = 0;


    // Percentage to which the above lowest items are 'dropped'.
    // 0% would incdicate that they are not counted at all, 50%
    // would indicate that they are half-weighted, etc..
    
    var hw_drop_perc    = 0;
    var quiz_drop_perc  = 0;
    var lab_drop_perc   = 0;
    var mid_drop_perc   = 0;
    var fin_drop_perc   = 0;
    var misc1_drop_perc = 0;
    var misc2_drop_perc = 0;
    var misc3_drop_perc = 0;


    // Function to return a new array of length 'size', 
    // containing 'val' as each of its values.

    var new_array = function (size, val) {
        var arr = new Array(size);
        arr.forEach(function (item, index) {
            arr[index] = val;
        });
        return arr;
    }


    // Values of each category, stored in arrays
    
    var hw    = [90, 92, 86, 100, 99, 96, 95, 95];
    var quiz  = [80, 60, 80, 60, 80, 60, 80, 60];
    var lab   = [];
    var mid   = [100, 77, 78, 94];
    var fin   = [96];
    var misc1 = [];
    var misc2 = [];
    var misc3 = [];


    // Weights of each item in each category

    var hw_weight = new_array (8, 100/8);
    var quiz_weight = new_array (8, 100/8);
    var lab_weight = [];
    var mid_weight = new_array (4, 100/4);
    var fin_weight = [100];
    var misc1_weight = [];
    var misc2_weight = [];
    var misc3_weight = [];


    // Function to verify that an array holding weights is 'valid'.
    // Returns true if it is valid, returns false and logs an error
    // otherwise.

    var verify_weights = function (arr) {

        arr.forEach(function (item, index) {
            Console.assert(arr[index] >= 0 && arr[index] <= 100);
            if(arr[index] < 0 || arr[index] > 100) return false;
        });

        s = arr.reduce((a, b) => a + b, 0);
        
        Console.assert(Math.abs(s - 100) <= Epsilon);
        return Math.abs(s - 100) <= Epsilon;
    }


    // Function to verify that each entry in each array is valid.

    var verify = function (arr) {
        // TODO -> LATER
        ;
    }


    // TODO -> MAKE A WEIGHED MEAN FUNCTION

    // Function to count the mean of the non-null values in an array.
    // Returns "Don't Count" if all values in the array are 'null'.

    var mean = function (arr) {
        var Sum = 0;
        var Count = 0;
        arr.forEach(function (item, index) {
            if (item !== null) {
                Sum += item;
                Count += 1;
            }
        });
        return Count === 0 ? "Don't Count" : Sum/Count;
    };


    // Calculates the mean score in each category, based on the values entered.

    var hw_mean    = mean (hw);
    var quiz_mean  = mean (quiz);
    var lab_mean   = mean (lab);
    var mid_mean   = mean (mid);
    var fin_mean   = mean (fin);
    var misc1_mean = mean (misc1);
    var misc2_mean = mean (misc2);
    var misc3_mean = mean (misc3);


    // Boolean values, which if true, indicate that the respective category will
    // count towards the total, otherwise not.

    var take_hw    = (hw_mean    !== "Don't Count");
    var take_quiz  = (quiz_mean  !== "Don't Count");
    var take_lab   = (lab_mean   !== "Don't Count");
    var take_mid   = (mid_mean   !== "Don't Count");
    var take_fin   = (fin_mean   !== "Don't Count");
    var take_misc1 = (misc1_mean !== "Don't Count");
    var take_misc2 = (misc2_mean !== "Don't Count");
    var take_misc3 = (misc3_mean !== "Don't Count");


    // Function to assign 'val' in place of each 'null' value in 'arr'

    var assign_null = function (arr, val) {
        arr.forEach(function (item, index) {
            if(arr[index] === null) arr[index] = val;
        });
    };


    // Assign values to the places that the user left empty during the 
    // input process, only if that given category counts towards the total.

    if (take_hw)    assign_null (hw, hw_mean);
    if (take_quiz)  assign_null (quiz, quiz_mean);
    if (take_lab)   assign_null (lab, lab_mean);
    if (take_mid)   assign_null (mid, mid_mean);
    if (take_fin)   assign_null (fin, fin_mean);
    if (take_misc1) assign_null (misc1, misc1_mean);
    if (take_misc2) assign_null (misc2, misc2_mean);
    if (take_misc3) assign_null (misc3, misc3_mean);


    // Returns a Comparison Function, which returns -1 if 'a' should 
    // come before 'b', 0 if they are the 'same', and 1 otherwise.
    // This depends on whether the input boolean variable 'ascending' is set
    // to true or false respectively. This comparison function ensures that
    // null values in the input array, if any, always end up at the end of 
    // the sorted array.

    var order = function (ascending) {
        return function(a, b) {
            if (a === null) return 1;
            else if (b === null) return -1;
            else if (a === b) return 0;
            else if (ascending) return a < b ? -1 : 1;
            else if (!ascending) return a < b ? 1 : -1;
            else console.log("Error while sorting values!");
        };
    };


    // Function to "drop" the lowest 'no' values from 'arr',
    // to 'perc' percent of their current values, without
    // rearranging the elements of the array.

    var drop = function(arr, no, perc) {
        
        console.assert(0 <= no && no <= arr.length);
        console.assert(0 <= perc);

        var arr2 = arr.slice();
        arr2.forEach(function (item, index) {
            arr[index] = index;
        });

        var arr3 = arr.slice().sort(order(true)).slice(0, no);
        
        var thresh = 0;
        while(thresh < no) {
            arr2[arr2.findIndex(x => x === arr3[thresh])] = -1;
            thresh += 1;
        }



    };


    // The below operations are performed only if the user had chosen
    // the Drop Lowest Grade(s) option.
    // Drops the lowest 'x' grades to 'y' percent, as indicated by the user.
    
    if (drop_lowest) {
        if (take_hw)    drop (hw, hw_drop, hw_drop_perc);
        if (take_quiz)  drop (quiz, quiz_drop, quiz_drop_perc);
        if (take_lab)   drop (lab, lab_drop, lab_drop_perc);
        if (take_mid)   drop (mid, mid_drop, mid_drop_perc);
        if (take_fin)   drop (fin, fin_drop, fin_drop_perc);
        if (take_misc1) drop (misc1, misc1_drop, misc1_drop_perc);
        if (take_misc2) drop (misc2, misc2_drop, misc2_drop_perc);
        if (take_misc3) drop (misc3, misc3_drop, misc3_drop_perc);
    } 

    // Otherwise, the user must have chosen the Variable Weights option.
    // Assings the scores of each category, while taking into account 
    // the variable weights.

    else {
        Console.assert(variable_weights);


    }


    // Create arrays that store the different 'ideal' curves for each 
    // category too.

    var hw_0   = assign_null (hw.slice(), 0);
    var hw_25  = assign_null (hw.slice(), 25);
    var hw_50  = assign_null (hw.slice(), 50);
    var hw_75  = assign_null (hw.slice(), 75);
    var hw_100 = assign_null (hw.slice(), 100);

    var quiz_0   = assign_null (quiz.slice(), 0);
    var quiz_25  = assign_null (quiz.slice(), 25);
    var quiz_50  = assign_null (quiz.slice(), 50);
    var quiz_75  = assign_null (quiz.slice(), 75);
    var quiz_100 = assign_null (quiz.slice(), 100);

    var lab_0   = assign_null (lab.slice(), 0);
    var lab_25  = assign_null (lab.slice(), 25);
    var lab_50  = assign_null (lab.slice(), 50);
    var lab_75  = assign_null (lab.slice(), 75);
    var lab_100 = assign_null (lab.slice(), 100);

    var mid_0   = assign_null (mid.slice(), 0);
    var mid_25  = assign_null (mid.slice(), 25);
    var mid_50  = assign_null (mid.slice(), 50);
    var mid_75  = assign_null (mid.slice(), 75);
    var mid_100 = assign_null (mid.slice(), 100);

    var fin_0   = assign_null (fin.slice(), 0);
    var fin_25  = assign_null (fin.slice(), 25);
    var fin_50  = assign_null (fin.slice(), 50);
    var fin_75  = assign_null (fin.slice(), 75);
    var fin_100 = assign_null (fin.slice(), 100);

    var misc1_0   = assign_null (misc1.slice(), 0);
    var misc1_25  = assign_null (misc1.slice(), 25);
    var misc1_50  = assign_null (misc1.slice(), 50);
    var misc1_75  = assign_null (misc1.slice(), 75);
    var misc1_100 = assign_null (misc1.slice(), 100);

    var misc2_0   = assign_null (misc2.slice(), 0);
    var misc2_25  = assign_null (misc2.slice(), 25);
    var misc2_50  = assign_null (misc2.slice(), 50);
    var misc2_75  = assign_null (misc2.slice(), 75);
    var misc2_100 = assign_null (misc2.slice(), 100);

    var misc3_0   = assign_null (misc3.slice(), 0);
    var misc3_25  = assign_null (misc3.slice(), 25);
    var misc3_50  = assign_null (misc3.slice(), 50);
    var misc3_75  = assign_null (misc3.slice(), 75);
    var misc3_100 = assign_null (misc3.slice(), 100);


    // Actual Plotter


    PREDICTOR = document.getElementById('predictor');
    Plotly.plot( PREDICTOR, [{
    x: [1, 2, 3, 4, 5],
    y: [1, 2, 4, 8, 16] }], {
    margin: { t: 0 } } );
    

    </script>


</body>


</html>