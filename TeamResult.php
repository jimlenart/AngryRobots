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

}
