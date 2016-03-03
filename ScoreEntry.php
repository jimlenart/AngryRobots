<?php
    include("Team.php");
    include("TeamResult.php");
    include("Tools.php");
    include("AccumulatedPoints.php");

    $team = new Team("A", 1); //bogus dude
    $teamResults = new TeamResult(1,1,1,1);
    $teamResultData = array();
    $teamData = array();
    $weeks = array(1,2,3,4); //will make this dynamic / user driven in future
    $selectedYear = 2015;  //needs to be dynamic in the future
    $accumulatedPoints = new AccumulatedPoints($selectedYear,1,1);


    if(isset($_POST['WeekDropdown']) )
    {
        $selectedWeek = $_POST['WeekDropdown'];
    } else {
        $selectedWeek = 1; //default to week 1 if not selected yet
    }

    $teamResultData = $teamResults->getResultsFromDBByWeek($selectedYear, $selectedWeek);
    //$sortedTeamResultData = sortArrayByIndex($teamResultData, 'score'); //we used to need this for accumulated points calculation - might not need anymore.

    if(isset($_POST['saveButton']))
    {
        //echo "POST saveButton";
        $teamResultData = gatherUserResults($teamResultData);
        $teamResultData = sortArrayByIndex($teamResultData, 'score');
        $teamData = $team->GetTeamsFromDB();
        $numberOfTeams = count($teamData);
        $teamResultData = $teamResults->calculateRank($teamResultData,$numberOfTeams);
        $teamResults->loadTeamResultsIntoDB($selectedYear, $selectedWeek, $teamResultData);
        $accumulatedPointsData = $accumulatedPoints->calculateAccumulatedPointsByTeamRank($selectedYear);
        $accumulatedPoints->deleteAccumulatedPoints($selectedYear);
        $accumulatedPoints->loadAccumulatedPointsIntoDB($accumulatedPointsData);
    }

    //grab results from user, save to DB so they will be displayed when form reloads
    function gatherUserResults($teamResultData)
    {
        for ($i=0; $i<count($teamResultData); ++$i)
        {
            $index = 'teamIndex' . $i;  //dynamically build our team index
            //echo "index: " . $index;
            $currentScore = $_POST[$index];
            //echo "currentScore: " . $currentScore . "<br>";
            $teamResultData[$i]['score'] = $currentScore;
        }

        return $teamResultData;
    }
?>

<!-- TODO - build this out into a separate function that can be called from anywhere similar to accumulated points -->

<html>
<head>
    <link rel="stylesheet" type="text/css" href="AngryRobotsStyleSheet.css">
    <title>Results Input Form</title>
</head>
<body>


<form NAME ="resultInput" ACTION= "<?php $_SERVER['PHP_SELF'] ?>" METHOD="POST">
    <select name="WeekDropdown">

        <?php
        // Iterating through the week array
        foreach($weeks as $item){
            echo '<option value="'.$item.'"';
            if($item==$selectedWeek)
            {
                echo ' selected';
            }
            echo '>'. $item . '</option>'."\n";
        }
        ?>

    </select>
    <input type="submit" name="WeekSelect" value="Change Week">

    <fieldset>
        <legend>Enter Scores For Week <?php $selectedWeek?> </legend>

        <?php
        for ($i=0; $i<count($teamResultData); ++$i)
        {
            echo '<label class="field" >' . $teamResultData[$i]['teamName'] . '</label>';
            echo '<label><INPUT TYPE = "TEXT" VALUE = "' . $teamResultData[$i]['score'] . '" name="teamIndex' .$i. '" size="5"> </label><br>';
        }
        ?>

        <INPUT TYPE = "submit" Name = "saveButton" VALUE = "save">
    </fieldset>

</FORM>

<?php
    //$accumulated = new AccumulatedPoints($selectedYear);
    $accumulatedPoints->displayAccumulatedPoints($selectedYear);
?>
</body>
</html>
