<?php
include "scorequery.php";

$num = $_POST['navnumber'];

function printTrackscores($recordsArray, $i){
    foreach ($recordsArray as $x => $record) {
        if ($record["trackNum"] == $i)
        {
            $ms = $record["playerTime"];
            $minute = floor($ms / 1000 / 60);
            $second = (floor($ms / 1000) % 60);
            $sec = $second;
            if (strlen($sec)< 2){
                $sec = "0".$sec;
            }
            $millisecond = $ms % 1000;
            $milsec = $millisecond;
            if (strlen($milsec) < 3) {
                $milsec = "0" .$milsec;
            }     

            $displaytime = $minute . ":" . $sec . "," . $milsec;
            echo "<tr>";
            echo "<td>".$record["playerName"]."</td><td>".$displaytime."</td>";
            echo "</tr>";
        }
    }
}

printTrackscores($recordsArray, $num)
?>