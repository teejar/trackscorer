    <?php
    include "dbConnect.php";
    mysqli_query($conn, "SET NAMES utf8");

    $tracksArray  = array();

// get track names from database
    $query = "SELECT * FROM exp_maps";

    $result = mysqli_query($conn, $query);
    $i      = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $tname = $row["challenge_name"];

            $tracksArray[$i] = array(
                "trackNum" => $row["challenge_id"]
                );
            $i++;
        }
    } else {
        echo "0 results";
    }
    mysqli_close($conn);

    echo "var tracksPlayed = ".count($tracksArray).";";
    ?>