<?php
include "scorequery.php";

$num = $_POST['navnumber'];

function printTrackscores($recordsArray, $i, $pointsArray){
    $position = 1;
    $topscore;
    echo "<tr>";
    echo "<th>#</th><th>Name</th><th>Time</th><th>Difference</th><th>Points</th>";
    echo "</tr>";
    foreach ($recordsArray as $x => $record) {
        if ($position ==1)
            $topscore = $record["playerTime"];

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
            $difference = $record["playerTime"]-$topscore;
            $ms = $difference;
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
            if ($minute==0 && $sec=="00") {$sec = "0";
            $displaydifference = $sec . "," . $milsec;
        }
        elseif ($minute==0) {$displaydifference = $sec . "," . $milsec;
    }
    else $displaydifference = $minute . ":" . $sec . "," . $milsec;


    if ($position<=4){
        $p = $pointsArray[$position-1];
    }   
    else $p=$pointsArray[4];


    if ($position !== 1)
    {
        if( $position == 2 )
            echo "<tr class='second'>";
        elseif( $position == 3 )
            echo "<tr class='third'>";
     elseif( $position > 3 && $position % 2 == 1 )
           echo "<tr class='tralt'>";
        else         echo "<tr>";

        echo "<td>".$position."</td><td>".$record["playerName"]."</td><td>".$displaytime."</td><td>( +".$displaydifference.")</td><td> +".$p."</td>";
        echo "</tr>";
    }
    else {
        echo "<tr class='first'>";
        echo "<td>".$position."</td><td>".$record["playerName"]."</td><td>".$displaytime."</td><td> - </td><td> +".$p."</td>";
        echo "</tr>";
    }
    $position++;
}
}
}


printTrackscores($recordsArray, $num, $pointsArray)
?>