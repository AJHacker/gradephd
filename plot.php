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
        $user_sql   = "SELECT * FROM $class WHERE name='$user';";
        $result     = pg_query($db,$user_sql);
        $A          = pg_fetch_row($result);
        
        $class_sql  = "SELECT * FROM all_classes WHERE name='$class';";
        $result     = pg_query($db,$class_sql);
        $B          = pg_fetch_row($result);
        
        $C          = explode("_",$class);
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

    <div id="predictor" style="display:inline-block;position:fixed;top:0;bottom:0;left:0;
                               right:0;width:60%;height:60%; margin: auto;"></div>


    <script>


    // Floating Point Error allowed in Grade Calculations (Out of 100).

    var Epsilon = 0.00000001;

    
    // Number of items in each category

    // TODO -> GET VALUES FROM BACKEND.
    
    var no_hw    = 8;
    var no_quiz  = 8;
    var no_lab   = 0;
    var no_mid   = 4;
    var no_fin   = 1;
    var no_misc1 = 0;
    var no_misc2 = 0;
    var no_misc3 = 0;

    
    // Percent weight of each category contributing towards total grade

    // TODO -> GET VALUES FROM BACKEND.
    
    var hw_perc    = 35  ;
    var quiz_perc  = 15  ;
    var lab_perc   = 0   ;
    var mid_perc   = 37.5;
    var fin_perc   = 12.5;
    var misc1_perc = 0   ;
    var misc2_perc = 0   ;
    var misc3_perc = 0   ;


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
    var quiz_weight  = no_quiz  === 0 ? [] : verify_weights(new_array (no_quiz , 100/no_hw   ));
    var lab_weight   = no_lab   === 0 ? [] : verify_weights(new_array (no_lab  , 100/no_hw   ));
    var mid_weight   = no_mid   === 0 ? [] : verify_weights(new_array (no_mid  , 100/no_mid  ));
    var fin_weight   = no_fin   === 0 ? [] : verify_weights(new_array (no_fin  , 100/no_fin  ));
    var misc1_weight = no_misc1 === 0 ? [] : verify_weights(new_array (no_misc1, 100/no_misc1));
    var misc2_weight = no_misc2 === 0 ? [] : verify_weights(new_array (no_misc2, 100/no_misc2));
    var misc3_weight = no_misc3 === 0 ? [] : verify_weights(new_array (no_msic3, 100/no_misc3));


    // Boolean variables to denote the mode of grading:
    // Either Variable Weights, or Drop Lowest.
    // By default, this is set to Drop Lowest.

    // TODO -> GET VALUES FROM BACKEND.

    var variable_weights = false;
    var drop_lowest = true;


    // As a design decision, users are allowed to choose only one of the two modes:
    // Either Variable Weights, or Drop Lowest, Not Both for now.
    // The assert statement below checks this.

    console.assert( (drop_lowest && !variable_weights) || (variable_weights && !drop_lowest) );


    // Creates new boolean variables for each category, which are set to true
    // if entries in that category have variable weights, false otherwise.

    // TODO -> GET VALUES FROM BACKEND.

    var hw_vw    = false;
    var quiz_vw  = false;
    var lab_vw   = false;
    var mid_vw   = false;
    var fin_vw   = false;
    var misc1_vw = false;
    var misc2_vw = false;
    var misc3_vw = false;


    // Number of lowest items of each category that are 'dropped'

    // TODO -> GET VALUES FROM BACKEND.
    
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

    // TODO -> GET VALUES FROM BACKEND.
    
    var hw_drop_perc    = 0;
    var quiz_drop_perc  = 0;
    var lab_drop_perc   = 0;
    var mid_drop_perc   = 0;
    var fin_drop_perc   = 0;
    var misc1_drop_perc = 0;
    var misc2_drop_perc = 0;
    var misc3_drop_perc = 0;


    // Values of each category, stored in arrays

    // TODO -> GET VALUES FROM BACKEND.
    
    var hw    = [90, 92, 86, 100, 99, null, null, null];
    var quiz  = [80, 60, 80, 60, 80, 60, 80, 60]       ;
    var lab   = []                                     ;
    var mid   = [100, null, 78, 94]                      ;
    var fin   = [null]                                   ;
    var misc1 = []                                     ;
    var misc2 = []                                     ;
    var misc3 = []                                     ;


    // The below code is executed only if the user opted for Variable
    // Weights.

    // TODO -> GET VALUES FROM BACKEND.

    if (variable_weights) {

        // hw_weight    = ;
        // quiz_weight  = ;
        // lab_weight   = ;
        // mid_weight   = ;
        // misc1_weight = ;
        // misc2_weight = ;
        // misc3_weight = ;
    }


    // Function to verify that each entry in each array is valid.

    var verify = function (arr) {
        // TODO -> LATER
        ;
    };


    // Function to caclculate the mean of the non-null values in an array.
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


    // Function to caclculate the weighted mean of the non-null values in 
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

    var hw_mean    = variable_weights ? weighted_mean (hw,    hw_weight)    : mean (hw)   ;
    var quiz_mean  = variable_weights ? weighted_mean (quiz,  quiz_weight)  : mean (quiz) ;
    var lab_mean   = variable_weights ? weighted_mean (lab,   lab_weight)   : mean (lab)  ;
    var mid_mean   = variable_weights ? weighted_mean (mid,   mid_weight)   : mean (mid)  ;
    var fin_mean   = variable_weights ? weighted_mean (fin,   fin_weight)   : mean (fin)  ;
    var misc1_mean = variable_weights ? weighted_mean (misc1, misc1_weight) : mean (misc1);
    var misc2_mean = variable_weights ? weighted_mean (misc2, misc2_weight) : mean (misc2);
    var misc3_mean = variable_weights ? weighted_mean (misc3, misc3_weight) : mean (misc3);


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
        return arr;
    };


    // Assign values to the places that the user left empty during the 
    // input process, only if that given category counts towards the total,
    // by creating arrays that store the different curves for each 
    // category, to store this info in.

    if (take_hw)    {   var hw_u      = assign_null (hw.slice()   , hw_mean   );
                        var hw_0      = assign_null (hw.slice()   , 0         );
                        var hw_25     = assign_null (hw.slice()   , 25        );
                        var hw_50     = assign_null (hw.slice()   , 50        );
                        var hw_75     = assign_null (hw.slice()   , 75        );
                        var hw_100    = assign_null (hw.slice()   , 100       );
                    }
    if (take_quiz)  {   var quiz_u    = assign_null (quiz.slice() , quiz_mean );
                        var quiz_0    = assign_null (quiz.slice() , 0         );
                        var quiz_25   = assign_null (quiz.slice() , 25        );
                        var quiz_50   = assign_null (quiz.slice() , 50        );
                        var quiz_75   = assign_null (quiz.slice() , 75        );
                        var quiz_100  = assign_null (quiz.slice() , 100       );
                    }
    if (take_lab)   {   var lab_u     = assign_null (lab.slice()  , lab_mean  );
                        var lab_0     = assign_null (lab.slice()  , 0         );
                        var lab_25    = assign_null (lab.slice()  , 25        );
                        var lab_50    = assign_null (lab.slice()  , 50        );
                        var lab_75    = assign_null (lab.slice()  , 75        );
                        var lab_100   = assign_null (lab.slice()  , 100       );
                    }
    if (take_mid)   {   var mid_u     = assign_null (mid.slice()  , mid_mean  );
                        var mid_0     = assign_null (mid.slice()  , 0         );
                        var mid_25    = assign_null (mid.slice()  , 25        );
                        var mid_50    = assign_null (mid.slice()  , 50        );
                        var mid_75    = assign_null (mid.slice()  , 75        );
                        var mid_100   = assign_null (mid.slice()  , 100       );
                    }
    if (take_fin)   {   var fin_u     = assign_null (fin.slice()  , fin_mean  );
                        var fin_0     = assign_null (fin.slice()  , 0         );
                        var fin_25    = assign_null (fin.slice()  , 25        );
                        var fin_50    = assign_null (fin.slice()  , 50        );
                        var fin_75    = assign_null (fin.slice()  , 75        );
                        var fin_100   = assign_null (fin.slice()  , 100       );
                    }
    if (take_misc1) {   var misc1_u   = assign_null (misc1.slice(), misc1_mean);
                        var misc1_0   = assign_null (misc1.slice(), 0         );
                        var misc1_25  = assign_null (misc1.slice(), 25        );
                        var misc1_50  = assign_null (misc1.slice(), 50        );
                        var misc1_75  = assign_null (misc1.slice(), 75        );
                        var misc1_100 = assign_null (misc1.slice(), 100       );
                    }
    if (take_misc2) {   var misc2_u   = assign_null (misc2.slice(), misc2_mean);
                        var misc2_0   = assign_null (misc2.slice(), 0         );
                        var misc2_25  = assign_null (misc2.slice(), 25        );
                        var misc2_50  = assign_null (misc2.slice(), 50        );
                        var misc2_75  = assign_null (misc2.slice(), 75        );
                        var misc2_100 = assign_null (misc2.slice(), 100       );
                    }
    if (take_misc3) {   var misc3_u   = assign_null (misc3.slice(), misc3_mean);
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
    var hw_0_scr      = null;
    var hw_25_scr     = null;
    var hw_50_scr     = null;
    var hw_100_scr    = null;

    var quiz_scr      = null;
    var quiz_0_scr    = null;
    var quiz_25_scr   = null;
    var quiz_50_scr   = null;
    var quiz_100_scr  = null;

    var lab_scr       = null;
    var lab_0_scr     = null;
    var lab_25_scr    = null;
    var lab_50_scr    = null;
    var lab_100_scr   = null;

    var mid_scr       = null;
    var mid_0_scr     = null;
    var mid_25_scr    = null;
    var mid_50_scr    = null;
    var mid_100_scr   = null;

    var fin_scr       = null;
    var fin_0_scr     = null;
    var fin_25_scr    = null;
    var fin_50_scr    = null;
    var fin_100_scr   = null;

    var misc1_scr     = null;
    var misc1_0_scr   = null;
    var misc1_25_scr  = null;
    var misc1_50_scr  = null;
    var misc1_100_scr = null;

    var misc2_scr     = null;
    var misc2_0_scr   = null;
    var misc2_25_scr  = null;
    var misc2_50_scr  = null;
    var misc2_100_scr = null;

    var misc3_scr     = null;
    var misc3_0_scr   = null;
    var misc3_25_scr  = null;
    var misc3_50_scr  = null;
    var misc3_100_scr = null;


    //

    var scores           = new_array(8, null);
    var scores_0         = new_array(8, null);
    var scores_25        = new_array(8, null);
    var scores_50        = new_array(8, null);
    var scores_75        = new_array(8, null);
    var scores_100       = new_array(8, null);
    var percentages_temp = new_array(8, null);


    // The below operations are performed only if the user had chosen
    // the Drop Lowest Score(s) option.
    // Drops the lowest 'x' grades to 'y' percent, as indicated by the user.
    
    if (drop_lowest) {
        if (take_hw)    { hw_weight    = drop (hw   , hw_drop   , hw_drop_perc   , hw_weight   ); }
        if (take_quiz)  { quiz_weight  = drop (quiz , quiz_drop , quiz_drop_perc , quiz_weight ); }
        if (take_lab)   { lab_weight   = drop (lab  , lab_drop  , lab_drop_perc  , lab_weight  ); }
        if (take_mid)   { mid_weight   = drop (mid  , mid_drop  , mid_drop_perc  , mid_weight  ); }
        if (take_fin)   { fin_weight   = drop (fin  , fin_drop  , fin_drop_perc  , fin_weight  ); }
        if (take_misc1) { misc1_weight = drop (misc1, misc1_drop, misc1_drop_perc, misc1_weight); }
        if (take_misc2) { misc2_weight = drop (misc2, misc2_drop, misc2_drop_perc, misc2_weight); }
        if (take_misc3) { misc3_weight = drop (misc3, misc3_drop, misc3_drop_perc, misc3_weight); }
    }


    // Function to recompute percentage weight of each element
    // in perc_temp, based on which categories are left completely
    // blank by the user (if flag is truee), and for all elements
    // in the array, otherwise (if flag is false). The scores and the 
    // older percentages (in 'perc_temp') are provided as input, and
    // the newly computed percentages are then returned as an arry;

    var re_perc = function (scores, perc_temp, flag) {
        var total = 0;
        if (flag) {
            perc_temp.forEach (function (item, index) {
                if (scores [index] !== null) {
                    total += perc_temp [index];
                }
            });
            var perc = new_array (8, null);
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

    if (take_hw)    { hw_weight     = re_perc ([], hw_weight, false);
                      hw_scr        = weighted_mean (hw_u     , hw_weight   ); scores     [0] = hw_scr       ; 
                      hw_0_scr      = weighted_mean (hw_0     , hw_weight   ); scores_0   [0] = hw_0_scr     ;
                      hw_25_scr     = weighted_mean (hw_25    , hw_weight   ); scores_25  [0] = hw_25_scr    ; 
                      hw_50_scr     = weighted_mean (hw_50    , hw_weight   ); scores_50  [0] = hw_50_scr    ; 
                      hw_75_scr     = weighted_mean (hw_75    , hw_weight   ); scores_75  [0] = hw_75_scr    ; 
                      hw_100_scr    = weighted_mean (hw_100   , hw_weight   ); scores_100 [0] = hw_100_scr   ; 
                      percentages_temp [0] = hw_perc   ;        
                     } 
    if (take_quiz)  { quiz_weight   = re_perc ([], quiz_weight, false);
                      quiz_scr      = weighted_mean (quiz_u   , quiz_weight ); scores     [1] = quiz_scr     ; 
                      quiz_0_scr    = weighted_mean (quiz_0   , quiz_weight ); scores_0   [1] = quiz_0_scr   ;
                      quiz_25_scr   = weighted_mean (quiz_25  , quiz_weight ); scores_25  [1] = quiz_25_scr  ; 
                      quiz_50_scr   = weighted_mean (quiz_50  , quiz_weight ); scores_50  [1] = quiz_50_scr  ; 
                      quiz_75_scr   = weighted_mean (quiz_75  , quiz_weight ); scores_75  [1] = quiz_75_scr  ; 
                      quiz_100_scr  = weighted_mean (quiz_100 , quiz_weight ); scores_100 [1] = quiz_100_scr ; 
                      percentages_temp [1] = quiz_perc ;                    
                     }                                                     
    if (take_lab)   { lab_weight    = re_perc ([], lab_weight, false);
                      lab_scr       = weighted_mean (lab_u    , lab_weight  ); scores     [2] = lab_scr      ; 
                      lab_0_scr     = weighted_mean (lab_0    , lab_weight  ); scores_0   [2] = lab_0_scr    ;
                      lab_25_scr    = weighted_mean (lab_25   , lab_weight  ); scores_25  [2] = lab_25_scr   ; 
                      lab_50_scr    = weighted_mean (lab_50   , lab_weight  ); scores_50  [2] = lab_50_scr   ; 
                      lab_75_scr    = weighted_mean (lab_75   , lab_weight  ); scores_75  [2] = lab_75_scr   ; 
                      lab_100_scr   = weighted_mean (lab_100  , lab_weight  ); scores_100 [2] = lab_100_scr  ; 
                      percentages_temp [2] = lab_perc  ;                                      
                     }                                                                      
    if (take_mid)   { mid_weight    = re_perc ([], mid_weight, false);       
                      mid_scr       = weighted_mean (mid_u    , mid_weight  ); scores     [3] = mid_scr      ; 
                      mid_0_scr     = weighted_mean (mid_0    , mid_weight  ); scores_0   [3] = mid_0_scr    ;
                      mid_25_scr    = weighted_mean (mid_25   , mid_weight  ); scores_25  [3] = mid_25_scr   ; 
                      mid_50_scr    = weighted_mean (mid_50   , mid_weight  ); scores_50  [3] = mid_50_scr   ; 
                      mid_75_scr    = weighted_mean (mid_75   , mid_weight  ); scores_75  [3] = mid_75_scr   ; 
                      mid_100_scr   = weighted_mean (mid_100  , mid_weight  ); scores_100 [3] = mid_100_scr  ; 
                      percentages_temp [3] = mid_perc  ;
                     }              
    if (take_fin)   { fin_weight    = re_perc ([], fin_weight, false);
                      fin_scr       = weighted_mean (fin_u    , fin_weight  ); scores     [4] = fin_scr      ; 
                      fin_0_scr     = weighted_mean (fin_0    , fin_weight  ); scores_0   [4] = fin_0_scr    ;
                      fin_25_scr    = weighted_mean (fin_25   , fin_weight  ); scores_25  [4] = fin_25_scr   ; 
                      fin_50_scr    = weighted_mean (fin_50   , fin_weight  ); scores_50  [4] = fin_50_scr   ; 
                      fin_75_scr    = weighted_mean (fin_75   , fin_weight  ); scores_75  [4] = fin_75_scr   ; 
                      fin_100_scr   = weighted_mean (fin_100  , fin_weight  ); scores_100 [4] = fin_100_scr  ;
                      percentages_temp [4] = fin_perc  ;                                      
                     }                                                                      
    if (take_misc1) { misc1_weight  = re_perc ([], misc1_weight, false);
                      misc1_scr     = weighted_mean (misc1_u  , misc1_weight); scores     [5] = misc1_scr    ; 
                      misc1_0_scr   = weighted_mean (misc1_0  , misc1_weight); scores_0   [5] = misc1_0_scr  ;
                      misc1_25_scr  = weighted_mean (misc1_25 , misc1_weight); scores_25  [5] = misc1_25_scr ; 
                      misc1_50_scr  = weighted_mean (misc1_50 , misc1_weight); scores_50  [5] = misc1_50_scr ; 
                      misc1_75_scr  = weighted_mean (misc1_75 , misc1_weight); scores_75  [5] = misc1_75_scr ; 
                      misc1_100_scr = weighted_mean (misc1_100, misc1_weight); scores_100 [5] = misc1_100_scr; 
                      percentages_temp [5] = misc1_perc;
                     }
    if (take_misc2) { misc2_weight  = re_perc ([], misc2_weight, false);
                      misc2_scr     = weighted_mean (misc2_u  , misc2_weight); scores     [6] = misc2_scr    ; 
                      misc2_0_scr   = weighted_mean (misc2_0  , misc2_weight); scores_0   [6] = misc2_0_scr  ;
                      misc2_25_scr  = weighted_mean (misc2_25 , misc2_weight); scores_25  [6] = misc2_25_scr ; 
                      misc2_50_scr  = weighted_mean (misc2_50 , misc2_weight); scores_50  [6] = misc2_50_scr ; 
                      misc2_75_scr  = weighted_mean (misc2_75 , misc2_weight); scores_75  [6] = misc2_75_scr ; 
                      misc2_100_scr = weighted_mean (misc2_100, misc2_weight); scores_100 [6] = misc2_100_scr; 
                      percentages_temp [6] = misc2_perc;
                     }
    if (take_misc3) { misc3_weight  = re_perc ([], misc3_weight, false);
                      misc3_scr     = weighted_mean (misc3_u  , misc3_weight); scores     [7] = misc3_scr    ; 
                      misc3_0_scr   = weighted_mean (misc3_0  , misc3_weight); scores_0   [7] = misc3_0_scr  ;
                      misc3_25_scr  = weighted_mean (misc3_25 , misc3_weight); scores_25  [7] = misc3_25_scr ; 
                      misc3_50_scr  = weighted_mean (misc3_50 , misc3_weight); scores_50  [7] = misc3_50_scr ; 
                      misc3_75_scr  = weighted_mean (misc3_75 , misc3_weight); scores_75  [7] = misc3_75_scr ; 
                      misc3_100_scr = weighted_mean (misc3_100, misc3_weight); scores_100 [7] = misc3_100_scr; 
                      percentages_temp [7] = misc3_perc;
                     }


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

    var percentages = re_perc (scores, percentages_temp, true);

    
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
    // the boolean variable representing that category is set to true.
    // These boolean variables are take_hw, take_quiz, .... respectively.



    PREDICTOR = document.getElementById('predictor');
    // Plotly.plot( PREDICTOR, [{
    // x: [1, 2, 3, 4, 5],
    // y: [1, 2, 4, 8, 16] }], {
    // margin: { t: 0 } } );


    var user_line = {
        x: [1, 2, 3, 4, 5],
        y: [1, 2, 4, 8, 16],
        type: 'scatter',
        name: 'Username - Blue',
        line: {
            color: 'rgb(55, 128, 191)',
            width: 2,
            shape: 'spline'
        }
    };

    
    var data = [user_line];

    
    var layout = {
      legend: {
        y: 0.5,
        traceorder: 'reversed',
        font: {size: 16},
        yref: 'paper'
      }};


    Plotly.newPlot('predictor', data, layout);



    </script>


</body>


</html>