<?php
//include required files
require_once('Include/passwordGenerator.php');

//set initial requirements
$argumentArray["alphaLowerCase"] = false;
$argumentArray["alphaUpperCase"] = false;
$argumentArray["numeric"] = true;

//determine number of possibilities (for loop information later)
$numberPossible = 0;
if ($argumentArray["alphaLowerCase"] === true) {
    $numberPossible += 26;
} else if ($argumentArray["alphaUpperCase"] === true) {
    $numberPossible += 26;
} else if ($argumentArray["numeric"] === true) {
    $numberPossible += 10;
}

//start timer
$timeStart = microtime(true);

//figure out how many combinations are generated
$totalCombinations = 0;

//loop over the number of characters to generate
for ($outerLoop = $argv[1]; $outerLoop <= $argv[2]; $outerLoop++) {
    //set the number of characters as the current position of the loop
    $argumentArray["numberCharactersGenerate"] = $outerLoop;

    //do all the characters for each loop
    $argumentArray["startAtCharacter"] = 1;
    $argumentArray["endAtCharacter"] = $outerLoop;

    //determine the number of chunks we need to itterate over
    $innerLoopNumber = pow($numberPossible, $outerLoop);
    $totalCombinations += $innerLoopNumber;

    //chunk over the combinations at a specified loop size until completed
    for ($innerLoop = 1; $innerLoop <= $innerLoopNumber; $innerLoop += 10001) {
        //set ranges based on loop information
        $argumentArray["startAtRange"] = $innerLoop;
        $argumentArray["endAtRange"] = $innerLoop + 10000;

        //generate the passwords
        $finalArray = StaticPasswordGenerator::generatePW($argumentArray);
        //notification - not strictly necessary
        echo "Done! " . var_export($argumentArray, true) . PHP_EOL;
        //output results for verification - not strictly necessary
        file_put_contents('/testLocation/nonPoolList_' . $outerLoop . "_" . $innerLoop, print_r($finalArray, true));
    }
}
//stop timer
$clockTime = (microtime(true) - $timeStart);

//display information
echo 'Total execution time in seconds: ' . $clockTime . PHP_EOL;
echo 'Total combinations executed: ' . $totalCombinations . PHP_EOL;
echo 'Combinations per second: ' . $totalCombinations / $clockTime . PHP_EOL;