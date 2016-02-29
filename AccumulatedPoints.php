<?php
include("Team.php"); //interesting... Need to include this class here...
include("TeamResult.php");
include("AccumulatedResults.php");
include("Tools.php");

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

    $teamResults = new TeamResult(0,0,0);
    $accumulatedResults = new AccumulatedResults(0,0,0,0);
    $team = new Team('',0);
    $scoreInfo = array();
    $resultInfo = array();
    $teams = array();

    $scoreInfo = $teamResults->loadResultsFromDB();
    $teams = $team->LoadTeamInfoFromDB();
    $numberOfTeams = count($teams);

    $resultInfo = $accumulatedResults->calculateRank($scoreInfo, $numberOfTeams);
    $resultInfo = $accumulatedResults->calculateAccumulatedResults($resultInfo);


    //so it seems like i should just cull the accumulated points table
    //per team and store all results data for one team in a multi-dimensional array
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="AccumulatedPoints.css">
    <title>Results Input Form</title>
</head>
<body>

<div class="header">
    <h1>Angry Robots Accumulated Points</h1>
</div>

<table>
    <!-- If we find a new week add it to the column header-->
    <thead>
    <tr>
        <th>Teams</th>
        <?php
            $currentWeek = 0;
            for ($i=0; $i<count($resultInfo); ++$i)
            {
                if($currentWeek != $scoreInfo[$i]['week'])
                {
                    echo '<th>Week ' . $scoreInfo[$i]['week'] . '</th>';
                }
                $currentWeek = $scoreInfo[$i]['week'];
            }
        ?>
    </tr>
    </thead>

    <tbody>
    <?php
        $currentTeamId = 0;
        for ($i=0; $i<count($teams); ++$i)
        {
            echo '<tr>';
            echo '<td>' . $teams[$i]['teamName'] . '</td>';
            for ($j=0; $j<count($scoreInfo); ++$j)
            {
                if($teams[$i]['teamId'] == $scoreInfo[$j]['teamId'])
                {
                    echo '<td>' . $resultInfo[$j]['weeklyRank'] . '</td>';
                }
            }
            echo '</tr>';
        }
    ?>

    </tbody>
</table>

</body>
</html>

