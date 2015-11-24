<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Trackmania</title>
    <style>
    #body {
        background-color: #565656;        
    }
    #trackLists{
        background-color: #565656;
    }
    </style>
</head>

<body id="body">

    <div id="topScores">Tähän tulee TOP 5 taulukko.</div>
    <div id="trackLists"></div>

    <?php
    require_once('./classes/tmfcolorparser.inc.php');

    include "dbConnect.php";
    mysqli_query($conn, "SET NAMES utf8");
    $cp = new TMFcolorParser();
    $recordsArray[] = array();
    $tracksArray[] = array();
    $playerRecordsArray[] = array();
    $loopNumber = 1;

//get track names from database
    $query = "SELECT * FROM exp_maps";

    $result = mysqli_query($conn, $query);
    $i=0;
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {

            $tname = $row["challenge_name"];

            $tracksArray[$i] = array(
               "trackNum" => $row["challenge_id"],
               "trackuid" => $row["challenge_uid"],
               "trackName" => $cp->toHTML($tname, false, true)
               );
            $i++;
        }
    }
    else {
        echo "0 results";
    }

//get player times from database
    $query = "SELECT challenge_id, challenge_uid, challenge_name, record_playerlogin, record_score, player_nickname, player_login FROM exp_maps, exp_records, exp_players WHERE challenge_uid = record_challengeuid AND record_playerlogin = player_login ORDER BY challenge_id ASC, record_score ASC";
    $result = mysqli_query($conn, $query);
    $i=0;
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $playernickname = $row["player_nickname"];
            $recordsArray[$i] = new score($i, $row["player_login"],$cp->toHTML($playernickname, false, true));
            $i++;
        }
    }
    else {
        echo "0 results";
    }
    mysqli_close($conn);

class score {
    /* @var string */
    public $nick, $login;
    /* @var integer */
    public $score = 0;
    /* @var integer */
    public $total = 0;
    /* @var integer[] */
    private $scoresystem = array(5,4,3,2,1);  // tähän voidaan editoida sitten lopuksi pistejärjestelmä
    
    public function __construct($rank, $login, $nick) {
        $this->nick = $nick;
        $this->login = $login;
            $points = 1; // jokatapauksessa annetaan yksi piste, jos on pelannut kartan.
            if (array_key_exists($rank, $this->$scoresystem)) {
                $points = $this->scoresystem[$rank]; // muussa tapauksessa rank:in mukaan valitaan scoresysteemistä pistemäärä
            }
            $this->score = $points;
            $this->total = $points;
        }
        public function add($score) {
            $this->total += $score;
        }
    }

    print_r($recordsArray);
    /* @var score[] $points */
  //  $points = array(); 

/*
* @param string $mapuid
* @return score[];
*/
/*
function getPointsForMap($mapuid) {
    // käy kannasta mapUid:n mukaset pisteet "sort by `score` ascenting" jotta saa valmiiksi sortattuna oikein.
    $outscore = array();
    foreach ($kanta as $rank => $obj) {
        $outscore[$rank] = new score($rank, $obj->login, $obj->nick);
    }   
    return $outscore;
}
*/


/*
------------------------------------
käy läpi kaikki pisteet
------------------------------------


$totalPoints = array();         // tässä on kaikki pisteet
//totalpoints:in key on aina login, ja value = score objekti.

foreach ($mapsdata as $mapuid) {
    // @var score[] $playerpoints 
    $playerpoints = getPointsForMap($mapuid);   // tässä on yhden mapin pisteet

    foreach ($playerPoints as $rank => $object) {  
        if (array_key_exists($object->login, $points)) { // jos totalpointissa on jo player-objekti
            $totalPoints[$object->login]->add($object->score);   // lisätään kokonaispistemäärää
        }
        else {  
            $totalPoints[$object->login] = $object;  // muussa tapauksessa lisätään valmis objekti 
        }
    } 
    // tässä on käyty läpi kaikki kartat, ja laskettu joka loginille pisteet    
}


// pisteet olisi hyvä saada järjestyksessä... jotenka
uasort($totalpoints) // --> katso miten uasort toimii... tarkoitus on järjestää uudelleen tämä array tuon objektin pisteiden mukaan..
                     // eli $obj->total laskevassa järjestyksessä

// tässä vaan tulostetaan enää pisteet :)                    
foreach ($totalpoints as $login => $obj) 
{
    echo $obj->nickname. " -> " . $obj->total ."<br/>";
}
*/





/*
//loop through everything
    for ($i=0; $i< count($recordsArray); $i++ ){
        foreach ($recordsArray[$i] as $x => $value) {
            echo "key=" . $x. ", value=". $value;
            echo "<br>";
        }
        echo"<br>";
    }
*/

    
//convert arrays to javascript
    include "jsonEncodeScript.php";
    ?>
<!--
///////////////////////////////////////////

///////////////////////////////////////////
-->
    <script src="jquery-2.1.4.min.js"></script>

    <script type="text/javascript">

    var kek = 0;


    var pointArray = [5,4,3,2,1,0];
    var num = 1;
    var numOfRecords = records_array.length;
    var numOftracks = tracks_array.length;
var recordsArrayByTrack = []; //store scores by track
var recordsArrayByTrackNumber = 1;


function backButton()
{
    document.getElementById("trackLists").innerHTML = "";
    if (num == 1) {
        num = numOftracks + 1;
    }
    if (num > 1){
        num --;
    }

    DisplayRecords();
}
function forwardButton()
{
    document.getElementById("trackLists").innerHTML = "";
    if (num == numOftracks){
        num = 1;
    }  
    else num++;

    DisplayRecords();
}

function DisplayRecords() {
    var tnum = num-1;
    document.getElementById("trackLists").innerHTML += "Track: ";
    document.getElementById("trackLists").innerHTML += tracks_array[tnum].trackNum + " / " + numOftracks + " - ";
    document.getElementById("trackLists").innerHTML += tracks_array[tnum].trackName;
    document.getElementById("trackLists").innerHTML += "  ";
    document.getElementById("trackLists").innerHTML += "<button type='button' onClick='backButton()'>back</button>";
    document.getElementById("trackLists").innerHTML += "<button type='button' onClick='forwardButton()'>forward</button>";
    document.getElementById("trackLists").innerHTML += " <br>";
    document.getElementById("trackLists").innerHTML += " <br>";
    for (var i=0; i < 848; i++)
    {
        if (records_array[i].trackNum == num)
        {
            var ms = records_array[i].playerTime,
            minute = (ms/1000/60) << 0,
            second = Math.floor((ms/1000) % 60),
            millisecond = ms%1000;
            var milsec = millisecond.toString(); 
            if (milsec.length < 3){
                milsec = "0"+milsec;
            }

            var displaytime = minute + ":"+second+","+milsec;
            document.getElementById("trackLists").innerHTML += records_array[i].playerName;
            document.getElementById("trackLists").innerHTML += " - Time: ";
            document.getElementById("trackLists").innerHTML += displaytime;
            document.getElementById("trackLists").innerHTML += " DEBUG " + records_array[i].playerTime;
            document.getElementById("trackLists").innerHTML += "<br>";

        }
    }
}

DisplayRecords();

</script>

</body>
</html>