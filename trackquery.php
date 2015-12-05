    <?php
    require_once('./classes/tmfcolorparser.inc.php');

    include "dbConnect.php";
    mysqli_query($conn, "SET NAMES utf8");
    $cp           = new TMFcolorParser();

    $tracksArray  = array();


// get track names from database
    $query = "SELECT * FROM exp_maps";

    $result = mysqli_query($conn, $query);
    $i      = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $tname = $row["challenge_name"];

            $tracksArray[$i] = array(
                "trackNum" => $row["challenge_id"],
                "trackuid" => $row["challenge_uid"],
                "trackName" => $cp->toHTML($tname, false, true)
                );
            $i++;
        }
    } else {
        echo "0 results";
    }
    mysqli_close($conn);

            //convert some arrays to javascript
            //include "jsonEncodeScript.php";
    ?>