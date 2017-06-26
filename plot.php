
<?php
    session_start();
?>

<html>


<head>
    <link rel="stylesheet" href="styles/plot.css">

    <script src="plotly-latest.min.js"></script>



</head>


<body>

    <center>
        <h1>Welcome To Grade PHD</h1>
        <h3>Your one stop shop for all grade predicting needs</h3>
    </center>

    <?php
        function pg_connection_string_from_database_url() {
            extract(parse_url($_ENV["DATABASE_URL"]));
            return "user=$user password=$pass host=$host dbname=" . substr($path, 1); 
        }
        $db = pg_connect(pg_connection_string_from_database_url());

        $class      = $_GET['class'];
        $user       = $_SESSION['verifiedUser'];
        
            
        $class   = str_replace("-","0xDEADBEEF",$class);
        $class_at = $class;
        
        $class_sql  = "SELECT * FROM all_classes WHERE name='$class';";
        $result     = pg_query($db,$class_sql);
        $B          = pg_fetch_row($result);
        
        $class      =str_replace("0xDEADBEEF","-",$class);
        $C          = explode("_",$class);
        $class_name = $C[2];
        $semester   = $C[1];
        $prof       = $C[0];

        echo "<center>Currently predicting the grade for $class_name taught by $prof for the $semester semester<br></center>";
        if($user){
            echo "<center><h4>Welcome Back, $user</h4></center>";
        }else{
            echo "<center><h4>You're not logged in. <a href = '/login.php'>Log in to save your grades</a></h4></center>";
        }

        //Homework
        $HWINFO     = explode("|",$B[1]);
        $hwnum      = $HWINFO[0];
        $hwpercent  = $HWINFO[1];
        $hwexploded = explode(",",$HWINFO[2]);
        $hwweight   = $hwexploded[0];
        if ($hwweight == "same") {
            $hwdrop     = $hwexploded[1];
            $hwdroppc   = $hwexploded[2];
            $hwweights="[]";
        } else {
            $hwdrop=0;
            $hwdroppc=0;
            $i          = strpos($hwexploded,",");
            $hwweights  = "[".substr($hwexploded,$i+1)."]";
        }
        $hwweight=($hwexploded[0]=="different");
        
        
        //Lab
        $LABINFO    = explode("|",$B[2]);
        $lnum       = $LABINFO[0];
        $lpercent   = $LABINFO[1];
        $lexploded  = explode(",",$LABINFO[2]);
        $lweight    = $lexploded[0];
        if ($lweight == "same") {
            $ldrop      = $lexploded[1];
            $ldroppc    = $lexploded[2];
            $lweights="[]";
        } else {
            $ldrop=0;
            $ldroppc=0;
            $i          = strpos($lexploded,",");
            $lweights   = "[".substr($lexploded,$i+1)."]";
        }
        $lweight=($lexploded[0]=="different");
        
        //Quiz
        $QUIZINFO   = explode("|",$B[3]);
        $qnum       = $QUIZINFO[0];
        $qpercent   = $QUIZINFO[1];
        $qexploded  = explode(",",$QUIZINFO[2]);
        $qweight    = $qexploded[0];
        if ($qweight == "same") {
            $qdrop      = $qexploded[1];
            $qdroppc    = $qexploded[2];
            $qweights="[]";
        } else {
            $qdrop=0;
            $qdroppc=0;
            $i          = strpos($qexploded,",");
            $qweights   = "[".substr($qexploded,$i+1)."]";
        }
        $qweight=($qexploded[0]=="different");
        
        //Test
        $TESTINFO   = explode("|",$B[4]);
        $tnum       = $TESTINFO[0];
        $tpercent   = $TESTINFO[1];
        $texploded  = explode(",",$TESTINFO[2]);
        $tweight    = $texploded[0];
        if ($tweight == "same") {
            $tdrop      = $texploded[1];
            $tdroppc    = $texploded[2];
            $tweights="[]";
        } else {
            $tdrop=0;
            $tdroppc=0;
            $i          = strpos($texploded,",");
            $tweights   = "[".substr($texploded,$i+1)."]";
        }
        $tweight=($texploded[0]=="different");
        
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
            $misc1weights="[]";
        } else {
            $misc1drop=0;
            $misc1droppc=0;
            $i              = strpos($misc1exploded,",");
            $misc1weights   = "[".substr($misc1exploded,$i+1)."]";
        }
        $misc1name=$MISC1INFO[3];
        $misc1weight=($misc1exploded[0]=="different");

        //Misc2
        $MISC2INFO      = explode("|",$B[7]);
        $misc2num       = $MISC2INFO[0];
        $misc2percent   = $MISC2INFO[1];
        $misc2exploded  = explode(",",$MISC2INFO[2]);
        $misc2weight    = $misc2exploded[0];
        if ($misc2weight == "same") {
            $misc2drop      = $misc2exploded[1];
            $misc2droppc    = $misc2exploded[2];
            $misc2weights="[]";
        } else {
            $misc2drop=0;
            $misc2droppc=0;
            $i              = strpos($misc2exploded,",");
            $misc2weights   = "[".substr($misc2exploded,$i+1)."]";
        }
        $misc2name=$MISC2INFO[3];
        $misc2weight=($misc2exploded[0]=="different");
        
        //Misc3
        $MISC3INFO      = explode("|",$B[8]);
        $misc3num       = $MISC3INFO[0];
        $misc3percent   = $MISC3INFO[1];
        $misc3exploded  = explode(",",$MISC3INFO[2]);
        $misc3weight    = $misc3exploded[0];
        if ($misc3weight == "same") {
            $misc3drop      = $misc3exploded[1];
            $misc3droppc    = $misc3exploded[2];
            $misc3weights="[]";
        } else {
            $misc3drop=0;
            $misc3droppc=0;
            $i              = strpos($misc3exploded,",");
            $misc3weights   = "[".substr($misc3exploded,$i+1)."]";
        }
        $misc3name=$MISC3INFO[3];
        $misc3weight=($misc3exploded[0]=="different");
        
        if ($user) {
            $user_sql   = "SELECT * FROM $class_at WHERE name='$user';";
            $result     = pg_query($db,$user_sql);
            $A          = pg_fetch_row($result);
            for ($i=0;$i<count($A);$i++) {
                if ($A[$i]===null) {
                    $A[$i]="null";
                }
            }
            
            //User Scores
            $i      = 1;
            $hw     = "[".implode(",",array_slice($A,$i,$hwnum))."]";
            $i      +=$hwnum;
            $lab    = "[".implode(",",array_slice($A,$i,$lnum))."]";
            $i      +=$lnum;
            $quiz   = "[".implode(",",array_slice($A,$i,$qnum))."]";
            $i      +=$qnum;
            $mid    = "[".implode(",",array_slice($A,$i,$tnum))."]";
            $i      +=$tnum;
            $fin      = "[".implode(",",array_slice($A,$i,$fnum))."]";
            $i      +=$fnum;
            $misc1  = "[".implode(",",array_slice($A,$i,$misc1num))."]";
            $i      +=$misc1num;
            $misc2  = "[".implode(",",array_slice($A,$i,$misc2num))."]";
            $i      +=$misc2num;
            $misc3  = "[".implode(",",array_slice($A,$i,$misc3num))."]";
        } else {
            $hw='['.str_repeat("null,", $hwnum).']';
            $lab='['.str_repeat("null,", $lnum).']';
            $quiz='['.str_repeat("null,", $qnum).']';
            $mid='['.str_repeat("null,", $tnum).']';
            $fin='['.str_repeat("null,", $fnum).']';
            $misc1='['.str_repeat("null,", $misc1num).']';
            $misc2='['.str_repeat("null,", $misc2num).']';
            $misc3='['.str_repeat("null,", $misc3num).']';
        }
        
    ?>

    <!--<div id="predictor" style="display:inline-block;position:fixed;top:0;bottom:0;left:0;right:0;width:60%;height:60%;margin:auto;"></div>-->
    <div id="predictor"></div>

    <script id="mainjs">

    // Floating Point Error allowed in Grade Calculations (Out of 100).
    
    var Epsilon = 0.00000001;
    
    // Number of items in each category

    var no_hw    = <?php echo $hwnum; ?>;
    var no_quiz  = <?php echo $qnum; ?>;
    var no_lab   = <?php echo $lnum; ?>;
    var no_mid   = <?php echo $tnum; ?>;
    var no_fin   = <?php echo $fnum; ?>;
    var no_misc1 = <?php echo $misc1num; ?>;
    var no_misc2 = <?php echo $misc2num; ?>;
    var no_misc3 = <?php echo $misc3num; ?>;


    // Names of miscellaneous categories
    
    if (no_misc1 > 0) var misc1_name = <?php echo "'$misc1name'"; ?>;
    if (no_misc2 > 0) var misc2_name = <?php echo "'$misc2name'"; ?>;
    if (no_misc3 > 0) var misc3_name = <?php echo "'$misc3name'"; ?>;

    
    // Percent weight of each category contributing towards total grade

    var hw_perc    = <?php echo $hwpercent; ?>;
    var quiz_perc  = <?php echo $qpercent; ?>;
    var lab_perc   = <?php echo $lpercent; ?>;
    var mid_perc   = <?php echo $tpercent; ?>;
    var fin_perc   = <?php echo $fpercent; ?>;
    var misc1_perc = <?php echo $misc1percent; ?>;
    var misc2_perc = <?php echo $misc2percent; ?>;
    var misc3_perc = <?php echo $misc3percent; ?>;
    

    // Function to return a new array of length 'size', 
    // containing 'val' as each of its values.
    
    var new_array = function (size, val) {
        var arr = [];
        for(var i = 0; i < size; i++) {
            arr.push(val);
        }
        return arr;
    };


    // Function which sums two 'numbers', while considering the
    // the 'null' value to be the number 0.

    var sum_null = function (a, b) {
        if (a === null && b === null) return 0;
        else if (a === null) return b;
        else if (b === null) return a;
        else return a + b;
    };


    // Function to verify that an array holding weights is 'valid'.
    // Returns true if it is valid, returns false and logs an error
    // otherwise.
    
    var verify_weights = function (arr) {
        if (arr.length === 0) return arr;
        s = 0;
        arr.forEach(function (item, index) {
            if (arr [index] !== null) {
                console.assert(arr[index] >= 0 && arr[index] <= 100);
                if(arr[index] < 0 || arr[index] > 100) return false;
            }
            s = sum_null(s, arr[index]);
        });
        console.assert(Math.abs(s - 100) <= Epsilon);
        return arr;
    };


    // Arrays representing respective weights of each item in each category.

    var hw_weight    = no_hw    === 0 ? [] : verify_weights(new_array (no_hw   , 100/no_hw   ));
    var quiz_weight  = no_quiz  === 0 ? [] : verify_weights(new_array (no_quiz , 100/no_quiz ));
    var lab_weight   = no_lab   === 0 ? [] : verify_weights(new_array (no_lab  , 100/no_lab  ));
    var mid_weight   = no_mid   === 0 ? [] : verify_weights(new_array (no_mid  , 100/no_mid  ));
    var fin_weight   = no_fin   === 0 ? [] : verify_weights(new_array (no_fin  , 100/no_fin  ));
    var misc1_weight = no_misc1 === 0 ? [] : verify_weights(new_array (no_misc1, 100/no_misc1));
    var misc2_weight = no_misc2 === 0 ? [] : verify_weights(new_array (no_misc2, 100/no_misc2));
    var misc3_weight = no_misc3 === 0 ? [] : verify_weights(new_array (no_msic3, 100/no_misc3));


    // Creates new boolean variables for each category, which are set to true
    // if entries in that category have variable weights, false otherwise.

    var hw_vw    = <?php echo $hwweight? 'true' : 'false'; ?>;
    var quiz_vw  = <?php echo $qweight? 'true' : 'false'; ?>;
    var lab_vw   = <?php echo $lweight? 'true' : 'false'; ?>;
    var mid_vw   = <?php echo $tweight? 'true' : 'false'; ?>;
    var fin_vw   = false;
    var misc1_vw = <?php echo $misc1weight? 'true' : 'false'; ?>;
    var misc2_vw = <?php echo $misc2weight? 'true' : 'false'; ?>;
    var misc3_vw = <?php echo $misc3weight? 'true' : 'false'; ?>;



    // Values of each category, stored in arrays

        var hw    = <?php echo $hw; ?>;
        var quiz  = <?php echo $quiz; ?>;
        var lab   = <?php echo $lab; ?>;
        var mid   = <?php echo $mid; ?>;
        var fin   = <?php echo $fin; ?>;
        var misc1 = <?php echo $misc1; ?>;
        var misc2 = <?php echo $misc2; ?>;
        var misc3 = <?php echo $misc3; ?>;


    var arr_nv = {'HW': no_hw, 'Lab': no_lab, 'Quiz': no_quiz, 'Test': no_mid, 'Final': no_fin, misc1_name: no_misc1, misc2_name: no_misc2, misc3_name: no_misc3};

    var arr_nv2 = {'HW': hw, 'Lab': lab, 'Quiz': quiz, 'Test': mid, 'Final': fin, misc1_name: misc1, misc2_name: misc2, misc3_name: misc3};

    var arr_names = [];
    if (no_hw > 0) arr_names = arr_names.concat('HW');
    if (no_lab > 0) arr_names = arr_names.concat('Lab');
    if (no_quiz > 0) arr_names = arr_names.concat('Quiz');
    if (no_mid > 0) arr_names = arr_names.concat('Test');
    if (no_fin > 0) arr_names = arr_names.concat('Final');
    if (no_misc1 > 0) arr_names = arr_names.concat(misc1_name);
    if (no_misc2 > 0) arr_names = arr_names.concat(misc2_name);
    if (no_misc3 > 0) arr_names = arr_names.concat(misc3_name);

    var arr_nums = [no_hw, no_quiz, no_lab, no_mid, no_fin, no_misc1, no_misc2, no_misc3];

    var max_num = arr_nums.reduce(function(a, b) {
        return Math.max(a, b);
    });

    const reinput = function() {
//        if (document.getElementById("cooljs").name === null || document.getElementById("cooljs").value === null) return;
//        arr_names.forEach(function (item, index) {
//            var nums = [...Array(arr_nv[arr_names[index]]).keys()];
//            nums.forEach(function (it, i) {
//                if (document.getElementById("cooljs").name === document.getElementsByName(arr_names[index] + " " + (nums[i] + 1))[0]) {
//                    arr_nv2[arr_names[index]][nums[i]] = parseInt(document.getElementById("cooljs").value);
//                }
//            });
//        });
    };


