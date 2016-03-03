<?php
/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 2/25/2016
 * Time: 5:54 PM
 */

//given an array and an index of that array sort by that index and return the array
function sortArrayByIndex($inputArray, $indexString)
{
    $sortedArray = array();

    foreach ($inputArray as $array)
    {
        $sortedArray[] = (int)$array[$indexString];
    }

    array_multisort($sortedArray, SORT_DESC, $inputArray);
    return $inputArray;
}
