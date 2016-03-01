<?php
/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 2/25/2016
 * Time: 5:54 PM
 */

//given an array and an index of that array sort by that index and return the array
function sortArrayByIndex($inputArray, $indexString)
{
    $sortedArray = array();

    foreach ($inputArray as $array)
    {
        $sortedArray[] = (int)$array[$indexString];
    }

    array_multisort($sortedArray, SORT_DESC, $inputArray);
    return $inputArray;
}

function buildAccumulatedPoints($resultInfo, $teams)
{
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
                if($currentWeek != $resultInfo[$i]['week'])
                {
                    echo '<th>Week ' . $resultInfo[$i]['week'] . '</th>';
                }
                $currentWeek = $resultInfo[$i]['week'];
            }
            ?>
            <th>Total</th>
        </tr>
        </thead>

        <tbody>
        <?php
        $lastAccumulatedPoints = 0;
        for ($i=0; $i<count($teams); ++$i)
        {
            echo '<tr>';
            echo '<td>' . $teams[$i]['teamName'] . '</td>';
            for ($j=0; $j<count($resultInfo); ++$j)
            {
                if($teams[$i]['teamId'] == $resultInfo[$j]['teamId'])
                {
                    echo '<td>' . $resultInfo[$j]['weeklyRank'] . '</td>';
                    $lastAccumulatedPoints = $resultInfo[$j]['accumulatedPoints'];
                }
            }
            echo '<td>' . $lastAccumulatedPoints . '</td>';
            echo '</tr>';
        }
        ?>

        </tbody>
    </table>

    </body>
    </html>


    <?php
}


?>
