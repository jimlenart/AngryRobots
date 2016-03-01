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
    public function __construct()
    {

        $teamResults = new TeamResult(0,0,0);
        $accumulatedResults = new AccumulatedResults(0,0,0,0);
        $team = new Team('',0);

        $scoreInfo = $teamResults->GetTeamResultsFromDB();
        $teams = $team->GetTeamsFromDBSortedByAccumulatedPoints();
        $numberOfTeams = count($teams);

        $resultInfo = $accumulatedResults->calculateRank($scoreInfo, $numberOfTeams); //should we just grab this info from DB instead of calculate it?
        $resultInfo = $accumulatedResults->calculateAccumulatedResults($resultInfo);
        $accumulatedResults->loadAccumulatedResultsIntoDB($resultInfo);

        buildAccumulatedPoints($resultInfo, $teams);
    }
}

?>
