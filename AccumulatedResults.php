<?php

/**
 * Created by PhpStorm.
 * User: jim.lenart
 * Date: 2/22/2016
 * Time: 3:18 PM
 */
class AccumulatedResults extends TeamResult
{
    public $accumulatedPoints;
    public $weeklyRank;

    public function __construct($week, $teamId, $accumulatedPoints, $weeklyRank)
    {
        $this->teamId = $teamId;
        $this->week = $week;
        $this->$accumulatedPoints = $accumulatedPoints;
        $this->weeklyRank = $weeklyRank;
    }

    //For a given week, calculate the rank value.
    public function calculateWeeklyRank($sortedScoreResults, $week)
    {


        echo $week;

        //Loop through results sorted by highest score. Highest score = 10, next 9..etc.
        $accumulatedCtr = 10;
        for ($i=0; $i<count($sortedScoreResults); ++$i)
        {
            $sortedScoreResults[$i]['weeklyRank'] = $accumulatedCtr;
            $accumulatedCtr--;
        }

        var_dump($sortedScoreResults);

        return $sortedScoreResults;
    }

    //For a given week, calculate the rank value.
    public function calculateWeeklyRankHandleTwoWayTie($sortedScoreResults, $week)
    {

        //Loop through results sorted by highest score. Highest score = 10, next 9..etc.
        $accumulatedCtr = 10;
        $accumulatedVal = 0;
        $currentScore=0;
        $previousScore=0;

        for ($i=0; $i<count($sortedScoreResults); ++$i)
        {
            if(!empty($sortedScoreResults[$i]['score']))
            {
                $currentScore = $sortedScoreResults[$i]['score'];
                if ($currentScore == $previousScore)
                {
                    //split the rank between the tied scores
                    $accumulatedVal = ($accumulatedCtr + $accumulatedCtr+1) / 2;
                    $sortedScoreResults[$i]['weeklyRank'] = $accumulatedVal;
                    $sortedScoreResults[$i-1]['weeklyRank'] = $accumulatedVal;
                } else {
                    $sortedScoreResults[$i]['weeklyRank'] = $accumulatedCtr;
                }
                $accumulatedCtr--;
                $previousScore = $currentScore;
            }
        }
        //var_dump($sortedScoreResults);
        return $sortedScoreResults;
    }

    public function calculateAccumulatedResultsByWeek($accumulatedResults, $week)
    {
        for ($i=0; $i<count($accumulatedResults); ++$i)
        {
            if(!empty($accumulatedResults[$i]['score']))
            {
                if ($week == 1)
                {
                    $accumulatedResults[$i]['accumulatedPoints'] = $accumulatedResults[$i]['weeklyRank'];

                } else {
                    $prevAccumulated = $this->getAccumulatedPointsByWeek($accumulatedResults[$i]['teamId'], $week-1); //get the sum of all previous accumulated points
                    $accumulatedResults[$i]['accumulatedPoints'] = $accumulatedResults[$i]['weeklyRank'] + $prevAccumulated;
                }

            } else {

                $accumulatedResults[$i]['accumulatedPoints'] = "";
            }
        }
        //var_dump($accumulatedResults);
        return $accumulatedResults;
    }


    public function loadAccumulatedResultsIntoDBByWeek($accumulatedResults, $week)
    {
        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "DELETE FROM accumulated_rankings WHERE week = $week";
        mysqli_query($dbc, $query) or die('Error deleting data in angry_robots.accumulated_rankings table in loadAccumulatedResultsIntoDBByWeek.');

        for ($i=0; $i<count($accumulatedResults); ++$i)
        {
            if(!empty($accumulatedResults[$i]['score']))
            {
                $teamId = $accumulatedResults[$i]['teamId'];
                $weeklyRank = $accumulatedResults[$i]['weeklyRank'];
                $accumulatedPoints = $accumulatedResults[$i]['accumulatedPoints'];


                $query = "INSERT INTO accumulated_rankings (week, team_id, rank, accumulated_points)" .
                    "VALUES ($week,$teamId,$weeklyRank, $accumulatedPoints)";

                mysqli_query($dbc, $query) or die('Error inserting data into angry_robots.accumulated_rankings table in loadAccumulatedResultsIntoDBByWeek.');
            }
        }
    }

    public function getAccumulatedPointsByWeek($teamId, $week)
    {
        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT SUM(accumulated_points) " .
            " FROM accumulated_rankings " .
            " WHERE team_id = $teamId" .
            " AND week = $week";

        //var_dump($query);

        $result = mysqli_query($dbc, $query)
        or die('Error querying database in getAccumulatedPointsByWeek.');

        while ($row = mysqli_fetch_array($result)){
            $totalPoints = $row['SUM(accumulated_points)'];
        }

        //var_dump($totalPoints);
        $totalPoints = !empty($totalPoints) ? "$totalPoints" : "0";
        return $totalPoints;
    }
}