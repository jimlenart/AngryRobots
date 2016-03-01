<?php
include("Team.php");
include("TeamResult.php");
include("AccumulatedResults.php");
include("Tools.php");
include("AccumulatedPoints.php");

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
 *  9.  Clean up HTML with some loops -DONE 2/29/16
 *  10. Show All Weeks we have accumulated points for in that section with each weekly value and a summed total -DONE 2/29/16
 *  TODO 11. Allow user to add a new week.
 *  TODO 12. Create log in page (Might not need this...)
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
    $accumulated = new AccumulatedPoints();

?>
