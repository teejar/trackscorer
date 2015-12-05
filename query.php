    <?php
    require_once('./classes/tmfcolorparser.inc.php');

    include "dbConnect.php";
    mysqli_query($conn, "SET NAMES utf8");
    $cp           = new TMFcolorParser();
        // pelaajien  pisteet $topScoresArray['wd'] = 1;
    $topScoresArray = array();
    $recordsArray = array();
    $tracksArray  = array();
        // pelaajien nicknamet $topScoresArray['wd'] = "whited";
    $playerArray  = array();
    $playertopScoresArray = array();
    $loopNumber         = 1;

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

        // get player times from database
    $query  = "SELECT challenge_id, challenge_uid, challenge_name, record_playerlogin, record_score, player_nickname, player_login FROM exp_maps, exp_records, exp_players WHERE challenge_uid = record_challengeuid AND record_playerlogin = player_login ORDER BY challenge_id ASC, record_score ASC";
    $result = mysqli_query($conn, $query);
    $i      = 0;

    $pointsArray = array(5, 4, 3, 2, 1);

    $id         = -1;
    $recsPerMap = 0;
    $map        = 0;

    if (mysqli_num_rows($result) > 0) {
        $rank = 0;

        while ($row = mysqli_fetch_assoc($result)) {
                // tässä tehdään puskuri
                // radan id vaihtuu aina kun rata vaihduu.. daa, eli jos radan id on eri kun bufferin id on kyseessä uusi rata
            if ($row['challenge_id'] != $id) {
                  //  echo "num recs @ ".$row['challenge_id'].": ".$recsPerMap."<br>";

                    $rank       = 0;                    // rank nollataan
                    $recsPerMap = 0;              // rank ratalaskuri nollataan (tää on turha, voi poistaa, mutta auttoi debuggaamaan)
                    // bufferiin lisätään nykyinen uusi rataid, tämä pitää olla tämän iffin sisällä, tai muuten if-lause ei koskaan toteudu
                    $id         = $row['challenge_id'];
                }
                // apumuuttujat
                $login               = $row["player_login"];
                $playerArray[$login] = $row["player_nickname"];

                $playernickname = $row["player_nickname"];

                // make array for showing track times
                $recordsArray[$i] = array(
                   "trackNum" => $row["challenge_id"],
                   "playerName" => $cp->toHTML($playernickname, false, true),
                   "playerLogin" => $row["player_login"],
                   "trackuid" => $row["challenge_uid"],
                   "playerTime" => $row["record_score"]
                   );

                    // tässä muunnetaan pelaajan rank-sijoitus, pisteiksi
                    $points = 1;   // aina annetaan yksi piste
                    if (isset($pointsArray[$rank])) {
                        $points = $pointsArray[$rank]; // muussa tapauksessa point-arrayn mukaiset pisteet
                    }

                    // mikäli array:ssä on jo login
                    if (array_key_exists($login, $topScoresArray)) {


                    // mikäli arrayssä on login, lisätään pisteet
                        $topScoresArray[$login] += $points;
                    } else {

                    // muussa tapauksessa sijoitetaan aloituspisteet
                        $topScoresArray[$login] = $points;
                    }

                // lisätään laskurit
                    $i++;
                    $recsPerMap++;
                }
            } else {
                echo "0 results";
            }
            $i = 0;

            arsort($topScoresArray);

            $topScoresArrayKeys = array_keys($topScoresArray); //array keys so that top scoring players can be printed

            mysqli_close($conn);

            //convert some arrays to javascript
            //include "jsonEncodeScript.php";
            ?>