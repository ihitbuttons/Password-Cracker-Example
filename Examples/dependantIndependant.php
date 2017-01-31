<?php

//set a boolean value
if (mt_rand(0, 1) > 0) {
    $booleanValue = true;
} else {
    $booleanValue = false;
}

//this is dependant code
if ($booleanValue === true) {
    //do something
} else {
    //do something else
}

unset($booleanValue);

//generate an array
$arrayBooleanValues = [];
for ($i = 0; $i <= 10; $i++) {
    if (mt_rand(0, 1) > 0) {
        $arrayBooleanValues[] = true;
    } else {
        $arrayBooleanValues[] = false;
    }
}

foreach ($arrayBooleanValues as $key => $booleanValue) {
    //this is independant code
    if ($booleanValue === true) {
        //do something
    } else {
        //do something else
    }
}