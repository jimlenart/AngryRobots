<?php
    include("Team.php");
    include("TeamResult.php");
    include("Tools.php");
    include("AccumulatedPoints.php");
    include("Seasons.php");

    $team = new Team();
    $teamResults = new TeamResult();
    $season = new Seasons();
    $accumulatedPoints = new AccumulatedPoints();
    $teamResultData = array();
    $selectedYear = $season->getCurrentSeason(); //default to current season

    if(isset($_POST['WeekDropdown']) )
    {
        $selectedWeek = $_POST['WeekDropdown'];
    } else {
        $selectedWeek = 1; //default to week 1 if not selected yet
    }

    $teamResultData = $teamResults->getTeamResultsFromDBByWeek($selectedYear, $selectedWeek);
    $teamResultData = sortArrayByIndex($teamResultData, 'score');

    if(isset($_POST['saveButton']))
    {
        $teamResultData = gatherUserResults($teamResultData);
        $sortedTeamResultData = sortArrayByIndex($teamResultData, 'score');
        $numberOfTeams = $team->getNumberOfTeams();
        $teamResultData = $teamResults->calculateRank($sortedTeamResultData,$numberOfTeams);
        $teamResults->loadTeamResultsIntoDB($selectedYear, $selectedWeek, $teamResultData);
        $accumulatedPointsData = $accumulatedPoints->calculateAccumulatedPointsByTeamRank($selectedYear);
        $accumulatedPoints->deleteAccumulatedPoints($selectedYear);
        $accumulatedPoints->loadAccumulatedPointsIntoDB($accumulatedPointsData);
    }

    //UI Components
    displayScoreEntryForm($selectedYear, $selectedWeek);
    $accumulatedPoints->displayAccumulatedPoints($selectedYear);
    $teamResults->displayTeamResultsModule($selectedYear);

    //grab results from user input
    function gatherUserResults($teamResultData)
    {
        for ($i=0; $i<count($teamResultData); ++$i)
        {
            $index = 'teamIndex' . $i;  //dynamically build our team index
            $currentScore = $_POST[$index];
            $teamResultData[$i]['score'] = $currentScore;
        }
        return $teamResultData;
    }

    function displayWeekPickerModule($selectedYear, $selectedWeek)
    {
        $season = new Seasons();
        $weeks = $season->getWeeksArrayByYear($selectedYear);
        buildWeekPickerModule($selectedWeek, $weeks);
    }

    function buildWeekPickerModule($selectedWeek, $weeks)
    {
        ?>
        <label class="field" >
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
        </label>
        <?php
    }

    function displayScoreEntryModule($selectedYear, $selectedWeek)
    {
        $teamResults = new TeamResult();
        $teamResultData = $teamResults->getTeamResultsFromDBByWeek($selectedYear, $selectedWeek);
        $teamResultData = sortArrayByIndex($teamResultData, 'score');
        buildScoreEntryModule($teamResultData);
    }

    function buildScoreEntryModule($teamResultData)
    {
        ?>
        <label class="field">  </label>
        <?php
                for ($i=0; $i<count($teamResultData); ++$i)
                {
                    echo '<label class="field" >' . $teamResultData[$i]['teamName'] . '</label>';
                    echo '<label><INPUT TYPE = "TEXT" VALUE = "' . $teamResultData[$i]['score'] . '" name="teamIndex' .$i. '" size="5"> </label><br>';
                }
            ?>
        <?php
    }

    function displayScoreEntryForm($selectedYear, $selectedWeek)
    {
        ?>
            <html>
            <head>
                <link rel="stylesheet" type="text/css" href="AngryRobotsStyleSheet.css">
                <title>Results Input Form</title>
            </head>
            <body>
                <form Name = "ScoreEntry"  ACTION= "<?php $_SERVER['PHP_SELF'] ?>" METHOD="POST">

                    <?php
                        $season = new Seasons();
                        $season->displayYearPickerModule($selectedYear);
                        displayWeekPickerModule($selectedYear, $selectedWeek);
                    ?>
                    <br>
                    <fieldset>
                        <legend>Enter Scores For Week <?php echo $selectedWeek?> </legend>

                        <?php
                            displayScoreEntryModule($selectedYear, $selectedWeek);
                        ?>
                        <INPUT TYPE = "submit" Name = "saveButton" VALUE = "save">

                    </fieldset>

                </form>
            </body>
            </html>
        <?php
    }

?>
