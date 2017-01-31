<?php

class StaticPasswordGenerator
{
    static private $lowerAlphabetArray = [
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
    ];

    static private $upperAlphabetArray = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    static private $numericArray = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8' , '9'
        ];

    static public function generatePW ($argumentArray)
    {
        //get arguments
        $numberCharactersGenerate = $argumentArray["numberCharactersGenerate"];

        $startAtCharacter = ($argumentArray["startAtCharacter"] - 1);
        $endAtCharacter = ($argumentArray["endAtCharacter"] - 1);

        $startAtRange = ($argumentArray["startAtRange"] - 1);
        $endAtRange = ($argumentArray["endAtRange"] -1);

        //generate list of possible characters based on arguments
        $possibleCharactersArray = [];
        if ($argumentArray["alphaLowerCase"] === true) {
            $possibleCharactersArray = array_merge($possibleCharactersArray, self::$lowerAlphabetArray);
        } else if ($argumentArray["alphaUpperCase"] === true) {
            $possibleCharactersArray = array_merge($possibleCharactersArray, self::$upperAlphabetArray);
        } else if ($argumentArray["numeric"] === true) {
            $possibleCharactersArray = array_merge($possibleCharactersArray, self::$numericArray);
        }

        //start building combination strings based on arguments
        $characterCombinationArray = [];
        //total number of combinations to be calculated
        $totalNumber = pow(count($possibleCharactersArray), $numberCharactersGenerate);
        //shift the length back one, as the string starts at '0'
        $lengthTo = $numberCharactersGenerate - 1;

        //build all possible combinations for each string position, then move onto the next
        for ($stringLenCounter = 0; $stringLenCounter <= $lengthTo; $stringLenCounter++) {
            //the number of combinations of letters for this position in the string
            $numberLetter = $totalNumber / pow(count($possibleCharactersArray), ($stringLenCounter + 1));
            //the number of times we have to loop over the $possibleCharactersArray to achieve this
            $numberLoops = $totalNumber / ($numberLetter * count($possibleCharactersArray));
            //a position counter, so we can chunk over a character set
            $positionCounter = 0;

            //check to see if we are in the range of the chunk for the character position
            if (($stringLenCounter >= $startAtCharacter) && ($stringLenCounter <= $endAtCharacter)) {
                //loop over the entire $possibleCharacterArray a $numberLoops number of times
                for ($loopCounter = 1; $loopCounter <= $numberLoops; $loopCounter++) {
                    //go through the entire $possibleCharacterArray
                    foreach ($possibleCharactersArray as $gaI => $gaV) {
                        //generate this character $numberLetter number of times
                        for ($letterCounter = 1; $letterCounter <= $numberLetter; $letterCounter++) {
                            //check to see if we are in the range of the chunk for range position
                            if (($positionCounter >= $startAtRange) && ($positionCounter <= $endAtRange)) {
                                //check to see if we have generated a character. Start or append as necessary
                                if (@array_key_exists($positionCounter, $characterCombinationArray)) {
                                    $characterCombinationArray[$positionCounter] .= $gaV;
                                } else {
                                    $characterCombinationArray[$positionCounter] = $gaV;
                                }
                            }
                            //advance the $positionCounter
                            $positionCounter++;

                            //Check to see if we have exceeded the end range position. If so, break out as there is nothing more to do.
                            if ($positionCounter > $endAtRange) {
                                break 3;
                            }
                        }
                    }
                }
            }
        }

        //return the array of requested character combinations
        return $characterCombinationArray;
    }
}