<?php
include("Team.php");
include("TeamResult.php");
include("Tools.php");
include("AccumulatedPoints.php");

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 1/11/2016
 * Time: 2:33 PM
 *
 *  TODO - See https://freedcamp.com/jimlenarts_Projects_aRj/Angry_Robots_s07/todos
 */
    $accumulated = new AccumulatedPoints();
    $teamResult = new TeamResult();
    $accumulated->displayAccumulatedPoints(2015);
    $teamResult->displayTeamResultsModule(2015);
