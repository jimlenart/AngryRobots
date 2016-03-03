<?php

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 1/14/2016
 * Time: 4:49 PM
 */
class TeamResult extends Team
{
    public function displayTeamResultsModule($year)
    {
        $teamResultData = $this->getTeamResultsFromDB($year);
        $sortedTeamTotals = $this->getSortedTeamTotals($year);
        $this->buildTeamResultsModule($teamResultData, $sortedTeamTotals);
    }

    public function getSortedTeamTotals($year)
    {
        $matchCtr = 0;
        $teamTotals = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT ROUND(SUM(r.score),1) as score, r.team_id, t.team_name " .
            " FROM team_results r LEFT JOIN teams t" .
            " ON r.team_id = t.team_id" .
            " WHERE r.year = '$year'" .
            " GROUP BY r.team_id" .
            " ORDER BY score DESC";

        $result = mysqli_query($dbc, $query)
        or die('Error querying database in loadResultsFromDB.');

        while ($row = mysqli_fetch_array($result)){

            $teamTotals[$matchCtr]['teamId'] = $row['team_id'];
            $teamTotals[$matchCtr]['score'] = $row['score'];
            $teamTotals[$matchCtr]['teamName'] = $row['team_name'];
            $teamTotals[$matchCtr]['year'] = $year;

            $matchCtr++;
        }
        mysqli_close($dbc);
        return $teamTotals;
    }

    public function calculateRank($sortedScoreResults, $numberOfTeams)
    {
        $accumulatedCtr = $numberOfTeams;
        $previousScore=0;
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

    public function getAccumulatedScoreByTeam($teamResultData, $teamId)
    {
        $accumulatedTeamScore = 0;
        for ($i=0; $i<count($teamResultData); ++$i) {
            if($teamResultData[$i]['teamId'] == $teamId)
            {
                $accumulatedTeamScore = $accumulatedTeamScore + $teamResultData[$i]['score'];
            }
        }
        return $accumulatedTeamScore;
    }

    public function getTeamResultsFromDBByWeek($year, $week)
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

    public function buildTeamResultsModule($teamResultData, $sortedTeamTotals)
    {
        ?>

        <html>
        <head>
            <link rel="stylesheet" type="text/css" href="AccumulatedPoints.css">
            <title>Results Input Form</title>
        </head>
        <body>

        <div class="header">
            <h1>Team Scores</h1>
        </div>

        <table>
            <!-- If we find a new week add it to the column header-->
            <thead>
            <tr>
                <th>Teams</th>
                <?php
                    $currentWeek = 0;
                    for ($i=0; $i<count($teamResultData); ++$i)
                    {
                        if($currentWeek != $teamResultData[$i]['week'])
                        {
                            echo '<th>Week ' . $teamResultData[$i]['week'] . '</th>';
                        }
                        $currentWeek = $teamResultData[$i]['week'];
                    }
                ?>
                <th>Total</th>
            </tr>
            </thead>

            <tbody>
            <?php
                for ($i=0; $i<count($sortedTeamTotals); ++$i)
                {
                    echo '<tr>';
                    echo '<td>' . $sortedTeamTotals[$i]['teamName'] . '</td>';
                    for ($j=0; $j<count($teamResultData); ++$j)
                    {
                        if($sortedTeamTotals[$i]['teamId'] == $teamResultData[$j]['teamId'])
                        {
                            echo '<td>' . $teamResultData[$j]['score'] . '</td>';
                        }
                    }

                    echo '<td>' . $sortedTeamTotals[$i]['score']  . '</td>';
                    echo '</tr>';
                }
            ?>

            </tbody>
        </table>

        </body>
        </html>

        <?php
    }

}
