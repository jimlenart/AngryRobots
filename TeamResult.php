<?php

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 1/14/2016
 * Time: 4:49 PM
 */
class TeamResult extends Team
{
    public $year;
    public $week;
    public $score;
    public $rank;


    public function __construct($year, $teamId, $week, $score)
    {
        $this->year = $year;
        $this->teamId = $teamId;
        $this->week = $week;
        $this->score = $score;
    }

    public function calculateRank($sortedScoreResults, $numberOfTeams)
    {
        $accumulatedCtr = $numberOfTeams;
        //$currentScore=0;
        $previousScore=0;
        //$currentWeek=0;
        $previousWeek=0;

        for ($i=0; $i<count($sortedScoreResults); ++$i)
        {
            if(!empty($sortedScoreResults[$i]['score']))
            {
                $currentScore = $sortedScoreResults[$i]['score'];
                $currentWeek = $sortedScoreResults[$i]['week'];
                if($currentWeek != $previousWeek)
                {
                    $accumulatedCtr = $numberOfTeams; //reset the counter if we advanced to the next week
                }

                if ($currentScore == $previousScore)  //handle 2 way ties
                {
                    //split the rank between the tied scores
                    $sortedScoreResults[$i]['rank'] = (($accumulatedCtr + $accumulatedCtr+1) / 2);;
                    $sortedScoreResults[$i-1]['rank'] = (($accumulatedCtr + $accumulatedCtr+1) / 2);;
                } else {
                    $sortedScoreResults[$i]['rank'] = $accumulatedCtr;
                }
                $accumulatedCtr--;
                $previousScore = $currentScore;
                $previousWeek = $currentWeek;
            }
        }
        //var_dump($sortedScoreResults);
        return $sortedScoreResults;
    }

    public function getResultsFromDBByWeek($year, $week)
    {
        $matchCtr = 0;
        $scoreResults = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
            or die ('Error connecting to MySQL server.');

        $query = "SELECT t.team_id, r.score, t.team_name, r.rank " .
            " FROM teams t LEFT JOIN team_results r " .
            " ON r.week='$week'" .
            " AND r.team_id = t.team_id " .
            " AND r.year = '$year'";

        $result = mysqli_query($dbc, $query)
            or die('Error querying database in loadResultsFromDB.');

        while ($row = mysqli_fetch_array($result)){

            $scoreResults[$matchCtr]['teamId'] = $row['team_id'];
            $scoreResults[$matchCtr]['score'] = $row['score'];
            $scoreResults[$matchCtr]['teamName'] = $row['team_name'];
            $scoreResults[$matchCtr]['week'] = $week;
            $scoreResults[$matchCtr]['year'] = $year;

            $matchCtr++;
        }
        mysqli_close($dbc);
        return $scoreResults;
    }

    public function getTeamResultsFromDB($year)
    {
        $matchCtr = 0;
        $teamResults = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT t.team_id, r.score, t.team_name, r.week, r.rank " .
            " FROM teams t LEFT JOIN team_results r " .
            " ON r.team_id = t.team_id AND r.year = '$year'" .
            " ORDER BY r.week, r.score DESC";

        //var_dump($query);

        $result = mysqli_query($dbc, $query)
        or die('Error querying database in loadResultsFromDB.');

        while ($row = mysqli_fetch_array($result)){

            $teamResults[$matchCtr]['teamId'] = $row['team_id'];
            $teamResults[$matchCtr]['score'] = $row['score'];
            $teamResults[$matchCtr]['teamName'] = $row['team_name'];
            $teamResults[$matchCtr]['week'] = $row['week'];
            $teamResults[$matchCtr]['rank'] = $row['rank'];

            $matchCtr++;
        }

        //var_dump($teamResults);
        mysqli_close($dbc);
        return $teamResults;
    }

    public function loadTeamResultsIntoDB($year, $week, $scoreResults)
    {
        //var_dump($scoreResults);
        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
            or die ('Error connecting to MySQL server.');

        $query = "DELETE FROM team_results WHERE week = $week AND year = $year";
        mysqli_query($dbc, $query) or die('Error deleting data in angry_robots.team_results table in loadTeamResultsIntoDB.');

        for ($i=0; $i<count($scoreResults); ++$i)
        {
            $score = $scoreResults[$i]['score'];
            $rank = $scoreResults[$i]['rank'];
            $score = !empty($score) ? "$score" : "NULL";
            $rank = !empty($rank) ? "$rank" : "NULL";
            $teamId = $scoreResults[$i]['teamId'];

            $query = "INSERT INTO team_results (year, week, team_id, score, rank)" .
                "VALUES ($year, $week,$teamId,$score, $rank)";

            mysqli_query($dbc, $query) or die('Error inserting data into angry_robots.team_results table in loadTeamResultsIntoDB.');
        }
        mysqli_close($dbc);

    }

}
