<?php

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 1/14/2016
 * Time: 4:49 PM
 */
class TeamResult extends Team
{
    public $week;
    public $score;


    public function __construct($teamId, $week, $score)
    {
        $this->teamId = $teamId;
        $this->week = $week;
        $this->score = $score;
    }

    public function loadResultsFromDB($week)
    {

        $matchCtr = 0;
        $scoreResults = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
            or die ('Error connecting to MySQL server.');

        $query = "SELECT t.team_id, r.score, t.team_name " .
            " FROM teams t LEFT JOIN team_results r " .
            " ON r.week='$week'" .
            " AND r.team_id = t.team_id";

        //var_dump($query);

        $result = mysqli_query($dbc, $query)
            or die('Error querying database in loadResultsFromDB.');

        while ($row = mysqli_fetch_array($result)){

            $scoreResults[$matchCtr]['teamId'] = $row['team_id'];
            $scoreResults[$matchCtr]['score'] = $row['score'];
            $scoreResults[$matchCtr]['teamName'] = $row['team_name'];
            $scoreResults[$matchCtr]['week'] = $week;

            $matchCtr++;
        }

        //echo "loadResultsFromDB - teamResultsExpanded: " . "<br>";
        //var_dump($teamResultsExpanded);
        return $scoreResults;

    }

    public function loadResultsIntoDBByWeek($scoreResults, $week)
    {
        //var_dump($scoreResults);
        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
            or die ('Error connecting to MySQL server.');

        $query = "DELETE FROM team_results WHERE week = $week";
        mysqli_query($dbc, $query) or die('Error deleting data in angry_robots.team_results table in loadResultsIntoDB.');

        for ($i=0; $i<count($scoreResults); ++$i)
        {
            $score = $scoreResults[$i]['score'];
            //$score = !empty($scoreResults[$i]['score']) ? "$scoreResults[$i]['score']" : "NULL";
            $score = !empty($score) ? "$score" : "NULL";
            $teamId = $scoreResults[$i]['teamId'];

            $query = "INSERT INTO team_results (week, team_id, score)" .
                "VALUES ($week,$teamId,$score)";

            mysqli_query($dbc, $query) or die('Error inserting data into angry_robots.team_results table in loadResultsIntoDB.');
        }

    }

/*
    //NO LONGER USED

    public function initializeEmptyScoreResultsByWeek($scoreResults, $week)
    {
        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT team_id, team_name " .
            " FROM teams ";

        //var_dump($query);

        $result = mysqli_query($dbc, $query)
        or die('Error querying database in initializeEmptyResults.');

        $matchCtr=0;

        while ($row = mysqli_fetch_array($result)){

            $scoreResults[$matchCtr]['teamId'] = $row['team_id'];
            $scoreResults[$matchCtr]['score'] = "";
            $scoreResults[$matchCtr]['teamName'] = $row['team_name'];
            $scoreResults[$matchCtr]['week'] = $week;

            $matchCtr++;
        }
    }


    public function loadResultsFromFile($fileName)
    {
        $txtFileData = file_get_contents($fileName);
        $rows = explode("\n", $txtFileData);
        echo "Team result file data array";

        for ($i=0; $i<count($rows); ++$i)
        {
            $results = explode("|", $rows[$i]);
            //echo "Current Team Result: ";
            //var_dump($name);
            $teamResults[$i][0] = $results[0];
            $teamResults[$i][1] = $results[1];
            $teamResults[$i][2] = $results[2];
            //var_dump($teamArray);
        }
        var_dump($teamResults);
        return $teamResults;

    }

    //NO LONGER USED
    //Takes results array from file and builds it out to fill in data for every team and to sort it nicely for display
    public function buildTeamResults($teamResults, $teamData, $week)
    {
        $matchCtr = 0;
        $teamResultsExpanded = array();

        for ($i=0; $i<count($teamData); ++$i)
        {
            $valI = $teamData[$i][1];
            $foundMatch = false;
            for ($j=0; $j<count($teamResults); ++$j)
            {
                $valJ = $teamResults[$j][1];

                if((int)$valI == (int)$valJ)                                         //if($teamData[$i][1] == $teamResults[$j][1]) //matching on teamID data
                {
                    //echo "found match" . "<br>";
                    //var_dump($valI, $valJ, $matchCtr, $week, $teamData[$i][0], $teamData[$i][1], $teamResults[$j][2]);

                    $teamResultsExpanded[$matchCtr]['week'] = $week; //week
                    $teamResultsExpanded[$matchCtr]['teamName'] = $teamData[$i][0]; //teamName
                    $teamResultsExpanded[$matchCtr]['teamId'] = $teamData[$i][1]; //teamId
                    $teamResultsExpanded[$matchCtr]['score'] = $teamResults[$j][2]; //score


                    $matchCtr++;
                    $foundMatch = true;
                }
            }
            if (!$foundMatch) //if we don't find data for this team in our teamResults file build out just the name and id information
            {
                //echo "Results not found for " . $teamData[$i][0] . "<br>";
                $teamResultsExpanded[$matchCtr]['week'] = $week; //week
                $teamResultsExpanded[$matchCtr]['teamName'] = $teamData[$i][0]; //teamName
                $teamResultsExpanded[$matchCtr]['teamId'] = $teamData[$i][1]; //teamId
                $teamResultsExpanded[$matchCtr]['score'] = ""; //score


                $matchCtr++;
            }
        }


        echo "teamResultsExpanded: " . "<br>";
        var_dump($teamResultsExpanded);
        return $teamResultsExpanded;

    }

*/

}