//    const reinput = () => {
//        const num = document.getElementById("cooljs").name.match(/\d+$/);
//        const name = document.getElementById("cooljs").name.replace(/\s\d+$/, "");
//        const value = parseInt(document.getElementById("cooljs").value) - 1;
//
//        console.log("\n\n\nChange Values:\n\n\n");
//        console.log("name: " + name);
//        console.log("num: " + num);
//        console.log("value: " + value);
//        console.log('\n\n\n');
//
//        if (name === misc1_name) {
//            misc1[num] = value;
//        } else if (name === misc2_name) {
//            misc2[num] = value;
//        } else if (name === misc3_name) {
//            misc3[num] = value;
//        } else {
//            switch (name) {
//                case 'HW':
//                    hw[num] = value;
//                    break;
//                case 'Lab':
//                    lab[num] = value;
//                    break;
//                case 'Quiz':
//                    quiz[num] = value;
//                    break;
//                case 'Test':
//                    mid[num] = value;
//                    break;
//                case 'Final':
//                    fin[num] = value;
//                    break;
//            }
//        }
//    };
    
     // Log the output arrays for each category.

    console.log("hw   :"  + hw    + " !!! " + hw_scr   );
    console.log("lab  :"  + lab   + " !!! " + lab_scr  );
    console.log("quiz : " + quiz  + " !!! " + quiz_scr );
    console.log("mid  : " + mid   + " !!! " + mid_scr  );
    console.log("fin  : " + fin   + " !!! " + fin_scr  );
    console.log("misc1: " + misc1 + " !!! " + misc1_scr);
    console.log("misc2: " + misc2 + " !!! " + misc2_scr);
    console.log("misc3: " + misc3 + " !!! " + misc3_scr);


    // Each block below is executed only if the user opted for Variable
    // Weights for that category.

    // x_drop -> Number of lowest items of each category that are 
    // 'dropped'.

    // x_drop_perc ->
    // Percentage to which the above lowest items are 'dropped'.
    // 0% would incdicate that they are not counted at all, 50%
    // would indicate that they are half-weighted, etc..

    if ( hw_vw  ) { hw_weight    = <?php echo $hwweights; ?>; var hw_drop = 0; var hw_drop_perc = 0; }
    else          { var  hw_drop = <?php echo $hwdrop; ?>; var hw_drop_perc = <?php echo $hwdroppc; ?>;}
    if ( quiz_vw  ) { quiz_weight  = <?php echo $qweights; ?>; var quiz_drop = 0; var quiz_drop_perc = 0; }
    else          { var  quiz_drop = <?php echo $qdrop; ?>; var quiz_drop_perc = <?php echo $qdroppc; ?>;}
    if ( lab_vw  ) { lab_weight    = <?php echo $lweights; ?>; var lab_drop = 0; var lab_drop_perc = 0; }
    else          { var  lab_drop  = <?php echo $ldrop; ?>; var lab_drop_perc = <?php echo $ldroppc; ?>;}
    if ( mid_vw  ) { mid_weight    = <?php echo $tweights; ?>; var mid_drop = 0; var mid_drop_perc = 0; }
    else          { var  mid_drop  = <?php echo $tdrop; ?>; var mid_drop_perc = <?php echo $tdroppc; ?>;}
    if ( fin_vw  ) { fin_weight    = []; var fin_drop = 0; var fin_drop_perc = 0; }
    else          { var  fin_drop = 0; var fin_drop_perc = 100}
    if ( misc1_vw  ) { misc1_weight = <?php echo $misc1weights; ?>; var misc1_drop = 0; var misc1_drop_perc = 0; }
    else          { var  misc1_drop = <?php echo $misc1drop; ?>; var misc1_drop_perc = <?php echo $misc1droppc; ?>;}
    if ( misc2_vw  ) { misc2_weight = <?php echo $misc2weights; ?>; var misc2_drop = 0; var misc2_drop_perc = 0; }
    else          { var  misc2_drop = <?php echo $misc2drop; ?>; var misc2_drop_perc = <?php echo $misc2droppc; ?>;}
    if ( misc3_vw  ) { misc3_weight = <?php echo $misc3weights; ?>; var misc3_drop = 0; var misc3_drop_perc = 0; }
    else          { var  misc3_drop = <?php echo $misc3drop; ?>; var misc3_drop_perc = <?php echo $misc3droppc; ?>;}


