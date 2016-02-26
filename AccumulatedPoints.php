<?php
include("AccumulatedResults.php");
include("Tools.php");

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 2/25/2016
 * Time: 5:50 PM
 */

    $accumulatedResults = new AccumulatedResults(1,1,1,1);
    $resultInfo = array();

?>

<html>
<head>

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
    </form>
</head>
</html>

