<?php
//include required files
require_once('Include/passwordGenerator.php');
require_once('Include/pool.inc.php');

//set initial requirements
$numberOfThreads = 5;

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

//create the pool
$serverID = 1;
$serverArray = [];
$pool = new Pool($numberOfThreads, 'StaticThreadWorker', [$serverID, $serverArray]);

//start timer
$timeStart = microtime(true);

//figure out how many combinations are generated
$totalCombinations = 0;

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
        $argumentArray["processNumber"] = $outerLoop . "_" . $innerLoop;

        //add the job to the queue
        $pool->submit(new GeneratePasswordArray($argumentArray));
    }
}

//loop and wait for all workers to finish
while ($pool->collect(function($work){return $work->isGarbage();})) {
    continue;
}

//shutdown the pool
$pool->shutdown();

//stop timer
$clockTime = (microtime(true) - $timeStart);

//display information
echo 'Total execution time in seconds: ' . $clockTime . PHP_EOL;
echo 'Total combinations executed: ' . $totalCombinations . PHP_EOL;
echo 'Combinations per second: ' . $totalCombinations / $clockTime . PHP_EOL;