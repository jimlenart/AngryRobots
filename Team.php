<?php

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 1/11/2016
 * Time: 10:34 AM
 */
class Team
{
    public $teamId;    //static identifier
    public $teamName;  //can change from season to season
    public $teamOwnerId; //can have multiple owners


    public function __construct($name, $id)
    {
        $this->teamName = $name;
        $this->teamId = $id;
    }

    //reads data from input file and returns it in an array
    public function LoadTeamsFromFile($fileName)
    {
        $txtFileData = file_get_contents($fileName);
        $rows = explode("\n", $txtFileData);
        echo "Team file data array";
        //var_dump($rows);

        for ($i=0; $i<count($rows); ++$i)
        {
            $name = explode(",", $rows[$i]);
            //echo "Current Team: ";
            //var_dump($name);
            $teamArray[$i][0] = $name[0];
            $teamArray[$i][1] = $name[1];
            //var_dump($teamArray);
        }
        var_dump($teamArray);
        return $teamArray;
    }
}

