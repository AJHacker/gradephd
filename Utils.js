// Function to return a new array of length 'size',
// containing 'val' as each of its values.

var new_array = (size, val) => {
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


// Function to assign 'val' in place of each 'null' value in 'arr'

var assign_null = function (arr, val) {
    arr.forEach(function (item, index) {
        if(arr[index] === null) arr[index] = val;
    });
    return arr;
};


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


// Function to calculate Total Score (in perecent), based on the
// scores on weights for each createHash(algorithm, options);y.

var score_calc = function (scores, weights) {
    return weighted_mean(scores, weights);
};


// Recalculate each weight, now from the whole course's perspective

function new_total(arr, total) {
    arr.forEach((item, index) => {
        arr[index] = arr[index] * total / 100;
    });
    return arr;
}


//

var labeler = function (num, str) {
    arr = new_array(num, str);
    for (var i = 0; i < num; i++) {
        arr[i] += " " + (i+1).toString();
    }
    return arr;
};


var is_all_null = function (arr) {
    for(var i = 0; i < arr.length; i++) {
        if (arr[i] !== null) return false;
    }
    return true;
};



