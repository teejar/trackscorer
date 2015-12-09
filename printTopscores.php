<?php
include "scorequery.php";

function printMoreScores($a,$b,$c,$d){
    $position = 1;
    echo "<table id='top5table'>";
    echo "<tr>";
    echo "<th style='width: 48px' >#</th><th>Name</th><th style='width: 300px' >Total points</th>";
    echo "</tr>";
    for ($i=0;$i<count($d);$i++){
        $printScoreKey = $a[$i];
        $convertString = $b[$printScoreKey];
        $printScoreNick = $c->toHTML($convertString, false, true);
        if ($i==5){
            echo "</table>";
            echo "<table id='ntoptable'>";
            echo "<th style='width: 48px' ></th><th></th><th style='width: 300px' ></th>";
        }
        if( $position == 1 )
            echo "<tr class='first'>";
        elseif( $position == 2 )
            echo "<tr class='second'>";
        elseif( $position == 3 )
            echo "<tr class='third'>";
        elseif( $position > 3 && $position % 2 == 1 )
         echo "<tr class='tralt'>";
     else         echo "<tr>";
     echo "<td>".$position."</td><td>".$printScoreNick."</td><td>".$d[$printScoreKey]."</td>";
     echo "</tr>";
     $position++;
 }
 echo "</table>";
}
printMoreScores($topScoresArrayKeys, $playerArray, $cp, $topScoresArray);
?>