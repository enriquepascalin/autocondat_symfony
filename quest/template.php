<?php


function some_function(string $originalString): string
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

$result = some_function("I need to reverse this string");
echo "Result: $result\n";


