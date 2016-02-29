<?php
include("Team.php");
include("TeamResult.php");
include("AccumulatedResults.php");
include("Tools.php");

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 1/11/2016
 * Time: 2:33 PM
 *
 *  1. Read teams from text file -DONE 1/12/16
 *  2. Show UI to enter scores for a week -DONE
 *  3. Grab results data from file -DONE
 *  4. Display results data from file -DONE
 *   4a. Build multi-dimensional array better so we can use the built in sorting function -DONE
 *  5. Get data from DB instead of file -DONE
 *  6. Allow user to edit results data (and save it back to DB) -DONE
 *  7. Show accumulated point ranking based on scores entered -DONE  2/24/16
 *  8. Allow ability to change week -DONE 2/25/16
 *  TODO 9.  Clean up HTML with some loops
 *  TODO 10. Show All Weeks we have accumulated points for in that section with each weekly value and a summed total
 *  TODO 11. Allow user to add a new week.
 *  TODO 12. Create log in page
 *  TODO 13. Adjust score entry to only display on a separate form with a user log in
 *  TODO 14. Fix getAccumulatedResultsByWeek (see to do noted there).
 *  TODO 15. Understand OO code here - refactor so we're not building "fake" objects
 *    TODO 15a. Understand the include command up top - is there is a package I could create for this instead of adding one by one.
 *  TODO 16. Clean up the UI to make it look better
 *  TODO 17. Use some javascript to be able to remove the select button for changing weeks (invoke a change event on drop down list change)
 *  TODO 18. Handle 3 way ties in calculateRank
 *  TODO 19. Read up on session management
 *
 */

    $team = new Team("A", 1); //bogus dude
    $teamResults = new TeamResult(1,1,1);
    $accumulatedResults = new AccumulatedResults(1,1,1,1);
    $scoreInfo = array();
    $resultInfo = array();
    $numberOfTeams = 10; //this should be dynamic in the future instead of hard coded.
    $weeks = array(1,2,3,4); //will make this dynamic / user driven in future
    $selectedWeek;

    if(isset($_POST['WeekDropdown']) )
    {
        $selectedWeek = $_POST['WeekDropdown'];
    } else {
        $selectedWeek = 1; //default to week 1 if not selected yet
    }

    $scoreInfo = $teamResults->loadResultsFromDBByWeek($selectedWeek);
    $scoreInfo = sortArrayByIndex($scoreInfo, 'score'); //we used to need this for accumulated points calculation - might not need anymore.

    if(isset($_POST['saveButton']))
    {
        //echo "POST saveButton";
        gatherUserResults($scoreInfo, $teamResults, $selectedWeek);
        $scoreInfo = $teamResults->loadResultsFromDBByWeek($selectedWeek);
        $scoreInfo = sortArrayByIndex($scoreInfo, 'score');
    }

    //grab results from user, save to DB so they will be displayed when form reloads
    function gatherUserResults($scoreInfo, $teamResults, $week)
    {
        for ($i=0; $i<count($scoreInfo); ++$i)
        {
            $index = 'teamIndex' . $i;  //dynamically build our team index
            //echo "index: " . $index;
            $currentScore = $_POST[$index];
            //echo "currentScore: " . $currentScore . "<br>";
            $scoreInfo[$i]['score'] = $currentScore;
        }

        $teamResults->loadResultsIntoDBByWeek($scoreInfo, $week);
    }

    //$resultInfo = $accumulatedResults->calculateWeeklyRank($scoreInfo, $selectedWeek);
    $resultInfo = $accumulatedResults->calculateRank($scoreInfo, $numberOfTeams);
    $resultInfo = $accumulatedResults->calculateAccumulatedResultsByWeek($resultInfo, $selectedWeek);
    $accumulatedResults->loadAccumulatedResultsIntoDBByWeek($resultInfo, $selectedWeek);

    $resultInfo = sortArrayByIndex($resultInfo, 'accumulatedPoints');

    //echo " selected week -" . $selectedWeek;

?>


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
        for ($i=0; $i<count($scoreInfo); ++$i)
        {
            echo '<label class="field" >' . $scoreInfo[$i]['teamName'] . '</label>';
            echo '<label><INPUT TYPE = "TEXT" VALUE = "' . $scoreInfo[$i]['score'] . '" name="teamIndex' .$i. '" size="5"> </label><br>';
        }
        ?>

        <INPUT TYPE = "submit" Name = "saveButton" VALUE = "save">
    </fieldset>

</FORM>

<form NAME ="AccumulatedPointsOutput" ACTION= "<?php $_SERVER['PHP_SELF'] ?>" >
    <fieldset>
        <legend>Accumulated Points</legend>

        <?php $htmlCtr = 0; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <?php $htmlCtr++; ?>
        <label class="field" > <?php echo $resultInfo[$htmlCtr]['teamName']?> </label>
        <label><?php echo $resultInfo[$htmlCtr]['accumulatedPoints'] ?> </label><br>
        <br>

    </fieldset>

</FORM>
</body>
</html>
