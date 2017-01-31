<?php

class StaticThreadWorker extends Worker
{
    private $serverID;
    private $serverArray;

    public function __construct (int $serverID, array $serverArray)
    {
        //initialize passed in variables (will be used later
        $this->serverID = $serverID;
        $this->serverArray = $serverArray;
    }

    public function getServerID ()
    {
        //return the set serverID
        return $this->serverID;
    }

    public function getServerArray ()
    {
        //return the set serverArray
        return $this->serverArray;
    }
}

class GeneratePasswordArray extends Threaded
{
    private $argumentArray;
    private $processNumber;

    public function __construct (array $argumentArray)
    {
        //initialize the passed variables
        $this->argumentArray = $argumentArray;
        $this->processNumber = $argumentArray["processNumber"];
    }

    public function run ()
    {
        $finalArray = StaticPasswordGenerator::generatePW($this->argumentArray);
        echo "Done! " . var_export($this->argumentArray, true) . PHP_EOL;
        file_put_contents('/testLocation/poolList_' . $this->processNumber, print_r($finalArray, true));
    }
}