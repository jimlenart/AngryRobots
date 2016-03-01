<?php
include("Team.php");
include("TeamResult.php");
include("AccumulatedResults.php");
include("Tools.php");
include("AccumulatedPoints.php");

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

<?php
    $accumulated = new AccumulatedPoints();
?>
</body>
</html>
