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

function buildAccumulatedPoints($teamResultData, $accumulatedResultsData)
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


?>
