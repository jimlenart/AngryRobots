<?php

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 2/25/2016
 * Time: 5:50 PM
 *
 * GOAL:
 * To make this a stand alone form that will just display accumulated results info.
 * It should:
 * 1. Allow user to see all accumulated results with a sum total
 * 2. Allow user to see all points scored for each team with a sum total
 * 3. Eventually have some nice UI
 */

class AccumulatedPoints
{
    public function displayAccumulatedPoints($year)
    {
        $teamResults = new TeamResult();

        $teamResultData = $teamResults->getTeamResultsFromDB($year);
        $accumulatedPointsData = $this->getAccumulatedPoints($year);

        $this->buildAccumulatedPointsModule($teamResultData, $accumulatedPointsData);
    }

    public function getAccumulatedPoints($year)
    {
        $matchCtr = 0;
        $results = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT t.team_name, a.team_id, a.accumulated_points " .
            " FROM accumulated_points a LEFT JOIN teams t" .
            " ON a.team_id = t.team_id" .
            " WHERE a.year ='$year'" .
            " ORDER BY a.accumulated_points DESC";

        //var_dump($query);
        $result = mysqli_query($dbc, $query)
        or die('Error querying database in getAccumulatedPoints.');

        while ($row = mysqli_fetch_array($result)){

            $results[$matchCtr]['teamId'] = $row['team_id'];
            $results[$matchCtr]['teamName'] = $row['team_name'];
            $results[$matchCtr]['accumulatedPoints'] = $row['accumulated_points'];
            $results[$matchCtr]['year'] = $year;

            $matchCtr++;
        }
        mysqli_close($dbc);
        return $results;
    }

    public function calculateAccumulatedPointsByTeamRank($year)
    {
        $matchCtr = 0;
        $accumulatedPoints = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT SUM(r.rank) as points, r.team_id, t.team_name" .
            " FROM team_results r LEFT JOIN teams t" .
            " ON r.team_id = t.team_id" .
            " WHERE r.year ='$year'" .
            " GROUP BY r.team_id" .
            " ORDER BY points DESC";

        //var_dump($query);
        $result = mysqli_query($dbc, $query)
        or die('Error querying database in getAccumulatedPoints.');

        while ($row = mysqli_fetch_array($result)){

            $accumulatedPoints[$matchCtr]['teamId'] = $row['team_id'];
            $accumulatedPoints[$matchCtr]['teamName'] = $row['team_name'];
            $accumulatedPoints[$matchCtr]['accumulatedPoints'] = $row['points'];
            $accumulatedPoints[$matchCtr]['year'] = $year;

            $matchCtr++;
        }
        mysqli_close($dbc);
        return $accumulatedPoints;
    }

    public function deleteAccumulatedPoints($year)
    {
        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "DELETE FROM accumulated_points WHERE year ='$year'";
        mysqli_query($dbc, $query) or die('Error deleting data in angry_robots.accumulated_points table in deleteAccumulatedPoints.');

        mysqli_close($dbc);
    }

    public function loadAccumulatedPointsIntoDB($accumulatedPoints)
    {
        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        for ($i=0; $i<count($accumulatedPoints); ++$i)
        {
            $teamId = $accumulatedPoints[$i]['teamId'];
            $points = $accumulatedPoints[$i]['accumulatedPoints'];
            $year = $accumulatedPoints[$i]['year'];

            $query = "INSERT INTO accumulated_points (year, team_id, accumulated_points) " .
                "VALUES ($year,$teamId, $points)";

            mysqli_query($dbc, $query) or die('Error inserting data into angry_robots.accumulated_points table in loadAccumulatedPointsIntoDB.');
        }
        mysqli_close($dbc);
    }

    function buildAccumulatedPointsModule($teamResultData, $accumulatedResultsData)
    {
        ?>

        <html>
        <head>
            <link rel="stylesheet" type="text/css" href="AccumulatedPoints.css">
            <title>Results Input Form</title>
        </head>
        <body>

        <div class="header">
            <h1>Accumulated Points</h1>
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
            for ($i=0; $i<count($accumulatedResultsData); ++$i)
            {
                echo '<tr>';
                echo '<td>' . $accumulatedResultsData[$i]['teamName'] . '</td>';
                for ($j=0; $j<count($teamResultData); ++$j)
                {
                    if($accumulatedResultsData[$i]['teamId'] == $teamResultData[$j]['teamId'])
                    {
                        echo '<td>' . $teamResultData[$j]['rank'] . '</td>';
                    }
                }
                echo '<td>' . $accumulatedResultsData[$i]['accumulatedPoints'] . '</td>';
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

?>
