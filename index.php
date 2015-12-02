<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Trackmania</title>
    <style type="text/css">
    body {
        background-color: #565656;
    }
    #trackLists{
        background-color: #565656;
    }
    </style>
</head>

<body id="body">

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
            include "jsonEncodeScript.php";
            ?>
            <div id="topScores">Top Scoring Players<br>
                <?php
            // print top scoring players
                for ($i=0;$i<5;$i++){
                    $printScoreKey = $topScoresArrayKeys[$i];
                    $convertString = $playerArray[$printScoreKey];
                    $printScoreNick = $cp->toHTML($convertString, false, true);
                    echo $printScoreNick." - ".$topScoresArray[$printScoreKey]."<br>";
                }
                ?>

                <?php
                //If we submitted the form
                if(isset($_POST['loadmoreScores']))
                {
                    printMoreScores($topScoresArrayKeys, $playerArray, $cp, $topScoresArray);
                }
                //If we haven't submitted the form
                else
                {
                    echo  '<form action="index.php" method="POST">';
                    echo   '<input type="submit" value="Load more scores" name="loadmoreScores">';
                    echo   '</form>';
                }
                //load rest of the stuff in the scoreboards
                function printMoreScores($a,$b,$c,$d){
                    for ($i=5;$i<count($d);$i++){
                        $printScoreKey = $a[$i];
                        $convertString = $b[$printScoreKey];
                        $printScoreNick = $c->toHTML($convertString, false, true);
                        echo $printScoreNick." - ".$d[$printScoreKey]."<br>";
                    }
                }
                ?>

            </div>
            <div id="trackLists"></div>
            <script src="jquery-2.1.4.min.js"></script>

            <script type="text/javascript">

            var pointArray = [5, 4, 3, 2, 1, 0];
            var num = 1;
            var numOfRecords = records_array.length;
            var numOftracks = tracks_array.length;
            var topScoresArrayByTrack = []; //store scores by track
            var topScoresArrayByTrackNumber = 1;

            function backButton() {
                document.getElementById(
                    "trackLists").innerHTML = "";
                if (num == 1) {
                    num = numOftracks + 1;
                }
                if (num > 1) {
                    num--;
                }

                DisplayRecords();
            }
            function forwardButton()
            {
                document.getElementById(
                    "trackLists").innerHTML = "";
                if (num == numOftracks) {
                    num = 1;
                }
                else num++;

                DisplayRecords();
            }
            function DisplayRecords() {
                var tnum = num - 1;
                document.getElementById(
                    "trackLists").innerHTML += "Track: ";
                document.getElementById(
                    "trackLists").innerHTML += tracks_array[tnum].trackNum + " / " + numOftracks + " - ";
                document.getElementById(
                    "trackLists").innerHTML += tracks_array[tnum].trackName;
                document.getElementById(
                    "trackLists").innerHTML += "  ";
                document.getElementById(
                    "trackLists").innerHTML += "<button type='button' onClick='backButton()'>back</button>";
                document.getElementById(
                    "trackLists").innerHTML += "<button type='button' onClick='forwardButton()'>forward</button>";
                document.getElementById(
                    "trackLists").innerHTML += " <br>";
                for (var i = 0; i < 848; i++)
                {
                    if (records_array[i].trackNum == num)
                    {
                        var ms = records_array[i].playerTime,
                        minute = (ms / 1000 / 60) << 0,
                        second = Math.floor((
                            ms / 1000) % 60),
                        millisecond = ms % 1000;
                        var milsec = millisecond.toString();
                        if (milsec.length < 3) {
                            milsec = "0" + milsec;
                        }
                        var displaytime = minute + ":" + second + "," + milsec;
                        document.getElementById(
                            "trackLists").innerHTML += records_array[i].playerName;
                        document.getElementById(
                            "trackLists").innerHTML += " - Time: ";
                        document.getElementById(
                            "trackLists").innerHTML += displaytime;
                        document.getElementById(
                            "trackLists").innerHTML += "<br>";
                    }
                }
            }
            DisplayRecords();


            </script>

        </body>
        </html>