// ---------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------


       // Function to verify that each entry in each array is valid.

        var verify = function (arr) {
            // TODO -> LATER
            ;
        };


        // Function to calculate the mean of the non-null values in an array.
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


        // Function to calculate the weighted mean of the non-null values in
        // the array 'arr', based on the weights provided in the array 'weights'.
        // Returns "Don't Count" if all values in the array are 'null'.

        var weighted_mean = function (arr, weights) {
            if (JSON.stringify(verify_weights(weights)) === JSON.stringify(weights)) {
                var Sum = 0;
                var Count = 0;
                arr.forEach(function (item, index) {
                    if (item !== null) {
                        Sum += item*weights[index];
                        Count += 1;
                    }
                });
                return Count === 0 ? "Don't Count" : Sum / 100;
            }
            console.error("weighted_mean(): Weights provided are invalid: " + weights);
            return "Don't Count";
        };


        // Calculates the mean/weighted mean score in each category,
        // based on the option chosen, and the values entered.

        var hw_mean    = hw_vw    ? weighted_mean (hw,    hw_weight)    : mean (hw)   ;
        var quiz_mean  = quiz_vw  ? weighted_mean (quiz,  quiz_weight)  : mean (quiz) ;
        var lab_mean   = lab_vw   ? weighted_mean (lab,   lab_weight)   : mean (lab)  ;
        var mid_mean   = mid_vw   ? weighted_mean (mid,   mid_weight)   : mean (mid)  ;
        var fin_mean   = fin_vw   ? weighted_mean (fin,   fin_weight)   : mean (fin)  ;
        var misc1_mean = misc1_vw ? weighted_mean (misc1, misc1_weight) : mean (misc1);
        var misc2_mean = misc2_vw ? weighted_mean (misc2, misc2_weight) : mean (misc2);
        var misc3_mean = misc3_vw ? weighted_mean (misc3, misc3_weight) : mean (misc3);


        // Boolean values, which if true, indicate that the user has put in
        // enough info for a given category to be approximated, otherwise not.

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
            return arr;
        };


        // Assign values to the places that the user left empty during the
        // input process, only if that given category counts towards the total,
        // by creating arrays that store the different curves for each
        // category, to store this info in.

        if (no_hw > 0)  {   if (take_hw)
                            var hw_u      = assign_null (hw.slice()   , hw_mean   );
                            var hw_0      = assign_null (hw.slice()   , 0         );
                            var hw_25     = assign_null (hw.slice()   , 25        );
                            var hw_50     = assign_null (hw.slice()   , 50        );
                            var hw_75     = assign_null (hw.slice()   , 75        );
                            var hw_100    = assign_null (hw.slice()   , 100       );
                         }
        if (no_quiz > 0) {  if (take_quiz)
                            var quiz_u    = assign_null (quiz.slice() , quiz_mean );
                            var quiz_0    = assign_null (quiz.slice() , 0         );
                            var quiz_25   = assign_null (quiz.slice() , 25        );
                            var quiz_50   = assign_null (quiz.slice() , 50        );
                            var quiz_75   = assign_null (quiz.slice() , 75        );
                            var quiz_100  = assign_null (quiz.slice() , 100       );
                         }
        if (no_lab > 0)  {  if (take_lab)
                            var lab_u     = assign_null (lab.slice()  , lab_mean  );
                            var lab_0     = assign_null (lab.slice()  , 0         );
                            var lab_25    = assign_null (lab.slice()  , 25        );
                            var lab_50    = assign_null (lab.slice()  , 50        );
                            var lab_75    = assign_null (lab.slice()  , 75        );
                            var lab_100   = assign_null (lab.slice()  , 100       );
                        }
        if (no_mid > 0)   { if (take_mid)
                            var mid_u     = assign_null (mid.slice()  , mid_mean  );
                            var mid_0     = assign_null (mid.slice()  , 0         );
                            var mid_25    = assign_null (mid.slice()  , 25        );
                            var mid_50    = assign_null (mid.slice()  , 50        );
                            var mid_75    = assign_null (mid.slice()  , 75        );
                            var mid_100   = assign_null (mid.slice()  , 100       );
                        }
        if (no_fin > 0)   { if (take_fin)
                            var fin_u     = assign_null (fin.slice()  , fin_mean  );
                            var fin_0     = assign_null (fin.slice()  , 0         );
                            var fin_25    = assign_null (fin.slice()  , 25        );
                            var fin_50    = assign_null (fin.slice()  , 50        );
                            var fin_75    = assign_null (fin.slice()  , 75        );
                            var fin_100   = assign_null (fin.slice()  , 100       );
                        }
        if (no_misc1 > 0) { if (take_misc1)
                            var misc1_u   = assign_null (misc1.slice(), misc1_mean);
                            var misc1_0   = assign_null (misc1.slice(), 0         );
                            var misc1_25  = assign_null (misc1.slice(), 25        );
                            var misc1_50  = assign_null (misc1.slice(), 50        );
                            var misc1_75  = assign_null (misc1.slice(), 75        );
                            var misc1_100 = assign_null (misc1.slice(), 100       );
                        }
        if (no_misc2 > 0) { if (take_misc2)
                            var misc2_u   = assign_null (misc2.slice(), misc2_mean);
                            var misc2_0   = assign_null (misc2.slice(), 0         );
                            var misc2_25  = assign_null (misc2.slice(), 25        );
                            var misc2_50  = assign_null (misc2.slice(), 50        );
                            var misc2_75  = assign_null (misc2.slice(), 75        );
                            var misc2_100 = assign_null (misc2.slice(), 100       );
                        }
        if (no_misc3 > 0) { if (take_misc3)
                            var misc3_u   = assign_null (misc3.slice(), misc3_mean);
                            var misc3_0   = assign_null (misc3.slice(), 0         );
                            var misc3_25  = assign_null (misc3.slice(), 25        );
                            var misc3_50  = assign_null (misc3.slice(), 50        );
                            var misc3_75  = assign_null (misc3.slice(), 75        );
                            var misc3_100 = assign_null (misc3.slice(), 100       );
                           }


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
                else console.error("Error while sorting values!");
            };
        };


        // Function to "drop" the lowest 'no' values from 'arr',
        // to 'perc' percent of their current values, without
        // rearranging the elements of the array.

        var drop = function(arr, no, perc, weight) {

            if (no === 0) return weight;

            console.assert(0 <= no && no <= arr.length);
            console.assert(0 <= perc);

            var arr2 = arr.slice();
            arr2.forEach(function (item, index) {
                arr2[index] = index;
            });

            var arr3 = arr.slice().sort(order(true)).slice(0, no);

            var thresh = 0;
            while(thresh < no) {
                arr2[arr.findIndex(x => x === arr3[thresh])] = -1;
                thresh += 1;
            }

            var ind = 0;
            while(ind < arr.length) {
                if (arr2[ind] === -1) weight[ind] = weight[ind] * perc / 100;
                ind += 1;
            }

            return weight;
        };


        // Variables meant to eventually store the final cumulative
        // 'score' (in percent) for each category, for each curve.

        var hw_scr        = null;
        var quiz_scr      = null;
        var lab_scr       = null;
        var mid_scr       = null;
        var fin_scr       = null;
        var misc1_scr     = null;
        var misc2_scr     = null;
        var misc3_scr     = null;


        //

        var scores           = new_array(8, null);
        var scores_0         = new_array(8, null);
        var scores_25        = new_array(8, null);
        var scores_50        = new_array(8, null);
        var scores_75        = new_array(8, null);
        var scores_100       = new_array(8, null);
        var percentages_temp = new_array(8, null);

        console.log("\n\nOld weights: \n\n");
        console.log("hw   :"  + hw_weight   );
        console.log("lab  :"  + lab_weight  );
        console.log("quiz : " + quiz_weight );
        console.log("mid  : " + mid_weight  );
        console.log("fin  : " + fin_weight  );
        console.log("misc1: " + misc1_weight);
        console.log("misc2: " + misc2_weight);
        console.log("misc3: " + misc3_weight);
        console.log("\n\n");


        // The below operations are performed only if the user had chosen
        // the Drop Lowest Score(s) option.
        // Drops the lowest 'x' grades to 'y' percent, as indicated by the user.

        if (no_hw    > 0) { hw_weight    = drop (hw   , hw_drop   , hw_drop_perc   , hw_weight   ); }
        if (no_quiz  > 0) { quiz_weight  = drop (quiz , quiz_drop , quiz_drop_perc , quiz_weight ); }
        if (no_lab   > 0) { lab_weight   = drop (lab  , lab_drop  , lab_drop_perc  , lab_weight  ); }
        if (no_mid   > 0) { mid_weight   = drop (mid  , mid_drop  , mid_drop_perc  , mid_weight  ); }
        if (no_fin   > 0) { fin_weight   = drop (fin  , fin_drop  , fin_drop_perc  , fin_weight  ); }
        if (no_misc1 > 0) { misc1_weight = drop (misc1, misc1_drop, misc1_drop_perc, misc1_weight); }
        if (no_misc2 > 0) { misc2_weight = drop (misc2, misc2_drop, misc2_drop_perc, misc2_weight); }
        if (no_misc3 > 0) { misc3_weight = drop (misc3, misc3_drop, misc3_drop_perc, misc3_weight); }

        console.log("\n\nNew weights: \n\n");
        console.log("hw   :"  + hw_weight   );
        console.log("lab  :"  + lab_weight  );
        console.log("quiz : " + quiz_weight );
        console.log("mid  : " + mid_weight  );
        console.log("fin  : " + fin_weight  );
        console.log("misc1: " + misc1_weight);
        console.log("misc2: " + misc2_weight);
        console.log("misc3: " + misc3_weight);
        console.log("\n\n");

        // Function to recompute percentage weight of each element
        // in perc_temp, based on which categories are left completely
        // blank by the user (if flag is true), and for all elements
        // in the array, otherwise (if flag is false). The scores and the
        // older percentages (in 'perc_temp') are provided as input, and
        // the newly computed percentages are then returned as an array;

        var re_perc = function (scores, perc_temp, flag) {
            var total = 0;
            if (flag) {
                perc_temp.forEach (function (item, index) {
                    if (scores [index] !== null) {
                        total += perc_temp [index];
                    }
                });
                var perc = new_array (perc_temp.length, null);
                scores.forEach (function (item, index) {
                    if (scores[index] !== null) {
                        perc [index] = perc_temp [index] * 100 / total;
                    }
                });
            } else {
                perc_temp.forEach (function (item, index) {
                        total += perc_temp [index];
                });
                var perc = new_array (perc_temp.length, null);
                perc_temp.forEach (function (item, index) {
                    perc [index] = perc_temp [index] * 100 / total;
                });
            }
            return perc;
        };



        //

        if (no_hw > 0)  { hw_weight     = re_perc ([], hw_weight, false);
                          if (take_hw)
                              hw_scr        = weighted_mean (hw_u     , hw_weight   ); scores     [0] = hw_scr       ;
                          var hw_0_scr      = weighted_mean (hw_0     , hw_weight   ); scores_0   [0] = hw_0_scr     ;
                          var hw_25_scr     = weighted_mean (hw_25    , hw_weight   ); scores_25  [0] = hw_25_scr    ;
                          var hw_50_scr     = weighted_mean (hw_50    , hw_weight   ); scores_50  [0] = hw_50_scr    ;
                          var hw_75_scr     = weighted_mean (hw_75    , hw_weight   ); scores_75  [0] = hw_75_scr    ;
                          var hw_100_scr    = weighted_mean (hw_100   , hw_weight   ); scores_100 [0] = hw_100_scr   ;
                          percentages_temp [0] = hw_perc   ;
                         }
        if (no_quiz > 0){ quiz_weight   = re_perc ([], quiz_weight, false);
                          if (take_quiz)
                              quiz_scr      = weighted_mean (quiz_u   , quiz_weight ); scores     [1] = quiz_scr     ;
                          var quiz_0_scr    = weighted_mean (quiz_0   , quiz_weight ); scores_0   [1] = quiz_0_scr   ;
                          var quiz_25_scr   = weighted_mean (quiz_25  , quiz_weight ); scores_25  [1] = quiz_25_scr  ;
                          var quiz_50_scr   = weighted_mean (quiz_50  , quiz_weight ); scores_50  [1] = quiz_50_scr  ;
                          var quiz_75_scr   = weighted_mean (quiz_75  , quiz_weight ); scores_75  [1] = quiz_75_scr  ;
                          var quiz_100_scr  = weighted_mean (quiz_100 , quiz_weight ); scores_100 [1] = quiz_100_scr ;
                          percentages_temp [1] = quiz_perc ;
                         }
        if (no_lab > 0) { lab_weight    = re_perc ([], lab_weight, false);
                          if (take_lab)
                              lab_scr       = weighted_mean (lab_u    , lab_weight  ); scores     [2] = lab_scr      ;
                          var lab_0_scr     = weighted_mean (lab_0    , lab_weight  ); scores_0   [2] = lab_0_scr    ;
                          var lab_25_scr    = weighted_mean (lab_25   , lab_weight  ); scores_25  [2] = lab_25_scr   ;
                          var lab_50_scr    = weighted_mean (lab_50   , lab_weight  ); scores_50  [2] = lab_50_scr   ;
                          var lab_75_scr    = weighted_mean (lab_75   , lab_weight  ); scores_75  [2] = lab_75_scr   ;
                          var lab_100_scr   = weighted_mean (lab_100  , lab_weight  ); scores_100 [2] = lab_100_scr  ;
                          percentages_temp [2] = lab_perc  ;
                         }
        if (no_mid > 0) { mid_weight    = re_perc ([], mid_weight, false);
                          if(take_mid)
                              mid_scr       = weighted_mean (mid_u    , mid_weight  ); scores     [3] = mid_scr      ;
                          var mid_0_scr     = weighted_mean (mid_0    , mid_weight  ); scores_0   [3] = mid_0_scr    ;
                          var mid_25_scr    = weighted_mean (mid_25   , mid_weight  ); scores_25  [3] = mid_25_scr   ;
                          var mid_50_scr    = weighted_mean (mid_50   , mid_weight  ); scores_50  [3] = mid_50_scr   ;
                          var mid_75_scr    = weighted_mean (mid_75   , mid_weight  ); scores_75  [3] = mid_75_scr   ;
                          var mid_100_scr   = weighted_mean (mid_100  , mid_weight  ); scores_100 [3] = mid_100_scr  ;
                          percentages_temp [3] = mid_perc  ;
                         }
        if (no_fin > 0){  fin_weight    = re_perc ([], fin_weight, false);
                          if (take_fin)
                              fin_scr       = weighted_mean (fin_u    , fin_weight  ); scores     [4] = fin_scr      ;
                          var fin_0_scr     = weighted_mean (fin_0    , fin_weight  ); scores_0   [4] = fin_0_scr    ;
                          var fin_25_scr    = weighted_mean (fin_25   , fin_weight  ); scores_25  [4] = fin_25_scr   ;
                          var fin_50_scr    = weighted_mean (fin_50   , fin_weight  ); scores_50  [4] = fin_50_scr   ;
                          var fin_75_scr    = weighted_mean (fin_75   , fin_weight  ); scores_75  [4] = fin_75_scr   ;
                          var fin_100_scr   = weighted_mean (fin_100  , fin_weight  ); scores_100 [4] = fin_100_scr  ;
                          percentages_temp [4] = fin_perc  ;
                         }
        if (no_misc1 > 0){misc1_weight  = re_perc ([], misc1_weight, false);
                          if (take_misc1)
                              misc1_scr     = weighted_mean (misc1_u  , misc1_weight); scores     [5] = misc1_scr    ;
                          var misc1_0_scr   = weighted_mean (misc1_0  , misc1_weight); scores_0   [5] = misc1_0_scr  ;
                          var misc1_25_scr  = weighted_mean (misc1_25 , misc1_weight); scores_25  [5] = misc1_25_scr ;
                          var misc1_50_scr  = weighted_mean (misc1_50 , misc1_weight); scores_50  [5] = misc1_50_scr ;
                          var misc1_75_scr  = weighted_mean (misc1_75 , misc1_weight); scores_75  [5] = misc1_75_scr ;
                          var misc1_100_scr = weighted_mean (misc1_100, misc1_weight); scores_100 [5] = misc1_100_scr;
                          percentages_temp [5] = misc1_perc;
                         }
        if (no_misc2 > 0){misc2_weight  = re_perc ([], misc2_weight, false);
                          if (take_misc2)
                              misc2_scr     = weighted_mean (misc2_u  , misc2_weight); scores     [6] = misc2_scr    ;
                          var misc2_0_scr   = weighted_mean (misc2_0  , misc2_weight); scores_0   [6] = misc2_0_scr  ;
                          var misc2_25_scr  = weighted_mean (misc2_25 , misc2_weight); scores_25  [6] = misc2_25_scr ;
                          var misc2_50_scr  = weighted_mean (misc2_50 , misc2_weight); scores_50  [6] = misc2_50_scr ;
                          var misc2_75_scr  = weighted_mean (misc2_75 , misc2_weight); scores_75  [6] = misc2_75_scr ;
                          var misc2_100_scr = weighted_mean (misc2_100, misc2_weight); scores_100 [6] = misc2_100_scr;
                          percentages_temp [6] = misc2_perc;
                         }
        if (no_misc3 > 0){misc3_weight  = re_perc ([], misc3_weight, false);
                          if (take_misc3)
                              misc3_scr     = weighted_mean (misc3_u  , misc3_weight); scores     [7] = misc3_scr    ;
                          var misc3_0_scr   = weighted_mean (misc3_0  , misc3_weight); scores_0   [7] = misc3_0_scr  ;
                          var misc3_25_scr  = weighted_mean (misc3_25 , misc3_weight); scores_25  [7] = misc3_25_scr ;
                          var misc3_50_scr  = weighted_mean (misc3_50 , misc3_weight); scores_50  [7] = misc3_50_scr ;
                          var misc3_75_scr  = weighted_mean (misc3_75 , misc3_weight); scores_75  [7] = misc3_75_scr ;
                          var misc3_100_scr = weighted_mean (misc3_100, misc3_weight); scores_100 [7] = misc3_100_scr;
                          percentages_temp [7] = misc3_perc;
                         }

        console.log("\n\nNew weights after re_perc: \n\n");
        console.log("hw   :"  + hw_weight   );
        console.log("lab  :"  + lab_weight  );
        console.log("quiz : " + quiz_weight );
        console.log("mid  : " + mid_weight  );
        console.log("fin  : " + fin_weight  );
        console.log("misc1: " + misc1_weight);
        console.log("misc2: " + misc2_weight);
        console.log("misc3: " + misc3_weight);
        console.log("\n\n");

        // Log the output arrays for each category.

        console.log("hw   :"  + hw_u    + " !!! " + hw_scr   );
        console.log("lab  :"  + lab_u   + " !!! " + lab_scr  );
        console.log("quiz : " + quiz_u  + " !!! " + quiz_scr );
        console.log("mid  : " + mid_u   + " !!! " + mid_scr  );
        console.log("fin  : " + fin_u   + " !!! " + fin_scr  );
        console.log("misc1: " + misc1_u + " !!! " + misc1_scr);
        console.log("misc2: " + misc2_u + " !!! " + misc2_scr);
        console.log("misc3: " + misc3_u + " !!! " + misc3_scr);


        // Apply the above re_perc() function, in order to obtain
        // the required percentages of each category in this situation,
        // and store them in 'percentages'.

        console.log("Scores: " + scores);
        console.log("Percentages Temp: " + percentages_temp);

        var percentages = re_perc (scores, percentages_temp, true);

        console.log("Percentages: " + percentages);


        // Function to calculate Total Score (in perecent), based on the
        // scores on weights for each createHash(algorithm, options);y.

        var score_calc = function (scores, weights) {
            return weighted_mean(scores, weights);
        };


        // Find total score for each curve, using the above score_calc()
        // function.

        var total     = score_calc (scores    , percentages);
        var total_0   = score_calc (scores_0  , percentages);
        var total_25  = score_calc (scores_25 , percentages);
        var total_50  = score_calc (scores_50 , percentages);
        var total_75  = score_calc (scores_75 , percentages);
        var total_100 = score_calc (scores_100, percentages);


        // Log the output arrays, based on which plotting is to be done.

        console.log("User: " + scores     + " !!! " + total    );
        console.log("0   : " + scores_0   + " !!! " + total_0  );
        console.log("25  : " + scores_25  + " !!! " + total_25 );
        console.log("50  : " + scores_50  + " !!! " + total_50 );
        console.log("75  : " + scores_75  + " !!! " + total_75 );
        console.log("100 : " + scores_100 + " !!! " + total_100);


        // Recalculate each weight, now from the whole course's perspective

        function new_total(arr, total) {
            arr.forEach((item, index) => {
                arr[index] = arr[index] * total / 100;
            });
            return arr;
        }

        if (no_hw > 0) hw_weight = new_total(hw_weight.slice(), percentages[0])
        if (no_quiz > 0) quiz_weight = new_total(quiz_weight.slice(), percentages[1])
        if (no_lab > 0) lab_weight = new_total(lab_weight.slice(), percentages[2])
        if (no_mid > 0) mid_weight = new_total(mid_weight.slice(), percentages[3])
        if (no_fin > 0) fin_weight = new_total(fin_weight.slice(), percentages[4])
        if (no_misc1 > 0) misc1_weight = new_total(misc1_weight.slice(), percentages[5])
        if (no_misc2 > 0) misc2_weight = new_total(misc2_weight.slice(), percentages[6])
        if (no_misc3 > 0) misc3_weight = new_total(misc3_weight.slice(), percentages[7])


        console.log("\n\nNew weights after reTotal: \n\n");
        console.log("hw   :"  + hw_weight   );
        console.log("lab  :"  + lab_weight  );
        console.log("quiz : " + quiz_weight );
        console.log("mid  : " + mid_weight  );
        console.log("fin  : " + fin_weight  );
        console.log("misc1: " + misc1_weight);
        console.log("misc2: " + misc2_weight);
        console.log("misc3: " + misc3_weight);
        console.log("\n\n");



        // ------------  ACTUAL PLOTTING BEGINS HERE. ---------------




        // SOME DETAILS ABOUT THE ARRAYS CONTAINING THE DATA,
        // TO PREVENT FROM HAVING TO READ THE ABOVE CODE:


        // hw, quiz, lab, mid, fin, misc1, misc2, misc3 are
        // the arrays containing the raw input from the user.
        // They have 'null' values whenever the user leaves a
        // particular field blank.

        // hw_u, quiz_u, lab_u, mid_u, fin_u, misc1_u, misc2_u, misc3_u
        // contain the data to be plotted for the user's curve.
        // Similary, we also have hw_0, quiz_0, .... for the 'zero' curve,
        // hw_25, quiz_25, ... for the '25' curve, etc. (50, 75, 100).

        // A particular category is to be considered for plotting only if
        // the number of that category is strictly greater than 0.

        // A particular category is to be considered for approximation only
        // if the boolean variable representing that category is set to true.
        // These boolean variables are take_hw, take_quiz, .... respectively.



        // STRING ARRAYS FOR LABELLING PLOTS ARE MADE HERE.


        //

        var labeler = function (num, str) {
            arr = new_array(num, str);
            for (var i = 0; i < num; i++) {
                arr[i] += " " + (i+1).toString();
            }
            return arr;
        };


        //

        if (no_hw    > 0) var hw_label    = labeler   (no_hw   , "Homework" );
        if (no_lab   > 0) var lab_label   = labeler   (no_lab  , "Lab"      );
        if (no_quiz  > 0) var quiz_label  = labeler   (no_quiz , "Quiz"     );
        if (no_mid   > 0) var mid_label   = labeler   (no_mid  , "Midterm"  );
        if (no_fin   > 0) var fin_label   = new_array (no_fin  , "Final"    );
        if (no_misc1 > 0) var misc1_label = labeler   (no_misc1,  misc1_name);
        if (no_misc2 > 0) var misc2_label = labeler   (no_misc2,  misc2_name);
        if (no_misc3 > 0) var misc3_label = labeler   (no_misc3,  misc3_name);



        var is_all_null = function (arr) {
            for(var i = 0; i < arr.length; i++) {
                if (arr[i] !== null) return false;
            }
            return true;
        };


        var label  = [];
        var p_cy = [];
        var p_py = [];
        var p_0y   = [];
        var p_25y  = [];
        var p_50y  = [];
        var p_75y  = [];
        var p_100y = [];
        var weight = [];



        if (no_hw    > 0) { p_cy    = p_cy.concat   (hw);
                            p_py    = p_py.concat   (hw_u);
                            label   = label.concat  (hw_label);
                            p_0y    = p_0y.concat   (hw_0);
                            p_25y   = p_25y.concat  (hw_25);
                            p_50y   = p_50y.concat  (hw_50);
                            p_75y   = p_75y.concat  (hw_75);
                            p_100y  = p_100y.concat (hw_100);
                            weight  = weight.concat (hw_weight);
                           }
        if (no_lab   > 0) { p_cy    = p_cy.concat   (lab);
                            p_py    = p_py.concat   (lab_u);
                            label   = label.concat  (lab_label);
                            p_0y    = p_0y.concat   (lab_0);
                            p_25y   = p_25y.concat  (lab_25);
                            p_50y   = p_50y.concat  (lab_50);
                            p_75y   = p_75y.concat  (lab_75);
                            p_100y  = p_100y.concat (lab_100);
                            weight  = weight.concat (lab_weight);
                           }
        if (no_quiz  > 0) { p_cy    = p_cy.concat   (quiz);
                            p_py    = p_py.concat   (quiz_u);
                            label   = label.concat  (quiz_label);
                            p_0y    = p_0y.concat   (quiz_0);
                            p_25y   = p_25y.concat  (quiz_25);
                            p_50y   = p_50y.concat  (quiz_50);
                            p_75y   = p_75y.concat  (quiz_75);
                            p_100y  = p_100y.concat (quiz_100);
                            weight  = weight.concat (quiz_weight);
                           }
        if (no_mid   > 0) { p_cy    = p_cy.concat   (mid);
                            p_py    = p_py.concat   (mid_u);
                            label   = label.concat  (mid_label);
                            p_0y    = p_0y.concat   (mid_0);
                            p_25y   = p_25y.concat  (mid_25);
                            p_50y   = p_50y.concat  (mid_50);
                            p_75y   = p_75y.concat  (mid_75);
                            p_100y  = p_100y.concat (mid_100);
                            weight  = weight.concat (mid_weight);
                           }
        if (no_fin   > 0) { p_cy    = p_cy.concat   (fin);
                            p_py    = p_py.concat   (fin_u);
                            label   = label.concat  (fin_label);
                            p_0y    = p_0y.concat   (fin_0);
                            p_25y   = p_25y.concat  (fin_25);
                            p_50y   = p_50y.concat  (fin_50);
                            p_75y   = p_75y.concat  (fin_75);
                            p_100y  = p_100y.concat (fin_100);
                            weight  = weight.concat (fin_weight);
                           }
        if (no_misc1 > 0) { p_cy    = p_cy.concat   (misc1);
                            p_py    = p_py.concat   (misc1_u);
                            label   = label.concat  (misc1_label);
                            p_0y    = p_0y.concat   (misc1_0);
                            p_25y   = p_25y.concat  (misc1_25);
                            p_50y   = p_50y.concat  (misc1_50);
                            p_75y   = p_75y.concat  (misc1_75);
                            p_100y  = p_100y.concat (misc1_100);
                            weight  = weight.concat (misc1_weight);
                           }
        if (no_misc2 > 0) { p_cy    = p_cy.concat   (misc2);
                            p_py    = p_py.concat   (misc2_u);
                            label   = label.concat  (misc2_label);
                            p_0y    = p_0y.concat   (misc2_0);
                            p_25y   = p_25y.concat  (misc2_25);
                            p_50y   = p_50y.concat  (misc2_50);
                            p_75y   = p_75y.concat  (misc2_75);
                            p_100y  = p_100y.concat (misc2_100);
                            weight  = weight.concat (misc2_weight);
                           }
        if (no_misc3 > 0) { p_cy    = p_cy.concat   (misc3);
                            p_py    = p_py.concat   (misc3_u);
                            label   = label.concat  (misc3_label);
                            p_0y    = p_0y.concat   (misc3_0);
                            p_25y   = p_25y.concat  (misc3_25);
                            p_50y   = p_50y.concat  (misc3_50);
                            p_75y   = p_75y.concat  (misc3_75);
                            p_100y  = p_100y.concat (misc3_100);
                            weight  = weight.concat (misc3_weight);
                          }

        console.log("curr   : "  + p_cy );
        console.log("pred   : "  + p_py );
        console.log("Label  : " + label );
        console.log("0      : "  + p_0y  );
        console.log("25     : "  + p_25y );
        console.log("50     : "  + p_50y );
        console.log("75     : "  + p_75y );
        console.log("100    : "  + p_100y);
        console.log("weight : "  + weight);
        console.log("\n\n");

        var combined = [];

        for(var i = 0; i < p_100y.length; i++) {
            combined.push([label[i], p_cy[i], p_py[i], p_0y[i], p_25y[i], p_50y[i], p_75y[i], p_100y[i], weight[i]]);
        }

        console.log("combined: "  + combined);
        var arr1 = [];
        var arr2 = [];
        for (var i = 0; i < combined.length; i++) {
            if (combined[i][1] === null) {
                arr2.push(combined[i]);
            } else {
                arr1.push(combined[i]);
            }
        }
        var arr3 = [];
        var arr4 = [];
        for (var i = 0; i < arr2.length; i++) {
            if (arr2[i][2] === null) {
                arr4.push(arr2[i]);
            } else {
                arr3.push(arr2[i]);
            }
        }
        var final_array = arr1.concat(arr3);
        final_array = final_array.concat(arr4);
        console.log("final: "  + final_array);

        for(var i = 0; i < final_array.length; i++) {
            label[i] = final_array[i][0];
            p_cy[i] = final_array[i][1];
            p_py[i] = final_array[i][2];
            p_0y[i] = final_array[i][3];
            p_25y[i] = final_array[i][4];
            p_50y[i] = final_array[i][5];
            p_75y[i] = final_array[i][6];
            p_100y[i] = final_array[i][7];
            weight[i] = final_array[i][8];
        }

        // var sumify = function(arr) {
        //     var arr2 = new_array(arr.length, 0);
        //     for (var i = 0; i < arr.length; i++) {
        //         var sum = 0;
        //         for(var j = 0; j <= i; j++) {
        //             sum += arr[j];
        //         }
        //         arr2[i] = sum;
        //     }
        //     return arr2;
        // }

        var sumify = function(arr) {
            var num = null;
            for (var i = 0; i < arr.length; i++) {
                if (arr[i] === null) {
                    num = i;
                    break;
                }
            }
            if(num === null) num = arr.length;
            var arr2 = new_array(num, 0);
            for (var i = 0; i < num; i++) {
                var sum = 0;
                for(var j = 0; j <= i; j++) {
                    sum += arr[j];
                }
                arr2[i] = sum;
            }
            return arr2;
        }

        var ideal = new_array(final_array.length, 100);

        var yify2 = function (arr, sum) {
            var arr2 = arr.slice();
            // var total = 0;
            // for (var i = 0; i < arr2.length; i++) {
            //     total += arr2[i];
            // }
            for (var i = 0; i < arr2.length; i++) {
                if(arr2[i] != null)
                    arr2[i] = arr2[i] * 100 / (final_array.length * 100);
            }
            return arr2;
        };


        var yify3 = (arr, sum) => {
            var arr2 = arr.slice();
            for (var i = 0; i < arr2.length; i++) {
                if(arr2[i] != null)
                    arr2[i] = arr2[i] * final_array[i][8] / 100;
            }
            return arr2;
        }


    //    var nideal = yify2(sumify(ideal), 100);
    //    var np_cy = yify2(sumify(p_cy), 100);
    //    var np_py = yify2(sumify(p_py), 100);
    //    var np_0y = yify2(sumify(p_0y), 100);
    //    var np_25y = yify2(sumify(p_25y), 100);
    //    var np_50y = yify2(sumify(p_50y), 100);
    //    var np_75y = yify2(sumify(p_75y), 100);
    //    var np_100y = yify2(sumify(p_100y), 100);

        var nideal = sumify(yify3(ideal));
        var np_cy = sumify(yify3(p_cy));
        var np_py = sumify(yify3(p_py));
        var np_0y = sumify(yify3(p_0y));
        var np_25y = sumify(yify3(p_25y));
        var np_50y = sumify(yify3(p_50y));
        var np_75y = sumify(yify3(p_75y));
        var np_100y = sumify(yify3(p_100y));

        console.log("\n\n Awesome \n\n");
        console.log("Label  : " + label );
        console.log("curr   : "  + np_cy );
        console.log("pred   : "  + np_py );
        console.log("0      : "  + np_0y  );
        console.log("25     : "  + np_25y );
        console.log("50     : "  + np_50y );
        console.log("75     : "  + np_75y );
        console.log("100    : "  + np_100y);
        console.log("weight : "  + weight);
        console.log("\n\n");



        // var p_ux = []
        // var p_uy = []

        // if (take_hw) {

        //     p_uy = p_uy.concat(hw_u);
        // }

        // if(take_lab) {

        //     p_uy = p_uy.c
        // }






        PREDICTOR = document.getElementById('predictor');
        // Plotly.plot( PREDICTOR, [{
        // x: [1, 2, 3, 4, 5],
        // y: [1, 2, 4, 8, 16] }], {
        // margin: { t: 0 } } );


        // var user_line = {
        //     x: [...Array(no_hw + no_quiz + no_mid + no_fin).keys()],
        //     y: hw_u.concat(quiz_u).concat(mid_u).concat(fin_u),
        //     type: 'scatter',
        //     name: 'UserCurrent - Blue',
        //     line: {
        //         color: 'rgb(55, 128, 191)',
        //         width: 2
        //     },
        //     connectgaps: true,
        //     showarrow: true
        // };


        var user_line0 = {
            x: label,
            y: np_0y,
            mode:'lines+markers',
            type: 'scatter',
            name: '0 - Black',
            line: {
                color: 'rgb(0, 0, 0)',
                width: 2,
                // shape: 'spline'
            }
        };

        var user_line25 = {
            x: label,
            y: np_25y,
            mode:'lines+markers',
            type: 'scatter',
            name: '25 - Red',
            line: {
                color: 'rgb(255, 0, 0)',
                width: 2,
                // shape: 'spline'
            }
        };

        var user_line50 = {
            x: label,
            y: np_50y,
            mode:'lines+markers',
            type: 'scatter',
            name: '50 - Blue',
            line: {
                color: 'rgb(0, 255, 0)',
                width: 2,
                // shape: 'spline'
            }
        };

        var user_line75 = {
            x: label,
            y: np_75y,
            mode:'lines+markers',
            type: 'scatter',
            name: '75 - Green',
            line: {
                color: 'rgb(0, 0, 255)',
                width: 2,
                // shape: 'spline'
            }
        };

        var user_line100 = {
            x: label,
            y: np_100y,
            mode:'lines+markers',
            type: 'scatter',
            name: '100 - Yellow',
            line: {
                color: 'rgb(255, 255, 0)',
                width: 2,
                // shape: 'spline'
            }
        };

        var user_line_current = {
            x: label,
            y: np_cy,
            mode:'lines+markers',
            type: 'scatter',
            name: 'current - Pink',
            line: {
                color: 'rgb(255, 0, 255)',
                width: 2,
                // shape: 'spline'
            }
        };

        var user_line_predicted = {
            x: label,
            y: np_py,
            mode:'lines+markers',
            type: 'scatter',
            name: 'predicted - Blue',
            line: {
                color: 'rgb(0, 255, 255)',
                width: 2,
                // shape: 'spline'
            }
        };

        var ideal_line = {
            x: label,
            y: nideal,
            mode:'lines+markers',
            type: 'scatter',
            name: 'ideal - Black',
            line: {
                color: 'rgb(0, 0, 0)',
                width: 2,
                // shape: 'spline'
            }
        };


        var data = [user_line0, user_line25, user_line50, user_line75, user_line100, user_line_predicted, user_line_current, ideal_line];


        var layout = {
          // legend: {
          //   y: 0.5,
          //   traceorder: 'reversed',
          //   font: {size: 16},
          //   yref: 'paper'
          // }
          xaxis: {
            dtick: 1
          },
          yaxis: {
            dtick: 10,
            zeroline: false,
            showline: false
          },
          annotations: {
            text: 'Grade Predictor'
          }
          // xaxis: {
          //   tickvals: ['hw1', 'hw2', 'hw3', 'hw4']
            //ticktext: ['HW1', 'HW2', 'HW3', 'HW4']
          };


        Plotly.newPlot('predictor', data, layout);



        </script>

    <script id="recalculatejs">

        const rerunjs = () => {
            const oldScript = document.getElementById('mainjs');
            eval(oldScript.innerText);
        }

        const recalculate = () => {
            reinput();
            rerunjs();
        }

    </script>

    <?php 

        $hw = array();
        for($i=1;$i<$hwnum+1;$i++){
            array_push($hw,"HW $i");
        }

        $l = array();
        for($i=1;$i<$lnum+1;$i++){
            array_push($l,"Lab $i");
        }

        $q = array();
        for($i=1;$i<$qnum+1;$i++){
            array_push($q,"Quiz $i");
        }

        $t = array();
        for($i=1;$i<$tnum+1;$i++){
            array_push($t,"Test $i");
        }
        $f = array();
        for($i=1;$i<$fnum+1;$i++){
            array_push($f,"Final");
        }
        $misc1 = array();
        for($i=1;$i<$misc1num+1;$i++){
            array_push($misc1,"$misc1name $i");
        }
        $_SESSION['misc1name']=$misc1name;
        $misc2 = array();
        for($i=1;$i<$misc2num+1;$i++){
            array_push($misc2,"$misc2name $i");
        }
        $_SESSION['misc2name']=$misc2name;

        $misc3 = array();
        for($i=1;$i<$misc3num+1;$i++){
            array_push($misc3,"$misc3name $i");

        }
        $_SESSION['misc3name']=$misc3name;

        $final = array_merge($hw,$l,$q,$t,$f,$misc1,$misc2,$misc3);
        if ($user) {
            $grades = array_slice($A,1);
        } else {
            $A=array();
            $grades=array_pad($A,count($final),null);
        }

        $GA = array_combine($final, $grades);
        if ($GA===null) echo "fuck\n";
        

    ?>
    <center><a href = '/interpret.php'>How to read this graph</a></center>


    <div id = 'gradesDiv'>
        <center><h1>Current Grades</h1></center>
        <form method = 'POST' action='/save.php'>
            <table style="width:100%;overflow:scroll;">

        <?php 
            echo "<tr>";
            foreach ($final as $name) {
                echo "<th>$name</th>";
            }
            echo "</tr>";
            echo "<tr>";
            foreach ($final as $name) {
                $grade = $GA[$name];

                // add JS event listener for name variable here, and refresh div every time it changes
                echo "<td><center><input style = 'width:3em;' type='number' name='$name' value = '$grade' id='cooljs' onchange='recalculate()'></center></td>";
            }
            echo "</tr>";
            
            $_SESSION['final']=$final;
            $_SESSION['class']=$class_at;
        ?>  

            </table>
        <input type='submit' value = 'Save Grades'>
        </form>
    </div>

    <div id='metricsDiv'>
        This class is graded on the following scale:
        //TODO: Describe the grading system for this class
    </div>




</body>

</html>