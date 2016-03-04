<?php

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 3/3/2016
 * Time: 4:09 PM
 */
class Seasons
{
    public function getSeasonsFromDB()
    {
        $matchCtr = 0;
        $seasonData = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT * FROM angry_robots.seasons ";

        $result = mysqli_query($dbc, $query)
        or die('Error querying database in Seasons->getSeasonsFromDB.');

        while ($row = mysqli_fetch_array($result)){

            $seasonData[$matchCtr]['year'] = $row['year'];
            $seasonData[$matchCtr]['weeks'] = $row['weeks'];
            $seasonData[$matchCtr]['currentSeason'] = $row['current_season'];

            $matchCtr++;
        }
        //var_dump($seasonData);
        mysqli_close($dbc);
        return $seasonData;
    }

    public function getCurrentSeason()
    {
        $seasonData = $this->getSeasonsFromDB();
        for ($i=0; $i<count($seasonData); ++$i)
        {
            if($seasonData[$i]['currentSeason'] == 'Y')
            {
                return $seasonData[$i]['year'];
            }
        }
        return ""; //this should not happen unless there is a data issue
    }

    public function getWeeksByYear($year)
    {
        $seasonData = $this->getSeasonsFromDB();
        for ($i=0; $i<count($seasonData); ++$i)
        {
            if($seasonData[$i]['year'] == $year)
            {
                return $seasonData[$i]['weeks'];
            }
        }
        return ""; //this should not happen unless there is a data or input year problem
    }

    public function getWeeksOfCurrentSeason()
    {
        $seasonData = $this->getSeasonsFromDB();
        for ($i=0; $i<count($seasonData); ++$i)
        {
            if($seasonData[$i]['currentSeason'] == 'Y')
            {
                return $seasonData[$i]['weeks'];
            }
        }
        return ""; //this should not happen unless there is a data issue
    }

    public function getWeeksArrayByYear($year)
    {
        $weeks = $this->getWeeksByYear($year);
        $weekArray = array();
        for ($i=1; $i<=$weeks; ++$i)
        {
            $weekArray[] = $i;
        }
        //var_dump($weekArray);
        return $weekArray;
    }

    public function getYearsArray()
    {
        $seasonData = $this->getSeasonsFromDB();
        $yearsArray = array();

        for ($i=0; $i<count($seasonData); ++$i)
        {
            $yearsArray[] = $seasonData[$i]['year'];
        }
        return $yearsArray;
    }

    public function displayYearPickerForm($selectedYear)
    {
        $years = $this->getYearsArray();
        $this->buildYearPickerForm($years, $selectedYear);
    }

    public function displayYearPickerModule($selectedYear)
    {
        $years = $this->getYearsArray();
        $this->buildYearPickerModule($years, $selectedYear);
    }

    public function buildYearPickerForm($years, $selectedYear)
    {
        ?>

        <html>
        <head>
            <link rel="stylesheet" type="text/css" href="AngryRobotsStyleSheet.css">
            <title>Seasons Year Picker Form</title>
        </head>
        <body>

        <form NAME ="yearDropdownForm" ACTION= "<?php $_SERVER['PHP_SELF'] ?>" METHOD="POST">
            <select name="YearDropdown">

                <?php
                    foreach($years as $item){
                        echo '<option value="'.$item.'"';
                        if($item==$selectedYear)
                        {
                            echo ' selected';
                        }
                        echo '>'. $item . '</option>'."\n";
                    }
                ?>

            </select>
            <input type="submit" name="YearSelect" value="Change Year">

        </form>
        </body>
        </html>
        <?php
    }

    public function buildYearPickerModule($years, $selectedYear)
    {
        ?>
        <label class="field" >
            <select name="YearDropdown">

                <?php
                    foreach($years as $item){
                        echo '<option value="'.$item.'"';
                        if($item==$selectedYear)
                        {
                            echo ' selected';
                        }
                        echo '>'. $item . '</option>'."\n";
                    }
                ?>
            </select>
            <input type="submit" name="YearSelect" value="Change Year">
        </label>

        <?php
    }
}
