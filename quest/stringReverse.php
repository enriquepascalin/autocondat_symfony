<?php


function stringReverseOriginal(string $originalString): string
{
    echo "Original String: $originalString\n";
    //Convert the string to an array using the least php methods possible
    $array = [];
    for ($i = 0; $i < strlen($originalString); $i++) {
        $array[] = $originalString[$i];
    }
    
    //Reverse the array and then convert it back to a string
    $count = count($array);
    
    $reversedArray = [];
    for ($i = $count; $i > 0; $i--) {
        $reversedArray[] = $array[$i-1];
    }
    
    return implode("", $reversedArray);
}


function stringReverseOptimized(string $originalString): string
 {
    $length = strlen($originalString);
    $reversed = "";
    for ($i = $length - 1; $i >= 0; $i--) {
        $reversed .= $originalString[$i];
    }
    
    return $reversed;

 }

function stringReverseNative(string $originalString): string
{
 
    return strrev($originalString);
}

$result1 = stringReverseOriginal("I need to reverse this string");
$result2 = stringReverseOptimized("I need to reverse this string");
$result3 = stringReverseNative("I need to reverse this string");
echo "Result Original: $result1\n";
echo "Result Optimized: $result2\n";
echo "Result Native: $result3\n";
