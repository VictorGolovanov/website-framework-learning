<?php

/* 
 * File with custom functions
 * 
 * @author victor
 */


/*
 * print array
 */
function printArr($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre';
}

/*
 * Merge arrays recursively
 * first array is main
 */
function arrayMergeRecursive(...$arrays) {
        $base = array_shift($arrays);
        
        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                if (is_array($value) && is_array($base[$key])) {
                    $base[$key] = arrayMergeRecursive($base[$key], $value);
                } else {
                    if (is_int($key)) {
                        if (!in_array($value, $base)) {
                            array_push($base, $value);
                            continue;
                        }
                    }
                    $base[$key] = $value;
                }
            }
        }
        
        return $base;
    }

